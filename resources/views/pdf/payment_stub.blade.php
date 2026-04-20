<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Arial', sans-serif; text-align: center; color: #333; }
        .header img { width: 80px; height: auto; }
        .title { margin-top: 10px; font-weight: bold; font-size: 14pt; }
        .sub-title { font-size: 12pt; margin-bottom: 5px; }
        .location { font-size: 11pt; margin-bottom: 20px; }
        
        .order-payment { font-style: italic; margin-bottom: 30px; font-size: 13pt; }
        
        .info-table { width: 100%; margin-bottom: 20px; text-align: left; }
        .info-table td { padding: 5px 0; }
        .line { border-bottom: 1px solid black; display: inline-block; width: 80%; }

        table.billing { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.billing th, table.billing td { border: 1px solid #000; padding: 10px; text-align: left; }
        
        .signature-section { margin-top: 80px; text-align: center; }
        .sig-line { border-top: 1px solid black; width: 60%; margin: 0 auto; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/bu_logo.png') }}">
        <div class="title">Bicol University</div>
        <div class="sub-title">EXPANDED TERTIARY EDUCATION EQUIVALENCY</div>
        <div class="sub-title">AND ACCREDITATION PROGRAM (ETEEAP)</div>
        <div class="location">Daraga, Albay</div>
    </div>

    <h3>The Cashier</h3>
    <div class="order-payment">Order of Payment</div>

    <table class="info-table">
        <tr>
            <td width="15%">Date:</td>
            <td><span class="line">{{ now()->format('F d, Y') }}</span></td>
        </tr>
        <tr>
            <td>Name:</td>
            <td><span class="line">{{ strtoupper($applicant->first_name . ' ' . $applicant->last_name) }}</span></td>
        </tr>
    </table>

    <table class="billing">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Application Fee</td>
                <td>100.00</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="sig-line">
            <strong>Signature Over Printed Name</strong><br>
            BU ETEEAP Authorized Personnel
        </div>
    </div>
</body>
</html>