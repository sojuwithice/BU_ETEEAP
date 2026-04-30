<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/documents.css') }}">
<link rel="icon" type="image/png" href="{{ asset('images/eteeap_logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
<style>
    /* Document item styles */
.doc-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 5px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.doc-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.doc-name-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.doc-name {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}

/* Status badges with icon and text */
.doc-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

.doc-status-badge .material-symbols-outlined {
    font-size: 14px !important;
}

.doc-status-badge.approved {
    color: #25c14a;
    background: #e8f5e9;
}

.doc-status-badge.rejected {
    color: #e03d4d;
    background: #ffebee;
}

.doc-status-badge.incomplete {
    color: #EF7631;
    background: #fff3e0;
}

.doc-note {
    font-size: 0.75rem;
    color: #666;
    line-height: 1.4;
    margin: 0;
}

.doc-reason {
    font-size: 0.7rem;
    color: #EF7631;
    line-height: 1.3;
    margin-top: 2px;
}

/* Status badges for recent table */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

.status-badge .material-symbols-outlined {
    font-size: 14px !important;
}

.status-badge.approved {
    color: #25c14a;
    background: #e8f5e9;
}

.status-badge.rejected {
    color: #e03d4d;
    background: #ffebee;
}

.status-badge.incomplete {
    color: #EF7631;
    background: #fff3e0;
}

.status-badge.pending {
    color: #f47c20;
    background: #fff8f0;
}

/* Google Drive Section Styles */
.gdrive-section {
    margin-bottom: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px dashed #223381;
}

.gdrive-section.hidden {
    display: none;
}

.upload-box.hidden {
    display: none;
}

.gdrive-label {
    font-weight: 700;
    font-size: 14px;
    color: #223381;
    display: block;
    margin-bottom: 10px;
}

