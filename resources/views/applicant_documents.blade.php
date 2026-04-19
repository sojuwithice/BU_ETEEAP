<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/documents.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
<style>
    /* Verification status styles */
    .doc-status {
        display: block;
        font-size: 11px;
        margin-top: 4px;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }
    .doc-status.approved {
        color: #25c14a;
        background: #e8f5e9;
    }
    .doc-status.rejected {
        color: #e03d4d;
        background: #ffebee;
    }
    .doc-status.incomplete {
        color: #EF7631;
        background: #fff3e0;
    }
    .approved-badge {
        background: #25c14a;
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 10px;
    }
    .verification-note {
        display: block;
        margin-top: 8px;
        padding: 6px 10px;
        background: #fff3e0;
        border-radius: 6px;
        font-size: 11px;
        color: #EF7631;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .upload-box.disabled {
        pointer-events: none;
        opacity: 0.6;
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
                    @endphp
                    <li class="doc-item {{ $upload ? 'completed' : '' }}" 
                        data-id="{{ $req->id }}" 
                        data-name="{{ $req->name }}" 
                        data-note="{{ $req->note ?? '' }}"
                        data-completed="{{ $upload ? 'true' : 'false' }}"
                        data-filepath="{{ $upload ? asset('storage/' . $upload->file_path) : '' }}"
                        data-status="{{ $status }}"
                        data-reason="{{ $reason }}">
                        <div class="{{ $upload ? 'check-icon' : 'circle-icon' }}">
                            @if($upload)
                                @if($status == 'approved')
                                    <span class="material-symbols-outlined" style="color: #25c14a;">check_circle</span>
                                @elseif($status == 'rejected')
                                    <span class="material-symbols-outlined" style="color: #e03d4d;">cancel</span>
                                @else
                                    <span class="material-symbols-outlined">check</span>
                                @endif
                            @endif
                        </div>
                        <div class="doc-content">
                            <span class="doc-name">{{ $req->name }}</span>
                            @if($req->note)
                            <small class="doc-note">{{ $req->note }}</small>
                            @endif
                            @if($upload && $status == 'approved')
                            <small class="doc-status approved">✓ Verified</small>
                            @elseif($upload && $status == 'rejected')
                            <small class="doc-status rejected">✗ Rejected: {{ $reason }}</small>
                            @elseif($upload && $status == 'incomplete')
                            <small class="doc-status incomplete">⚠ Incomplete: {{ $reason }}</small>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="upload-section">
                <h2 class="section-title">File Upload Files</h2>
                <input type="file" id="fileInput" style="display: none;" accept="image/*,application/pdf,.doc,.docx,.txt">
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
                                        <span style="color: #25c14a;">✓ Approved</span>
                                    @elseif($upload->status == 'rejected')
                                        <span style="color: #e03d4d;">✗ Rejected</span>
                                    @elseif($upload->status == 'incomplete')
                                        <span style="color: #EF7631;">⚠ Incomplete</span>
                                    @else
                                        {{ $upload->status }}
                                    @endif
                                </td>
                                <td><button class="btn-view" onclick="window.open('/storage/{{ $upload->file_path }}')">View</button></td>
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

let selectedRequirementId = null;
let isRequirementCompleted = false;
let currentFileName = '';
let currentFilePath = '';
let currentDocItem = null;
let currentDocStatus = '';
let currentDocReason = '';
let isReuploadingAction = false;

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ================= HIDE/SHOW UPLOAD SECTION (FOR MOBILE) ================= */
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
            previewContainer.innerHTML = `
                <iframe src="${fileUrl}" class="pdf-preview"></iframe>
                <div class="file-info"> PDF Document - ${displayName}</div>
            `;
        } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(extension)) {
            previewContainer.innerHTML = `
                <img src="${fileUrl}" alt="Preview">
                <div class="file-info"> Image File - ${displayName}</div>
            `;
        } else if (extension === 'txt') {
            fetch(fileUrl)
                .then(res => res.text())
                .then(text => {
                    const previewText = text.substring(0, 500);
                    previewContainer.innerHTML = `
                        <div style="width:100%; max-height:200px; overflow:auto; background:#f5f5f5; padding:10px; border-radius:8px; text-align:left; font-family:monospace; font-size:12px;">
                            ${previewText.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                            ${text.length > 500 ? '<br><em>... (truncated)</em>' : ''}
                        </div>
                        <div class="file-info"> Text File - ${displayName}</div>
                    `;
                })
                .catch(() => {
                    previewContainer.innerHTML = `
                        <span class="material-symbols-outlined" style="font-size: 60px; color: #223381;">description</span>
                        <div class="file-info"> ${displayName}</div>
                        <button onclick="window.open('${fileUrl}')" style="margin-top:8px; padding:5px 12px; background:#223381; color:white; border:none; border-radius:5px; cursor:pointer;">View File</button>
                    `;
                });
            return;
        } else {
            previewContainer.innerHTML = `
                <span class="material-symbols-outlined" style="font-size: 60px; color: #223381;">description</span>
                <div class="file-info"> ${displayName}</div>
                <button onclick="window.open('${fileUrl}')" style="margin-top:8px; padding:5px 12px; background:#223381; color:white; border:none; border-radius:5px; cursor:pointer;">View File</button>
            `;
        }
    } else if (file) {
        const fileType = file.type;
        const reader = new FileReader();
        
        if (fileType.startsWith('image/')) {
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <div class="file-info"> ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>
                `;
            };
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            const pdfUrl = URL.createObjectURL(file);
            previewContainer.innerHTML = `
                <iframe src="${pdfUrl}" class="pdf-preview"></iframe>
                <div class="file-info"> ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>
            `;
        } else if (fileType === 'text/plain') {
            reader.onload = function(e) {
                const text = e.target.result.substring(0, 500);
                previewContainer.innerHTML = `
                    <div style="width:100%; max-height:200px; overflow:auto; background:#f5f5f5; padding:10px; border-radius:8px; text-align:left; font-family:monospace; font-size:12px;">
                        ${text.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                        ${e.target.result.length > 500 ? '<br><em>... (truncated)</em>' : ''}
                    </div>
                    <div class="file-info"> ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>
                `;
            };
            reader.readAsText(file);
        } else {
            previewContainer.innerHTML = `
                <span class="material-symbols-outlined" style="font-size: 60px; color: #223381;">description</span>
                <div class="file-info"> ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>
                <small style="color:#666;">Click Save to upload this file</small>
            `;
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
}

/* ================= UPDATE DOCUMENT ITEM UI ================= */
function updateDocumentItemUI(docItem, hasFile, filePath = null) {
    if (!docItem) return;
    const iconDiv = docItem.querySelector('div:first-child');
    
    if (hasFile) {
        docItem.classList.add('completed');
        if (iconDiv) {
            iconDiv.className = 'check-icon';
            iconDiv.innerHTML = '<span class="material-symbols-outlined">check</span>';
        }
        docItem.dataset.completed = 'true';
        docItem.dataset.filepath = filePath || '';
    } else {
        docItem.classList.remove('completed');
        if (iconDiv) {
            iconDiv.className = 'circle-icon';
            iconDiv.innerHTML = '';
        }
        docItem.dataset.completed = 'false';
        docItem.dataset.filepath = '';
    }
}

/* ================= UPDATE RECENT TABLE ================= */
function updateRecentTable(newUpload) {
    if (!recentTableBody) return;
    
    const now = new Date();
    const formattedDate = new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    }).format(now);

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${newUpload.requirement_name}</td>
        <td>${formattedDate}</td>
        <td class="status-verified">
            <span style="color: #EF7631;">Pending</span>
        </td>
        <td><button class="btn-view" onclick="window.open('${newUpload.file_path}')">View</button></td>
    `;
    
    if (recentTableBody.firstChild) {
        recentTableBody.insertBefore(newRow, recentTableBody.firstChild);
    } else {
        recentTableBody.appendChild(newRow);
    }
}

function removeFromRecentTable(requirementName) {
    if (!recentTableBody) return;
    const rows = recentTableBody.querySelectorAll('tr');
    for (let row of rows) {
        if (row.cells[0] && row.cells[0].innerText === requirementName) {
            row.remove();
            break;
        }
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
        const docStatus = this.dataset.status || '';
        const docReason = this.dataset.reason || '';
        
        selectedRequirementId = id;
        isRequirementCompleted = isDone;
        currentFileName = name;
        currentFilePath = filePath;
        currentDocItem = this;
        currentDocStatus = docStatus;
        currentDocReason = docReason;
        
        resetUploadUI();
        
        showUploadSection();
        
        if (window.innerWidth > 1024 && uploadSection) {
            uploadSection.style.opacity = '1';
            uploadSection.style.pointerEvents = 'auto';
        }
        
        if (isDone && filePath) {
            if (docStatus === 'approved') {
                uploadBox.classList.add('disabled');
                showFilePreview(null, true, filePath, name);
                uploadBtn.style.display = 'none';
                successActions.style.display = 'none';
                fileNameText.innerText = `✓ Approved: ${name}`;
                
                // Add approved badge
                const approvedBadge = document.createElement('div');
                approvedBadge.className = 'approved-badge';
                approvedBadge.innerHTML = '<span class="material-symbols-outlined">verified</span> Document Verified and Approved';
                uploadBox.appendChild(approvedBadge);
            } 
            else if (docStatus === 'rejected' || docStatus === 'incomplete') {
                showFilePreview(null, true, filePath, name);
                uploadBtn.style.display = 'none';
                successActions.style.display = 'block';
                fileNameText.innerText = `Uploaded: ${name}`;
                
                const note = document.createElement('div');
                note.className = 'verification-note';
                note.innerHTML = `<span class="material-symbols-outlined">info</span> ${docStatus.toUpperCase()}: ${docReason || 'Please re-upload the correct document'}`;
                uploadBox.appendChild(note);
            } 
            else {
                showFilePreview(null, true, filePath, name);
                uploadBtn.style.display = 'none';
                successActions.style.display = 'block';
                fileNameText.innerText = `Uploaded: ${name}`;
            }
        } else {
            fileNameText.innerText = "Click to select: " + name;
        }
    });
});

