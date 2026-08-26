<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Created</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:30px;">

    <div style="
        max-width:600px;
        margin:auto;
        background:#ffffff;
        padding:30px;
        border-radius:8px;
        border:1px solid #ddd;
    ">

        <h2 style="color:#2563eb; margin-top:0;">
            Invoice Created Successfully
        </h2>

        <p>
            A new invoice has been created in the Invoice Management System.
        </p>

        <table width="100%" cellpadding="8" cellspacing="0"
               style="border-collapse:collapse;">

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Invoice ID / No.</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ $invoice->invoice_no }}
                </td>
            </tr>

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Customer Name</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ $invoice->customer_name }}
                </td>
            </tr>

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Customer Email</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ $invoice->email }}
                </td>
            </tr>

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Company Name</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ $invoice->company_name }}
                </td>
            </tr>

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Total Amount</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ number_format($invoice->total, 2) }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Status</strong>
                </td>
                <td>
                    {{ ucfirst($invoice->status) }}
                </td>
            </tr>

        </table>

        <p style="margin-top:25px; color:#666;">
            This is an automatic notification from
            <strong>WebZone Expertz Invoice Management</strong>.
        </p>

    </div>

</body>
</html>