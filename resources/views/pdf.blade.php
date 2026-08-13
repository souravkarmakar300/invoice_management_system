<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    @php($isPdf = $isPdf ?? false)
    @unless ($isPdf)
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endunless
    <style>
        @if ($isPdf)
            @page {
                margin: 8mm;
                size: A4 portrait;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 0;
                background: #fff;
                font-family: DejaVu Sans, sans-serif;
                font-size: 11px;
                line-height: 1.35;
            }

            .invoice {
                max-width: 100%;
                margin: 0;
                background: #fff;
                border-radius: 0;
                box-shadow: none;
            }

            .invoice-header {
                border-radius: 8px;
                border: 1px solid #e5e7eb;
                box-shadow: none;
                padding: 12px;
                margin-bottom: 10px;
            }

            .section {
                padding: 10px 12px;
            }

            .card-box {
                padding: 10px;
                border-radius: 8px;
                height: auto;
            }

            .company-name {
                font-size: 20px;
            }

            .company-info {
                font-size: 11px;
            }

            .invoice-title {
                font-size: 28px;
                letter-spacing: 1px;
            }

            .invoice-number {
                font-size: 14px;
            }

            .invoice-date {
                font-size: 11px;
            }

            .status-badge {
                padding: 4px 10px;
                font-size: 10px;
            }

            .company-logo {
                width: 150px;
                height: 100px;
                padding: 4px;
            }

            .label {
                font-size: 10px;
                margin-bottom: 4px;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
                font-size: 11px;
            }

            .table th,
            .table td {
                border: 1px solid #e5e7eb;
                padding: 6px 8px;
            }

            .table thead {
                background: #2563eb;
                color: #fff;
            }

            .summary {
                padding: 12px;
            }

            .grand-total {
                font-size: 16px;
                padding: 10px 12px;
            }

            .footer {
                padding: 12px;
                font-size: 11px;
            }

            .row {
                display: table;
                width: 100%;
                border-collapse: collapse;
            }

            .row>[class*="col-"] {
                display: table-cell;
                vertical-align: top;
                float: none;
            }

            .col-lg-7,
            .col-md-7 {
                width: 58%;
            }

            .col-lg-5,
            .col-md-5 {
                width: 42%;
            }

            .col-md-6 {
                width: 50%;
            }

            .col-md-3 {
                width: 25%;
            }

            .d-flex {
                display: block;
            }

            .text-md-end {
                text-align: right;
            }

            .text-end {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .mb-1 {
                margin-bottom: 4px;
            }

            .mb-2 {
                margin-bottom: 6px;
            }

            .mb-3 {
                margin-bottom: 8px;
            }

            .mb-4 {
                margin-bottom: 10px;
            }

            .mt-2 {
                margin-top: 6px;
            }

            .mt-4 {
                margin-top: 10px;
            }

            .me-3 {
                margin-right: 10px;
            }

            .p-4 {
                padding: 12px;
            }

            .pt-0 {
                padding-top: 0;
            }

            .g-4>* {
                margin-bottom: 8px;
            }

            .toolbar,
            .no-print {
                display: none !important;
            }

            .card-box {
                background: #f8fafc;
                border: 1px solid #e5e7eb;
            }

            .company-name {
                color: #1e3a8a;
                font-weight: 700;
            }

            .invoice-title {
                color: #2563eb;
                font-weight: 800;
            }

            .invoice-number {
                color: #111827;
                font-weight: 700;
            }

            .label {
                color: #64748b;
                text-transform: uppercase;
                font-weight: 600;
            }

            .summary {
                background: #f8fafc;
                border-radius: 8px;
            }

            .summary table td {
                border: none;
                padding: 6px 0;
            }

            .grand-total {
                background: #2563eb;
                color: #fff;
                border-radius: 8px;
                font-weight: bold;
            }

            .footer {
                background: #0f172a;
                color: #cbd5e1;
                text-align: center;
            }

            .badge-paid {
                background: #16a34a;
            }

            .badge-pending {
                background: #dc2626;
            }

            .text-muted {
                color: #6b7280;
            }

            .text-success {
                color: #16a34a;
            }

            .float-end {
                float: right;
            }

            .invoice-header,
            .section,
            .footer {
                page-break-inside: avoid;
            }
        @else
            body {
                background: #eef2f7;
                font-family: 'Segoe UI', sans-serif;
                margin: 0;
                padding: 0 0 24px;
                overflow-x: hidden;
            }

            .invoice {
                max-width: 950px;
                width: calc(100% - 24px);
                margin: 20px auto;
                background: #fff;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 20px 50px rgba(0, 0, 0, .08);
            }

            .top-header {
                background: linear-gradient(135deg, #2563eb, #1e40af);
                color: #fff;
                padding: 40px;
            }

            .logo {
                max-width: 170px;
                max-height: 80px;
            }

            .invoice-title {
                font-size: 42px;
                font-weight: 700;
                letter-spacing: 2px;
            }

            .section {
                padding: 35px 40px;
            }

            .card-box {
                background: #f8fafc;
                border-radius: 12px;
                padding: 20px;
                height: 100%;
                border: 1px solid #e5e7eb;
            }

            .label {
                font-size: 13px;
                color: #64748b;
                text-transform: uppercase;
                margin-bottom: 8px;
                font-weight: 600;
            }

            .table-responsive-invoice {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table thead {
                background: #2563eb;
                color: #fff;
            }

            .table th {
                border: none;
            }

            .table td {
                vertical-align: middle;
            }

            .summary {
                background: #f8fafc;
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                padding: 22px;
            }

            .summary-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                font-size: 15px;
            }

            .summary-item span:first-child {
                color: #6b7280;
                font-weight: 500;
            }

            .summary-item span:last-child {
                font-weight: 600;
                color: #111827;
            }

            .summary hr {
                margin: 15px 0;
                border-color: #dbe2ea;
            }

            .grand-total {
                display: flex;
                justify-content: space-between;
                align-items: center;

                background: #2563eb;
                color: #fff;

                padding: 16px 20px;

                border-radius: 12px;

                font-size: 22px;
                font-weight: 700;
            }

            .grand-total span:last-child {
                font-size: 26px;
            }

            .grand-total {
                background: #2563eb;
                color: white;
                border-radius: 10px;
                padding: 15px 20px;
                font-size: 24px;
                font-weight: bold;
            }

            .footer {
                background: #0f172a;
                color: #cbd5e1;
                text-align: center;
                padding: 25px;
                font-size: 14px;
            }

            .badge-status {
                color: white;
                padding: 8px 18px;
                border-radius: 30px;
                font-weight: 600;
                display: inline-block;
            }

            .badge-paid {
                background: #22c55e;
            }

            .badge-pending {
                background: #ef4444;
            }

            .badge-partial {
                background: #f59e0b;
            }

            .badge-draft {
                background: #6b7280;
            }

            .toolbar {
                max-width: 950px;
                width: calc(100% - 24px);
                margin: 16px auto 0;
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: .5rem;
            }

            .toolbar .btn {
                margin: 0 !important;
            }

            .company-header-flex {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            @media print {
                body {
                    background: white;
                }

                .invoice {
                    box-shadow: none;
                    margin: 0;
                    width: 100%;
                }

                .toolbar,
                .no-print {
                    display: none !important;
                }
            }

            .invoice-header {
                background: #fff;
                border-radius: 20px;
                border: 1px solid #e5e7eb;
                box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            }

            .company-logo {
                width: 90px;
                height: 90px;
                object-fit: contain;
                border-radius: 12px;
                background: #fff;
                padding: 8px;
                border: 1px solid #ddd;
                flex-shrink: 0;
            }

            .company-name {
                font-size: 30px;
                font-weight: 700;
                color: #1e3a8a;
                margin: 0;
                word-break: break-word;
            }

            .company-info {
                color: #6b7280;
                font-size: 14px;
                word-break: break-word;
            }

            .invoice-title {
                font-size: 42px;
                font-weight: 800;
                color: #2563eb;
                letter-spacing: 2px;
            }

            .invoice-number {
                font-size: 20px;
                font-weight: 700;
                color: #111827;
                word-break: break-all;
            }

            .invoice-date {
                font-size: 15px;
                color: #555;
                margin-bottom: 5px;
            }

            .status-badge {
                display: inline-block;
                padding: 8px 18px;
                border-radius: 50px;
                color: #fff;
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 1px;
            }

            .badge-paid {
                background: #16a34a;
            }

            .badge-partial {
                background: #f59e0b;
            }

            .badge-pending {
                background: #dc2626;
            }

            .badge-draft {
                background: #64748b;
            }

            @media (max-width: 991.98px) {
                .invoice {
                    margin: 16px auto;
                }

                .section {
                    padding: 24px;
                }

                .invoice-title {
                    font-size: 34px;
                }

                .company-name {
                    font-size: 24px;
                }
            }

            @media (max-width: 767.98px) {
                .toolbar {
                    justify-content: stretch;
                    width: calc(100% - 16px);
                }

                .toolbar .btn {
                    flex: 1 1 calc(50% - .5rem);
                    min-height: 40px;
                }

                .invoice {
                    width: calc(100% - 16px);
                    margin: 12px auto;
                    border-radius: 14px;
                }

                .invoice-header {
                    border-radius: 14px !important;
                    padding: 1rem !important;
                }

                .company-header-flex {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .company-logo {
                    width: 72px;
                    height: 72px;
                }

                .company-name {
                    font-size: 20px;
                }

                .company-info {
                    font-size: 13px;
                }

                .invoice-title {
                    font-size: 28px;
                    letter-spacing: 1px;
                }

                .invoice-number {
                    font-size: 16px;
                }

                .text-md-end {
                    text-align: left !important;
                }

                .section {
                    padding: 16px;
                }

                .card-box {
                    padding: 14px;
                    margin-bottom: .25rem;
                }

                .card-box h3 {
                    font-size: 1.1rem;
                }

                .grand-total {
                    font-size: 18px;
                    padding: 12px 14px;
                }

                .footer {
                    padding: 18px 14px;
                    font-size: 13px;
                }

                .table {
                    min-width: 560px;
                    font-size: 13px;
                }
            }

            @media (max-width: 575.98px) {
                .toolbar .btn {
                    flex: 1 1 100%;
                }

                .invoice-title {
                    font-size: 24px;
                }

                .status-badge {
                    padding: 6px 12px;
                    font-size: 11px;
                }
            }
        @endif
    </style>
</head>

<body>

    @unless ($isPdf)
        <div class="toolbar no-print">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-success btn-sm">
                Download PDF
            </a>
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#whatsappModal">
                Send WhatsApp
            </button>
        </div>

        <div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mx-3 mx-sm-auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="whatsappModalLabel">Send Invoice on WhatsApp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="whatsappForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="whatsappPhone" class="form-label fw-semibold">WhatsApp Number</label>
                                <input type="tel" id="whatsappPhone" name="phone" class="form-control"
                                    placeholder="e.g. 61481234567"
                                    value="{{ preg_replace('/\D+/', '', (string) ($invoice->phone ?? '')) }}" required>
                                <div class="form-text">Enter number with country code (no + or spaces).</div>
                            </div>
                            <div id="whatsappAlert" class="alert d-none mb-0" role="alert"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="whatsappSubmitBtn">
                                Send PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            (function() {
                const form = document.getElementById('whatsappForm');
                const phoneInput = document.getElementById('whatsappPhone');
                const alertBox = document.getElementById('whatsappAlert');
                const submitBtn = document.getElementById('whatsappSubmitBtn');

                function showAlert(type, message) {
                    alertBox.className = 'alert alert-' + type;
                    alertBox.textContent = message;
                    alertBox.classList.remove('d-none');
                }

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    alertBox.classList.add('d-none');

                    const phone = phoneInput.value.replace(/\D+/g, '');
                    if (phone.length < 8) {
                        showAlert('danger', 'Please enter a valid WhatsApp number with country code.');
                        return;
                    }

                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';

                    try {
                        const response = await fetch(@json(route('invoices.whatsapp', $invoice)), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token())
                            },
                            body: JSON.stringify({
                                phone
                            })
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            showAlert('danger', data.message || 'Failed to send WhatsApp message.');
                            return;
                        }

                        if (data.mode === 'web' && data.whatsapp_url) {
                            showAlert('success', data.message || 'Opening WhatsApp...');
                            window.open(data.whatsapp_url, '_blank');
                        } else {
                            showAlert('success', data.message || 'Invoice PDF sent on WhatsApp.');
                        }
                    } catch (error) {
                        showAlert('danger', 'Something went wrong while sending WhatsApp message.');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Send PDF';
                    }
                });
            })();
        </script>
    @endunless

    <div class="invoice">
        <div class="invoice-header shadow-sm rounded-4 p-4 mb-4">
            <div class="row align-items-center">

                <!-- Company Details -->
                <div class="col-12 col-lg-7 col-md-7">

                    <div class="company-header-flex">

                        @if (file_exists(public_path('images/logo1.gif')))
                            <img src="{{ $isPdf ? public_path('images/logo (2).png') : asset('images/logo (2).png') }}"
                                class="company-logo" alt="Logo">
                        @endif

                        <div>
                            <h2 class="company-name mb-1">WEBZONE EXPERTZ</h2>

                            <p class="company-info mb-1">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                21 Graeme St, Mooroopna VIC 3629
                            </p>

                            <p class="company-info mb-1">
                                <i class="fas fa-id-card me-2 text-primary"></i>
                                ABN: 73 478 018 645
                            </p>

                            <p class="company-info mb-1">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                0483915095
                            </p>

                            <p class="company-info mb-0">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                {{ $invoice->author_mail }}
                            </p>

                        </div>

                    </div>

                </div>

                <!-- Invoice Info -->
                <div class="col-12 col-lg-5 col-md-5 text-md-end mt-3 mt-md-0">

                    <h1 class="invoice-title mb-2">
                        INVOICE
                    </h1>

                    <div class="invoice-number mb-3">
                        #{{ $invoice->invoice_no }}
                    </div>

                    <div class="mb-2">
                        <div class="invoice-date">
                            <strong>Invoice Date :</strong>
                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                        </div>
                        <span class="status-badge {{ $invoice->balance_due <= 0 ? 'badge-paid' : 'badge-pending' }}">
                            {{ $invoice->balance_due <= 0 ? 'PAID' : 'DUE' }}
                        </span>

                        {{-- @if ($invoice->status === 'draft')
                        <span class="status-badge badge-draft ms-2">
                            DRAFT
                        </span>
                    @endif --}}
                    </div>


                    {{-- <div class="invoice-date">
                    <strong>Due :</strong>
                    {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
                </div> --}}

                </div>

            </div>
        </div>

        <div class="section">
            <div class="row g-3 g-md-4">
                <div class="col-12 col-md-6">
                    <div class="card-box">
                        <div class="label">Bill To</div>
                        <h3>{{ $invoice->company_name }}</h3>
                        <strong>{{ $invoice->customer_name }}</strong><br>
                        @if ($invoice->email)
                            {{ $invoice->email }}<br>
                        @endif
                        @if ($invoice->phone)
                            {{ $invoice->phone }}<br>
                        @endif
                        @if ($invoice->address)
                            {!! nl2br(e($invoice->address)) !!}
                        @endif
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-box">

                        <div class="label">Reference Code</div>
                        <div>{{ $invoice->reference_code ?? 'N/A' }}</div>

                        <div class="label mt-2">Customer Code</div>
                        <div>{{ $invoice->customer_code ?? 'N/A' }}</div>

                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-box">
                        <div class="label">Payment</div>
                        {{ $invoice->payment_method }}<br>
                        @if ($invoice->bank_name)
                            {{ $invoice->bank_name }}<br>
                        @endif
                        @if ($invoice->account_number)
                            Account: {{ $invoice->account_number }}<br>
                        @endif
                        @if ($invoice->bsb)
                            BSB: {{ $invoice->bsb }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="section pt-0">
            <div class="table-responsive-invoice">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $item->product }}
                                    @if ($item->description)
                                        <br><small class="text-muted">{{ $item->description }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ number_format($item->qty, 2) }}</td>
                                <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->tax, 2) }}%</td>
                                <td class="text-end">${{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section pt-0">
            <div class="row justify-content-end">
                <div class="col-12 col-md-6">
                    <div class="summary">

                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span>${{ number_format($invoice->subtotal, 2) }}</span>
                        </div>

                        <div class="summary-item">
                            <span>Tax</span>
                            <span>${{ number_format($invoice->tax_total, 2) }}</span>
                        </div>

                        @if ($invoice->paid_amount > 0)
                            <div class="summary-item">
                                <span>Paid</span>
                                <span class="text-success">
                                    -${{ number_format($invoice->paid_amount, 2) }}
                                </span>
                            </div>
                        @endif

                        <hr>

                        <div class="grand-total">
                            <span>
                                @if ($invoice->balance_due > 0 && $invoice->paid_amount > 0)
                                    BALANCE DUE
                                @else
                                    TOTAL
                                @endif
                            </span>

                            <span>
                                $
                                @if ($invoice->balance_due > 0 && $invoice->paid_amount > 0)
                                    {{ number_format($invoice->balance_due, 2) }}
                                @else
                                    {{ number_format($invoice->total, 2) }}
                                @endif
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <strong>Thank You!</strong><br>
            Thank you for choosing Webzone Expertz. We appreciate your business and look forward to serving you again in the future.
            Please include invoice number <strong>{{ $invoice->invoice_no }}</strong> when making payment.
        </div>
    </div>

</body>

</html>
