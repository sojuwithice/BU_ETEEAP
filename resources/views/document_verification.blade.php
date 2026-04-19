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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <img 
        src="{{ auth()->user()->profile_image 
            ? asset('storage/' . auth()->user()->profile_image) 
            : asset('images/default-profile.png') }}" 
        class="profile"
        id="profileImg"
    >

    <div class="profile-dropdown" id="profileDropdown">
        <div class="dropdown-header">
            <img src="{{ auth()->user()->profile_image 
                ? asset('storage/' . auth()->user()->profile_image) 
                : asset('images/default-profile.png') }}" class="dropdown-avatar">
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
            <button type="submit" class="dropdown-item" onclick="event.stopPropagation();">
                <span class="material-symbols-outlined">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
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
        <a href="{{ route('staff.applicant.info', $applicant->id) }}" class="tab-btn blue-tab">
            Application
        </a>
        <a href="{{ route('staff.applicant.documents', $applicant->id) }}" class="tab-btn orange-tab">
            Document
        </a>
    </div>

    <div class="docs-content-card">
        <div class="docs-list-column">
            <h2>Documents List</h2>
            <div class="doc-items" id="docItemsList">
    @foreach($requirements as $requirement)
    @php
        $upload = $documents[$requirement->id] ?? null;
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
         onclick="selectDocument(this, {{ $requirement->id }}, '{{ addslashes($requirement->name) }}')">
        <span class="doc-status-icon">
            <span class="material-symbols-outlined" style="font-size: 20px; color: {{ $statusColor }};">{{ $statusIcon }}</span>
        </span>
        <span class="doc-name">{{ $requirement->name }}</span>
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

                <div class="form-group" id="reasonGroup" style="display: none;">
    <label>Reason for Rejection/Issue</label>
    <div class="custom-select-container" id="reasonDropdownContainer">
        <div class="select-trigger" onclick="toggleReasonMenu()">
            <span id="selectedReasonLabel">Select a reason</span>
            <span class="material-symbols-outlined select-arrow">expand_more</span>
        </div>
        <div class="select-options-menu" id="reasonMenu">
            <div class="option-item" onclick="selectReason('Blurry/Unclear Image')">Blurry/Unclear Image</div>
            <div class="option-item" onclick="selectReason('Missing Signature')">Missing Signature</div>
            <div class="option-item" onclick="selectReason('Wrong Document Submitted')">Wrong Document Submitted</div>
            <div class="option-item" onclick="selectReason('Expired Document')">Expired Document</div>
            <div class="option-item" onclick="selectReason('Incomplete Pages')">Incomplete Pages</div>
            <div class="option-item" onclick="selectReason('Not Notarized')">Not Notarized</div>
            <div class="option-item" onclick="selectReason('Illegible Text')">Illegible Text</div>
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