.gdrive-input-group {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.gdrive-link-input {
    flex: 1;
    padding: 12px 15px;
    border: 1.5px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
}

.gdrive-link-input:focus {
    outline: none;
    border-color: #223381;
}

.gdrive-link-input:disabled {
    background: #f0f0f0;
    cursor: not-allowed;
}

.btn-gdrive {
    background: #223381;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.btn-gdrive-outline {
    background: transparent;
    border: 1.5px solid #223381;
    color: #223381;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.btn-gdrive-outline-danger {
    border-color: #e03d4d;
    color: #e03d4d;
}

.gdrive-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.gdrive-status {
    font-size: 12px;
    margin-top: 10px;
    padding: 8px 12px;
    border-radius: 8px;
}

.gdrive-status.success {
    background: #e8f5e9;
    color: #25c14a;
}

.gdrive-status.warning {
    background: #fff3e0;
    color: #EF7631;
}

.gdrive-link-display {
    margin-top: 10px;
    padding: 8px 12px;
    background: #eef5ff;
    border-radius: 8px;
    word-break: break-all;
}

.gdrive-link-display a {
    color: #223381;
    text-decoration: none;
}
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="left-head">
        <img src="{{ asset('images/eteeap_logo.png') }}">
        <h2>BU-ETEEAP</h2>
    </div>

    <div class="right-head">
        <div class="time-container">
            <div class="date-box">
                <div id="cur-month">APR</div>
                <div id="cur-day">17</div>
            </div>
            <div class="clock-box">
                <span id="cur-time">10:00</span>
                <div class="period-icon-wrapper">
                    <span id="time-icon" class="material-symbols-outlined">light_mode</span>
                    <span id="cur-period">AM</span>
                </div>
            </div>
        </div>
        <div class="profile-wrapper" id="profileWrapper">
            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" class="profile" id="profileImg">
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" class="dropdown-avatar">
                    <div class="header-info">
                        <span class="user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                        <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" onclick="openAccountModal()" class="dropdown-item">
                    <span class="material-symbols-outlined">manage_accounts</span>
                    <span>Manage Account</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MAIN BG -->
<div class="main-bg">
    <div class="welcome">
        Welcome, {{ auth()->user()->first_name }}!
    </div>

    <div class="nav">
        <a href="{{ url('/dashboard') }}" style="text-decoration: none;"><button type="button">Home</button></a>
        <a href="{{ url('/profile') }}" style="text-decoration: none;"><button type="button">Profile</button></a>
        <a href="{{ route('applicant.documents') }}" style="text-decoration: none;"><button type="button" class="active">Documents</button></a>
    </div>

    <div class="card-container">
        <div class="documents-container">
            <div class="docs-list-section">
                <div class="docs-header">
                    <h2 class="section-title">Documents List</h2>
                    <button class="onsite-btn">Already Submitted Onsite</button>
                </div>
                <ul class="docs-items">
    @foreach($requirements as $req)
    @php
        $upload = $req->userUpload;
        $status = $upload ? $upload->status : null;
        $reason = $upload ? $upload->verification_reason : null;
        $submissionType = $req->submission_type ?? 'file_upload';
        $submissionValue = $upload && $upload->submission_type === 'gdrive_link' ? $upload->submission_value : null;
    @endphp
    <li class="doc-item {{ $upload ? 'completed' : '' }}" 
        data-id="{{ $req->id }}" 
        data-name="{{ $req->name }}" 
        data-note="{{ $req->note ?? '' }}"
        data-completed="{{ $upload ? 'true' : 'false' }}"
        data-filepath="{{ $upload && $upload->submission_type === 'file_upload' ? asset('storage/' . $upload->file_path) : '' }}"
        data-gdrive-link="{{ $submissionValue }}"
        data-status="{{ $status }}"
        data-reason="{{ $reason }}"
        data-submission-type="{{ $submissionType }}">
        <div class="{{ $upload ? 'check-icon' : 'circle-icon' }}">
            @if($upload)
                @if($status == 'approved')
                    <span class="material-symbols-outlined" style="color: #25c14a;">check_circle</span>
                @elseif($status == 'rejected')
                    <span class="material-symbols-outlined" style="color: #e03d4d;">cancel</span>
                @elseif($status == 'incomplete')
                    <span class="material-symbols-outlined" style="color: #EF7631;">pending</span>
                @else
                    <span class="material-symbols-outlined">check</span>
                @endif
            @endif
        </div>
        <div class="doc-content">
            <div class="doc-name-wrapper">
                <span class="doc-name">{{ $req->name }}</span>
                @if($upload && $status == 'approved')
                <span class="doc-status-badge approved">
                    <span class="material-symbols-outlined">check_circle</span> Verified
                </span>
                @elseif($upload && $status == 'rejected')
                <span class="doc-status-badge rejected">
                    <span class="material-symbols-outlined">cancel</span> Rejected
                </span>
                @elseif($upload && $status == 'incomplete')
                <span class="doc-status-badge incomplete">
                    <span class="material-symbols-outlined">pending</span> Incomplete
                </span>
                @endif
            </div>
            @if($req->note)
            <small class="doc-note">{{ $req->note }}</small>
            @endif
            @if($upload && ($status == 'rejected' || $status == 'incomplete') && $reason)
            <small class="doc-reason">{{ $reason }}</small>
            @endif
        </div>
    </li>
    @endforeach
</ul>
            </div>

            <div class="upload-section">
                <h2 class="section-title" id="sectionTitle">File Upload</h2>
                
                <!-- FILE UPLOAD BOX -->
                <div id="uploadBoxContainer">
                    <input type="file" id="fileInput" style="display: none;" accept="image/*,application/pdf,.doc,.docx,.txt" multiple>
                    <div class="upload-box" id="uploadBox">
                        <div id="previewContainer" class="file-preview" style="display:none;"></div>
                        <div class="upload-content" id="uploadContent">
                            <div class="upload-icon">
                                <span class="material-symbols-outlined">upload</span>
                            </div>
                            <p id="fileNameText">Select a document first</p>
                        </div>
                        <div class="upload-actions" id="uploadActions" style="display: none;">
                            <button type="button" id="uploadBtn" class="btn-main">Save</button>
                            <div id="successActions" style="display: none;">
                                <button type="button" id="removeBtn" class="btn-outline">Remove</button>
                                <button type="button" id="reuploadBtn" class="btn-outline">Re-upload</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GOOGLE DRIVE LINK SECTION -->
                <div id="gdriveSection" class="gdrive-section hidden">
                    <label class="gdrive-label">Google Drive Link</label>
                    <div class="gdrive-input-group">
                        <input type="url" id="gdriveLinkInput" class="gdrive-link-input" placeholder="Paste your Link here">
                        <button type="button" id="saveGDriveBtn" class="btn-gdrive">Save Link</button>
                    </div>
                    <div id="gdriveActions" class="gdrive-actions" style="display: none;">
                        <button type="button" id="changeGDriveBtn" class="btn-gdrive-outline">Change Link</button>
                        <button type="button" id="removeGDriveBtn" class="btn-gdrive-outline btn-gdrive-outline-danger">Remove Link</button>
                    </div>
                    <div id="gdriveStatus" class="gdrive-status" style="display: none;"></div>
                    <div id="gdriveLinkDisplay" class="gdrive-link-display" style="display: none;"></div>
                </div>

                <h3 class="recent-title">Recently Upload Files</h3>
                <div class="table-responsive">
                    <table class="recent-table">
                        <thead>
                            <tr><th>Name</th><th>Upload Date</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="recent-table-body">
    @foreach($recentUploads as $upload)
    <tr>
        <td>{{ $upload->requirement->name }}</td>
        <td class="upload-date-cell" data-timestamp="{{ $upload->created_at->toISOString() }}">
            {{ $upload->created_at->diffForHumans() }}
        </td>
        <td class="status-verified">
            @if($upload->status == 'approved')
                <span class="status-badge approved">
                    <span class="material-symbols-outlined">check_circle</span> Approved
                </span>
            @elseif($upload->status == 'rejected')
                <span class="status-badge rejected">
                    <span class="material-symbols-outlined">cancel</span> Rejected
                </span>
            @elseif($upload->status == 'incomplete')
                <span class="status-badge incomplete">
                    <span class="material-symbols-outlined">pending</span> Incomplete
                </span>
            @else
                <span class="status-badge pending">
                    <span class="material-symbols-outlined">hourglass_empty</span> Pending
                </span>
            @endif
        </td>
        <td>
            @if($upload->submission_type == 'gdrive_link' && $upload->submission_value)
                <button class="btn-view" onclick="window.open('{{ $upload->submission_value }}')">View Link</button>
            @else
                <button class="btn-view" onclick="window.open('/storage/{{ $upload->file_path }}')">View</button>
            @endif
        </td>
    </tr>
    @endforeach
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="account-modal" id="accountModal">
    <div class="account-box">
        <span class="close-modal" onclick="closeAccountModal()">&times;</span>
        <h2>Manage Account</h2>
        <div class="input-group"><label>Email</label><input type="text" value="{{ auth()->user()->email }}" disabled></div>
        <div class="input-group"><label>Password</label><div class="password-wrapper"><input type="password" value="{{ session('raw_password') ?? auth()->user()->password_plain ?? '' }}" id="currentPassword" readonly><span class="toggle-eye" onclick="togglePassword('currentPassword', 'currentEyeIcon')"><span class="material-symbols-outlined" id="currentEyeIcon">visibility</span></span></div></div>
        <button class="change-btn" id="changeBtn" onclick="showChangeSection()">Change Password</button>
        <div id="changeSection" style="display:none;">
            <div class="input-group"><label>New Password</label><div class="password-wrapper"><input type="password" id="newPassword" placeholder="Enter new password"><span class="toggle-eye" onclick="togglePassword('newPassword', 'newEyeIcon')"><span class="material-symbols-outlined" id="newEyeIcon">visibility</span></span></div></div>
            <div class="input-group"><label>Confirm Password</label><div class="password-wrapper"><input type="password" id="confirmPassword" placeholder="Confirm new password"><span class="toggle-eye" onclick="togglePassword('confirmPassword', 'confirmEyeIcon')"><span class="material-symbols-outlined" id="confirmEyeIcon">visibility</span></span></div></div>
            <div class="account-actions"><button class="cancel-btn" onclick="closeAccountModal()">Cancel</button><button class="save-btn" onclick="updatePassword()">Save</button></div>
        </div>
    </div>
</div>

<div id="toast" class="toast"><span id="toast-icon" class="material-symbols-outlined"></span><span id="toast-message"></span></div>

<script>
/* ================= GLOBAL ELEMENTS ================= */
const uploadSection = document.querySelector('.upload-section');
const uploadBoxContainer = document.getElementById('uploadBoxContainer');
const uploadBox = document.getElementById('uploadBox');
const fileInput = document.getElementById('fileInput');
const fileNameText = document.getElementById('fileNameText');
const uploadActions = document.getElementById('uploadActions');
const uploadBtn = document.getElementById('uploadBtn');
const successActions = document.getElementById('successActions');
const removeBtn = document.getElementById('removeBtn');
const reuploadBtn = document.getElementById('reuploadBtn');
const uploadContent = document.getElementById('uploadContent');
const previewContainer = document.getElementById('previewContainer');
const recentTableBody = document.getElementById('recent-table-body');
const sectionTitle = document.getElementById('sectionTitle');

// Google Drive elements
const gdriveSection = document.getElementById('gdriveSection');
const gdriveLinkInput = document.getElementById('gdriveLinkInput');
const saveGDriveBtn = document.getElementById('saveGDriveBtn');
const gdriveActions = document.getElementById('gdriveActions');
const changeGDriveBtn = document.getElementById('changeGDriveBtn');
const removeGDriveBtn = document.getElementById('removeGDriveBtn');
const gdriveStatus = document.getElementById('gdriveStatus');
const gdriveLinkDisplay = document.getElementById('gdriveLinkDisplay');

let selectedRequirementId = null;
let selectedSubmissionType = 'file_upload';
let isRequirementCompleted = false;
let currentFileName = '';
let currentFilePath = '';
let currentGDriveLink = '';
let currentDocItem = null;
let currentDocStatus = '';
let currentDocReason = '';
let isReuploadingAction = false;

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ================= HIDE/SHOW UPLOAD SECTION ================= */
function hideUploadSection() {
    if (uploadSection) {
        uploadSection.classList.remove('active');
    }
}

function showUploadSection() {
    if (uploadSection) {
        uploadSection.classList.add('active');
    }
}

hideUploadSection();

if (window.innerWidth > 1024 && uploadSection) {
    uploadSection.style.opacity = '0.5';
    uploadSection.style.pointerEvents = 'none';
}

/* ================= TOAST ================= */
function showToast(message, type = 'success') {
    const toast = document.getElementById("toast");
    const icon = document.getElementById("toast-icon");
    const msg = document.getElementById("toast-message");
    toast.className = `toast show ${type}`;
    msg.innerText = message;
    icon.innerText = type === 'success' ? 'check_circle' : 'error';
    setTimeout(() => toast.classList.remove("show"), 3000);
}

// ================= CLOCK LOGIC =================
function updateClock() {
    const now = new Date();
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const iconElement = document.getElementById('time-icon');
    
    if (hours >= 5 && hours < 12) {
        iconElement.innerText = 'light_mode'; 
        iconElement.className = 'material-symbols-outlined icon-morning';
    } else if (hours >= 12 && hours < 18) {
        iconElement.innerText = 'wb_sunny'; 
        iconElement.className = 'material-symbols-outlined icon-afternoon';
    } else {
        iconElement.innerText = 'dark_mode'; 
        iconElement.className = 'material-symbols-outlined icon-night';
    }

    let displayHours = hours % 12 || 12;
    document.getElementById('cur-time').innerText = displayHours + ':' + minutes;
    document.getElementById('cur-period').innerText = ampm;

    const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    document.getElementById('cur-month').innerText = months[now.getMonth()];
    document.getElementById('cur-day').innerText = now.getDate();
}

setInterval(updateClock, 1000);
updateClock();

/* ================= PROFILE ================= */
const profileWrapper = document.getElementById("profileWrapper");
const dropdown = document.getElementById("profileDropdown");
if (profileWrapper) {
    profileWrapper.addEventListener("click", e => { e.stopPropagation(); dropdown.classList.toggle("show"); });
    document.addEventListener("click", () => dropdown.classList.remove("show"));
}

/* ================= ACCOUNT MODAL ================= */
function openAccountModal() { document.getElementById("accountModal")?.classList.add("show"); }
function closeAccountModal() { document.getElementById("accountModal")?.classList.remove("show"); setTimeout(() => { document.getElementById("changeBtn").style.display = "block"; document.getElementById("changeSection").style.display = "none"; }, 300); }
function showChangeSection() { document.getElementById("changeBtn").style.display = "none"; document.getElementById("changeSection").style.display = "flex"; }
function togglePassword(inputId, iconId) { const input = document.getElementById(inputId); const icon = document.getElementById(iconId); if(input && icon) { input.type = input.type === "password" ? "text" : "password"; icon.innerText = input.type === "password" ? "visibility" : "visibility_off"; } }

/* ================= UI FUNCTIONS ================= */
function showGDriveSection(show) {
    if (show) {
        gdriveSection.classList.remove('hidden');
        uploadBoxContainer.style.display = 'none';
        sectionTitle.innerText = 'Google Drive Link Submission';
    } else {
        gdriveSection.classList.add('hidden');
        uploadBoxContainer.style.display = 'block';
        sectionTitle.innerText = 'File Upload';
    }
}

function resetGDriveUI() {
    gdriveLinkInput.value = '';
    gdriveLinkInput.disabled = false;
    saveGDriveBtn.style.display = 'inline-block';
    gdriveActions.style.display = 'none';
    gdriveStatus.style.display = 'none';
    gdriveLinkDisplay.style.display = 'none';
}

function setGDriveExisting(link, status, reason) {
    gdriveLinkInput.value = link;
    currentGDriveLink = link;
    
    if (status === 'approved') {
        gdriveLinkInput.disabled = true;
        saveGDriveBtn.style.display = 'none';
        gdriveActions.style.display = 'block';
        gdriveStatus.style.display = 'block';
        gdriveStatus.className = 'gdrive-status success';
        gdriveStatus.innerHTML = `✓ Verified and Approved`;
        gdriveLinkDisplay.style.display = 'block';
        gdriveLinkDisplay.innerHTML = `<a href="${link}" target="_blank">${link.substring(0, 60)}...</a>`;
    } else if (status === 'rejected' || status === 'incomplete') {
        saveGDriveBtn.style.display = 'none';
        gdriveActions.style.display = 'block';
        gdriveStatus.style.display = 'block';
        gdriveStatus.className = 'gdrive-status warning';
        gdriveStatus.innerHTML = `⚠ ${status.toUpperCase()}: ${reason || 'Please re-submit the correct link'}`;
        gdriveLinkDisplay.style.display = 'block';
        gdriveLinkDisplay.innerHTML = `<a href="${link}" target="_blank">${link.substring(0, 60)}...</a>`;
    } else {
        saveGDriveBtn.style.display = 'none';
        gdriveActions.style.display = 'block';
        gdriveLinkDisplay.style.display = 'block';
        gdriveLinkDisplay.innerHTML = `<a href="${link}" target="_blank">${link.substring(0, 60)}...</a>`;
    }
}

/* ================= PREVIEW FUNCTION ================= */
function showFilePreview(file, isUploaded = false, fileUrl = null, fileNameValue = null) {
    previewContainer.innerHTML = '';
    previewContainer.style.display = 'flex';
    uploadContent.style.display = 'none';
    uploadActions.style.display = 'block';
    uploadBox.classList.add('has-file');
    
    if (isUploaded && fileUrl) {
        const extension = fileUrl.split('.').pop().toLowerCase();
        const displayName = fileNameValue ? fileNameValue : fileUrl.split('/').pop();
        
        if (extension === 'pdf') {
            previewContainer.innerHTML = `<iframe src="${fileUrl}" class="pdf-preview"></iframe><div class="file-info"> PDF Document - ${displayName}</div>`;
        } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(extension)) {
            previewContainer.innerHTML = `<img src="${fileUrl}" alt="Preview"><div class="file-info"> Image File - ${displayName}</div>`;
        } else {
            previewContainer.innerHTML = `<span class="material-symbols-outlined" style="font-size: 60px; color: #223381;">description</span><div class="file-info"> ${displayName}</div><button onclick="window.open('${fileUrl}')" style="margin-top:8px; padding:5px 12px; background:#223381; color:white; border:none; border-radius:5px; cursor:pointer;">View File</button>`;
        }
    } else if (file) {
        const fileType = file.type;
        if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `<img src="${e.target.result}" alt="Preview"><div class="file-info"> ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>`;
            };
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            const pdfUrl = URL.createObjectURL(file);
            previewContainer.innerHTML = `<iframe src="${pdfUrl}" class="pdf-preview"></iframe><div class="file-info"> ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>`;
        } else {
            previewContainer.innerHTML = `<span class="material-symbols-outlined" style="font-size: 60px; color: #223381;">description</span><div class="file-info"> ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div><small style="color:#666;">Click Save to upload this file</small>`;
        }
    }
}

