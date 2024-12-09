<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice</title>
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header .company-name {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .header .contact {
            font-size: 12px;
            text-align: center;
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
        .metadata {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header Section -->
        <div class="header">
            <div class="company-name">
                PlaceIt
            </div>
            <div class="contact">
                <p>Alaska Duljo Fatima Cebu City Cebu 6000</p>
                <p>placeit13@gmail.com</p>
            </div>
        </div>

        <!-- Title -->
        <h1>Payment Invoice</h1>

        <!-- Invoice Details -->
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
                <td>P{{ number_format($amount, 2) }}</td>
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

        <!-- Metadata Section -->
        <div class="metadata">
            <p><strong>Generated On:</strong> {{ $dategen }}</p>
            <p><strong>Reference:</strong> {{ $uniqueKey }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            This is a system-generated receipt. Please keep it for your records.
        </div>
    </div>
</body>
</html>
