<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Arial', sans-serif; text-align: center; color: #333; padding: 5px; margin: 0; }
        .header img { width: 40px; height: auto; }
        .title { margin-top: 3px; font-weight: bold; font-size: 9pt; }
        .sub-title { font-size: 7pt; margin-bottom: 2px; }
        .location { font-size: 7pt; margin-bottom: 8px; }
        
        h3 { font-size: 10pt; margin: 5px 0; }
        .order-payment { font-style: italic; margin-bottom: 8px; font-size: 10pt; }
        
        .info-table { width: 100%; margin-bottom: 8px; text-align: left; }
        .info-table td { padding: 2px 0; font-size: 8pt; }
        .line { border-bottom: 1px solid black; display: inline-block; width: 80%; }

        table.billing { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.billing th, table.billing td { border: 1px solid #000; padding: 5px; text-align: left; font-size: 8pt; }
        
        .signature-section { margin-top: 80px; text-align: center; }
        .sig-line { border-top: 1px solid black; width: 60%; margin: 0 auto; padding-top: 5px; }

        .signature-image {
            max-width: 100px;
            max-height: 55px;
            margin-bottom: -25px;
            object-fit: contain;
        }

        .printed-name { 
            font-size: 9pt;
            font-weight: bold; 
            margin-top: 2px;
            margin-bottom: 2px;
            text-decoration: underline;
        }

        .signature-label { 
            font-size: 8pt;
            color: #555; 
            margin-top: 2px;
        }
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

    <!-- Signature Section -->
    <div class="signature-section">
        <!-- Authorized Personnel Signature (with image) -->
        <div class="signature-box">
            <img src="{{ public_path('images/shine_signature.png') }}" class="signature-image" alt="Signature">
            <div class="printed-name">{{ $authorizedName ?? 'Sanny Shine F. Zoilo' }}</div>
            <div class="signature-label">BU ETEEAP Authorized Personnel</div>
        </div>
    </div>
</body>
</html>