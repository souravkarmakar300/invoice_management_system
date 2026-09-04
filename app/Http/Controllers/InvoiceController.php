<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\InvoiceDeletedMail;
use App\Mail\InvoiceCreatedMail;

class InvoiceController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search', ''));
        $paymentFilter = strtolower((string) request('payment_filter', 'all'));

        $invoicesQuery = Invoice::with('items')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('invoice_no', 'like', '%' . $search . '%')
                        ->orWhere('author_mail', 'like', '%' . $search . '%');
                });
            })
            ->when(in_array($paymentFilter, ['paid', 'partial', 'due'], true), function ($query) use ($paymentFilter) {
                if ($paymentFilter === 'paid') {
                    $query->where('payment_status', 'Paid');
                } elseif ($paymentFilter === 'partial') {
                    $query->where('payment_status', 'Partial');
                } else {
                    $query->where('payment_status', 'Due');
                }
            });

        $stats = [
            'all' => Invoice::count(),
            'paid' => Invoice::where('payment_status', 'Paid')->count(),
            'due' => Invoice::where('payment_status', '!=', 'Paid')->count(),
            'total_amount' => (float) Invoice::sum('total'),
            'paid_amount' => (float) Invoice::sum('paid_amount'),
            'due_amount' => (float) Invoice::sum('balance_due'),
        ];

        $invoices = $invoicesQuery->latest()->paginate(10)->withQueryString();

        return view('dashboard', compact('invoices', 'stats', 'search', 'paymentFilter'));
    }

    public function exportCsv(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $paymentFilter = strtolower((string) $request->get('payment_filter', 'all'));

        $invoices = Invoice::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('author_mail', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%');
                });
            })
            ->when(in_array($paymentFilter, ['paid', 'partial', 'due'], true), function ($query) use ($paymentFilter) {
                if ($paymentFilter === 'paid') {
                    $query->where('payment_status', 'Paid');
                } elseif ($paymentFilter === 'partial') {
                    $query->where('payment_status', 'Partial');
                } else {
                    $query->where('payment_status', 'Due');
                }
            })
            ->latest()
            ->get();

        $filename = 'invoices_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($invoices) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'SL',
                'Invoice No',
                'Invoice Date',
                'Due Date',
                'Company Name',
                'Customer Name',
                'Email',
                'Phone',
                'Address',
                'Author Name',
                'Author Email',
                'Payment Method',
                'Payment Status',
                'Subtotal',
                'Tax',
                'Total',
                'Paid Amount',
                'Balance Due',
                'Bank Name',
                'Account Number',
                'BSB',
                'Notes',
                'Created At',
            ]);

            foreach ($invoices as $index => $invoice) {
                $paymentStatus = $invoice->paymentStatusLabel();

                fputcsv($handle, [
                    $index + 1,
                    $invoice->invoice_no,
                    optional($invoice->invoice_date)->format('Y-m-d'),
                    optional($invoice->due_date)->format('Y-m-d'),
                    $invoice->company_name,
                    $invoice->customer_name,
                    $invoice->author_name,
                    $invoice->author_mail,
                    $invoice->email,
                    $invoice->phone,
                    $invoice->address,
                    $invoice->author_name,
                    $invoice->author_mail,
                    $invoice->payment_method,
                    $paymentStatus,
                    number_format((float) $invoice->subtotal, 2, '.', ''),
                    number_format((float) $invoice->tax_total, 2, '.', ''),
                    number_format((float) $invoice->total, 2, '.', ''),
                    number_format((float) $invoice->paid_amount, 2, '.', ''),
                    number_format((float) $invoice->balance_due, 2, '.', ''),
                    $invoice->bank_name,
                    $invoice->account_number,
                    $invoice->bsb,
                    $invoice->notes,
                    optional($invoice->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create()
    {
        return view('create', [
            'invoice' => null,
            'invoice_no' => Invoice::generateInvoiceNo(),
        ]);
    }

    public function store(Request $request)
{
    $data = $this->validated($request);
    $totals = $this->calculateTotals($request);

    $invoice = DB::transaction(function () use ($request, $data, $totals) {

        $invoice = Invoice::create([
            ...$data,
            ...$totals,
            'user_id' => auth()->id(),
            'author_mail' => $request->input('author_mail'),
            'invoice_no' => Invoice::generateInvoiceNo(),
            'invoice_date' => $request->input(
                'invoice_date',
                now()->toDateString()
            ),
            'due_date' => $request->input(
                'due_date',
                now()->addDays(7)->toDateString()
            ),
            'status' => $request->input('action') === 'draft'
                ? 'draft'
                : 'generated',
            'notes' => $request->input('notes'),
        ]);

        $this->syncItems($invoice, $request);
        $this->syncInitialPayment($invoice, $request);
        $invoice->recalculatePayments();

        return $invoice;
    });

    // Send invoice-created notification emails
    $notifyEmails = config('mail.invoice_cc_email');

    if (filled($notifyEmails)) {

        $emails = array_filter(
            array_map('trim', explode(',', $notifyEmails))
        );

        $emailFailed = false;

        foreach ($emails as $email) {

            try {

                Mail::to($email)->send(
                    new InvoiceCreatedMail($invoice)
                );

            } catch (\Throwable $e) {

                report($e);

                \Log::error('Invoice creation email failed', [
                    'invoice_no' => $invoice->invoice_no,
                    'recipient' => $email,
                    'error' => $e->getMessage(),
                ]);

                $emailFailed = true;
            }
        }

        if ($emailFailed) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'success',
                    "Invoice {$invoice->invoice_no} created successfully, but one or more notification emails failed."
                );
        }
    }

    return redirect()
        ->route('dashboard')
        ->with(
            'success',
            "Invoice {$invoice->invoice_no} created successfully."
        );
}

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'payments']);

        return view('pdf', [
            'invoice' => $invoice,
            'isPdf' => false,
        ]);
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');

        return view('create', [
            'invoice' => $invoice,
            'invoice_no' => $invoice->invoice_no,
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $this->validated($request);
        $totals = $this->calculateTotals($request);

        DB::transaction(function () use ($request, $invoice, $data, $totals) {
            $invoice->update([
                ...$data,
                ...$totals,
                'author_mail' => $request->input('author_mail'),
                'invoice_date' => $request->input('invoice_date', $invoice->invoice_date),
                'due_date' => $request->input('due_date', $invoice->due_date),
                'status' => $request->input('action') === 'draft' ? 'draft' : 'generated',
                'notes' => $request->input('notes'),
            ]);

            $invoice->items()->delete();
            $this->syncItems($invoice, $request);
            $invoice->recalculatePayments();
            $invoice->refresh();
        });

        return redirect()
            ->route('dashboard')
            ->with('success', "Invoice {$invoice->invoice_no} updated successfully.");
    }

    // public function destroy(Invoice $invoice)
    // {
    //     $invoiceNo = $invoice->invoice_no;
    //     $customerName = $invoice->customer_name;
    //     $customerEmail = $invoice->email;
    //     $companyName = $invoice->company_name;

    //     $notifyEmail = config('mail.invoice_cc_email');

    //     $invoice->delete();

    //     if (filled($notifyEmail)) {
    //         try {
    //             Mail::to($notifyEmail)->send(
    //                 new InvoiceDeletedMail(
    //                     $invoiceNo,
    //                     $customerName,
    //                     $customerEmail,
    //                     $companyName
    //                 )
    //             );
    //         } catch (\Throwable $e) {
    //             report($e);

    //             return redirect()
    //                 ->route('dashboard')
    //                 ->with(
    //                     'success',
    //                     "Invoice {$invoiceNo} deleted successfully, but notification email failed."
    //                 );
    //         }
    //     }

    //     return redirect()
    //         ->route('dashboard')
    //         ->with('success', "Invoice {$invoiceNo} deleted successfully.");
    // }


    public function destroy(Invoice $invoice)
{
    $invoiceNo = $invoice->invoice_no;
    $customerName = $invoice->customer_name;
    $customerEmail = $invoice->email;
    $companyName = $invoice->company_name;

    $notifyEmails = config('mail.invoice_cc_email');

    // Delete invoice
    $invoice->delete();

    if (filled($notifyEmails)) {

        $emails = array_filter(
            array_map('trim', explode(',', $notifyEmails))
        );

        $emailFailed = false;

        foreach ($emails as $email) {
            try {
                // Send separate email to each recipient
                Mail::to($email)->send(
                    new InvoiceDeletedMail(
                        $invoiceNo,
                        $customerName,
                        $customerEmail,
                        $companyName
                    )
                );
            } catch (\Throwable $e) {
                report($e);
                $emailFailed = true;
            }
        }

        if ($emailFailed) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'success',
                    "Invoice {$invoiceNo} deleted successfully, but one or more notification emails failed."
                );
        }
    }

    return redirect()
        ->route('dashboard')
        ->with(
            'success',
            "Invoice {$invoiceNo} deleted successfully and notification emails were sent."
        );
}

    public function storePayment(Request $request, Invoice $invoice)
    {
        $invoice->recalculatePayments();
        $invoice->refresh();

        $due = round((float) $invoice->due_amount, 2);

        if ($due <= 0) {
            return back()->with('error', "Invoice {$invoice->invoice_no} is already fully paid.");
        }

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $due],
            'paid_at' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($invoice, $data) {
            InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'amount' => round((float) $data['amount'], 2),
                'paid_at' => $data['paid_at'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $invoice->recalculatePayments();
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    public function download(Invoice $invoice)
    {
        $invoice->load(['items', 'payments']);

        return $this->makePdf($invoice)->download($invoice->invoice_no . '.pdf');
    }

    public function sendWhatsApp(Request $request, Invoice $invoice, WhatsAppService $whatsApp)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'min:8', 'max:20'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']);

        if (strlen($phone) < 8) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid WhatsApp number with country code.',
            ], 422);
        }

        $invoice->load(['items', 'payments']);
        $fileName = $invoice->invoice_no . '.pdf';
        $relativePath = 'invoices/' . $fileName;
        $absolutePath = storage_path('app/public/' . $relativePath);

        Storage::disk('public')->makeDirectory('invoices');
        $this->makePdf($invoice)->save($absolutePath);

        $token = Str::random(40);
        Cache::put('invoice_share_' . $token, [
            'invoice_id' => $invoice->id,
            'path' => $relativePath,
            'file_name' => $fileName,
        ], now()->addDays(2));

        $pdfUrl = route('invoices.shared', $token);
        $totalFormatted = number_format((float) $invoice->total, 2);

        if (! $whatsApp->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp API is not configured. Please check WHATSAPP_API_KEY and template settings.',
            ], 500);
        }

        if (! $whatsApp->canSendViaApi()) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp API cannot be used with the current APP_URL configuration.',
            ], 500);
        }

        $result = $whatsApp->sendDocument(
            $phone,
            $fileName,
            $pdfUrl,
            $invoice->invoice_no,
            $totalFormatted
        );

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'debug' => $result['debug'] ?? null,
            ], 422);
        }

        return response()->json($result);

        // if ($whatsApp->canSendViaApi()) {
        //     $result = $whatsApp->sendDocument(
        //         $phone,
        //         $fileName,
        //         $pdfUrl,
        //         $invoice->invoice_no,
        //         $totalFormatted
        //     );

        //     if ($result['success']) {
        //         return response()->json($result);
        //     }
        // }

        // $message = "Hello,\n\nPlease find invoice {$invoice->invoice_no}.\nTotal: \${$totalFormatted}\n\nDownload PDF:\n{$pdfUrl}";

        // return response()->json([
        //     'success' => true,
        //     'mode' => 'web',
        //     'message' => $whatsApp->isConfigured()
        //         ? 'WhatsApp API is not available on localhost. Opening WhatsApp with PDF link.'
        //         : 'Opening WhatsApp with invoice PDF link.',
        //     'whatsapp_url' => 'https://wa.me/' . $phone . '?text=' . rawurlencode($message),
        //     'pdf_url' => $pdfUrl,
        // ]);
    }

    // public function sendEmail(Request $request, Invoice $invoice)
    // {
    //     $data = $request->validate([
    //         'to_email' => ['required', 'email', 'max:255'],
    //         'to_name' => ['required', 'string', 'max:255'],
    //         'message' => ['nullable', 'string', 'max:1000'],
    //     ]);

    //     $invoice->load(['items', 'payments']);

    //     $bodyMessage = $data['message']
    //         ?: "Please find attached invoice {$invoice->invoice_no}. Total: $"
    //         . number_format((float) $invoice->total, 2)
    //         . ". Amount due: $"
    //         . number_format((float) $invoice->due_amount, 2)
    //         . ".";

    //     try {

    //         // Send individual email to customer
    //         Mail::to($data['to_email'], $data['to_name'])
    //             ->send(
    //                 new InvoiceMail(
    //                     $invoice,
    //                     $data['to_name'],
    //                     $bodyMessage
    //                 )
    //             );

    //         // Send separate individual email to admin
    //         $extraEmail = config('mail.invoice_cc_email');

    //         if ($extraEmail) {
    //             Mail::to($extraEmail)
    //                 ->send(
    //                     new InvoiceMail(
    //                         $invoice,
    //                         'Invoice Admin',
    //                         $bodyMessage
    //                     )
    //                 );
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => "Invoice {$invoice->invoice_no} sent successfully.",
    //         ]);
    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to send email: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }



    public function sendEmail(Request $request, Invoice $invoice)
{
    $data = $request->validate([
        'to_email' => ['required', 'email', 'max:255'],
        'to_name' => ['required', 'string', 'max:255'],
        'message' => ['nullable', 'string', 'max:1000'],
    ]);

    $invoice->load(['items', 'payments']);

    $customerEmail = trim($data['to_email']);
    $customerName = trim($data['to_name']);

    $bodyMessage = !empty(trim($data['message'] ?? ''))
        ? trim($data['message'])
        : "Please find attached invoice {$invoice->invoice_no}. Total: $"
            . number_format((float) $invoice->total, 2)
            . ". Amount due: $"
            . number_format((float) $invoice->due_amount, 2)
            . ".";

    try {

        // ==========================================
        // Send invoice email to customer
        // ==========================================

        Mail::to($customerEmail, $customerName)
            ->send(
                new InvoiceMail(
                    $invoice,
                    $customerName,
                    $bodyMessage
                )
            );

        // ==========================================
        // Send separate invoice email to admin
        // ==========================================

        $extraEmail = trim((string) config('mail.invoice_cc_email'));

        if (
            !empty($extraEmail) &&
            filter_var($extraEmail, FILTER_VALIDATE_EMAIL)
        ) {
            Mail::to($extraEmail)
                ->send(
                    new InvoiceMail(
                        $invoice,
                        'Invoice Admin',
                        $bodyMessage
                    )
                );
        }

        return response()->json([
            'success' => true,
            'message' => "Invoice {$invoice->invoice_no} sent successfully.",
        ]);

    } catch (\Throwable $e) {

        \Log::error('Invoice email sending failed', [
            'invoice' => $invoice->invoice_no,
            'customer_email' => $customerEmail,
            'admin_email' => $extraEmail ?? null,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to send email: ' . $e->getMessage(),
        ], 500);
    }
}

    public function sharedPdf(string $token)
    {
        $share = Cache::get('invoice_share_' . $token);

        if (!$share || empty($share['path']) || !Storage::disk('public')->exists($share['path'])) {
            abort(404, 'This invoice PDF link has expired or is invalid.');
        }

        return Storage::disk('public')->download(
            $share['path'],
            $share['file_name'] ?? 'invoice.pdf'
        );
    }

    private function makePdf(Invoice $invoice)
    {
        $invoice->loadMissing(['items', 'payments']);

        return Pdf::loadView('pdf', [
            'invoice' => $invoice,
            'isPdf' => true,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'author_name' => 'required|string|max:255',
            'author_mail' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'payment_gateway' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'bank_address' => 'nullable|string|max:255',
            // 'bsb' => 'nullable|string|max:50',
            'payment_method' => 'nullable|string|max:100',
            'currency' => 'required|string|in:USD,CAD',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'paid_amount' => 'nullable|numeric|min:0',
            'product' => 'required|array|min:1',
            'product.*' => 'required|string|max:255',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0.01',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'tax' => 'nullable|array',
            'tax.*' => 'nullable|numeric|min:0',
        ]);
    }

    private function calculateTotals(Request $request): array
    {
        $subtotal = 0;
        $taxTotal = 0;

        foreach ($request->product as $i => $product) {
            $qty = (float) ($request->qty[$i] ?? 0);
            $price = (float) ($request->unit_price[$i] ?? 0);
            $tax = (float) ($request->tax[$i] ?? 0);
            $line = $qty * $price;
            $lineTax = $line * ($tax / 100);

            $subtotal += $line;
            $taxTotal += $lineTax;
        }

        $total = round($subtotal + $taxTotal, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'tax_total' => round($taxTotal, 2),
            'total' => $total,
            'paid_amount' => 0,
            'balance_due' => $total,
            'payment_status' => 'Due',
        ];
    }

    private function syncInitialPayment(Invoice $invoice, Request $request): void
    {
        if ($invoice->payments()->exists()) {
            return;
        }

        $paid = round((float) $request->input('paid_amount', 0), 2);

        if ($paid <= 0) {
            return;
        }

        InvoicePayment::create([
            'invoice_id' => $invoice->id,
            'amount' => min($paid, (float) $invoice->total),
            'paid_at' => now(),
            'payment_method' => $request->input('payment_method', 'Bank Transfer'),
            'reference_number' => $request->input('reference_code'),
            'notes' => 'Initial payment recorded with invoice',
        ]);
    }

    private function syncItems(Invoice $invoice, Request $request): void
    {
        foreach ($request->product as $i => $product) {
            $qty = (float) ($request->qty[$i] ?? 0);
            $price = (float) ($request->unit_price[$i] ?? 0);
            $tax = (float) ($request->tax[$i] ?? 0);
            $line = $qty * $price;
            $amount = $line + ($line * ($tax / 100));

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product' => $product,
                'description' => $request->description[$i] ?? null,
                'qty' => $qty,
                'unit_price' => $price,
                'tax' => $tax,
                'amount' => round($amount, 2),
            ]);
        }
    }
}