/* ================= UPLOAD BOX CLICK ================= */
if (uploadBox) {
    uploadBox.addEventListener('click', e => {
        if (!selectedRequirementId) { 
            showToast("Please select a document first", "error"); 
            return; 
        }
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
        const file = fileInput.files[0];
        showFilePreview(file, false);
        uploadBtn.style.display = 'inline-block';
        successActions.style.display = 'none';
    });
}

/* ================= SAVE BUTTON - FIXED ================= */
if (uploadBtn) {
    uploadBtn.addEventListener('click', () => {
        if (!fileInput.files[0]) { 
            showToast("Please select a file first", "error"); 
            return; 
        }
        if (!selectedRequirementId) { 
            showToast("Please select a document first", "error"); 
            return; 
        }
        
        Swal.fire({
            title: 'Confirm Upload?',
            html: `Please check if all information is correct before saving.<br><br><strong>File:</strong> ${fileInput.files[0].name}<br><strong>Document:</strong> ${currentFileName}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#223381',
            cancelButtonColor: '#ffffff',
            confirmButtonText: 'Yes, Upload',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('requirement_id', selectedRequirementId);
            
            // --- ADDED LOGIC HERE ---
            if (isReuploadingAction) {
                formData.append('is_reuploaded', 'true');
            }
            // ------------------------
            
            fetch("{{ route('applicant.upload.save') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    
                    isReuploadingAction = false; 

                    const filePath = data.file_path || `/storage/${data.path}`;
                    
                    updateDocumentItemUI(currentDocItem, true, filePath);
                    isRequirementCompleted = true;
                    currentFilePath = filePath;
                    currentDocStatus = 'pending';
                    
                    uploadBtn.style.display = 'none';
                    successActions.style.display = 'block';
                    fileNameText.innerText = `Uploaded: ${currentFileName}`;
                    
                    updateRecentTable({
                        requirement_name: currentFileName,
                        upload_date: new Date().toISOString().split('T')[0],
                        status: 'Pending',
                        file_path: filePath
                    });
                    
                    showToast("File uploaded successfully");
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || "Upload failed", "error");
                }
            })
            .catch(error => {
                Swal.close();
                showToast("Upload error. Please try again.", "error");
            });
        });
    });
}

/* ================= REUPLOAD BUTTON ================= */
if (reuploadBtn) {
    reuploadBtn.addEventListener('click', e => {
        e.stopPropagation();
        if (!selectedRequirementId) return;
        
        isReuploadingAction = true; 
        isRequirementCompleted = false;
        fileInput.click();
    });
}

/* ================= REMOVE BUTTON ================= */
if (removeBtn) {
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!selectedRequirementId) return;
        
        Swal.fire({
            title: 'Delete File',
            html: `Are you sure you want to delete the uploaded file for "<strong>${currentFileName}</strong>"?<br><br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            focusCancel: false,
            focusConfirm: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch("{{ route('applicant.upload.remove') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ requirement_id: selectedRequirementId })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if (data.success) {
                        updateDocumentItemUI(currentDocItem, false);
                        isRequirementCompleted = false;
                        currentFilePath = '';
                        resetUploadUI();
                        fileNameText.innerText = "Click to select: " + currentFileName;
                        uploadBtn.style.display = 'inline-block';
                        successActions.style.display = 'none';
                        removeFromRecentTable(currentFileName);
                        showToast("File removed successfully");
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || "Remove failed", "error");
                    }
                })
                .catch(error => {
                    Swal.close();
                    showToast("Error removing file", "error");
                });
            }
        });
    });
}

