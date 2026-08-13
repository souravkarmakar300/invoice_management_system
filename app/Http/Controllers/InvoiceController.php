<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            ->when(in_array($paymentFilter, ['paid', 'due'], true), function ($query) use ($paymentFilter) {
                if ($paymentFilter === 'paid') {
                    $query->where('balance_due', '<=', 0);
                } else {
                    $query->where('balance_due', '>', 0);
                }
            });

        $stats = [
            'all' => Invoice::count(),
            'paid' => Invoice::where('balance_due', '<=', 0)->count(),
            'due' => Invoice::where('balance_due', '>', 0)->count(),
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
            ->when(in_array($paymentFilter, ['paid', 'due'], true), function ($query) use ($paymentFilter) {
                if ($paymentFilter === 'paid') {
                    $query->where('balance_due', '<=', 0);
                } else {
                    $query->where('balance_due', '>', 0);
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
                'Customer Code',
                'Reference Code',
                'Email',
                'Phone',
                'Address',
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
                // 'Notes',
                'Created At',
            ]);

            foreach ($invoices as $index => $invoice) {
                $paymentStatus = $invoice->balance_due <= 0 ? 'Paid' : 'Due';

                fputcsv($handle, [
                    $index + 1,
                    $invoice->invoice_no,
                    optional($invoice->invoice_date)->format('Y-m-d'),
                    optional($invoice->due_date)->format('Y-m-d'),
                    $invoice->company_name,
                    $invoice->customer_name,
                    $invoice->customer_code,
                    $invoice->reference_code,
                    $invoice->email,
                    $invoice->phone,
                    $invoice->address,
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
                    // $invoice->notes,
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
                'invoice_date' => $request->input('invoice_date', now()->toDateString()),
                'due_date' => $request->input('due_date', now()->addDays(7)->toDateString()),
                'status' => $request->input('action') === 'draft' ? 'draft' : 'generated',
                // 'notes' => $request->input('notes'),
            ]);

            $this->syncItems($invoice, $request);

            return $invoice;
        });

        return redirect()
            ->route('dashboard')
            ->with('success', "Invoice {$invoice->invoice_no} created successfully.");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');

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
                // 'notes' => $request->input('notes'),
            ]);

            $invoice->items()->delete();
            $this->syncItems($invoice, $request);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', "Invoice {$invoice->invoice_no} updated successfully.");
    }

    public function destroy(Invoice $invoice)
    {
        $no = $invoice->invoice_no;
        $invoice->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', "Invoice {$no} deleted successfully.");
    }

    public function download(Invoice $invoice)
    {
        $invoice->load('items');

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

        $invoice->load('items');
        $fileName = $invoice->invoice_no . '.pdf';
        $relativePath = 'invoices/' . $fileName;
        $absolutePath = storage_path('app/public/' . $relativePath);

        Storage::disk('public')->makeDirectory('invoices');
        $this->makePdf($invoice)->save($absolutePath);

        $caption = "Invoice {$invoice->invoice_no} from Webzone Expertz. Total: $" . number_format((float) $invoice->total, 2);

        if ($whatsApp->isConfigured()) {
            $result = $whatsApp->sendDocument($phone, $absolutePath, $fileName, $caption);

            return response()->json($result, $result['success'] ? 200 : 500);
        }

        $token = Str::random(40);
        Cache::put('invoice_share_' . $token, [
            'invoice_id' => $invoice->id,
            'path' => $relativePath,
            'file_name' => $fileName,
        ], now()->addDays(2));

        $pdfUrl = route('invoices.shared', $token);
        $message = rawurlencode("Hello,\n\nPlease find invoice {$invoice->invoice_no}.\nTotal: $" . number_format((float) $invoice->total, 2) . "\n\nDownload PDF:\n{$pdfUrl}");

        return response()->json([
            'success' => true,
            'mode' => 'web',
            'message' => 'Opening WhatsApp with invoice PDF link.',
            'whatsapp_url' => "https://wa.me/{$phone}?text={$message}",
            'pdf_url' => $pdfUrl,
        ]);
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
            'author_mail' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'bsb' => 'nullable|string|max:50',
            'payment_method' => 'required|string|max:100',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            // 'notes' => 'nullable|string',
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

        $total = $subtotal + $taxTotal;
        $paid = (float) ($request->paid_amount ?? 0);

        return [
            'subtotal' => round($subtotal, 2),
            'tax_total' => round($taxTotal, 2),
            'total' => round($total, 2),
            'paid_amount' => round($paid, 2),
            'balance_due' => round(max($total - $paid, 0), 2),
            'payment_status' => $paid >= $total && $total > 0 ? 'Paid' : 'Due',
        ];
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
