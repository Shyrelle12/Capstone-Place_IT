<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .receipt {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table td, table th {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>Payment Receipt</h1>
        <table>
            <tr>
                <th>Renter</th>
                <td>{{ $payer }}</td>
                <th>Owner</th>
                <td>{{ $payee }}</td>
            </tr>
            <tr>
                <th>Renter Email</th>
                <td>{{ $payerAddress }}</td>
                <th>Owner Email</th>
                <td>{{ $payeeAddress }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>${{ number_format($amount, 2) }}</td>
                <th>Space Title</th>
                <td>{{ $purpose }}</td>
            </tr>
            <tr>
                <th>Renter Number</th>
                <td>{{ $mobileNumber }}</td>
                <th>Owner Gcash Number</th>
                <td>{{ $receiptNumber }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>{{ $status }}</td>
                <th>Date</th>
                <td>{{ $date }}</td>
            </tr>
        </table>
        <div class="footer">
            This is a system-generated receipt. Please keep it for your records.
        </div>
    </div>
</body>
</html>
