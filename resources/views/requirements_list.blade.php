<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Dashboard - BU-ETEEAP</title>
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('images/eteeap_logo.png') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link rel="stylesheet" href="{{ asset('css/requirements.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Style for clickable links in notes */
    .note-cell a {
        color: #223381;
        text-decoration: none;
        font-weight: 500;
        border-bottom: 1px dashed #223381;
        transition: all 0.2s ease;
    }
    .note-cell a:hover {
        color: #EF7631;
        border-bottom-color: #EF7631;
        text-decoration: none;
    }
    .note-cell {
        word-break: break-word;
        max-width: 300px;
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
                    <button type="submit" class="dropdown-item" onclick="event.stopPropagation();">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="sidebar">
        <a href="{{ route('staff.dashboard') }}" class="nav-item">
            <span class="material-symbols-outlined icon">home</span>
            Home
        </a>
        <a href="#" class="nav-item active">
            <span class="material-symbols-outlined icon">assignment</span>
            Requirements List
        </a>
        <a href="{{ route('reports') }}" class="nav-item">
            <span class="material-symbols-outlined icon">bar_chart_4_bars</span>
            Reports
        </a>
    </div>

    <div class="main-bg">
        <h1 class="page-title">Requirements List Management</h1>

        <div class="action-bar">
            <button class="add-btn" onclick="toggleModal()">
                <span class="material-symbols-outlined">add</span>
                Add New Requirement
            </button>
            <div class="search-wrapper">
                <span class="material-symbols-outlined search-icon">search</span>
                <input type="text" placeholder="Search Requirements" class="search-input">
            </div>
        </div>

        <div class="table-container">
            <table class="requirements-table">
                <thead>
                    <tr>
                        <th class="col-name">Requirement Name</th>
                        <th class="col-note">Note</th>
                        <th class="col-submission">Type of Submission</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody id="reqTableBody">
                    @foreach($requirements as $req)
                    <tr data-id="{{ $req->id }}">
                        <td class="req-name-cell">{{ $req->name }}</td>
                        <td class="note-cell" data-raw-note="{{ $req->note }}">{{ $req->note }}</td>
                        <td class="submission-type-cell">
                            @if(($req->submission_type ?? 'gdrive_link') == 'gdrive_link')
                                <span><span class="material-symbols-outlined" style="font-size: 16px; color: #223381;">link</span> Google Drive Link</span>
                            @else
                                <span><span class="material-symbols-outlined" style="font-size: 16px; color: #EF7631;">upload_file</span> File Upload</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <div class="action-btns">
                                <button class="edit-btn" onclick="openEditModal({{ $req->id }}, '{{ addslashes($req->name) }}', `{{ addslashes($req->note) }}`, '{{ $req->submission_type ?? 'gdrive_link' }}')">
                                    <span class="material-symbols-outlined">edit</span>
                                    <span>Edit</span>
                                </button>
                                <button class="delete-btn" onclick="confirmDelete({{ $req->id }})">
                                    <span class="material-symbols-outlined">delete</span>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD REQUIREMENT MODAL -->
<div id="addRequirementModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Add New Requirement</h2>
            <span class="close-modal-btn" onclick="toggleModal()">&times;</span>
        </div>
        <form id="addRequirementForm">
            <div class="form-group">
                <label for="reqName">Requirement Name <span class="required">*</span></label>
                <input type="text" id="reqName" placeholder="e.g. Birth Certificate" required>
            </div>
            <div class="form-group">
                <label for="reqNote">Note <span class="optional">(Optional)</span></label>
                <textarea id="reqNote" rows="4" placeholder="Add some instructions or details... (You can paste links, they will be clickable)"></textarea>
                <small style="color: #666; font-size: 11px;"> Tip: Links like https://example.com will be automatically clickable</small>
            </div>
            
            <div class="submission-type-group">
                <label class="submission-label">Type of Submission <span class="required">*</span></label>
                <div class="submission-options">
                    <label class="sub-option">
                        <input type="radio" name="submission_type" value="gdrive_link" checked> 
                        <span>Google Drive Link</span>
                    </label>
                    <label class="sub-option">
                        <input type="radio" name="submission_type" value="file_upload"> 
                        <span>File Upload</span>
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="toggleModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Requirement</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT REQUIREMENT MODAL -->
<div id="editRequirementModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Edit Requirement</h2>
            <span class="close-modal-btn" onclick="closeEditModal()">&times;</span>
        </div>
        <form id="editRequirementForm">
            <input type="hidden" id="editReqId">
            <div class="form-group">
                <label>Requirement Name <span class="required">*</span></label>
                <input type="text" id="editReqName" required>
            </div>
            <div class="form-group">
                <label>Note</label>
                <textarea id="editReqNote" rows="4"></textarea>
                <small style="color: #666; font-size: 11px;">Tip: Links like https://example.com will be automatically clickable</small>
            </div>
            
            <div class="submission-type-group">
                <label class="submission-label">Type of Submission <span class="required">*</span></label>
                <div class="submission-options">
                    <label class="sub-option">
                        <input type="radio" name="edit_submission_type" value="gdrive_link"> 
                        <span>Google Drive Link</span>
                    </label>
                    <label class="sub-option">
                        <input type="radio" name="edit_submission_type" value="file_upload"> 
                        <span>File Upload</span>
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-save">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ACCOUNT MODAL -->
<div id="accountModal" class="account-modal">
    <div class="account-box">
        <span class="close-modal" onclick="closeAccountModal()">&times;</span>
        <h2>Manage Account</h2>
        <div class="profile-upload-section">
            <div class="avatar-wrapper" id="defaultAvatarView">
                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" id="modalProfilePreview" class="modal-avatar">
                <label for="profileUpload" class="upload-badge">
                    <span class="material-symbols-outlined">photo_camera</span>
                </label>
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
            <div class="input-group">
                <label>New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="newPassword" placeholder="Enter new password">
                    <span class="toggle-eye" onclick="togglePassword('newPassword', 'newEyeIcon')"><span class="material-symbols-outlined" id="newEyeIcon">visibility</span></span>
                </div>
            </div>
            <div class="input-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirmPassword" placeholder="Confirm new password">
                    <span class="toggle-eye" onclick="togglePassword('confirmPassword', 'confirmEyeIcon')"><span class="material-symbols-outlined" id="confirmEyeIcon">visibility</span></span>
                </div>
            </div>
            <div class="account-actions">
                <button class="cancel-btn" onclick="closeAccountModal()">Cancel</button>
                <button class="save-btn" onclick="updatePassword()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- CONFIRM MODAL -->
<div id="confirmModal" class="modal-overlay">
    <div class="modal-box" style="text-align:center;">
        <span class="material-symbols-outlined" style="font-size:55px; color:#d9534f; margin-bottom:10px;">warning</span>
        <h2 id="confirmText" style="margin-bottom:15px;">Are you sure?</h2>
        <div class="modal-footer" style="justify-content:center;">
            <button class="btn-cancel" onclick="closeConfirm()">Cancel</button>
            <button class="btn-save" id="confirmBtn">Yes</button>
        </div>
    </div>
</div>

<div id="toast" class="toast">
    <span id="toast-icon" class="material-symbols-outlined"></span>
    <span id="toast-message"></span>
</div>

<script>
    let croppieInstance = null;

    // ========== FUNCTION TO CONVERT TEXT LINKS TO CLICKABLE HTML ==========
    function autoConvertLinks(text) {
        if (!text) return '';
        
        // URL pattern: matches http://, https://, and www. links
        const urlPattern = /(\b(https?:\/\/|www\.)[\w\-\.]+\.[a-z]{2,}(?:\/[\w\-\.\/?%&=#]*)?)/gi;
        
        return text.replace(urlPattern, function(url) {
            let href = url;
            // Add https:// if missing for www links
            if (url.startsWith('www.')) {
                href = 'https://' + url;
            }
            return `<a href="${href}" target="_blank" rel="noopener noreferrer">${url}</a>`;
        });
    }

    // ========== FUNCTION TO PROCESS ALL NOTE CELLS ==========
    function processAllNoteCells() {
        const noteCells = document.querySelectorAll('.note-cell');
        noteCells.forEach(cell => {
            const rawNote = cell.getAttribute('data-raw-note') || cell.innerText;
            if (rawNote && rawNote.trim() !== '') {
                // Only process if contains http, https, or www
                if (rawNote.match(/(https?:\/\/|www\.)/i)) {
                    const convertedHtml = autoConvertLinks(rawNote);
                    if (convertedHtml !== cell.innerText) {
                        cell.innerHTML = convertedHtml;
                    }
                }
            }
        });
    }

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

    function openAccountModal() { document.getElementById("accountModal").classList.add("show"); }
    function toggleModal() { document.getElementById('addRequirementModal').classList.toggle('show'); }

    function closeAccountModal() {
        const modal = document.getElementById("accountModal");
        modal.classList.remove("show");
        setTimeout(() => {
            document.getElementById("changeBtn").style.display = "block";
            document.getElementById("changeSection").style.display = "none";
            cancelAdjustment();
        }, 300);
    }

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
        const originalContent = saveBtn.innerHTML;
        saveBtn.disabled = true; saveBtn.innerText = "Saving...";
        const base64 = await croppieInstance.result({ type: 'base64', size: 'viewport', format: 'jpeg' });
        try {
            const res = await fetch("/profile/upload-image", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
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
            }
        } catch (e) { showToast("Upload failed", "error"); }
        saveBtn.disabled = false; saveBtn.innerHTML = originalContent;
    }

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
        const saveBtn = document.querySelector('#changeSection .save-btn');
        if (!newPass || !confirmPass) { showToast("Please fill all fields", "error"); return; }
        if (newPass !== confirmPass) { showToast("Passwords do not match", "error"); return; }
        saveBtn.disabled = true; saveBtn.innerText = "Saving...";
        try {
            const response = await fetch("/update-password", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ password: newPass, password_confirmation: confirmPass })
            });
            const data = await response.json();
            if (response.ok) {
                document.getElementById("currentPassword").value = newPass;
                showToast("Password updated successfully!", "success");
                closeAccountModal();
            } else { showToast(data.message || "Invalid password requirements", "error"); }
        } catch (error) { showToast("Server connection failed", "error"); }
        finally { saveBtn.disabled = false; saveBtn.innerText = "Save"; }
    }

    let confirmCallback = null;
    function showConfirm(message, callback) {
        const modal = document.getElementById("confirmModal");
        const text = document.getElementById("confirmText");
        text.innerText = message;
        modal.classList.add("show");
        confirmCallback = callback;
    }
    function closeConfirm() { document.getElementById("confirmModal").classList.remove("show"); confirmCallback = null; }
    
    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById("confirmBtn");
        if (btn) btn.addEventListener("click", async () => { if (typeof confirmCallback === "function") await confirmCallback(); closeConfirm(); });
        
        // Process note cells to convert links to clickable
        processAllNoteCells();
    });

    // ADD REQUIREMENT
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("addRequirementForm");
        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const name = document.getElementById("reqName").value;
                let note = document.getElementById("reqNote").value;
                const submissionType = document.querySelector('input[name="submission_type"]:checked').value;
                const saveBtn = form.querySelector(".btn-save");
                saveBtn.disabled = true; 
                saveBtn.innerText = "Saving...";
                
                try {
                    const res = await fetch("/requirements", {
                        method: "POST",
                        headers: { 
                            "Content-Type": "application/json", 
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify({ name, note, submission_type: submissionType })
                    });
                    
                    const data = await res.json();
                    if (data.success) {
                        showToast("Requirement added successfully!", "success");
                        form.reset();
                        document.querySelector('input[name="submission_type"][value="gdrive_link"]').checked = true;
                        toggleModal();
                        setTimeout(() => location.reload(), 1000);
                    } else { 
                        showToast(data.message || "Failed to add requirement", "error"); 
                    }
                } catch (err) { 
                    console.error(err);
                    showToast("Server error: " + err.message, "error"); 
                }
                finally { 
                    saveBtn.disabled = false; 
                    saveBtn.innerText = "Save Requirement"; 
                }
            });
        }
    });

    // EDIT MODAL
    function openEditModal(id, name, note, submissionType) {
        document.getElementById("editReqId").value = id;
        document.getElementById("editReqName").value = name;
        document.getElementById("editReqNote").value = note;
        const radioValue = submissionType || 'gdrive_link';
        const radioToCheck = document.querySelector(`input[name="edit_submission_type"][value="${radioValue}"]`);
        if (radioToCheck) radioToCheck.checked = true;
        document.getElementById("editRequirementModal").classList.add("show");
    }
    
    function closeEditModal() { 
        document.getElementById("editRequirementModal").classList.remove("show"); 
    }

    // EDIT SUBMIT
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("editRequirementForm");
        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const id = document.getElementById("editReqId").value;
                const name = document.getElementById("editReqName").value;
                const note = document.getElementById("editReqNote").value;
                const submissionType = document.querySelector('input[name="edit_submission_type"]:checked').value;
                const btn = form.querySelector(".btn-save");
                btn.disabled = true; 
                btn.innerText = "Updating...";
                
                try {
                    const res = await fetch(`/requirements/${id}`, {
                        method: "PUT",
                        headers: { 
                            "Content-Type": "application/json", 
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify({ name, note, submission_type: submissionType })
                    });
                    
                    const data = await res.json();
                    if (data.success) { 
                        showToast("Requirement updated successfully!", "success"); 
                        closeEditModal(); 
                        setTimeout(() => location.reload(), 1000);
                    } else { 
                        showToast(data.message || "Update failed", "error"); 
                    }
                } catch (err) { 
                    console.error(err);
                    showToast("Server error: " + err.message, "error"); 
                }
                finally { 
                    btn.disabled = false; 
                    btn.innerText = "Update"; 
                }
            });
        }
    });

    function confirmDelete(id) {
        showConfirm("Delete this requirement?", async () => {
            try {
                const res = await fetch(`/requirements/${id}`, { 
                    method: "DELETE", 
                    headers: { 
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
                    } 
                });
                const data = await res.json();
                if (data.success) { 
                    showToast("Deleted successfully!", "success"); 
                    document.querySelector(`tr[data-id="${id}"]`)?.remove(); 
                } else { 
                    showToast("Delete failed", "error"); 
                }
            } catch (err) { 
                showToast("Server error", "error"); 
            }
        });
    }

    document.querySelector('.search-input')?.addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('table tbody tr'); 
        tableRows.forEach(row => { row.style.display = row.innerText.toLowerCase().includes(searchTerm) ? "" : "none"; });
    });
</script>

</body>
</html>