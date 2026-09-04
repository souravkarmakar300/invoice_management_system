<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    @php($isPdf = $isPdf ?? false)
    @unless ($isPdf)
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @endunless
    <style>
        @if ($isPdf)
            @page {
                margin: 4mm;
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

                 user-select: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none; 
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
                margin-bottom: 10px;
            }

            .company-images {
                display: flex;
                align-items: center;
                gap: 5px;
                margin-top: 5px;
            }

            .company-images img {
                width: 100px !important;
                height: 25px !important;
                max-width: 100px !important;
                max-height: 25px !important;
                object-fit: contain;
                display: block;
                border: none;
                margin-bottom: 5px;
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
                font-size: 20px;
            }

            .company-logo {
                width: 200px;
                height: 100px;
                padding: 4px;
            }
            .mb-1{
                margin-bottom: 1px; !important;
            }

.contact-line {
    display: flex;
    align-items: center;
    gap: 4px;
    margin: 0 0 2px 0 !important;
    padding: 0;
    line-height: 1.1;
}

.contact-line:last-child {
    margin-bottom: 0 !important;
}

.contact-icon {
    width: 12px;
    height: 12px;
    object-fit: contain;
    flex: 0 0 12px;
}






.payment-history-footer {
    padding-top: 8px !important;
    padding-bottom: 8px !important;
}

.payment-history {
    width: 30%;
    margin-left: auto;
    margin-right: 0;
}

.payment-history-title {
    text-align: center;
    font-weight: 500;
    margin-bottom: 5px;
}

.payment-history-row {
    display: flex;
    text-align: right;
    justify-content: flex-end;
    gap: 8px;
    line-height: 1.6;
}

.payment-label {
    min-width: 90px;
    text-align: left;
}

.payment-date {
    min-width: 90px;
    text-align: left;
}