function resetUploadUI() {
    fileInput.value = '';
    uploadContent.style.display = 'flex';
    previewContainer.style.display = 'none';
    previewContainer.innerHTML = '';
    uploadActions.style.display = 'none';
    successActions.style.display = 'none';
    uploadBtn.style.display = 'inline-block';
    uploadBox.classList.remove('has-file');
    uploadBox.classList.remove('disabled');

    const badges = uploadBox.querySelectorAll('.approved-badge, .verification-note');
    badges.forEach(badge => badge.remove());
    
    resetGDriveUI();
}

/* ================= UPDATE DOCUMENT ITEM UI ================= */
function updateDocumentItemUI(docItem, hasFile, filePath = null, gdriveLink = null, submissionType = null) {
    if (!docItem) return;
    const iconDiv = docItem.querySelector('div:first-child');
    
    if (hasFile) {
        docItem.classList.add('completed');
        if (iconDiv) {
            iconDiv.className = 'check-icon';
            iconDiv.innerHTML = '<span class="material-symbols-outlined">check</span>';
        }
        docItem.dataset.completed = 'true';
        if (filePath) docItem.dataset.filepath = filePath;
        if (gdriveLink) docItem.dataset.gdriveLink = gdriveLink;
        if (submissionType) docItem.dataset.submissionType = submissionType;
    } else {
        docItem.classList.remove('completed');
        if (iconDiv) {
            iconDiv.className = 'circle-icon';
            iconDiv.innerHTML = '';
        }
        docItem.dataset.completed = 'false';
        docItem.dataset.filepath = '';
        docItem.dataset.gdriveLink = '';
    }
}

