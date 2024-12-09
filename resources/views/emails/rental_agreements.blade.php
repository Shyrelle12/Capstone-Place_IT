<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Agreement Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 10px;
        }
        .header {
            display: center;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 0;
            border-bottom: 2px solid #ddd;
        }
        .header .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .header .contact {
            font-size: 14px;
            text-align: center;
            color: #555;
        }
        p {
            margin: 10px 0;
        }
        .terms {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .terms h3 {
            margin-top: 0;
        }
        .details {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        .details th, .details td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .details th {
            background: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="contact">
                <p class="company-name">PlaceIt</p>
                <p>Alaska Duljo Fatima Cebu City Cebu 6000</p>
                <p>placeit13@gmail.com</p>
            </div>
        </div>
        <h1>Rental Agreement Details</h1>

        <div class="terms">
            <h3>Terms and Conditions:</h3>
            <ul>
                <li>Always verify the identity of the person or entity you are transacting with.</li>
                <li>Avoid making advance payments until a contract has been signed or legal obligations are clear.</li>
                <li>Report any suspicious activity to our team immediately through the provided channels.</li>
                <li>Be aware that any confirmed instances of scamming or fraud may lead to the immediate deactivation of your account.</li>
            </ul>
        </div>

        <table class="details">
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Renter</td>
                <td>{{ ucwords($agreementData['renter']) }}</td>
            </tr>
            <tr>
                <td>Space Owner</td>
                <td>{{ ucwords($agreementData['spaceOwner']) }}</td>
            </tr>
            <tr>
                <td>Listing Name</td>
                <td>{{ $agreementData['listingName'] }}</td>
            </tr>
            <tr>
                <td>Date Visited</td>
                <td>{{ $agreementData['visit_date'] }}</td>
            </tr>
            <tr>
                <td>Rental Term</td>
                <td>{{ ucwords($agreementData['rentalTerm']) }}</td>
            </tr>
            <tr>
                <td>Start Date</td>
                <td>{{ $agreementData['startDate'] }}</td>
            </tr>
            <tr>
                <td>End Date</td>
                <td>{{ $agreementData['endDate'] }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>{{ ucwords($agreementData['status']) }}</td>
            </tr>
            <tr>
                <td>Payment Sent</td>
                <td>{{ $agreementData['offerAmount'] }}</td>
            </tr>
            <tr>
                <td>Date Created</td>
                <td>{{ $agreementData['dateCreated'] }}</td>
            </tr>
        </table>

        <p class="footer">You may use this file as proof or reference in case of any disputes or issues. If the other party engages in inappropriate behavior or violates terms, please report it via this email. Thank you for trusting our system!</p>
    </div>
</body>
</html>
