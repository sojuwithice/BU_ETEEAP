<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Document Verification - BU-ETEEAP</title>
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link rel="stylesheet" href="{{ asset('css/docs.css') }}">
<link rel="icon" type="image/png" href="{{ asset('images/eteeap_logo.png') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .submission-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 12px;
        background: #f0f0f0;
        color: #666;
        margin-left: 8px;
    }
    
    .gdrive-onsite-edit-section {
        margin-bottom: 20px;
        padding: 15px;
        background: #fff8e0;
        border-radius: 12px;
        border: 1px solid #EF7631;
    }
    .gdrive-onsite-edit-section.hidden {
        display: none;
    }
    .gdrive-onsite-label {
        font-weight: 600;
        font-size: 13px;
        color: #EF7631;
        display: block;
        margin-bottom: 8px;
    }
    .gdrive-onsite-input-group {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    .gdrive-onsite-input {
        flex: 1;
        padding: 10px 12px;
        border: 1.5px solid #ddd;
        border-radius: 8px;
        font-size: 13px;
    }
    .gdrive-onsite-input:focus {
        outline: none;
        border-color: #EF7631;
    }
    .btn-gdrive-onsite-update {
        background: #EF7631;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
    }
    .btn-gdrive-onsite-update:hover {
        background: #d66528;
    }
    .gdrive-link-inline {
        margin-top: 10px;
        padding: 8px 12px;
        background: #eef5ff;
        border-radius: 8px;
        word-break: break-all;
    }
    .gdrive-link-inline a {
        color: #223381;
        text-decoration: none;
    }
</style>
</head>

<body>

    <!-- HEADER (same as before, keep your existing header) -->
<div class="header">
    <div class="left-head">
        <img src="{{ asset('images/eteeap_logo.png') }}">
        <h2>BU-ETEEAP</h2>
    </div>
    <div class="right-head">
        <div class="time-container">
            <div class="date-box"><div id="cur-month">APR</div><div id="cur-day">17</div></div>
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
                <a href="#" onclick="openAccountModal()" class="dropdown-item"><span class="material-symbols-outlined">manage_accounts</span><span>Manage Account</span></a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item" onclick="event.stopPropagation();"><span class="material-symbols-outlined">logout</span><span>Logout</span></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="docs-container">
    <div class="docs-header-banner">
        <h1>Document Verification</h1>
        <p>{{ $applicant->first_name }} {{ $applicant->last_name }}</p>
    </div>

    <div class="tab-container">
        <a href="{{ route('staff.applicant.info', $applicant->id) }}" class="tab-btn blue-tab">Application</a>
        <a href="{{ route('staff.applicant.documents', $applicant->id) }}" class="tab-btn orange-tab">Document</a>
        <a href="{{ route('staff.dashboard') }}" class="tab-btn back-btn"><span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span> Back to Dashboard</a>
    </div>

    <div class="docs-content-card">
        <div class="docs-list-column">
            <div class="docs-list-header">
                <h2>Documents List</h2>
                <div id="onsiteBadgeContainer"></div>
            </div>
            <div class="doc-items" id="docItemsList">
                @foreach($requirements as $requirement)
                @php
                    $upload = $documents[$requirement->id] ?? null;
                    $submissionType = $requirement->submission_type ?? 'file_upload';
                    $statusClass = '';
                    $statusIcon = 'hourglass_empty';
                    $statusColor = '#999';
                    
                    if ($upload) {
                        if ($upload->status == 'approved') {
                            $statusClass = 'approved';
                            $statusIcon = 'check_circle';
                            $statusColor = '#25c14a';
                        } elseif ($upload->status == 'rejected') {
                            $statusClass = 'rejected';
                            $statusIcon = 'cancel';
                            $statusColor = '#e03d4d';
                        } elseif ($upload->status == 'incomplete') {
                            $statusClass = 'incomplete';
                            $statusIcon = 'pending';
                            $statusColor = '#EF7631';
                        }
                    }
                @endphp
                <div class="doc-item {{ $loop->first ? 'active' : '' }} {{ $statusClass }}" 
                     data-id="{{ $requirement->id }}" 
                     data-name="{{ addslashes($requirement->name) }}"
                     data-submission-type="{{ $submissionType }}"
                     onclick="selectDocument(this, {{ $requirement->id }}, '{{ addslashes($requirement->name) }}')">
                    <span class="doc-status-icon"><span class="material-symbols-outlined" style="font-size: 20px; color: {{ $statusColor }};">{{ $statusIcon }}</span></span>
                    <span class="doc-name">{{ $requirement->name }}</span>
                    @if($submissionType == 'gdrive_link')
                    <span class="submission-badge"><span class="material-symbols-outlined" style="font-size: 12px;">link</span>GDrive</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="verification-column">
            <h2>Verification</h2>
            <div class="verification-scrollable">
                <div id="documentPreview" style="margin-bottom: 20px;">
                    <div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #999;">description</span>
                        <p style="margin-top: 10px;">Select a document to verify</p>
                    </div>
                </div>

                <!-- ONSITE GDRIVE EDIT SECTION - May fix para hindi mag-load ng link kung walang laman -->
                <div id="gdriveOnsiteEditSection" class="gdrive-onsite-edit-section hidden">
                    <label class="gdrive-onsite-label">Onsite Submission - Google Drive Link</label>
                    <div class="gdrive-onsite-input-group">
                        <input type="url" id="gdriveOnsiteInput" class="gdrive-onsite-input" placeholder="Paste your Link here">
                        <button class="btn-gdrive-onsite-update" onclick="updateGDriveLinkOnsite()">Update Link</button>
                    </div>
                    <div id="onsiteCurrentDisplay" class="gdrive-link-inline" style="margin-top: 10px; display: none;"></div>
                </div>

                <!-- STAFF UPLOAD SECTION -->
                <div class="staff-upload-section" id="staffUploadSection" style="display: none; margin-bottom: 20px;">
                    <div class="upload-header"><span class="material-symbols-outlined">cloud_upload</span><h4>Upload Document for Student</h4></div>
                    <div class="upload-area" id="staffUploadArea">
                        <input type="file" id="staffFileInput" style="display: none;" accept="image/*,application/pdf,.doc,.docx">
                        <div class="upload-box-staff" onclick="document.getElementById('staffFileInput').click()">
                            <span class="material-symbols-outlined">upload_file</span>
                            <p>Click to upload file</p>
                            <small id="staffFileName">No file selected</small>
                        </div>
                        <button class="btn-upload-staff" id="staffUploadBtn" style="display: none;" onclick="uploadStaffDocument()"><span class="material-symbols-outlined">publish</span> Upload Document</button>
                    </div>
                </div>
                
                <div class="radio-group">
                    <label class="radio-item"><input type="radio" name="status" value="approved"><span class="custom-radio"></span> Approved</label>
                    <label class="radio-item"><input type="radio"name="status" value="rejected"><span class="custom-radio"></span> Rejected</label>
                </div>

                <div class="form-group" id="reasonGroup" style="display: none;">
                    <label>Reason for Rejection/Issue</label>
                    <div class="custom-select-container" id="reasonDropdownContainer">
                        <div class="select-trigger" onclick="toggleReasonMenu()"><span id="selectedReasonLabel">Select a reason</span><span class="material-symbols-outlined select-arrow">expand_more</span></div>
                        <div class="select-options-menu" id="reasonMenu">
                            <div class="option-item" onclick="selectReason('Blurry/Unclear Image')">Blurry/Unclear Image</div>
                            <div class="option-item" onclick="selectReason('Missing Signature')">Missing Signature</div>
                            <div class="option-item" onclick="selectReason('Wrong Document Submitted')">Wrong Document Submitted</div>
                            <div class="option-item" onclick="selectReason('Expired Document')">Expired Document</div>
                            <div class="option-item" onclick="selectReason('Incomplete Pages')">Incomplete Pages</div>
                            <div class="option-item" onclick="selectReason('Not Notarized')">Not Notarized</div>
                            <div class="option-item" onclick="selectReason('Illegible Text')">Illegible Text</div>
                            <div class="option-item" onclick="selectReason('Invalid Link')">Invalid Google Drive Link</div>
                            <div class="option-item" onclick="selectReason('Link Access Denied')">Link Access Denied</div>
                        </div>
                        <input type="hidden" id="reasonSelect" value="">
                    </div>
                </div>

                <div class="form-group" id="commentGroup" style="display: none;">
                    <label>Add Comment</label>
                    <textarea id="commentText" class="comment-box" placeholder="Add additional comments..."></textarea>
                </div>

                <div class="button-container" id="buttonGroup" style="display: none;">
                    <button class="btn-update" onclick="updateVerification()">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Modal (same as before) -->
<div id="accountModal" class="account-modal">
    <div class="account-box">
        <span class="close-modal" onclick="closeAccountModal()">&times;</span>
        <h2>Manage Account</h2>
        <div class="profile-upload-section">
            <div class="avatar-wrapper" id="defaultAvatarView">
                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" id="modalProfilePreview" class="modal-avatar">
                <label for="profileUpload" class="upload-badge"><span class="material-symbols-outlined">photo_camera</span></label>
                <input type="file" id="profileUpload" hidden accept="image/*" onchange="previewImage(event)">
            </div>
            <div id="adjustmentArea" style="display: none; flex-direction: column; align-items: center; width: 100%;">
                <div id="image-editor"></div> 
                <div class="account-actions" style="width: 100%; margin-top: 30px;">
                    <button type="button" class="cancel-btn" onclick="cancelAdjustment()">Cancel</button>
                    <button id="savePhotoBtn" class="save-photo-btn" onclick="uploadCroppedImage()"><span class="material-symbols-outlined">save</span> Save Photo</button>
                </div>
            </div>
            <div id="profileActionButtons" style="display: flex; flex-direction: column; align-items: center; gap: 5px; margin-top: 10px;">
                <p class="upload-text" id="uploadInstruction">Click camera to change photo</p>
                <button type="button" id="reAdjustBtn" onclick="startAdjustingCurrent()" class="re-adjust-btn"><span class="material-symbols-outlined" style="font-size: 14px;">tune</span> Adjust Photo</button>
            </div>
        </div>
        <div class="input-group"><label>Full Name</label><input type="text" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" disabled></div>
        <div class="input-group"><label>Email</label><input type="text" value="{{ auth()->user()->email }}" disabled></div>
        <div class="input-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" value="********" id="currentPassword" readonly>
                <span class="toggle-eye" onclick="togglePassword('currentPassword', 'currentEyeIcon')"><span class="material-symbols-outlined" id="currentEyeIcon">visibility</span></span>
            </div>
        </div>
        <button class="change-btn" id="changeBtn" onclick="showChangeSection()">Change Password</button>
        <div id="changeSection" style="display:none; flex-direction:column; gap:12px;">
            <div class="input-group"><label>New Password</label><div class="password-wrapper"><input type="password" id="newPassword" placeholder="Enter new password"><span class="toggle-eye" onclick="togglePassword('newPassword', 'newEyeIcon')"><span class="material-symbols-outlined" id="newEyeIcon">visibility</span></span></div></div>
            <div class="input-group"><label>Confirm Password</label><div class="password-wrapper"><input type="password" id="confirmPassword" placeholder="Confirm new password"><span class="toggle-eye" onclick="togglePassword('confirmPassword', 'confirmEyeIcon')"><span class="material-symbols-outlined" id="confirmEyeIcon">visibility</span></span></div></div>
            <div class="account-actions"><button class="cancel-btn" onclick="closeAccountModal()">Cancel</button><button class="save-btn" onclick="updatePassword()">Save</button></div>
        </div>
    </div>
</div>

<div id="toast" class="toast"><span id="toast-icon" class="material-symbols-outlined"></span><span id="toast-message"></span></div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const applicantId = {{ $applicant->id }};
    let currentRequirementId = null;
    let currentRequirementName = null;
    let currentSubmissionType = null;
    let isOnsiteVerified = false;

    function showToast(message, type = 'success') {
        const toast = document.getElementById("toast");
        const icon = document.getElementById("toast-icon");
        const msg = document.getElementById("toast-message");
        if(!toast) return; 
        toast.className = `toast show ${type}`;
        msg.innerText = message;
        icon.innerText = type === 'success' ? 'check_circle' : 'error';
        setTimeout(() => toast.classList.remove("show"), 3000);
    }

    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const iconElement = document.getElementById('time-icon');
        const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        if(!iconElement) return;
        if (hours >= 5 && hours < 12) { iconElement.className = 'material-symbols-outlined icon-morning'; iconElement.innerText = 'light_mode'; }
        else if (hours >= 12 && hours < 18) { iconElement.className = 'material-symbols-outlined icon-afternoon'; iconElement.innerText = 'wb_sunny'; }
        else { iconElement.className = 'material-symbols-outlined icon-night'; iconElement.innerText = 'dark_mode'; }
        document.getElementById('cur-time').innerText = `${hours % 12 || 12}:${minutes}`;
        document.getElementById('cur-period').innerText = ampm;
        document.getElementById('cur-month').innerText = months[now.getMonth()];
        document.getElementById('cur-day').innerText = now.getDate();
    }
    setInterval(updateClock, 1000);
    updateClock();

    const profileWrapper = document.getElementById("profileWrapper");
    const dropdown = document.getElementById("profileDropdown");
    if(profileWrapper) {
        profileWrapper.onclick = () => dropdown.classList.toggle("show");
        document.addEventListener("click", (e) => !profileWrapper.contains(e.target) && dropdown.classList.remove("show"));
    }

    function toggleReasonMenu() {
        const menu = document.getElementById('reasonMenu');
        if (menu) menu.classList.toggle('show');
    }

    function selectReason(val) {
        document.getElementById('selectedReasonLabel').innerText = val;
        document.getElementById('reasonSelect').value = val;
        const menu = document.getElementById('reasonMenu');
        if (menu) menu.classList.remove('show');
    }

    window.addEventListener('click', function(e) {
        const container = document.getElementById('reasonDropdownContainer');
        if (container && !container.contains(e.target)) {
            const menu = document.getElementById('reasonMenu');
            if (menu) menu.classList.remove('show');
        }
    });

    function updateGDriveLinkOnsite() {
        if (!isOnsiteVerified) {
            showToast("Onsite verification required to update links", "error");
            return;
        }
        const newLink = document.getElementById('gdriveOnsiteInput').value.trim();
        if (!newLink) {
            showToast("Please enter a Google Drive link", "error");
            return;
        }
        Swal.fire({
            title: 'Update Google Drive Link',
            html: `Update onsite link for <strong>${currentRequirementName}</strong>?`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#EF7631', confirmButtonText: 'Yes, Update'
        }).then((result) => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Updating...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
            fetch(`/staff/applicant/${applicantId}/update-gdrive`, {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ requirement_id: currentRequirementId, submission_value: newLink })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    showToast("Google Drive link updated successfully!", "success");
                    const activeDoc = document.querySelector('.doc-item.active');
                    if (activeDoc) selectDocument(activeDoc, currentRequirementId, currentRequirementName);
                } else {
                    showToast(data.message || "Update failed", "error");
                }
            })
            .catch(error => { Swal.close(); showToast("Error updating link", "error"); });
        });
    }

    function selectDocument(element, requirementId, requirementName) {
        document.querySelectorAll('.doc-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');
        currentRequirementId = requirementId;
        currentRequirementName = requirementName;
        currentSubmissionType = element.getAttribute('data-submission-type') || 'file_upload';
        
        document.querySelectorAll('input[name="status"]').forEach(radio => radio.checked = false);
        document.getElementById('reasonSelect').value = '';
        document.getElementById('selectedReasonLabel').innerText = 'Select a reason';
        document.getElementById('commentText').value = '';
        document.getElementById('reasonGroup').style.display = 'none';
        document.getElementById('commentGroup').style.display = 'block';
        document.getElementById('buttonGroup').style.display = 'flex';
        
        document.getElementById('gdriveOnsiteEditSection').classList.add('hidden');
        document.getElementById('staffUploadSection').style.display = 'none';
        
        const previewDiv = document.getElementById('documentPreview');
        previewDiv.innerHTML = `<div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;"><span class="material-symbols-outlined" style="font-size: 48px; color: #999;">hourglass_empty</span><p>Loading document...</p></div>`;
        
        fetch(`/staff/applicant/${applicantId}/onsite-status`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
        .then(res => res.json())
        .then(onsiteData => {
            isOnsiteVerified = onsiteData.success && onsiteData.onsite_verified;
            return fetch(`/staff/applicant/${applicantId}/document/${requirementId}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
        })
        .then(response => response.json())
        .then(data => {
            const doc = data.document;
            const hasDocument = data.success && doc;
            
            if (hasDocument) {
                if (doc.status) {
                    const radio = document.querySelector(`input[name="status"][value="${doc.status}"]`);
                    if (radio) radio.checked = true;
                    if (doc.status === 'rejected' || doc.status === 'incomplete') {
                        document.getElementById('reasonGroup').style.display = 'block';
                        if (doc.verification_reason) {
                            document.getElementById('selectedReasonLabel').innerText = doc.verification_reason;
                            document.getElementById('reasonSelect').value = doc.verification_reason;
                        }
                    }
                }
                if (doc.verification_comment) document.getElementById('commentText').value = doc.verification_comment;
                
                let displayDate = 'Not available';
                if (doc.upload_date) {
                    let uploadDate = new Date(doc.upload_date);
                    if (!isNaN(uploadDate.getTime())) {
                        displayDate = uploadDate.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                    }
                }
                
                const submissionType = doc.submission_type || currentSubmissionType;
                
                if (submissionType === 'gdrive_link') {
                    if (isOnsiteVerified) {
                        // ONSITE VERIFIED - May edit box (FIXED: hindi magl-load ng link kung walang laman)
                        document.getElementById('gdriveOnsiteEditSection').classList.remove('hidden');
                        const onsiteInput = document.getElementById('gdriveOnsiteInput');
                        const onsiteDisplay = document.getElementById('onsiteCurrentDisplay');
                        
                        // FIXED: Check kung may valid link bago i-set ang value
                        const existingLink = doc.submission_value && doc.submission_value.trim() !== '' ? doc.submission_value : null;
                        
                        if (existingLink) {
                            onsiteInput.value = existingLink;
                            onsiteDisplay.style.display = 'block';
                            onsiteDisplay.innerHTML = `<strong>Current Link:</strong> <a href="${existingLink}" target="_blank">${existingLink}</a>`;
                        } else {
                            onsiteInput.value = '';  // Blanko - walang laman
                            onsiteDisplay.style.display = 'none';
                        }
                        previewDiv.innerHTML = `<div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;"><span class="material-symbols-outlined" style="font-size: 48px; color: #EF7631;">edit_note</span><p style="font-weight: 600; color: #EF7631;">Onsite Submission</p><p>Use the form above to add/edit the Google Drive link.</p></div>`;
                    } else {
                        // ONLINE SUBMISSION - Preview link
                        previewDiv.innerHTML = `
                            <div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;">
                                <span class="material-symbols-outlined" style="font-size: 48px; color: #223381;">link</span>
                                <p style="margin: 10px 0; font-weight: 600; color: #223381;">Google Drive Link Submission</p>
                                <p style="font-size: 13px; color: #666;"><span class="material-symbols-outlined" style="font-size: 16px;">calendar_month</span> Submitted: ${displayDate}</p>
                                ${doc.submission_value ? `
                                    <div style="margin-top: 15px; padding: 10px; background: #eef5ff; border-radius: 8px; word-break: break-all;">
                                        <a href="${doc.submission_value}" target="_blank">${doc.submission_value}</a>
                                    </div>
                                    <button onclick="window.open('${doc.submission_value}', '_blank')" style="margin-top: 15px; padding: 8px 20px; background: #223381; color: white; border: none; border-radius: 6px; cursor: pointer;">Open Link</button>
                                ` : '<p style="color: #999; margin-top: 10px;">No link submitted yet</p>'}
                            </div>
                        `;
                    }
                } else if (doc.file_path) {
                    previewDiv.innerHTML = `
                        <div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;">
                            <span class="material-symbols-outlined" style="font-size: 48px; color: #223381;">description</span>
                            <p style="margin: 10px 0; font-weight: 600; color: #333;">${doc.file_name || 'Document'}</p>
                            <p style="font-size: 13px; color: #666;"><span class="material-symbols-outlined" style="font-size: 16px;">calendar_month</span> Uploaded: ${displayDate}</p>
                            <button onclick="window.open('${doc.file_path}', '_blank')" style="margin-top: 15px; padding: 8px 20px; background: #223381; color: white; border: none; border-radius: 6px; cursor: pointer;">View Document</button>
                        </div>
                    `;
                    if (isOnsiteVerified) document.getElementById('staffUploadSection').style.display = 'block';
                }
            } else {
                if (currentSubmissionType === 'gdrive_link') {
                    if (isOnsiteVerified) {
                        document.getElementById('gdriveOnsiteEditSection').classList.remove('hidden');
                        // FIXED: I-clear ang input kapag walang existing link
                        document.getElementById('gdriveOnsiteInput').value = '';
                        document.getElementById('onsiteCurrentDisplay').style.display = 'none';
                        previewDiv.innerHTML = `<div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;"><span class="material-symbols-outlined" style="font-size: 48px; color: #EF7631;">pending</span><p style="font-weight: 600; color: #EF7631;">Onsite Submission - Pending</p><p>Please add the Google Drive link.</p></div>`;
                    } else {
                        previewDiv.innerHTML = `<div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;"><span class="material-symbols-outlined" style="font-size: 48px; color: #999;">link</span><p>No Google Drive link submitted yet</p></div>`;
                    }
                } else if (currentSubmissionType === 'file_upload' && isOnsiteVerified) {
                    document.getElementById('staffUploadSection').style.display = 'block';
                    previewDiv.innerHTML = `<div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;"><span class="material-symbols-outlined" style="font-size: 48px; color: #EF7631;">cloud_upload</span><p style="font-weight: 600; color: #EF7631;">Onsite Submission</p><p>Please upload the document.</p></div>`;
                } else {
                    previewDiv.innerHTML = `<div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;"><span class="material-symbols-outlined" style="font-size: 48px; color: #999;">description</span><p>No document uploaded yet</p></div>`;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            previewDiv.innerHTML = `<div class="file-preview" style="text-align: center; padding: 20px;"><span class="material-symbols-outlined" style="color: #e03d4d;">error</span><p>Error loading document.</p></div>`;
        });
    }

    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const reasonGroup = document.getElementById('reasonGroup');
            if (this.value === 'rejected' || this.value === 'incomplete') {
                reasonGroup.style.display = 'block';
            } else {
                reasonGroup.style.display = 'none';
                document.getElementById('reasonSelect').value = '';
                document.getElementById('selectedReasonLabel').innerText = 'Select a reason';
            }
        });
    });

    function updateDocumentStatusIcon(requirementId, status) {
        const docItem = document.querySelector(`.doc-item[data-id="${requirementId}"]`);
        if (docItem) {
            const iconSpan = docItem.querySelector('.doc-status-icon .material-symbols-outlined');
            let iconName = 'hourglass_empty', statusClass = '', statusColor = '#999';
            if (status === 'approved') { iconName = 'check_circle'; statusClass = 'approved'; statusColor = '#25c14a'; }
            else if (status === 'rejected') { iconName = 'cancel'; statusClass = 'rejected'; statusColor = '#e03d4d'; }
            else if (status === 'incomplete') { iconName = 'pending'; statusClass = 'incomplete'; statusColor = '#EF7631'; }
            if (iconSpan) { iconSpan.innerText = iconName; iconSpan.style.color = statusColor; }
            docItem.classList.remove('approved', 'rejected', 'incomplete');
            if (statusClass) docItem.classList.add(statusClass);
            docItem.setAttribute('data-status', status);
        }
    }

    function updateVerification() {
        if (!currentRequirementId) { showToast("Please select a document first", "error"); return; }
        const selectedStatus = document.querySelector('input[name="status"]:checked');
        if (!selectedStatus) { showToast("Please select a verification status", "error"); return; }
        const status = selectedStatus.value;
        const reason = document.getElementById('reasonSelect').value;
        const comment = document.getElementById('commentText').value;
        if ((status === 'rejected' || status === 'incomplete') && !reason) { showToast("Please provide a reason", "error"); return; }
        
        let studentMessage = '';
        if (status === 'approved') studentMessage = `Your document "${currentRequirementName}" has been APPROVED.`;
        else if (status === 'rejected') studentMessage = `Your document "${currentRequirementName}" has been REJECTED. Reason: ${reason}. ${comment ? ' Comment: ' + comment : ''}`;
        else if (status === 'incomplete') studentMessage = `Your document "${currentRequirementName}" is INCOMPLETE. Reason: ${reason}. ${comment ? ' Comment: ' + comment : ''}`;
        
        Swal.fire({ title: 'Confirm Verification', html: `Update "${currentRequirementName}" to ${status.toUpperCase()}?`, icon: 'question', showCancelButton: true, confirmButtonColor: '#223381', confirmButtonText: 'Yes, Update' })
        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Updating...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                fetch(`/staff/applicant/${applicantId}/document-verification`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ requirement_id: currentRequirementId, status: status, reason: reason, comment: comment })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        return fetch(`/staff/applicant/${applicantId}/message`, {
                            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: JSON.stringify({ message: studentMessage })
                        });
                    } else throw new Error(data.message || "Update failed");
                })
                .then(() => {
                    Swal.close();
                    showToast("Verification updated and notification sent!", "success");
                    updateDocumentStatusIcon(currentRequirementId, status);
                    document.getElementById('commentText').value = '';
                    document.getElementById('reasonSelect').value = '';
                    document.getElementById('selectedReasonLabel').innerText = 'Select a reason';
                    document.getElementById('reasonGroup').style.display = 'none';
                })
                .catch(error => { Swal.close(); showToast(error.message || "Error updating verification", "error"); });
            }
        });
    }

    function openAccountModal() { document.getElementById("accountModal").classList.add("show"); }
    function closeAccountModal() { document.getElementById("accountModal").classList.remove("show"); }
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") { input.type = "text"; icon.innerText = "visibility"; }
        else { input.type = "password"; icon.innerText = "visibility_off"; }
    }
    function showChangeSection() { document.getElementById("changeBtn").style.display = "none"; document.getElementById("changeSection").style.display = "flex"; }
    
    async function updatePassword() {
        const newPass = document.getElementById("newPassword").value;
        const confirmPass = document.getElementById("confirmPassword").value;
        if (!newPass || !confirmPass) { showToast("Please fill all fields", "error"); return; }
        if (newPass !== confirmPass) { showToast("Passwords do not match", "error"); return; }
        try {
            const response = await fetch("/update-password", { method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken }, body: JSON.stringify({ password: newPass, password_confirmation: confirmPass }) });
            const data = await response.json();
            if (response.ok) { showToast("Password updated successfully!", "success"); closeAccountModal(); }
            else { showToast(data.message || "Invalid password requirements", "error"); }
        } catch (error) { showToast("Server connection failed", "error"); }
    }

    let croppieInstance = null;
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file || !file.type.startsWith('image/')) return showToast("Invalid image file", "error");
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('defaultAvatarView').style.display = 'none';
            document.getElementById('profileActionButtons').style.display = 'none';
            document.getElementById('adjustmentArea').style.display = 'flex';
            if (croppieInstance) croppieInstance.destroy();
            croppieInstance = new Croppie(document.getElementById('image-editor'), { viewport: { width: 150, height: 150, type: 'circle' }, boundary: { width: 250, height: 250 }, showZoomer: true, enableOrientation: true });
            croppieInstance.bind({ url: e.target.result });
        };
        reader.readAsDataURL(file);
    }
    function startAdjustingCurrent() {
        const currentSrc = document.getElementById('modalProfilePreview').src;
        document.getElementById('defaultAvatarView').style.display = 'none';
        document.getElementById('profileActionButtons').style.display = 'none';
        document.getElementById('adjustmentArea').style.display = 'flex';
        if (croppieInstance) croppieInstance.destroy();
        croppieInstance = new Croppie(document.getElementById('image-editor'), { viewport: { width: 150, height: 150, type: 'circle' }, boundary: { width: 250, height: 250 }, showZoomer: true });
        croppieInstance.bind({ url: currentSrc });
    }
    function cancelAdjustment() {
        if (croppieInstance) { croppieInstance.destroy(); croppieInstance = null; }
        document.getElementById('profileUpload').value = "";
        document.getElementById('defaultAvatarView').style.display = 'block';
        document.getElementById('profileActionButtons').style.display = 'flex';
        document.getElementById('adjustmentArea').style.display = 'none';
    }
    async function uploadCroppedImage() {
        if (!croppieInstance) return;
        const saveBtn = document.getElementById('savePhotoBtn');
        saveBtn.disabled = true; saveBtn.innerText = "Saving...";
        const base64 = await croppieInstance.result({ type: 'base64', size: 'viewport', format: 'jpeg' });
        try {
            const res = await fetch("/profile/upload-image", { method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken }, body: JSON.stringify({ image: base64 }) });
            const data = await res.json();
            if (data.success) {
                showToast("Profile photo updated!", "success");
                ['profileImg', 'modalProfilePreview'].forEach(id => { const el = document.getElementById(id); if (el) el.src = data.path; });
                const dropAv = document.querySelector('.dropdown-avatar');
                if(dropAv) dropAv.src = data.path;
                cancelAdjustment();
            } else { showToast("Upload failed", "error"); }
        } catch (e) { showToast("Upload failed", "error"); }
        saveBtn.disabled = false; saveBtn.innerText = "Save Photo";
    }

    let selectedStaffFile = null;

    function checkOnsiteRequest() {
        fetch(`/staff/applicant/${applicantId}/onsite-status`, { method: 'GET', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
        .then(response => response.json())
        .then(data => {
            const badgeContainer = document.getElementById('onsiteBadgeContainer');
            isOnsiteVerified = data.success && data.onsite_verified;
            if (data.success) {
                if (data.onsite_verification_pending && !data.onsite_verified) badgeContainer.innerHTML = `<div class="onsite-pending-badge" onclick="confirmOnsiteSubmission()"><span class="material-symbols-outlined">pending_actions</span> Onsite Request Pending</div>`;
                else if (data.onsite_verified) badgeContainer.innerHTML = `<div class="onsite-verified-badge"><span class="material-symbols-outlined">verified</span> Onsite Verified</div>`;
                else badgeContainer.innerHTML = '';
            }
        })
        .catch(error => console.error('Error checking onsite request:', error));
    }

    function confirmOnsiteSubmission() {
        Swal.fire({
            title: 'Confirm Onsite Submission',
            html: `<div style="text-align: left;"><p><strong>Are you sure this student has submitted their documents ONSITE?</strong></p><br><p><strong>What happens after confirmation:</strong></p><ul><li>The student will be notified via message</li><li>All document uploads will be enabled for the student</li></ul></div>`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#EF7631', confirmButtonText: 'Yes, Confirm Onsite'
        }).then((result) => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Confirming...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
            fetch(`/staff/confirm/onsite`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ applicant_id: applicantId, confirmed: true }) })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    showToast("Onsite submission confirmed!", "success");
                    checkOnsiteRequest();
                    const activeDoc = document.querySelector('.doc-item.active');
                    if (activeDoc) selectDocument(activeDoc, currentRequirementId, currentRequirementName);
                } else { showToast(data.message || "Confirmation failed", "error"); }
            })
            .catch(error => { Swal.close(); showToast("Error confirming submission", "error"); });
        });
    }

    document.getElementById('staffFileInput')?.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            selectedStaffFile = e.target.files[0];
            document.getElementById('staffFileName').innerText = selectedStaffFile.name;
            document.getElementById('staffUploadBtn').style.display = 'flex';
        }
    });

    function uploadStaffDocument() {
        if (!currentRequirementId) { showToast("Please select a document first", "error"); return; }
        if (!selectedStaffFile) { showToast("Please select a file to upload", "error"); return; }
        Swal.fire({ title: 'Upload Document for Student', html: `Upload "${selectedStaffFile.name}" for <strong>${currentRequirementName}</strong>?`, icon: 'question', showCancelButton: true, confirmButtonColor: '#223381', confirmButtonText: 'Yes, Upload' })
        .then((result) => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Uploading...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
            const formData = new FormData();
            formData.append('file', selectedStaffFile);
            formData.append('requirement_id', currentRequirementId);
            formData.append('uploaded_by_staff', 'true');
            fetch(`/staff/applicant/${applicantId}/upload-document`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: formData })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    showToast("Document uploaded successfully!", "success");
                    document.getElementById('staffFileInput').value = '';
                    selectedStaffFile = null;
                    document.getElementById('staffFileName').innerText = 'No file selected';
                    document.getElementById('staffUploadBtn').style.display = 'none';
                    const activeDoc = document.querySelector('.doc-item.active');
                    if (activeDoc) selectDocument(activeDoc, currentRequirementId, currentRequirementName);
                } else { showToast(data.message || "Upload failed", "error"); }
            })
            .catch(error => { Swal.close(); showToast("Error uploading document", "error"); });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        checkOnsiteRequest();
        const firstDoc = document.querySelector('.doc-item');
        if (firstDoc) selectDocument(firstDoc, firstDoc.getAttribute('data-id'), firstDoc.getAttribute('data-name'));
    });
</script>
</body>
</html>