/* ================= UPDATE RECENT TABLE ================= */
function updateRecentTable(newUpload) {
    if (!recentTableBody) return;
    
    const now = new Date();
    const formattedDate = new Intl.DateTimeFormat('en-US', {
        month: 'short', day: 'numeric', year: 'numeric',
        hour: 'numeric', minute: '2-digit', hour12: true
    }).format(now);

    const actionButton = newUpload.submission_type === 'gdrive_link' 
        ? `<button class="btn-view" onclick="window.open('${newUpload.submission_value}')">View Link</button>`
        : `<button class="btn-view" onclick="window.open('${newUpload.file_path}')">View</button>`;
    
    const newRow = document.createElement('tr');
    newRow.innerHTML = `<td>${newUpload.requirement_name}</td><td>${formattedDate}</td><td class="status-verified"><span style="color: #EF7631;">Pending</span></td><td>${actionButton}</td>`;
    
    if (recentTableBody.firstChild) {
        recentTableBody.insertBefore(newRow, recentTableBody.firstChild);
    } else {
        recentTableBody.appendChild(newRow);
    }
}

/* ================= DOCUMENT ITEM CLICK ================= */
document.querySelectorAll('.doc-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.stopPropagation();
        
        document.querySelectorAll('.doc-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        const id = parseInt(this.dataset.id);
        const name = this.dataset.name;
        const isDone = this.dataset.completed === 'true';
        const filePath = this.dataset.filepath || '';
        const gdriveLink = this.dataset.gdriveLink || '';
        const submissionType = this.dataset.submissionType || 'file_upload';
        const docStatus = this.dataset.status || '';
        const docReason = this.dataset.reason || '';
        
        selectedRequirementId = id;
        selectedSubmissionType = submissionType;
        isRequirementCompleted = isDone;
        currentFileName = name;
        currentFilePath = filePath;
        currentGDriveLink = gdriveLink;
        currentDocItem = this;
        currentDocStatus = docStatus;
        currentDocReason = docReason;
        
        resetUploadUI();
        
        if (submissionType === 'gdrive_link') {
            showGDriveSection(true);
            if (isDone && gdriveLink) {
                setGDriveExisting(gdriveLink, docStatus, docReason);
            }
        } else {
            showGDriveSection(false);
            if (isDone && filePath) {
                if (docStatus === 'approved') {
                    uploadBox.classList.add('disabled');
                    showFilePreview(null, true, filePath, name);
                    uploadBtn.style.display = 'none';
                    successActions.style.display = 'none';
                    fileNameText.innerText = `✓ Approved: ${name}`;
                    const approvedBadge = document.createElement('div');
                    approvedBadge.className = 'approved-badge';
                    approvedBadge.innerHTML = '<span class="material-symbols-outlined">verified</span> Document Verified and Approved';
                    uploadBox.appendChild(approvedBadge);
                } else if (docStatus === 'rejected' || docStatus === 'incomplete') {
                    showFilePreview(null, true, filePath, name);
                    uploadBtn.style.display = 'none';
                    successActions.style.display = 'block';
                    fileNameText.innerText = `Uploaded: ${name}`;
                    const note = document.createElement('div');
                    note.className = 'verification-note';
                    note.innerHTML = `<span class="material-symbols-outlined">info</span> ${docStatus.toUpperCase()}: ${docReason || 'Please re-upload the correct document'}`;
                    uploadBox.appendChild(note);
                } else {
                    showFilePreview(null, true, filePath, name);
                    uploadBtn.style.display = 'none';
                    successActions.style.display = 'block';
                    fileNameText.innerText = `Uploaded: ${name}`;
                }
            } else {
                fileNameText.innerText = "Click to select: " + name;
            }
        }
        
        showUploadSection();
        
        if (window.innerWidth > 1024 && uploadSection) {
            uploadSection.style.opacity = '1';
            uploadSection.style.pointerEvents = 'auto';
        }
    });
});