<!-- Account Modal -->
<div id="accountModal" class="account-modal">
    <div class="account-box">
        <span class="close-modal" onclick="closeAccountModal()">&times;</span>
        <h2>Manage Account</h2>

        <div class="profile-upload-section">
            <div class="avatar-wrapper" id="defaultAvatarView">
                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" 
                     id="modalProfilePreview" class="modal-avatar">
                <label for="profileUpload" class="upload-badge">
                    <span class="material-symbols-outlined">photo_camera</span>
                </label>
                <input type="file" id="profileUpload" hidden accept="image/*" onchange="previewImage(event)">
            </div>

            <div id="adjustmentArea" style="display: none; flex-direction: column; align-items: center; width: 100%;">
                <div id="image-editor"></div> 
                <div class="account-actions" style="width: 100%; margin-top: 30px;">
                    <button type="button" class="cancel-btn" onclick="cancelAdjustment()">Cancel</button>
                    <button id="savePhotoBtn" class="save-photo-btn" onclick="uploadCroppedImage()">
                        <span class="material-symbols-outlined">save</span> Save Photo
                    </button>
                </div>
            </div>

            <div id="profileActionButtons" style="display: flex; flex-direction: column; align-items: center; gap: 5px; margin-top: 10px;">
                <p class="upload-text" id="uploadInstruction">Click camera to change photo</p>
                <button type="button" id="reAdjustBtn" onclick="startAdjustingCurrent()" class="re-adjust-btn">
                    <span class="material-symbols-outlined" style="font-size: 14px;">tune</span>
                    Adjust Photo
                </button>
            </div>
        </div>

        <div class="input-group">
            <label>Full Name</label>
            <input type="text" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" disabled>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="text" value="{{ auth()->user()->email }}" disabled>
        </div>

        <div class="input-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" value="********" id="currentPassword" readonly>
                <span class="toggle-eye" onclick="togglePassword('currentPassword', 'currentEyeIcon')">
                    <span class="material-symbols-outlined" id="currentEyeIcon">visibility</span>
                </span>
            </div>
        </div>

        <button class="change-btn" id="changeBtn" onclick="showChangeSection()">Change Password</button>

        <div id="changeSection" style="display:none; flex-direction:column; gap:12px;">
            <div class="input-group">
                <label>New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="newPassword" placeholder="Enter new password">
                    <span class="toggle-eye" onclick="togglePassword('newPassword', 'newEyeIcon')">
                        <span class="material-symbols-outlined" id="newEyeIcon">visibility</span>
                    </span>
                </div>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirmPassword" placeholder="Confirm new password">
                    <span class="toggle-eye" onclick="togglePassword('confirmPassword', 'confirmEyeIcon')">
                        <span class="material-symbols-outlined" id="confirmEyeIcon">visibility</span>
                    </span>
                </div>
            </div>

            <div class="account-actions">
                <button class="cancel-btn" onclick="closeAccountModal()">Cancel</button>
                <button class="save-btn" onclick="updatePassword()">Save</button>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast">
    <span id="toast-icon" class="material-symbols-outlined"></span>
    <span id="toast-message"></span>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const applicantId = {{ $applicant->id }};
    let currentRequirementId = null;
    let currentRequirementName = null;

    // ================= TOAST NOTIFICATION =================
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

    // ================= CLOCK LOGIC =================
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

    // ================= PROFILE DROPDOWN =================
    const profileWrapper = document.getElementById("profileWrapper");
    const dropdown = document.getElementById("profileDropdown");
    if(profileWrapper) {
        profileWrapper.onclick = () => dropdown.classList.toggle("show");
        document.addEventListener("click", (e) => !profileWrapper.contains(e.target) && dropdown.classList.remove("show"));
    }

    // ================= CUSTOM REASON DROPDOWN FUNCTIONS =================
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

    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        const container = document.getElementById('reasonDropdownContainer');
        if (container && !container.contains(e.target)) {
            const menu = document.getElementById('reasonMenu');
            if (menu) menu.classList.remove('show');
        }
    });

// ================= DOCUMENT SELECTION =================
function selectDocument(element, requirementId, requirementName) {
    document.querySelectorAll('.doc-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
    
    currentRequirementId = requirementId;
    currentRequirementName = requirementName;
    
    // Reset form fields
    document.querySelectorAll('input[name="status"]').forEach(radio => radio.checked = false);
    document.getElementById('reasonSelect').value = '';
    document.getElementById('selectedReasonLabel').innerText = 'Select a reason';
    document.getElementById('commentText').value = '';
    
    document.getElementById('reasonGroup').style.display = 'none';
    document.getElementById('commentGroup').style.display = 'block';
    document.getElementById('buttonGroup').style.display = 'flex';
    
    const previewDiv = document.getElementById('documentPreview');
    previewDiv.innerHTML = `
        <div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: #999;">hourglass_empty</span>
            <p style="margin-top: 10px;">Loading document...</p>
        </div>
    `;
    
    fetch(`/staff/applicant/${applicantId}/document/${requirementId}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.document) {
            const doc = data.document;
            
            // Handle Status Radios
            if (doc.status) {
                const radio = document.querySelector(`input[name="status"][value="${doc.status}"]`);
                if (radio) {
                    radio.checked = true;
                    if (doc.status === 'rejected' || doc.status === 'incomplete') {
                        document.getElementById('reasonGroup').style.display = 'block';
                        if (doc.verification_reason) {
                            document.getElementById('selectedReasonLabel').innerText = doc.verification_reason;
                            document.getElementById('reasonSelect').value = doc.verification_reason;
                        }
                    }
                }
            }
            if (doc.verification_comment) {
                document.getElementById('commentText').value = doc.verification_comment;
            }
            
            let displayDate = 'Not available';
            if (doc.upload_date) {
                let uploadDate = new Date(doc.upload_date);
                
                if (!isNaN(uploadDate.getTime())) {
                    displayDate = uploadDate.toLocaleString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                }
            }
            
            previewDiv.innerHTML = `
                <div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: #223381;">description</span>
                    <p style="margin: 10px 0; font-weight: 600; color: #333;">${doc.file_name || 'Document'}</p>
                    <p style="font-size: 13px; color: #666; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 5px;">
                        <span class="material-symbols-outlined" style="font-size: 16px;">calendar_month</span> 
                        Uploaded: ${displayDate}
                    </p>
                    <button onclick="window.open('${doc.file_path}', '_blank')" style="margin-top: 15px; padding: 10px 25px; background: #223381; color: white; border: none; border-radius: 8px; cursor: pointer; font-family: 'Poppins';">
                        View Document
                    </button>
                </div>
            `;
        } else {
            previewDiv.innerHTML = `
                <div class="file-preview" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 12px;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: #999;">description</span>
                    <p style="margin-top: 10px;">No document uploaded yet for this requirement</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        previewDiv.innerHTML = `
            <div class="file-preview" style="text-align: center; padding: 20px;">
                <span class="material-symbols-outlined" style="color: #e03d4d;">error</span>
                <p>Error loading document.</p>
            </div>
        `;
    });
}

    // ================= RADIO BUTTON CHANGE HANDLER =================
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
        let iconName = 'hourglass_empty';
        let statusClass = '';
        let statusColor = '#999';
        
        if (status === 'approved') {
            iconName = 'check_circle';
            statusClass = 'approved';
            statusColor = '#25c14a';
        } else if (status === 'rejected') {
            iconName = 'cancel';
            statusClass = 'rejected';
            statusColor = '#e03d4d';
        } else if (status === 'incomplete') {
            iconName = 'pending';
            statusClass = 'incomplete';
            statusColor = '#EF7631';
        }
        
        if (iconSpan) {
            iconSpan.innerText = iconName;
            iconSpan.style.color = statusColor;
        }
        
        // Update the document item class
        docItem.classList.remove('approved', 'rejected', 'incomplete');
        if (statusClass) {
            docItem.classList.add(statusClass);
        }
        
        // Update the data-status attribute
        docItem.setAttribute('data-status', status);
    }
}

    // ================= UPDATE VERIFICATION =================
