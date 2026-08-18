<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice ? 'Edit Invoice' : 'Create Invoice' }} — Webzone Expertz</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
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

        .invoice-card {
            border: 0;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
            margin-bottom: 25px;
        }

        .invoice-card .card-header {
            background: #2563eb;
            color: #fff;
            border-radius: 15px 15px 0 0;
            padding: 15px 20px;
        }

        .invoice-card .card-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .form-label {
            font-weight: 600;
            color: #555;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            height: 45px;
        }

        textarea.form-control {
            height: auto;
        }

        .table thead {
            background: #2563eb;
            color: #fff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .summary-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .btn-add {
            background: #fa4d81;
            color: #fff;
        }

        .btn-add:hover {
            background: #f1d00f;
            color: #fff;
        }

        .btn-save {
            background: #16a34a;
            color: #fff;
        }

        .btn-save:hover {
            background: #15803d;
            color: #fff;
        }

        .btn-draft {
            background: #6b7280;
            color: #fff;
        }

        .btn-draft:hover {
            background: #4b5563;
            color: #fff;
        }

        .invoice-no-badge {
            background: rgba(255, 255, 255, .2);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 14px;
        }

        .text-end {
            display: flex;
            text-align: center !important;
        }


        @media (max-width: 576px) {
        .topbar .container-fluid {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 12px;
        }

        .brand-text {
            font-size: 25px;
            font-weight: 700;
            line-height: 1;
        }

        .topbar .ms-auto {
            margin-left: 0 !important;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .back-dashboard {
            font-size: 14px;
            padding: 7px 12px;
        }
    }
    </style>
</head>

<body>
    
<nav class="navbar navbar-expand-lg topbar shadow-sm">
    <div class="container-fluid page-wrap py-2 py-lg-0">

        <a class="navbar-brand brand-title d-flex align-items-center"
           href="{{ route('dashboard') }}">

            <img src="{{ asset('images/logo.png') }}"
                 alt="Webzone Expertz"
                 class="logo-img">

            {{-- <span class="brand-text">WEBZONE EXPERTZ</span> --}}
        </a>

        <div class="ms-auto">
            <a href="{{ route('dashboard') }}"
               class="btn btn-outline-light btn-sm back-dashboard">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>

    </div>
</nav>

    <div class="container py-2 pb-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
            action="{{ $invoice ? route('invoices.update', $invoice) : route('invoices.store') }}"
            id="invoiceForm">
            @csrf
            @if ($invoice)
                @method('PUT')
            @endif

            <!-- Invoice Meta -->
            <div class="card invoice-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-file-earmark-text me-1"></i> Invoice Details</h5>
                    {{-- <span class="invoice-no-badge">#{{ $invoice_no }}</span> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Author Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="author_mail" required
                                value="{{ old('author_mail', $invoice?->author_mail) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" class="form-control" name="invoice_date"
                                value="{{ old('invoice_date', $invoice?->invoice_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-control" name="due_date"
                                value="{{ old('due_date', $invoice?->due_date?->format('Y-m-d') ?? now()->addDays(7)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Reference Code</label>
                            <input type="text" class="form-control" name="reference_code"
                                value="{{ old('reference_code', $invoice?->reference_code) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Customer Code</label>
                            <input type="text" class="form-control" name="customer_code"
                                value="{{ old('customer_code', $invoice?->customer_code) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            {{-- <div class="card invoice-card">
                <div class="card-header">
                    <h5>Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" required
                                value="{{ old('company_name', $invoice?->company_name) }}">
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Customer Information -->
            <div class="card invoice-card">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" required
                                value="{{ old('company_name', $invoice?->company_name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="customer_name" required
                                value="{{ old('customer_name', $invoice?->customer_name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                value="{{ old('email', $invoice?->email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone"
                                value="{{ old('phone', $invoice?->phone) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="2" name="address">{{ old('address', $invoice?->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment -->

            <div class="card invoice-card">
                <div class="card-header">
                    <h5>Payment Information</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- Bank Name --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bank Name</label>

                            <select class="form-select" name="bank_name" id="bank_name">
                                <option value="">Select Bank</option>

                                <option value="SBI"
                                    {{ old('bank_name', $invoice?->bank_name) == 'SBI' ? 'selected' : '' }}>
                                    SBI
                                </option>

                                <option value="PNB"
                                    {{ old('bank_name', $invoice?->bank_name) == 'PNB' ? 'selected' : '' }}>
                                    PNB
                                </option>

                                <option value="HDFC"
                                    {{ old('bank_name', $invoice?->bank_name) == 'HDFC' ? 'selected' : '' }}>
                                    HDFC
                                </option>

                                <option value="AXIS"
                                    {{ old('bank_name', $invoice?->bank_name) == 'AXIS' ? 'selected' : '' }}>
                                    AXIS
                                </option>
                            </select>
                        </div>


                        {{-- BSB --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">BSB</label>

                            <input type="text"
                                class="form-control"
                                name="bsb"
                                id="bsb"
                                value="{{ old('bsb', $invoice?->bsb) }}">
                        </div>


                        {{-- Account Number --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Account Number</label>

                            <input type="text"
                                class="form-control"
                                name="account_number"
                                id="account_number"
                                value="{{ old('account_number', $invoice?->account_number) }}">
                        </div>


                        {{-- Payment Method --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                Payment Method <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" name="payment_method" required>

                                @foreach (['Cash', 'Bank Transfer', 'PayPal', 'Card'] as $method)

                                    <option value="{{ $method }}"
                                        {{ old(
                                            'payment_method',
                                            $invoice?->payment_method ?? 'Bank Transfer'
                                        ) === $method ? 'selected' : '' }}>

                                        {{ $method }}

                                    </option>

                                @endforeach

                            </select>
                        </div>

                    </div>
                </div>
            </div>


            {{-- <div class="card invoice-card">
                <div class="card-header">
                    <h5>Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name"
                                value="{{ old('bank_name', $invoice?->bank_name) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">BSB</label>
                            <input type="text" class="form-control" name="bsb"
                                value="{{ old('bsb', $invoice?->bsb) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" class="form-control" name="account_number"
                                value="{{ old('account_number', $invoice?->account_number) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select" name="payment_method" required>
                                @foreach (['Cash', 'Bank Transfer', 'PayPal', 'Card'] as $method)
                                    <option value="{{ $method }}"
                                        {{ old('payment_method', $invoice?->payment_method ?? 'Bank Transfer') === $method ? 'selected' : '' }}>
                                        {{ $method }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Products -->
            <div class="card invoice-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Invoice Items</h5>
                    <button type="button" class="btn btn-add btn-sm" id="addItemBtn">
                        <i class="bi bi-plus-lg"></i> Add More Items
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th width="90">Qty</th>
                                    <th width="130">Unit Price</th>
                                    <th width="100">Tax %</th>
                                    <th width="140">Amount</th>
                                    <th width="70">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                @php
                                    $oldProducts = old('product');
                                    $items = $oldProducts
                                        ? collect($oldProducts)->map(fn($p, $i) => (object) [
                                            'product' => $p,
                                            'description' => old('description.' . $i),
                                            'qty' => old('qty.' . $i, 1),
                                            'unit_price' => old('unit_price.' . $i, 0),
                                            'tax' => old('tax.' . $i, 0),
                                        ])
                                        : ($invoice?->items ?? collect([(object) ['product' => '', 'description' => '', 'qty' => 1, 'unit_price' => 0, 'tax' => 0]]));
                                @endphp
                                @foreach ($items as $item)
                                    <tr class="item-row">
                                        <td>
                                            <input class="form-control item-input" name="product[]" required
                                                value="{{ $item->product ?? '' }}">
                                        </td>
                                        <td>
                                            <textarea class="form-control item-input" rows="2" name="description[]">{{ $item->description ?? '' }}</textarea>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-input" name="qty[]"
                                                min="0.01" step="0.01" value="{{ $item->qty ?? 1 }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-input" name="unit_price[]"
                                                min="0" step="0.01" value="{{ $item->unit_price ?? 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-input" name="tax[]"
                                                min="0" step="0.01" value="{{ $item->tax ?? 0 }}">
                                        </td>
                                        <td>
                                            <input class="form-control line-amount" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="summary-box mt-4">
                <div class="row g-3">
            
                    <div class="col-md-2">
                        <label class="form-label">Subtotal</label>
                        <input class="form-control text-end" readonly id="subtotal">
                    </div>
            
                    <div class="col-md-2">
                        <label class="form-label">Tax</label>
                        <input class="form-control text-end" readonly id="tax_total">
                    </div>
            
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input class="form-control fw-bold text-end" readonly id="total">
                    </div>
            
                    <div class="col-md-2">
                        <label class="form-label">Paid</label>
                        <input class="form-control text-end"
                            name="paid_amount"
                            id="paid_amount">
                    </div>
            
                    <div class="col-md-2">
                        <label class="form-label">Balance</label>
                        <input class="form-control text-danger fw-bold text-end"
                            readonly
                            id="balance_due">
                    </div>
            
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <input class="form-control fw-bold text-center"
                            readonly
                            id="payment_status_auto">
                    </div>
            
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 me-2">Cancel</a>
                {{-- <button type="submit" name="action" value="draft" class="btn btn-draft px-4">
                    <i class="bi bi-save"></i> Save Draft
                </button> --}}
                <button type="submit" name="action" value="generate" class="btn btn-save px-4">
                    <i class="bi bi-file-earmark-check"></i> Generate Invoice
                </button>
            </div>
        </form>
    </div>

    <script>
        function formatMoney(n) {
            return parseFloat(n || 0).toFixed(2);
        }

        function calculateTotals() {
            let subtotal = 0;
            let taxTotal = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('[name="qty[]"]').value) || 0;
                const price = parseFloat(row.querySelector('[name="unit_price[]"]').value) || 0;
                const tax = parseFloat(row.querySelector('[name="tax[]"]').value) || 0;
                const line = qty * price;
                const lineTax = line * (tax / 100);
                const amount = line + lineTax;

                row.querySelector('.line-amount').value = formatMoney(amount);
                subtotal += line;
                taxTotal += lineTax;
            });

            const total = subtotal + taxTotal;
            const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
            const balance = Math.max(total - paid, 0);
            const paymentStatus = total > 0 && paid >= total ? 'Paid' : 'Due';

            document.getElementById('subtotal').value = formatMoney(subtotal);
            document.getElementById('tax_total').value = formatMoney(taxTotal);
            document.getElementById('total').value = formatMoney(total);
            document.getElementById('balance_due').value = formatMoney(balance);
            document.getElementById('payment_status_auto').value = paymentStatus;
        }

        function bindRowEvents(row) {
            row.querySelectorAll('.item-input').forEach(input => {
                input.addEventListener('input', calculateTotals);
            });
            row.querySelector('.remove-row').addEventListener('click', () => {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length <= 1) return;
                row.remove();
                calculateTotals();
            });
        }

        document.getElementById('addItemBtn').addEventListener('click', () => {
            const tbody = document.getElementById('itemsBody');
            const firstRow = tbody.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input:not([readonly]), textarea').forEach(el => {
                if (el.name === 'qty[]') el.value = '1';
                else if (el.name === 'tax[]' || el.name === 'unit_price[]') el.value = '0';
                else el.value = '';
            });
            newRow.querySelector('.line-amount').value = '0.00';
            tbody.appendChild(newRow);
            bindRowEvents(newRow);
            calculateTotals();
        });

        document.getElementById('paid_amount').addEventListener('input', calculateTotals);

        document.querySelectorAll('.item-row').forEach(bindRowEvents);
        calculateTotals();
    </script>

<script>
    const bankDetails = {
        "SBI": {
            bsb: "123456",
            account_number: "1234567890"
        },

        "PNB": {
            bsb: "062000",
            account_number: "123456789"
        },

        "HDFC": {
            bsb: "082000",
            account_number: "123456789"
        },

        "AXIS": {
            bsb: "013006",
            account_number: "123456789"
        }
    };

    document.getElementById('bank_name').addEventListener('change', function () {

        const bank = this.value;

        const bsbInput = document.getElementById('bsb');
        const accountInput = document.getElementById('account_number');

        if (bankDetails[bank]) {

            bsbInput.value = bankDetails[bank].bsb;
            accountInput.value = bankDetails[bank].account_number;

        } else {

            bsbInput.value = '';
            accountInput.value = '';
        }
    });
</script>


</body>

</html>