/* ================= UPLOAD BOX CLICK ================= */
if (uploadBox) {
    uploadBox.addEventListener('click', e => {
        if (!selectedRequirementId) { showToast("Please select a document first", "error"); return; }
        if (selectedSubmissionType !== 'file_upload') return;
        if (e.target.tagName === 'BUTTON') return;
        if (isRequirementCompleted && currentDocStatus === 'approved') {
            showToast("This document is already approved and verified. No changes allowed.", "error");
            return;
        }
        if (isRequirementCompleted) {
            showToast("This document already has a file. Use Re-upload to replace it.", "error");
            return;
        }
        fileInput.click();
    });
}

/* ================= FILE SELECTION ================= */
if (fileInput) {
    fileInput.addEventListener('change', () => {
        if (!fileInput.files[0]) return;
        showFilePreview(fileInput.files[0], false);
        uploadBtn.style.display = 'inline-block';
        successActions.style.display = 'none';
    });
}

/* ================= SAVE FILE BUTTON ================= */
if (uploadBtn) {
    uploadBtn.addEventListener('click', () => {
        if (!fileInput.files[0]) { showToast("Please select a file first", "error"); return; }
        if (!selectedRequirementId) { showToast("Please select a document first", "error"); return; }
        if (selectedSubmissionType !== 'file_upload') return;
        
        Swal.fire({
            title: 'Confirm Upload?',
            html: `<strong>File:</strong> ${fileInput.files[0].name}<br><strong>Document:</strong> ${currentFileName}`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#223381', confirmButtonText: 'Yes, Upload'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            Swal.fire({ title: 'Uploading...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('requirement_id', selectedRequirementId);
            formData.append('submission_type', 'file_upload');
            if (isReuploadingAction) formData.append('is_reuploaded', 'true');
            
            fetch("{{ route('applicant.upload.save') }}", {
                method: "POST", headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    isReuploadingAction = false;
                    updateDocumentItemUI(currentDocItem, true, data.file_path || `/storage/${data.path}`, null, 'file_upload');
                    isRequirementCompleted = true;
                    uploadBtn.style.display = 'none';
                    successActions.style.display = 'block';
                    fileNameText.innerText = `Uploaded: ${currentFileName}`;
                    updateRecentTable({ requirement_name: currentFileName, submission_type: 'file_upload', file_path: data.file_path, submission_value: null });
                    showToast("File uploaded successfully");
                    setTimeout(() => location.reload(), 1000);
                } else { showToast(data.message || "Upload failed", "error"); }
            })
            .catch(() => { Swal.close(); showToast("Upload error", "error"); });
        });
    });
}

