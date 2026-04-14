<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Dashboard - BU-ETEEAP</title>
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link rel="stylesheet" href="{{ asset('css/docs.css') }}">
</head>

<body>

    <div class="header">
        <div class="left-head">
            <img src="{{ asset('images/eteeap_logo.png') }}" alt="BU Logo">
            <h2>BU-ETEEAP</h2>
        </div>
        <div class="profile"></div>
    </div>

<div class="docs-container">
    <div class="docs-header-banner">
        <h1>Document Verification</h1>
        <p>Juan Dela Cruz</p>
    </div>

    <div class="tab-container">
        <a href="{{ route('applicant.info') }}" 
        class="tab-btn {{ Route::is('applicant.info') ? 'orange-tab' : 'blue-tab' }}">
            Application
        </a>

        <a href="{{ route('document.verification') }}" 
        class="tab-btn {{ Route::is('document.verification') ? 'orange-tab' : 'blue-tab' }}">
            Document
        </a>
    </div>

    <div class="docs-content-card">
        <div class="docs-list-column">
            <h2>Documents List</h2>
            <div class="doc-items">
                <div class="doc-item active">Birth Certificate</div>
                <div class="doc-item">Barangay Clearance</div>
            </div>
        </div>

        <div class="verification-column">
            <h2>Verification</h2>
            
            <div class="radio-group">
                <label class="radio-item">
                    <input type="radio" name="status" value="approved">
                    <span class="custom-radio"></span> Approved
                </label>
                <label class="radio-item">
                    <input type="radio" name="status" value="incomplete">
                    <span class="custom-radio"></span> Incomplete
                </label>
                <label class="radio-item">
                    <input type="radio" name="status" value="rejected">
                    <span class="custom-radio"></span> Rejected
                </label>
            </div>

            <div class="form-group">
                <label>Reason for Rejection/Issue</label>
                <div class="select-wrapper">
                    <select>
                        <option>Blurry</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Add Comment</label>
                <textarea class="comment-box"></textarea>
            </div>

            <div class="button-container">
                <button class="btn-update">Update</button>
            </div>
        </div>
    </div>
</div>