function updateVerification() {
    if (!currentRequirementId) {
        showToast("Please select a document first", "error");
        return;
    }
    
    const selectedStatus = document.querySelector('input[name="status"]:checked');
    if (!selectedStatus) {
        showToast("Please select a verification status", "error");
        return;
    }
    
    const status = selectedStatus.value;
    const reason = document.getElementById('reasonSelect').value;
    const comment = document.getElementById('commentText').value;
    
    if ((status === 'rejected' || status === 'incomplete') && !reason) {
        showToast("Please provide a reason for rejection/incomplete", "error");
        return;
    }
    
    // Build the message that will be sent to student
    let studentMessage = '';
    if (status === 'approved') {
        studentMessage = `Your document "${currentRequirementName}" has been APPROVED.`;
    } else if (status === 'rejected') {
        studentMessage = `Your document "${currentRequirementName}" has been REJECTED. Reason: ${reason}. ${comment ? ' Comment: ' + comment : ''}`;
    } else if (status === 'incomplete') {
        studentMessage = `Your document "${currentRequirementName}" is INCOMPLETE. Reason: ${reason}. ${comment ? ' Comment: ' + comment : ''}`;
    }
    
    Swal.fire({
        title: 'Confirm Verification',
        html: `Update <strong>"${currentRequirementName}"</strong> to <strong style="color: #EF7631;">${status.toUpperCase()}</strong>?<br><br>${comment ? '<strong>Comment:</strong> ' + escapeHtml(comment) : ''}`,
        icon: 'question',
        width: '400px', 
        padding: '1rem', 
        showCancelButton: true,
        confirmButtonColor: '#223381',
        cancelButtonColor: '#858585',
        confirmButtonText: 'Yes, Update',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Updating...',
                width: '300px',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            // First, update the document verification
            fetch(`/staff/applicant/${applicantId}/document-verification`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    requirement_id: currentRequirementId,
                    status: status,
                    reason: reason,
                    comment: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Then, send a message to the student about the update
                    return fetch(`/staff/applicant/${applicantId}/message`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            message: studentMessage
                        })
                    });
                } else {
                    throw new Error(data.message || "Update failed");
                }
            })
            .then(response => response.json())
            .then(messageData => {
                Swal.close();
                showToast("Verification updated and notification sent to student!", "success");
                updateDocumentStatusIcon(currentRequirementId, status);
                
                // Clear the comment box after successful update
                document.getElementById('commentText').value = '';
                document.getElementById('reasonSelect').value = '';
                document.getElementById('selectedReasonLabel').innerText = 'Select a reason';
                document.getElementById('reasonGroup').style.display = 'none';
            })
            .catch(error => {
                Swal.close();
                showToast(error.message || "Error updating verification", "error");
            });
        }
    });
}