/* ================= SAVE GDRIVE LINK BUTTON ================= */
if (saveGDriveBtn) {
    saveGDriveBtn.addEventListener('click', () => {
        const gdriveLink = gdriveLinkInput?.value.trim();
        if (!gdriveLink) { showToast("Please enter a Google Drive link", "error"); return; }
        if (!selectedRequirementId) { showToast("Please select a document first", "error"); return; }
        
        Swal.fire({
            title: 'Confirm Submission?',
            html: `<strong>Document:</strong> ${currentFileName}<br><strong>Link:</strong> ${gdriveLink.substring(0, 50)}...`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#223381', confirmButtonText: 'Yes, Submit Link'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            Swal.fire({ title: 'Submitting...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
            
            fetch("{{ route('applicant.upload.save') }}", {
                method: "POST", headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ requirement_id: selectedRequirementId, submission_type: 'gdrive_link', submission_value: gdriveLink })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    updateDocumentItemUI(currentDocItem, true, null, gdriveLink, 'gdrive_link');
                    isRequirementCompleted = true;
                    saveGDriveBtn.style.display = 'none';
                    gdriveActions.style.display = 'block';
                    gdriveLinkDisplay.style.display = 'block';
                    gdriveLinkDisplay.innerHTML = `<a href="${gdriveLink}" target="_blank">${gdriveLink.substring(0, 60)}...</a>`;
                    updateRecentTable({ requirement_name: currentFileName, submission_type: 'gdrive_link', file_path: null, submission_value: gdriveLink });
                    showToast("Google Drive link submitted successfully");
                    setTimeout(() => location.reload(), 1000);
                } else { showToast(data.message || "Submission failed", "error"); }
            })
            .catch(() => { Swal.close(); showToast("Error submitting link", "error"); });
        });
    });
}