/* ================= PASSWORD UPDATE ================= */
async function updatePassword() {
    const newPassword = document.getElementById('newPassword')?.value;
    const confirmPassword = document.getElementById('confirmPassword')?.value;
    
    if (!newPassword) {
        showToast("Please enter a new password", "error");
        return;
    }
    if (newPassword !== confirmPassword) {
        showToast("Passwords do not match", "error");
        return;
    }
    if (newPassword.length < 6) {
        showToast("Password must be at least 6 characters", "error");
        return;
    }
    
    try {
        const response = await fetch("{{ url('/update-password') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                password: newPassword, 
                password_confirmation: confirmPassword 
            })
        });
        
        if (response.ok) {
            showToast("Password updated successfully");
            closeAccountModal();
            if (document.getElementById('newPassword')) document.getElementById('newPassword').value = '';
            if (document.getElementById('confirmPassword')) document.getElementById('confirmPassword').value = '';
        } else {
            const data = await response.json();
            showToast(data.message || "Failed to update password", "error");
        }
    } catch (error) {
        console.error('Password error:', error);
        showToast("Error updating password", "error");
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const savedDocId = localStorage.getItem('activeDocumentId');
    if (savedDocId) {
        const savedDoc = document.querySelector(`.doc-item[data-id="${savedDocId}"]`);
        if (savedDoc) {
            savedDoc.click();
        }
        localStorage.removeItem('activeDocumentId');
    }
});