.payment-amount {
    min-width: 80px;
    text-align: right;
    white-space: nowrap;
}






            .payment-info {
                margin-bottom: 3px;
                line-height: 1.35;
            }

            .payment-info:last-child {
                margin-bottom: 0;
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

            /* .summary {
                padding: 12px;
                background: #f8fafc;
                border-radius: 8px;
            } */

            .summary {
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
            }

            .summary-item {
                overflow: hidden;
                padding: 5px 0;
                font-size: 11px;
            }

            .summary-item span:first-child {
                float: left;
                color: #6b7280;
            }

            .summary-item span:last-child {
                float: right;
                font-weight: 700;
            }

            /* .grand-total {
                font-size: 12px;
                padding: 10px 12px;
            } */


.grand-total {
    background: #2563eb !important;
    color: #fff !important;
    border-radius: 6px !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    text-align: center !important;
    padding: 7px 10px !important;
    margin: 0 !important;
    font-size: 12px !important;
    line-height: 1.2 !important;

}

.grand-total span:last-child {
    font-size: 14px !important;
}

.payment-history-cell {
    padding: 8px !important;
    background: #f8fafc !important;
    vertical-align: top !important;
}

.payment-history-inner {
    width: 58%;
    margin-left: auto;
    border-collapse: collapse;
}

.payment-history-inner td {
    border: none !important;
    padding: 3px 6px !important;
    font-size: 10px !important;
    vertical-align: middle;
}

.payment-history-inner .payment-history-title td {
    font-weight: 700;
    text-align: center;
    padding-bottom: 6px !important;
    color: #1e3a8a;
}

.payment-history-inner .payment-amount {
    text-align: right !important;
    font-weight: 700;
    white-space: nowrap;
}

.payment-empty {
    text-align: center !important;
    color: #6b7280;
    font-style: italic;
    padding: 8px 0 !important;
}

.invoice-items-table tfoot td {
    background: #f8fafc;
}

.invoice-items-table .invoice-total-row td {
    border-top: 2px solid #2563eb !important;
    font-weight: 700;
}

.invoice-items-table .invoice-paid-row td {
    font-weight: 600;
}

.item-description small {
    color: #6b7280;
    font-size: 10px;
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

            /* .card-box {
                background: #f8fafc;
                border: 1px solid #e5e7eb;
            } */

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
                background: #2563eb !important;
                color: #fff !important;
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

            .badge-partial {
                background: #f59e0b;
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

            .text-danger {
                color: #dc2626;
            }

            .float-end {
                float: right;
            }

            .invoice-header,
            .section,
            .footer {
                page-break-inside: avoid;
            }



            /* Remove gap before Remaining Balance */
.section.pt-0 {
    padding-top: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

.section.pt-0 .row {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

.section.pt-0 .summary {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.grand-total {
    margin-top: 0 !important;
}




/* =========================
   PDF FINAL SPACING FIX
   ========================= */

/* Remove space above Remaining Balance */
.summary {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.summary-item {
    margin-bottom: 0 !important;
    padding-top: 4px !important;
    padding-bottom: 4px !important;
}

/* Last item directly touches Remaining Balance */
.summary-item:last-of-type {
    padding-bottom: 2px !important;
    margin-bottom: 0 !important;
}

/* Remaining Balance */
.grand-total {
    background: #2563eb !important;
    color: #fff !important;
    border-radius: 6px !important;

    display: flex !important;
    justify-content: center !important;
    align-items: center !important;

    text-align: center !important;

    padding: 7px 10px !important;
    margin: 0 !important;

    font-size: 12px !important;
    line-height: 1.2 !important;
}

.grand-total span:last-child {
    font-size: 14px !important;
}



/* REMOVE SPACE BEFORE REMAINING BALANCE */
.section.pt-0 {
    padding: 0 !important;
    margin: 0 !important;
}

.section.pt-0 .row {
    margin: 0 !important;
    padding: 0 !important;
}

.section.pt-0 .col-12,
.section.pt-0 .col-md-6 {
    margin: 0 !important;
    padding: 0 !important;
}

.section.pt-0 .summary {
    margin: 0 !important;
    padding: 0 !important;
    background: transparent !important;
    border: none !important;
}

.section.pt-0 .summary-item {
    padding: 3px 0 !important;
    margin: 0 !important;
}

.section.pt-0 .grand-total {
    margin: 2px 0 0 0 !important;
    padding: 7px 10px !important;
}




        @else
            body {
                background: #eef2f7;
                font-family: 'Segoe UI', sans-serif;
                margin: 0;
                padding: 0 0 24px;
                overflow-x: hidden;


                 user-select: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;




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

            .invoice-items-table {
                margin-bottom: 0;
            }

            .invoice-items-table thead th {
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: .03em;
                padding: 12px 10px;
            }

            .invoice-items-table tbody td {
                padding: 12px 10px;
            }

            .invoice-items-table tfoot td {
                background: #f8fafc;
                padding: 10px;
            }

            .invoice-items-table .invoice-total-row td {
                border-top: 2px solid #2563eb;
                font-weight: 700;
            }

            .invoice-items-table .invoice-paid-row td {
                font-weight: 600;
            }

            .payment-history-cell {
                padding: 14px !important;
                background: #f8fafc;
            }

            .payment-history-inner {
                width: 60%;
                margin-left: auto;
                border-collapse: collapse;
            }

            .payment-history-inner td {
                border: none;
                padding: 6px 8px;
                font-size: 13px;
            }

            .payment-history-inner .payment-history-title td {
                font-weight: 700;
                text-align: center;
                color: #1e3a8a;
                padding-bottom: 8px;
            }

            .payment-history-inner .payment-amount {
                text-align: right;
                font-weight: 700;
                white-space: nowrap;
            }

            .payment-empty {
                text-align: center;
                color: #6b7280;
                font-style: italic;
            }

            .item-description small {
                color: #6b7280;
            }

            .table thead th {
                background: #2563eb;
                color: #fff;
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

                font-size: 18px;
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
                font-size: 18px;
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
                gap: 6px; /* small gap */
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
                margin-bottom: 10px;
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
                font-size: 20px;
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
                    margin-bottom: 10px;
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
        {{-- Bootstrap Icons --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

        <style>
            .modal-header-wa  { background: #25D366; color: #fff; }
            .modal-header-wa .btn-close { filter: brightness(0) invert(1); }
            .modal-header-mail { background: #2563eb; color: #fff; }
            .modal-header-mail .btn-close { filter: brightness(0) invert(1); }
        </style>

        <div class="toolbar no-print">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Download PDF
            </a>

            @if(auth()->user()->isSuperAdmin())
               @if ($invoice->due_amount > 0)
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="bi bi-cash-coin"></i> Record Payment
                    </button>
                @endif
            @endif
            
            <button type="button" class="btn btn-sm btn-success" style="background:#25D366;border-color:#25D366;"
                data-bs-toggle="modal" data-bs-target="#whatsappModal">
                <i class="bi bi-whatsapp"></i> Send WhatsApp
            </button> 
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#emailModal">
                <i class="bi bi-envelope-fill"></i> Send Email
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success mx-auto mt-3" style="max-width:950px;width:calc(100% - 24px);">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mx-auto mt-3" style="max-width:950px;width:calc(100% - 24px);">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mx-auto mt-3" style="max-width:950px;width:calc(100% - 24px);">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ===== RECORD PAYMENT MODAL ===== --}}
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mx-3 mx-sm-auto" style="max-width:520px;">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header" style="background:#0f172a;color:#fff;">
                        <h5 class="modal-title fw-bold" id="paymentModalLabel">
                            <i class="bi bi-cash-coin me-2"></i>Record Payment
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('invoices.payments.store', $invoice) }}">
                        @csrf
                        <div class="modal-body">
                            <div class="d-flex justify-content-between small mb-3 p-2 rounded" style="background:#f8fafc;">
                                <span>Invoice Total: <strong>${{ number_format($invoice->total, 2) }}</strong></span>
                                <span>Paid: <strong class="text-success">${{ number_format($invoice->total_paid, 2) }}</strong></span>
                                <span>Due: <strong class="text-danger">${{ number_format($invoice->due_amount, 2) }}</strong></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Amount</label>
                                <input type="number" name="amount" class="form-control" min="0.01"
                                    max="{{ $invoice->due_amount }}" step="0.01"
                                    value="{{ old('amount', number_format($invoice->due_amount, 2, '.', '')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Payment Date & Time</label>
                                <input type="datetime-local" name="paid_at" class="form-control"
                                    value="{{ old('paid_at', now()->format('Y-m-d\\TH:i')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    @foreach (['Bank Transfer', 'Cash', 'Card', 'PayPal'] as $method)
                                        <option value="{{ $method }}" {{ old('payment_method', $invoice->payment_method) === $method ? 'selected' : '' }}>
                                            {{ $method }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Reference Number</label>
                                <input type="text" name="reference_number" class="form-control"
                                    value="{{ old('reference_number') }}" placeholder="Txn / cheque / receipt no.">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning fw-semibold px-4">Save Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== WHATSAPP MODAL ===== --}}
        <div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mx-3 mx-sm-auto">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header modal-header-wa">
                        <h5 class="modal-title fw-bold" id="whatsappModalLabel">
                            <i class="bi bi-whatsapp me-2"></i>Send Invoice on WhatsApp
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="whatsappForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="whatsappPhone" class="form-label fw-semibold">WhatsApp Number</label>
                                <input type="tel" id="whatsappPhone" name="phone" class="form-control"
                                    placeholder="e.g. 61481234567"
                                    value="{{ preg_replace('/\D+/', '', (string) ($invoice->phone ?? '')) }}"
                                    required>
                                <div class="form-text">Enter number with country code (no + or spaces).</div>
                            </div>
                            <div id="whatsappAlert" class="alert d-none mb-0" role="alert"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success fw-semibold px-4" id="whatsappSubmitBtn"
                                style="background:#25D366;border-color:#25D366;">
                                Send PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== EMAIL MODAL ===== --}}
        <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mx-3 mx-sm-auto" style="max-width:500px;">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header modal-header-mail">
                        <h5 class="modal-title fw-bold" id="emailModalLabel">
                            <i class="bi bi-envelope-fill me-2"></i>Send Invoice by Email
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="emailForm">
                        <div class="modal-body pb-0">

                            <p class="text-muted small mb-3">
                                The invoice PDF will be attached and sent to the recipient's email address.
                            </p>

                            <div class="mb-3">
                                <label for="emailToName" class="form-label fw-semibold">Recipient Name</label>
                                <input type="text" id="emailToName" name="to_name" class="form-control"
                                    value="{{ $invoice->customer_name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="emailToAddress" class="form-label fw-semibold">Email Address</label>
                                <input type="email" id="emailToAddress" name="to_email" class="form-control"
                                    value="{{ $invoice->email }}" placeholder="recipient@example.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="emailMessage" class="form-label fw-semibold">Message <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea id="emailMessage" name="message" class="form-control" rows="3"
                                    placeholder="Leave blank to use the default message."></textarea>
                            </div>

                            <div id="emailAlert" class="alert d-none mb-0" role="alert"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-4" id="emailSubmitBtn">
                                <i class="bi bi-send-fill me-1"></i> Send Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        (function () {

            // ---------- helpers ----------
            function showAlert(boxId, type, html) {
                const el = document.getElementById(boxId);
                el.className = 'alert alert-' + type;
                el.innerHTML = html;
                el.classList.remove('d-none');
            }
            function hideAlert(boxId) {
                document.getElementById(boxId).classList.add('d-none');
            }

            // ---------- WhatsApp ----------
            const waForm      = document.getElementById('whatsappForm');
            const waPhoneInput = document.getElementById('whatsappPhone');
            const waSubmitBtn = document.getElementById('whatsappSubmitBtn');

            waForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                hideAlert('whatsappAlert');

                const phone = waPhoneInput.value.replace(/\D+/g, '');
                if (phone.length < 8) {
                    showAlert('whatsappAlert', 'danger', 'Please enter a valid WhatsApp number with country code.');
                    return;
                }

                waSubmitBtn.disabled = true;
                waSubmitBtn.textContent = 'Sending...';

                try {
                    const res = await fetch(@json(route('invoices.whatsapp', $invoice)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token())
                        },
                        body: JSON.stringify({ phone })
                    });

                    const data = await res.json();

                    if (!res.ok || !data.success) {
                        showAlert('whatsappAlert', 'danger', data.message || 'Failed to send WhatsApp message.');
                        return;
                    }

                    if (data.mode === 'web' && data.whatsapp_url) {
                        window.location.href = data.whatsapp_url;
                        return;
                    }

                    if (data.whatsapp_url) {
                        window.location.href = data.whatsapp_url;
                        return;
                    }

                    showAlert('whatsappAlert', 'success', data.message || 'Invoice PDF sent on WhatsApp.');
                } catch (err) {
                    showAlert('whatsappAlert', 'danger', 'Something went wrong. Please try again.');
                } finally {
                    waSubmitBtn.disabled = false;
                    waSubmitBtn.textContent = 'Send PDF';
                }
            });

            // ---------- Email ----------
            const emailForm      = document.getElementById('emailForm');
            const emailSubmitBtn = document.getElementById('emailSubmitBtn');

            emailForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                hideAlert('emailAlert');

                const toEmail  = document.getElementById('emailToAddress').value.trim();
                const toName   = document.getElementById('emailToName').value.trim();
                const message  = document.getElementById('emailMessage').value.trim();

                if (!toEmail || !toName) {
                    showAlert('emailAlert', 'danger', 'Please fill in recipient name and email.');
                    return;
                }

                emailSubmitBtn.disabled = true;
                emailSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';

                try {
                    const res = await fetch(@json(route('invoices.email', $invoice)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token())
                        },
                        body: JSON.stringify({ to_email: toEmail, to_name: toName, message })
                    });

                    const data = await res.json();

                    if (!res.ok || !data.success) {
                        showAlert('emailAlert', 'danger', data.message || 'Failed to send email.');
                        return;
                    }

                    showAlert('emailAlert', 'success', '✅ ' + (data.message || 'Invoice email sent successfully.'));
                    emailForm.reset();
                    document.getElementById('emailToName').value  = @json($invoice->customer_name);
                    document.getElementById('emailToAddress').value = @json($invoice->email ?? '');

                } catch (err) {
                    showAlert('emailAlert', 'danger', 'Something went wrong. Please try again.');
                } finally {
                    emailSubmitBtn.disabled = false;
                    emailSubmitBtn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Send Email';
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

                        @if (file_exists(public_path('images/logo.png')))
                            <img src="{{ $isPdf ? public_path('images/logo.png') : asset('images/logo.png') }}"
                                class="company-logo" alt="Logo">
                        @endif

                        <div>
    <h2 class="company-name mb-1">WEBZONE EXPERTZ</h2>

    {{-- Call --}}
    <p class="company-info mb-1 contact-line">
        @if ($isPdf)
            <img src="{{ public_path('images/call_img.jpg') }}"
                 class="contact-icon"
                 alt="Call">
        @else
            <i class="fas fa-phone contact-icon"></i>
        @endif

        <span>+12272571716</span>
    </p>

    {{-- WhatsApp --}}
    <p class="company-info mb-1 contact-line">
        @if ($isPdf)
            <img src="{{ public_path('images/whatsapp img.jpg') }}"
                 class="contact-icon"
                 alt="WhatsApp">
        @else
            <i class="fab fa-whatsapp contact-icon whatsapp-icon"></i>
        @endif

        <span>+15176455057</span
    </p>

    @if($isPdf)

        <div class="company-images" style="margin-top: 2px;">
            <span style="display: block; font-size: 10px; color: #666; margin-bottom: 4px;">
                Countries We Operate In
            </span>

            <img
                src="{{ public_path('images/flags.png') }}"
                alt="Flags"
                style="display: block; width: 155px; margin: 5px auto 0 auto;"
            >
        </div>

        @else

        <div class="company-images" style="margin-top: 2px; display: flex; flex-direction: column;">
            <span style="font-size: 10px; color: #666; margin-bottom: 4px;">
                Countries We Operate In
            </span>

            <img
                src="{{ asset('images/flags.png') }}"
                alt="Flags"
                style="display: block; margin-top: 5px; width: 145px;"
            >
        </div>

    @endif


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
                        <span class="status-badge {{ $invoice->paymentStatusBadgeClass() }}">
                            {{ strtoupper($invoice->paymentStatusLabel()) }}
                        </span>

                    </div>

                </div>

            </div>
        </div>

        <div class="section">
            <div class="row g-3 g-md-4">
                <div class="col-12 col-sm-6 col-md-4">
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
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card-box">

                        <div class="label">Invoice Author Name</div>
                        <div>{{ $invoice->author_name ?? 'N/A' }}</div>

                        <div class="label mt-2">Invoice Author mail</div>
                        <div>{{ $invoice->author_mail }}</div>

                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card-box">
                        <div class="label">Payment</div>

                        @if ($invoice->payment_gateway)
                            <div class="payment-info">
                                Payment Gateway: {{ $invoice->payment_gateway }}
                            </div>
                        @endif

                        @if ($invoice->bank_name)
                            <div class="payment-info">
                                Bank Name: {{ $invoice->bank_name }}
                            </div>
                        @endif

                        @if ($invoice->account_number)
                            <div class="payment-info">
                                Account No.: {{ $invoice->account_number }}
                            </div>
                        @endif
                        
                        @if ($invoice->bank_address)
                            <div class="payment-info">
                                Bank Address: {{ $invoice->bank_address }}
                            </div>
                        @endif


                        @if($invoice->payment_method)
                            <div class="payment-info">
                                Payment Method: {{ $invoice->payment_method }}
                            </div>
                        @endif

                        <div class="payment-info">
                            Currency: {{ $invoice->currency }}
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="section pt-0">
            <div class="table-responsive-invoice">
                <table class="table invoice-items-table">

                    <thead>
                        <tr>
                            <th width="6%">#</th>
                            <th>Description</th>
                            <th class="text-center" width="12%">Qty</th>
                            <th class="text-end" width="18%">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($invoice->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="item-description">
                                    <strong>{{ $item->product }}</strong>
                                    @if ($item->description)
                                        <br>
                                        <small>{{ $item->description }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ number_format($item->qty, 2) }}</td>
                                <td class="text-end">${{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="invoice-total-row">
                            <td colspan="3" class="text-end">
                                <strong>Total Amount (Project Cost)</strong>
                            </td>
                            <td class="text-end">
                                <strong>${{ number_format($invoice->total, 2) }}</strong>
                            </td>
                        </tr>

                        <tr class="invoice-paid-row">
                            <td colspan="3" class="text-end">Total Paid</td>
                            <td class="text-end">${{ number_format($invoice->total_paid, 2) }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" class="payment-history-cell">
                                <table class="payment-history-inner" width="100%" cellspacing="0" cellpadding="0">
                                    <tr class="payment-history-title">
                                        <td colspan="3">Payment History</td>
                                    </tr>

                                    @forelse ($invoice->payments->sortBy('paid_at')->values() as $index => $payment)
                                        <tr>
                                            <td class="payment-label">
                                                @if ($index === 0)
                                                    1st Payment
                                                @elseif ($index === 1)
                                                    2nd Payment
                                                @elseif ($index === 2)
                                                    3rd Payment
                                                @else
                                                    {{ $index + 1 }}th Payment
                                                @endif
                                            </td>
                                            <td class="payment-date">
                                                {{ $payment->paid_at?->format('d M Y') ?? '—' }}
                                            </td>
                                            <td class="payment-amount">
                                                ${{ number_format($payment->amount, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center payment-empty">
                                                No payment recorded
                                            </td>
                                        </tr>
                                    @endforelse
                                </table>
                            </td>
                        </tr>
                    </tfoot>

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
                            <span>+${{ number_format($invoice->tax_total, 2) }}</span>
                        </div>

                        <div class="summary-item">
                            <span>Invoice Total</span>
                            <span>${{ number_format($invoice->total, 2) }}</span>
                        </div>

                        <div class="summary-item">
                            <span>Paid</span>
                            <span class="text-success">${{ number_format($invoice->total_paid, 2) }}</span>
                        </div>

                        <div class="summary-item">
                            <span>Due</span>
                            <span class="text-danger">${{ number_format($invoice->due_amount, 2) }}</span>
                        </div>

                        <div class="summary-item">
                            <span>Payment Status</span>
                            <span>{{ $invoice->paymentStatusLabel() }}</span>
                        </div>

                        <div class="summary-item">
                            <span>Last Paid Amount</span>
                            <span>${{ number_format(optional($invoice->payments->first())->amount ?? 0, 2) }}</span>
                        </div>

                        <div class="grand-total" style="margin: 5px auto !important;
                        display: table !important; width: 100% !important; ">
                            <span>{{ $invoice->due_amount > 0 ? 'REMAINING BALANCE' : 'TOTAL PAID' }}</span>
                            <span>
                                $
                                {{ $invoice->due_amount > 0
                                    ? number_format($invoice->due_amount, 2)
                                    : number_format($invoice->total, 2) }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @unless ($isPdf)
            <div class="section pt-0 no-print">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h5 class="mb-0 fw-bold">Payment History</h5>
                    @if(auth()->user()->isSuperAdmin())
                        @if ($invoice->due_amount > 0)
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="bi bi-plus-lg"></i> Record Payment
                        </button>
                    @endif
                    @endif
                    
                </div>
                <div class="table-responsive-invoice">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date & Time</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Notes</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoice->payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $payment->paid_at?->format('d M Y, h:i A') ?? '—' }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ $payment->reference_number ?: '—' }}</td>
                                    <td>{{ $payment->notes ?: '—' }}</td>
                                    <td class="text-end">${{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No payments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="small text-muted">
                    Invoice Total ${{ number_format($invoice->total, 2) }}
                    &nbsp;|&nbsp; Paid ${{ number_format($invoice->total_paid, 2) }}
                    &nbsp;|&nbsp; Due ${{ number_format($invoice->due_amount, 2) }}
                    &nbsp;|&nbsp; Status: {{ $invoice->paymentStatusLabel() }}
                </div>
            </div>
        @endunless

        <div style="
            font-size: 9px;
            line-height: 1.4;
            margin-top: 4px;
            margin-bottom: 6px;
            padding: 7px 10px;
            border-left: 3px solid #2563eb;
            background: #f8fafc;
            color: #374151;
        ">
            <strong style="
                display: block;
                font-size: 10px;
                color: #111827;
                margin-bottom: 4px;
            ">
                Invoice Terms &amp; Conditions
            </strong>

            <ol style="
                margin: 0;
                padding-left: 18px;
            ">
                <li>
                    <strong>Proposal Acceptance:</strong>
                    Receipt of this invoice constitutes the Client’s acceptance and approval
                    of the proposal, scope of work, terms, and invoiced amount.
                </li>

                <li>
                    <strong>Payment Verification:</strong>
                    The Client must verify the authenticity of <strong>Webzone Expertz</strong>
                    and the invoice/payment details directly with us before making any payment.
                </li>

                <li>
                    <strong>Non-Refundable:</strong>
                    All payments made to <strong>Webzone Expertz are strictly non-refundable</strong>.
                </li>

                <li>
                    <strong>Unauthorized Claims:</strong>
                    If the Client receives any communication claiming that
                    <strong>Webzone Expertz has closed, ceased operations, or been taken over</strong>,
                    no payment should be made based on such information.
                </li>

                <li>
                    <strong>Immediate Notification:</strong>
                    Any such communication must be reported to
                    <strong>Webzone Expertz immediately</strong>, and the Client should contact
                    us directly for verification.
                </li>
            </ol>
        </div>

        <div class="footer">
            <strong>Thank You!</strong><br>
            Thank you for choosing Webzone Expertz. We appreciate your business and look forward to serving you again in the future.
            Please include invoice number <strong>{{ $invoice->invoice_no }}</strong> when making payment.
        </div>
    </div>

</body>

</html>