/* ================= REUPLOAD BUTTON ================= */
if (reuploadBtn) {
    reuploadBtn.addEventListener('click', e => {
        e.stopPropagation();
        if (!selectedRequirementId) return;
        if (selectedSubmissionType !== 'file_upload') return;
        isReuploadingAction = true;
        isRequirementCompleted = false;
        fileInput.click();
    });
}

/* ================= CHANGE GDRIVE LINK BUTTON ================= */
if (changeGDriveBtn) {
    changeGDriveBtn.addEventListener('click', e => {
        e.stopPropagation();
        if (!selectedRequirementId || selectedSubmissionType !== 'gdrive_link') return;
        
        Swal.fire({
            title: 'Change Link', input: 'url', inputLabel: 'Enter new Google Drive link',
            inputValue: currentGDriveLink, showCancelButton: true, confirmButtonText: 'Submit'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const newLink = result.value.trim();
                if (!newLink) { showToast("Please enter a valid link", "error"); return; }
                
                Swal.fire({ title: 'Updating...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                
                fetch("{{ route('applicant.upload.update') }}", {
                    method: "POST", headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ requirement_id: selectedRequirementId, submission_type: 'gdrive_link', submission_value: newLink })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        currentGDriveLink = newLink;
                        gdriveLinkInput.value = newLink;
                        gdriveLinkDisplay.innerHTML = `<a href="${newLink}" target="_blank">${newLink.substring(0, 60)}...</a>`;
                        gdriveStatus.style.display = 'none';
                        updateDocumentItemUI(currentDocItem, true, null, newLink, 'gdrive_link');
                        showToast("Link updated successfully");
                        setTimeout(() => location.reload(), 1000);
                    } else { showToast(data.message || "Update failed", "error"); }
                })
                .catch(() => { Swal.close(); showToast("Error updating link", "error"); });
            }
        });
    });
}

/* ================= REMOVE BUTTON (FILE) ================= */
if (removeBtn) {
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!selectedRequirementId || selectedSubmissionType !== 'file_upload') return;
        
        Swal.fire({
            title: 'Delete File', html: `Delete "${currentFileName}"?`, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                
                fetch("{{ route('applicant.upload.remove') }}", {
                    method: "POST", headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ requirement_id: selectedRequirementId })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        updateDocumentItemUI(currentDocItem, false);
                        isRequirementCompleted = false;
                        resetUploadUI();
                        fileNameText.innerText = "Click to select: " + currentFileName;
                        uploadBtn.style.display = 'inline-block';
                        successActions.style.display = 'none';
                        showToast("File removed successfully");
                        setTimeout(() => location.reload(), 1000);
                    } else { showToast(data.message || "Remove failed", "error"); }
                })
                .catch(() => { Swal.close(); showToast("Error removing file", "error"); });
            }
        });
    });
}

/* ================= REMOVE GDRIVE BUTTON ================= */
if (removeGDriveBtn) {
    removeGDriveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!selectedRequirementId || selectedSubmissionType !== 'gdrive_link') return;
        
        Swal.fire({
            title: 'Remove Link', html: `Remove Google Drive link for "${currentFileName}"?`, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, Remove'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Removing...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                
                fetch("{{ route('applicant.upload.remove') }}", {
                    method: "POST", headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ requirement_id: selectedRequirementId })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        updateDocumentItemUI(currentDocItem, false);
                        isRequirementCompleted = false;
                        resetGDriveUI();
                        showToast("Link removed successfully");
                        setTimeout(() => location.reload(), 1000);
                    } else { showToast(data.message || "Remove failed", "error"); }
                })
                .catch(() => { Swal.close(); showToast("Error removing link", "error"); });
            }
        });
    });
}

/* ================= PASSWORD UPDATE ================= */
async function updatePassword() {
    const newPassword = document.getElementById('newPassword')?.value;
    const confirmPassword = document.getElementById('confirmPassword')?.value;
    if (!newPassword) { showToast("Please enter a new password", "error"); return; }
    if (newPassword !== confirmPassword) { showToast("Passwords do not match", "error"); return; }
    if (newPassword.length < 6) { showToast("Password must be at least 6 characters", "error"); return; }
    
    try {
        const response = await fetch("{{ url('/update-password') }}", {
            method: "POST", headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ password: newPassword, password_confirmation: confirmPassword })
        });
        if (response.ok) { showToast("Password updated successfully"); closeAccountModal(); }
        else { const data = await response.json(); showToast(data.message || "Failed to update password", "error"); }
    } catch (error) { showToast("Error updating password", "error"); }
}

document.addEventListener('DOMContentLoaded', function() {
    const savedDocId = localStorage.getItem('activeDocumentId');
    if (savedDocId) { const savedDoc = document.querySelector(`.doc-item[data-id="${savedDocId}"]`); if (savedDoc) savedDoc.click(); localStorage.removeItem('activeDocumentId'); }
});

window.addEventListener('beforeunload', function() { if (selectedRequirementId) localStorage.setItem('activeDocumentId', selectedRequirementId); });

/* ================= ONSITE SUBMISSION VERIFICATION ================= */
const onsiteBtn = document.querySelector('.onsite-btn');
let isOnsiteMode = false;
let isVerified = false;