window.addEventListener('beforeunload', function() {
    if (selectedRequirementId) {
        localStorage.setItem('activeDocumentId', selectedRequirementId);
    }
});

/* ================= ONSITE SUBMISSION VERIFICATION ================= */
const onsiteBtn = document.querySelector('.onsite-btn');
let isOnsiteMode = false;
let isVerified = false;

function setOnsiteMode(disabled) {
    const allDocItems = document.querySelectorAll('.doc-item');
    const uploadBoxElem = document.getElementById('uploadBox');
    const fileInputElem = document.getElementById('fileInput');
    const uploadBtnElem = document.getElementById('uploadBtn');
    const removeBtnElem = document.getElementById('removeBtn');
    const reuploadBtnElem = document.getElementById('reuploadBtn');
    
    if (disabled) {
        allDocItems.forEach(item => {
            item.style.pointerEvents = 'none';
            item.style.opacity = '0.6';
            item.style.cursor = 'not-allowed';
        });
        
        if (uploadBoxElem) {
            uploadBoxElem.style.pointerEvents = 'none';
            uploadBoxElem.style.opacity = '0.6';
        }
        if (fileInputElem) fileInputElem.disabled = true;
        if (uploadBtnElem) uploadBtnElem.disabled = true;
        if (removeBtnElem) removeBtnElem.disabled = true;
        if (reuploadBtnElem) reuploadBtnElem.disabled = true;
    } else {
        allDocItems.forEach(item => {
            item.style.pointerEvents = 'auto';
            item.style.opacity = '1';
            item.style.cursor = 'pointer';
        });
        
        if (uploadBoxElem) {
            uploadBoxElem.style.pointerEvents = 'auto';
            uploadBoxElem.style.opacity = '1';
        }
        if (fileInputElem) fileInputElem.disabled = false;
        if (uploadBtnElem) uploadBtnElem.disabled = false;
        if (removeBtnElem) removeBtnElem.disabled = false;
        if (reuploadBtnElem) reuploadBtnElem.disabled = false;
    }
}

