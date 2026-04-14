<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/documents.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="left-head">
        <img src="{{ asset('images/eteeap_logo.png') }}">
        <h2>BU-ETEEAP</h2>
    </div>
    <div class="profile"></div>
</div>

<!-- MAIN BG -->
<div class="main-bg">

    <!-- WELCOME -->
    <div class="welcome">
        Welcome, Applicant!
    </div>

    <!-- NAV -->
    <div class="nav">
        <a href="{{ url('/dashboard') }}" style="text-decoration: none;">
            <button type="button">Home</button>
        </a>
        <a href="{{ url('/profile') }}" style="text-decoration: none;">
            <button type="button">Profile</button>
        </a>
        <a href="{{ route('applicant.documents') }}" style="text-decoration: none;">
        <button type="button" class="active">Documents</button>
    </a>
    </div>

    <!-- CARD CONTAINER -->
    <div class="card-container">

    <div class="documents-container">
    <div class="docs-list-section">
        <h2 class="section-title">Documents List</h2>
        <ul class="docs-items">
            <li class="doc-item completed">
                <div class="check-icon">✔</div>
                <span class="doc-name">Birth Certificate</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">High School Card/Form 13A/PEPT or ALS Certificate (1st year college placement)/Most recent TOR</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">NBI/Barangay/Police Clearance</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">Resume/Curriculum Vitae/Notarized PDS</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">Letter of Intent</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <div class="doc-details">
                    <span class="doc-name">Detailed Functions and Responsibilities</span>
                    <small>Note: If candidate has been employed to more than one company, DFR should be obtained from present and past jobs; signed by present and past supervisors/employers</small>
                </div>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">Service Record/Certificate of Employment (from each employer of company reflected in the DFR)</span>
            </li>
        </ul>
    </div>

    <div class="upload-section">
        <h2 class="section-title">File Upload Files</h2>
        
        <div class="upload-box">
            <div class="upload-content">
                <div class="upload-icon">⬆</div>
                <p>Upload your file here</p>
            </div>
        </div>

        <h3 class="recent-title">Recently Upload Files</h3>
        <div class="table-responsive">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Upload Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Birth Certificate</td>
                        <td>2026-03-11</td>
                        <td class="status-verified">Verified</td>
                        <td>
                            <button class="btn-view">View</button>
                            <button class="btn-reupload">Re-upload</button>
                        </td>
                    </tr>
                    <tr>
                        <td>File 2</td>
                        <td>2026-03-11</td>
                        <td class="status-verified">Verified</td>
                        <td>
                            <button class="btn-view">View</button>
                            <button class="btn-reupload">Re-upload</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

</body>
</html>