function setOnsiteMode(disabled) {
    const allDocItems = document.querySelectorAll('.doc-item');
    if (disabled) {
        allDocItems.forEach(item => { item.style.pointerEvents = 'none'; item.style.opacity = '0.6'; item.style.cursor = 'not-allowed'; });
        if (uploadBox) { uploadBox.style.pointerEvents = 'none'; uploadBox.style.opacity = '0.6'; }
        if (gdriveSection) { gdriveSection.style.pointerEvents = 'none'; gdriveSection.style.opacity = '0.6'; }
        if (fileInput) fileInput.disabled = true;
        if (uploadBtn) uploadBtn.disabled = true;
        if (removeBtn) removeBtn.disabled = true;
        if (reuploadBtn) reuploadBtn.disabled = true;
        if (saveGDriveBtn) saveGDriveBtn.disabled = true;
        if (changeGDriveBtn) changeGDriveBtn.disabled = true;
        if (removeGDriveBtn) removeGDriveBtn.disabled = true;
    } else {
        allDocItems.forEach(item => { item.style.pointerEvents = 'auto'; item.style.opacity = '1'; item.style.cursor = 'pointer'; });
        if (uploadBox) uploadBox.style.pointerEvents = 'auto';
        if (gdriveSection) gdriveSection.style.pointerEvents = 'auto';
        if (fileInput) fileInput.disabled = false;
        if (uploadBtn) uploadBtn.disabled = false;
        if (removeBtn) removeBtn.disabled = false;
        if (reuploadBtn) reuploadBtn.disabled = false;
        if (saveGDriveBtn) saveGDriveBtn.disabled = false;
        if (changeGDriveBtn) changeGDriveBtn.disabled = false;
        if (removeGDriveBtn) removeGDriveBtn.disabled = false;
    }
}

function checkOnsiteStatus() {
    fetch("{{ route('applicant.onsite.status') }}", { method: "GET", headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
    .then(response => response.json())
    .then(data => {
        if (data.verified) {
            isVerified = true;
            onsiteBtn.classList.add('verified');
            onsiteBtn.innerHTML = '<span class="material-symbols-outlined">verified</span> Onsite Verified';
            onsiteBtn.disabled = true;
            setOnsiteMode(false);
        } else if (data.pending_verification) {
            isOnsiteMode = true;
            setOnsiteMode(true);
            onsiteBtn.classList.add('pending');
            onsiteBtn.innerHTML = '<span class="material-symbols-outlined">schedule</span> Pending Verification';
        } else {
            onsiteBtn.classList.remove('pending', 'verified');
            onsiteBtn.innerHTML = 'Already Submitted Onsite';
        }
    })
    .catch(error => console.error('Error checking onsite status:', error));
}

if (onsiteBtn) {
    onsiteBtn.addEventListener('click', () => {
        if (isVerified) { showToast("Your documents are already verified onsite", "error"); return; }
        if (isOnsiteMode) { showToast("Verification already requested. Please wait for staff confirmation.", "error"); return; }
        
        Swal.fire({
            title: 'Onsite Submission Request',
            html: `<div style="text-align: left;"><p>By confirming, you declare that you have submitted your documents <strong>ONSITE</strong> at the BU-ETEEAP office.</p><br><p><strong>What happens next:</strong></p><ul><li>Your document upload functions will be temporarily disabled</li><li>A staff member will verify your onsite submission</li></ul></div>`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#223381', confirmButtonText: 'Yes, Request Verification'
        }).then((result) => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Requesting Verification...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
            fetch("{{ route('applicant.onsite.request') }}", { method: "POST", headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ onsite_request: true }) })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    isOnsiteMode = true; setOnsiteMode(true);
                    onsiteBtn.classList.add('pending');
                    onsiteBtn.innerHTML = '<span class="material-symbols-outlined">schedule</span> Pending Verification';
                    showToast("Onsite verification requested.", "success");
                } else { showToast(data.message || "Request failed", "error"); }
            })
            .catch(() => { Swal.close(); showToast("Error requesting verification", "error"); });
        });
    });
}

window.confirmOnsiteSubmission = function() {
    Swal.fire({
        title: 'Confirm Onsite Submission', html: `Confirm student submitted documents <strong>ONSITE</strong>?`,
        icon: 'warning', showCancelButton: true, confirmButtonColor: '#223381', confirmButtonText: 'Yes, Confirm'
    }).then((result) => {
        if (!result.isConfirmed) return;
        Swal.fire({ title: 'Confirming...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
        fetch("{{ route('staff.confirm.onsite') }}", { method: "POST", headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ confirmed: true }) })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            if (data.success) {
                isOnsiteMode = false; isVerified = true; setOnsiteMode(false);
                onsiteBtn.classList.remove('pending'); onsiteBtn.classList.add('verified');
                onsiteBtn.innerHTML = '<span class="material-symbols-outlined">verified</span> Onsite Verified';
                onsiteBtn.disabled = true;
                showToast("Onsite submission confirmed!", "success");
            } else { showToast(data.message || "Confirmation failed", "error"); }
        })
        .catch(() => { Swal.close(); showToast("Error confirming submission", "error"); });
    });
};

checkOnsiteStatus();

document.querySelectorAll('.upload-date-cell').forEach(cell => {
    const timestamp = cell.getAttribute('data-timestamp');
    if (timestamp) { cell.innerText = new Date(timestamp).toLocaleString(); }
});
</script>
</body>
</html>