function checkOnsiteStatus() {
    fetch("{{ route('applicant.onsite.status') }}", {
        method: "GET",
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
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
        if (isVerified) {
            showToast("Your documents are already verified onsite", "error");
            return;
        }
        if (isOnsiteMode) {
            showToast("Verification already requested. Please wait for staff confirmation.", "error");
            return;
        }
        
        Swal.fire({
            title: 'Onsite Submission Request',
            html: `<div style="text-align: left;">
                <p><strong>Important Notice</strong></p>
                <p>By confirming below, you are declaring that you have submitted your documents <strong>ONSITE</strong> at the BU-ETEEAP office.</p>
                <br>
                <p><strong>What happens next:</strong></p>
                <ul style="text-align: left; margin: 5px 0 0 20px;">
                    <li>Your document list and upload functions will be temporarily disabled</li>
                    <li>A staff member will verify your onsite submission</li>
                    <li>Once verified, everything will be enabled again</li>
                </ul>
                <br>
                <p><em>Are you sure you have submitted your documents onsite?</em></p>
            </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#223381',
            cancelButtonColor: '#ffffff',
            confirmButtonText: 'Yes, Request Verification',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            Swal.fire({
                title: 'Requesting Verification...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            fetch("{{ route('applicant.onsite.request') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ onsite_request: true })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    isOnsiteMode = true;
                    setOnsiteMode(true);
                    onsiteBtn.classList.add('pending');
                    onsiteBtn.innerHTML = '<span class="material-symbols-outlined">schedule</span> Pending Verification';
                    showToast("Onsite verification requested. Please wait for staff confirmation.", "success");
                } else {
                    showToast(data.message || "Request failed", "error");
                }
            })
            .catch(error => {
                Swal.close();
                showToast("Error requesting verification", "error");
            });
        });
    });
}

window.confirmOnsiteSubmission = function() {
    Swal.fire({
        title: 'Confirm Onsite Submission',
        html: `Are you sure this student has submitted their documents <strong>ONSITE</strong>?<br><br><strong>This will enable all document uploads.</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#223381',
        cancelButtonColor: '#ffffff',
        confirmButtonText: 'Yes, Confirm Onsite',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (!result.isConfirmed) return;
        
        Swal.fire({
            title: 'Confirming...',
            text: 'Please wait',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch("{{ route('staff.confirm.onsite') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ confirmed: true })
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                isOnsiteMode = false;
                isVerified = true;
                setOnsiteMode(false);
                onsiteBtn.classList.remove('pending');
                onsiteBtn.classList.add('verified');
                onsiteBtn.innerHTML = '<span class="material-symbols-outlined">verified</span> Onsite Verified';
                onsiteBtn.disabled = true;
                showToast("Onsite submission confirmed! Document uploads are now enabled.", "success");
            } else {
                showToast(data.message || "Confirmation failed", "error");
            }
        })
        .catch(error => {
            Swal.close();
            showToast("Error confirming submission", "error");
        });
    });
};

checkOnsiteStatus();

document.querySelectorAll('.upload-date-cell').forEach(cell => {
    const timestamp = cell.getAttribute('data-timestamp');
    if (timestamp) {
        const date = new Date(timestamp);
        cell.innerText = new Intl.DateTimeFormat('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        }).format(date);
    }
});

</script>
</body>
</html>