function updateDocumentStatusIcon(requirementId, status) {
    const docItem = document.querySelector(`.doc-item[data-id="${requirementId}"]`);
    if (docItem) {
        const iconSpan = docItem.querySelector('.doc-status-icon .material-symbols-outlined');
        let iconName = 'hourglass_empty';
        let statusClass = '';
        let statusColor = '#999';
        
        if (status === 'approved') {
            iconName = 'check_circle';
            statusClass = 'approved';
            statusColor = '#25c14a';
        } else if (status === 'rejected') {
            iconName = 'cancel';
            statusClass = 'rejected';
            statusColor = '#e03d4d';
        } else if (status === 'incomplete') {
            iconName = 'pending';
            statusClass = 'incomplete';
            statusColor = '#EF7631';
        }
        
        if (iconSpan) {
            iconSpan.innerText = iconName;
            iconSpan.style.color = statusColor;
        }
        
        docItem.classList.remove('approved', 'rejected', 'incomplete');
        if (statusClass) {
            docItem.classList.add(statusClass);
        }
        
        docItem.setAttribute('data-status', status);
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

    // ================= MODAL CONTROLS =================
    function openAccountModal() { document.getElementById("accountModal").classList.add("show"); }
    function closeAccountModal() { document.getElementById("accountModal").classList.remove("show"); }
    
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.innerText = "visibility";
        } else {
            input.type = "password";
            icon.innerText = "visibility_off";
        }
    }
    
    function showChangeSection() {
        document.getElementById("changeBtn").style.display = "none";
        document.getElementById("changeSection").style.display = "flex";
    }
    
    async function updatePassword() {
        const newPass = document.getElementById("newPassword").value;
        const confirmPass = document.getElementById("confirmPassword").value;
        if (!newPass || !confirmPass) { showToast("Please fill all fields", "error"); return; }
        if (newPass !== confirmPass) { showToast("Passwords do not match", "error"); return; }
        
        try {
            const response = await fetch("/update-password", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ password: newPass, password_confirmation: confirmPass })
            });
            const data = await response.json();
            if (response.ok) {
                showToast("Password updated successfully!", "success");
                closeAccountModal();
            } else {
                showToast(data.message || "Invalid password requirements", "error");
            }
        } catch (error) {
            showToast("Server connection failed", "error");
        }
    }

    // ================= CROPPIE FUNCTIONS =================
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
            croppieInstance = new Croppie(document.getElementById('image-editor'), {
                viewport: { width: 150, height: 150, type: 'circle' },
                boundary: { width: 250, height: 250 },
                showZoomer: true,
                enableOrientation: true
            });
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
        croppieInstance = new Croppie(document.getElementById('image-editor'), {
            viewport: { width: 150, height: 150, type: 'circle' },
            boundary: { width: 250, height: 250 },
            showZoomer: true
        });
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
        saveBtn.disabled = true;
        saveBtn.innerText = "Saving...";
        const base64 = await croppieInstance.result({ type: 'base64', size: 'viewport', format: 'jpeg' });
        try {
            const res = await fetch("/profile/upload-image", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ image: base64 })
            });
            const data = await res.json();
            if (data.success) {
                showToast("Profile photo updated!", "success");
                ['profileImg', 'modalProfilePreview'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.src = data.path;
                });
                const dropAv = document.querySelector('.dropdown-avatar');
                if(dropAv) dropAv.src = data.path;
                cancelAdjustment();
            } else {
                showToast("Upload failed", "error");
            }
        } catch (e) { showToast("Upload failed", "error"); }
        saveBtn.disabled = false;
        saveBtn.innerText = "Save Photo";
    }

    // ================= AUTO-SELECT FIRST DOCUMENT =================
    document.addEventListener('DOMContentLoaded', function() {
        const firstDoc = document.querySelector('.doc-item');
        if (firstDoc) {
            const id = firstDoc.getAttribute('data-id');
            const name = firstDoc.getAttribute('data-name');
            selectDocument(firstDoc, id, name);
        }
    });

    // ================= REAL-TIME DATE FORMATTER =================
function formatRealTimeDate(dateString) {
    if (!dateString) return 'Unknown date';
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) {
        return 'Just now';
    } else if (diffMins < 60) {
        return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    } else if (diffHours < 24) {
        return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    } else if (diffDays < 7) {
        return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    } else {
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }
}

let refreshInterval = null;

function startRealTimeRefresh(doc) {
    if (refreshInterval) clearInterval(refreshInterval);
    
    refreshInterval = setInterval(() => {
        if (currentRequirementId && doc) {
            const previewDiv = document.getElementById('documentPreview');
            if (previewDiv && previewDiv.innerHTML.includes('Uploaded:')) {
                const formattedDate = formatRealTimeDate(doc.upload_date);
                previewDiv.innerHTML = previewDiv.innerHTML.replace(/Uploaded: .*?(?=<)/, `Uploaded: ${formattedDate} `);
            }
        }
    }, 60000); // Update every minute
}

</script>
</body>
</html>