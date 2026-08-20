<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Deleted</title>
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

        <h2 style="color:#dc3545; margin-top:0;">
            Invoice Deleted
        </h2>

        <p>
            An invoice has been deleted from the Invoice Management System.
        </p>

        <table width="100%" cellpadding="8" cellspacing="0"
               style="border-collapse:collapse;">

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Invoice ID / No.</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ $invoiceNo }}
                </td>
            </tr>

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Customer Name</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ $customerName }}
                </td>
            </tr>

            <tr>
                <td style="border-bottom:1px solid #eee;">
                    <strong>Customer Email</strong>
                </td>
                <td style="border-bottom:1px solid #eee;">
                    {{ $customerEmail }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Company Name</strong>
                </td>
                <td>
                    {{ $companyName }}
                </td>
            </tr>

        </table>

        <p style="margin-top:25px; color:#666;">
            This is an automatic notification from
            <strong>Diginamic Invoice Management</strong>.
        </p>

    </div>

</body>
</html>