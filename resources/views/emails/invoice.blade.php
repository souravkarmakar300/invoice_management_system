<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',Arial,sans-serif; margin:0; padding:0; }
        .wrap { max-width:600px; margin:32px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08); }
        .header { background:linear-gradient(135deg,#2563eb,#1e40af); color:#fff; padding:32px 36px; }
        .header h2 { margin:0 0 4px; font-size:22px; letter-spacing:.5px; }
        .header p { margin:0; opacity:.85; font-size:14px; }
        .body { padding:32px 36px; color:#374151; }
        .body p { font-size:15px; line-height:1.7; margin:0 0 16px; }
        .info-box { background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:18px 22px; margin:20px 0; }
        .info-box table { width:100%; border-collapse:collapse; }
        .info-box td { padding:6px 0; font-size:14px; color:#374151; }
        .info-box td:first-child { color:#6b7280; width:140px; font-weight:600; }
        .badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; color:#fff; }
        .badge-paid { background:#16a34a; }
        .badge-partial { background:#f59e0b; }
        .badge-pending { background:#dc2626; }
        .btn { display:inline-block; background:#2563eb; color:#fff; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600; font-size:15px; margin-top:8px; }
        .footer { background:#0f172a; color:#94a3b8; text-align:center; padding:20px 24px; font-size:13px; }
        .footer a { color:#60a5fa; text-decoration:none; }
    </style>
</head>
<body>
<div class="wrap">

    <div class="header">
        <h2>&#x1F4CB; Invoice {{ $invoice->invoice_no }}</h2>
        <p>Webzone Expertz</p>
    </div>

    <div class="body">

        <p>Dear <strong>{{ strtoupper($recipientName) }}</strong>,</p>

        <p>{{ $bodyMessage }}</p>

        <div class="info-box">
            <table>
                <tr>
                    <td>Invoice No</td>
                    <td><strong>{{ $invoice->invoice_no }}</strong></td>
                </tr>
                <tr>
                    <td>Invoice Date</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                </tr>
                @if ($invoice->due_date)
                <tr>
                    <td>Due Date</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                </tr>
                @endif
                <tr>
                    <td>Total</td>
                    <td><strong>${{ number_format($invoice->total, 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td>${{ number_format($invoice->total_paid, 2) }}</td>
                </tr>
                <tr>
                    <td>Due</td>
                    <td>${{ number_format($invoice->due_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <span class="badge {{ $invoice->paymentStatusBadgeClass() }}">
                            {{ strtoupper($invoice->paymentStatusLabel()) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <p>The invoice PDF is attached to this email for your records.</p>

        <p style="margin:0;">Best regards,<br><strong>Webzone Expertz</strong></p>

    </div>

    <div class="footer">
        &copy; {{ now()->year }} Webzone Expertz &bull;
        21 Graeme St, Mooroopna VIC 3629 &bull;
        <a href="mailto:{{ $invoice->author_mail }}">{{ $invoice->author_mail }}</a>
    </div>

</div>
</body>
</html>
