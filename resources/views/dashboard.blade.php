<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Dashboard — Webzone Expertz</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #1d4ed8;
            --brand-dark: #1e3a8a;
            --surface: #f3f6fb;
            --card: #ffffff;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --success: #15803d;
            --danger: #b91c1c;
            --warn: #b45309;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(29, 78, 216, .08), transparent 28%),
                linear-gradient(180deg, #eef3fb 0%, var(--surface) 40%, #e8eef8 100%);
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
        }

        .topbar {
            background: linear-gradient(135deg, #dedfd8 0%, #566692 55%, #6e8dce 100%);
            border: 0;
            padding: .9rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .brand-title{
    display: inline-flex !important;
    align-items: center;
    gap: 10px;
    flex-wrap: nowrap !important;
    white-space: nowrap;
    color: #fff !important;
    text-decoration: none;
}

.logo-img{
    width: 200px;
    height: 80px;
    flex-shrink: 0;
}

.brand-text{
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
}

        .page-wrap {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem 1.25rem 2.5rem;
        }

        .hero-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, .95), rgba(255, 255, 255, .88));
            border: 1px solid rgba(226, 232, 240, .9);
            border-radius: 22px;
            padding: 1.4rem 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .06);
            margin-bottom: 1.25rem;
        }

        .hero-panel h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            margin: 0;
        }

        .hero-panel p {
            color: var(--muted);
            margin: .35rem 0 0;
        }

        .stat-card {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--card);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .04);
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: auto -20px -30px auto;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            opacity: .12;
        }

        .stat-card.all::after {
            background: #2563eb;
        }

        .stat-card.paid::after {
            background: #16a34a;
        }

        .stat-card.due::after {
            background: #dc2626;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .stat-card.all .stat-icon {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .stat-card.paid .stat-icon {
            background: #dcfce7;
            color: #15803d;
        }

        .stat-card.due .stat-icon {
            background: #fee2e2;
            color: #b91c1c;
        }

        .stat-label {
            color: var(--muted);
            font-size: .82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .stat-sub {
            color: var(--muted);
            font-size: .85rem;
            margin-top: .2rem;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: 20px;
            background: var(--card);
            box-shadow: 0 14px 34px rgba(15, 23, 42, .05);
            overflow: hidden;
        }

        .panel-head {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(180deg, #fff, #f8fafc);
        }

        .panel-body {
            padding: 1.15rem 1.25rem 1.35rem;
        }

        .filter-box {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 1rem;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border-color: #cbd5e1;
            min-height: 44px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-brand {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .btn-brand:hover {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
            color: #fff;
        }

        .btn-export {
            background: #0f766e;
            border-color: #0f766e;
            color: #fff;
        }

        .btn-export:hover {
            background: #115e59;
            border-color: #115e59;
            color: #fff;
        }

        .table-wrap {
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
        }

        .table-compact {
            font-size: .86rem;
        }

        #invoiceTable {
            margin: 0;
        }

        #invoiceTable thead th {
            background: #0f172a;
            color: #fff;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
            border: 0;
            white-space: nowrap;
            padding: .62rem .55rem;
        }

        #invoiceTable tbody td {
            border-color: #eef2f7;
            padding: .38rem .30rem;
            vertical-align: middle;
        }

        #invoiceTable tbody tr:hover {
            background: #f8fbff;
        }

        .invoice-no {
            font-weight: 700;
            color: var(--brand);
        }

        .company-cell {
            font-weight: 600;
        }

        .muted-cell {
            color: var(--muted);
            font-size: .9rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            padding: .35rem .7rem;
            font-size: .75rem;
            font-weight: 700;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-partial {
            background: #ffedd5;
            color: #c2410c;
        }

        .status-due {
            background: #fef3c7;
            color: #92400e;
        }

        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-group {
            display: inline-flex;
            gap: .35rem;
            flex-wrap: wrap;
        }

        .action-group .btn {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 2.4rem;
            color: #94a3b8;
        }

        .user-chip {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 999px;
            padding: .4rem .85rem;
            color: #fff;
            text-decoration: none;
        }

        .alert {
            border-radius: 14px;
        }

        .mobile-invoice-list {
            display: none;
        }

        .desktop-table {
            display: block;
        }

        .invoice-mobile-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
            padding: 1rem;
            margin-bottom: .85rem;
            box-shadow: 0 6px 16px rgba(15, 23, 42, .04);
        }

        .invoice-mobile-card .meta-row {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .55rem;
        }

        .invoice-mobile-card .label-soft {
            color: var(--muted);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            font-weight: 600;
        }

        .filter-actions .btn {
            white-space: nowrap;
        }

        @media (max-width: 1199.98px) {
            .page-wrap {
                padding: 1.2rem 1rem 2rem;
            }

            .stat-value {
                font-size: 1.55rem;
            }

            #invoiceTable thead th {
                font-size: .72rem;
                padding: .55rem .45rem;
            }

            #invoiceTable tbody td {
                padding: .38rem .30rem;
                font-size: .8rem;
            }

            .action-group .btn {
                width: 30px;
                height: 30px;
            }
        }

        @media (max-width: 991.98px) {
            .hero-panel {
                border-radius: 18px;
                padding: 1.15rem;
            }

            .hero-panel h1 {
                font-size: 1.4rem;
            }

            .panel,
            .panel-head,
            .panel-body {
                border-radius: 16px;
            }

            .panel-body {
                padding: 1rem;
            }

            .filter-actions {
                width: 100%;
            }

            .filter-actions .btn {
                flex: 1 1 auto;
            }

            .desktop-table .table-responsive {
                overflow-x: auto;
            }

            #invoiceTable th:nth-child(5),
            #invoiceTable td:nth-child(5),
            #invoiceTable th:nth-child(6),
            #invoiceTable td:nth-child(6) {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .page-wrap {
                padding: .9rem .75rem 1.6rem;
            }

            .topbar .page-wrap {
                padding-left: .75rem;
                padding-right: .75rem;
            }

            .brand-title {
                font-size: 1rem;
            }

            .user-chip {
                padding: .35rem .65rem;
                font-size: .85rem;
            }

            .hero-panel {
                margin-bottom: 1rem;
                padding: 1rem;
            }

            .hero-panel h1 {
                font-size: 1.25rem;
            }

            .hero-panel p {
                font-size: .9rem;
            }

            .hero-actions {
                width: 100%;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .stat-value {
                font-size: 1.4rem;
            }

            .stat-sub {
                font-size: .8rem;
            }

            .desktop-table {
                display: none !important;
            }

            .mobile-invoice-list {
                display: block;
            }

            .panel-head h5 {
                font-size: 1rem;
            }

            .filter-actions .btn {
                width: 100%;
            }

            .pagination {
                justify-content: center;
            }
        }

        @media (max-width: 575.98px) {
            .stat-icon {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }

            .action-group .btn {
                width: 38px;
                height: 38px;
            }

            .brand-title{
        gap: 6px;
    }

    .topbar .page-wrap {
        padding-left: .75rem;
        padding-right: .75rem;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-content: space-between;
        justify-content: center;
        align-items: center;   
    }     

    .logo-img{
        width: 34px;
        height: 34px;
    }

    .brand-text{
        font-size: 14px;
    }

    .user-chip{
        padding: 4px 8px;
        font-size: 12px;
    }

    .user-chip i{
        font-size: 16px;
    }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg topbar shadow-sm">
        <div class="container-fluid page-wrap py-0">
            <a class="navbar-brand brand-title" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.png') }}"
                    alt="Webzone Expertz"
                    class="logo-img">
            
                {{-- <span class="brand-text">WEBZONE EXPERTZ</span> --}}
            </a>
            <div class="ms-auto">
                <div class="dropdown">
                    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <span class="dropdown-item-text small text-muted">
                                Role: <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                            </span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="page-wrap">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="hero-panel">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1>Invoice Dashboard</h1>
                    <p>Track payments, filter customers, and export all invoice records.</p>
                </div>
                <div class="d-flex flex-wrap gap-2 hero-actions">
                    <a href="{{ route('invoices.export', request()->query()) }}" class="btn btn-export">
                        <i class="bi bi-filetype-csv me-1"></i> Print All Data (CSV)
                    </a>
                    <a href="{{ route('create_invoice') }}" class="btn btn-brand">
                        <i class="bi bi-plus-lg me-1"></i> Create Invoice
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="stat-card all">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">All Invoices</div>
                                <div class="stat-value mt-1">{{ $stats['all'] }}</div>
                                <div class="stat-sub">Total ${{ number_format($stats['total_amount'] ?? 0, 2) }}</div>
                            </div>
                            <span class="stat-icon"><i class="bi bi-journal-text"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="stat-card paid">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Paid</div>
                                <div class="stat-value mt-1 text-success">{{ $stats['paid'] }}</div>
                                <div class="stat-sub">Collected ${{ number_format($stats['paid_amount'] ?? 0, 2) }}
                                </div>
                            </div>
                            <span class="stat-icon"><i class="bi bi-check2-circle"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4">
                <div class="stat-card due">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Due</div>
                                <div class="stat-value mt-1 text-danger">{{ $stats['due'] }}</div>
                                <div class="stat-sub">Outstanding ${{ number_format($stats['due_amount'] ?? 0, 2) }}
                                </div>
                            </div>
                            <span class="stat-icon"><i class="bi bi-exclamation-circle"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-1 text-primary"></i> Customer Invoices
                    </h5>
                    <div class="small text-muted mt-1">Search by company or invoice number, then filter by payment
                        status.</div>
                </div>
            </div>

            <div class="panel-body">
                <form method="GET" action="{{ route('dashboard') }}" class="filter-box mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-lg-5">
                            <label class="form-label small fw-semibold text-muted mb-1">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0"
                                    placeholder="Company name or invoice number or author mail"
                                    value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Payment Status</label>
                            <select name="payment_filter" class="form-select">
                                <option value="all" {{ $paymentFilter === 'all' ? 'selected' : '' }}>All Status
                                </option>
                                <option value="paid" {{ $paymentFilter === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ $paymentFilter === 'partial' ? 'selected' : '' }}>Due</option>
                                {{-- <option value="due" {{ $paymentFilter === 'due' ? 'selected' : '' }}>Due</option> --}}
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 d-flex flex-wrap gap-2 filter-actions">
                            <button class="btn btn-brand" type="submit">
                                <i class="bi bi-funnel me-1"></i> Apply
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                            <a href="{{ route('invoices.export', request()->query()) }}"
                                class="btn btn-outline-success">
                                <i class="bi bi-download me-1"></i> Export CSV
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-wrap desktop-table">
                    <table class="table table-sm table-hover align-middle mb-0 table-compact" id="invoiceTable">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Author</th>
                                <th>Payment</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Due</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="muted-cell">{{ $invoices->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="invoice-no">{{ $invoice->invoice_no }}</div>
                                        <div class="small text-muted">
                                            {{ $invoice->invoice_date?->format('d M Y') ?? '—' }}
                                        </div>
                                    </td>
                                    <td>{{ $invoice->customer_name }}</td>
                                    <td class="company-cell">{{ $invoice->company_name }}</td>
                                    <td class="muted-cell">{{ $invoice->email ?? '—' }}</td>
                                    <td class="muted-cell">{{ $invoice->author_mail ?? '—' }}</td>
                                    <td>
                                        <span class="status-badge {{ $invoice->paymentStatusClass() }}">
                                            <i class="bi bi-circle-fill" style="font-size:.45rem"></i>
                                            {{ $invoice->paymentStatusLabel() === 'Partial' ? 'Due' : $invoice->paymentStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold">${{ number_format($invoice->total, 2) }}</td>
                                    <td class="text-end text-success">${{ number_format($invoice->total_paid, 2) }}</td>
                                    <td class="text-end text-danger">${{ number_format($invoice->due_amount, 2) }}</td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('invoices.show', $invoice) }}"
                                                class="btn btn-sm btn-info text-white" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.download', $invoice) }}"
                                                class="btn btn-sm btn-success" title="Download PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                            @if (auth()->user()->isSuperAdmin())
                                                <a href="{{ route('invoices.edit', $invoice) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('invoices.destroy', $invoice) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox d-block mb-2"></i>
                                            <div class="fw-semibold text-dark mb-1">No invoices found</div>
                                            <div class="mb-3">Create your first invoice or reset the current filters.
                                            </div>
                                            <a href="{{ route('create_invoice') }}" class="btn btn-brand btn-sm">
                                                <i class="bi bi-plus-lg me-1"></i> Create Invoice
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mobile-invoice-list">
                    @forelse($invoices as $invoice)
                        <div class="invoice-mobile-card">
                            <div class="meta-row">
                                <div>
                                    <div class="label-soft">Invoice</div>
                                    <div class="invoice-no">{{ $invoice->invoice_no }}</div>
                                    <div class="small text-muted">
                                        {{ $invoice->invoice_date?->format('d M Y') ?? '—' }}</div>
                                </div>
                                <div class="text-end">
                                    <span class="status-badge {{ $invoice->paymentStatusClass() }}">{{ $invoice->paymentStatusLabel() }}</span>
                                    <div class="fw-bold mt-1">${{ number_format($invoice->total, 2) }}</div>
                                </div>
                            </div>
                            <div class="meta-row">
                                <div>
                                    <div class="label-soft">Customer</div>
                                    <div>{{ $invoice->customer_name }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="label-soft">Company</div>
                                    <div class="company-cell">{{ $invoice->company_name }}</div>
                                </div>
                            </div>
                            <div class="muted-cell mb-2">
                                <div>{{ $invoice->email ?? '—' }}</div>
                                <div>{{ $invoice->author_mail ?? '—' }}</div>
                                <div class="mt-1">
                                    Total ${{ number_format($invoice->total, 2) }}
                                    &nbsp;|&nbsp; Paid ${{ number_format($invoice->total_paid, 2) }}
                                    &nbsp;|&nbsp; Due ${{ number_format($invoice->due_amount, 2) }}
                                </div>
                            </div>
                            <div class="action-group">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                    class="btn btn-sm btn-info text-white" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-sm btn-success"
                                    title="Download PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                                @if (auth()->user()->isSuperAdmin())
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox d-block mb-2"></i>
                            <div class="fw-semibold text-dark mb-1">No invoices found</div>
                            <div class="mb-3">Create your first invoice or reset the current filters.</div>
                            <a href="{{ route('create_invoice') }}" class="btn btn-brand btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Create Invoice
                            </a>
                        </div>
                    @endforelse
                </div>

                @if ($invoices->hasPages())
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                        <div class="text-muted small">
                            Showing {{ $invoices->firstItem() ?? 0 }}
                            to {{ $invoices->lastItem() ?? 0 }}
                            of {{ $invoices->total() }} entries
                        </div>
                        <div>{{ $invoices->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Delete invoice?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
</body>

</html>
