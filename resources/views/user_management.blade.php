<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | ETEEAP Analytics</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Raleway:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/user_management.css') }}">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-toggle" id="sidebarToggleBtn">
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="logo-section">
        <div class="logo-placeholder">
            <img src="{{ asset('images/eteeap_logo.png') }}" alt="ETEEAP Logo">
        </div>
        <span class="brand-name">BU-ETEEAP</span>
    </div>
    
    <nav class="nav-menu">
        <a href="{{ route('admin.dashboard') }}" 
        class="nav-item {{ request()->routeIs('admin-dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> <span>Dashboard</span>
        </a>

        <a href="{{ route('user_management') }}" 
        class="nav-item {{ request()->routeIs('user_management') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <span>User Management</span>
        </a>

        <a href="{{ route('audit_logs') }}" 
        class="nav-item {{ request()->routeIs('audit_logs') ? 'active' : '' }}">
            <i class="fas fa-user-check"></i> <span>Audit Logs</span>
        </a>

        <a href="{{ route('homepage_management') }}" 
        class="nav-item {{ request()->routeIs('homepage_management') ? 'active' : '' }}">
            <i class="fas fa-house-user"></i> <span>Homepage Management</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="#" onclick="openAccountModal()" class="nav-item footer-item">
            <i class="fas fa-cog"></i> <span>Manage Account</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0; width: 100%;">
            @csrf
            <button type="submit" class="nav-item footer-item logout-btn" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

<main class="main-content">
    <header class="header">
        <div class="header-left">
            <h1>User Management</h1>
            <p class="date-display" id="liveDateTime">May 4, 2026 | 3:19 PM</p>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                <span class="admin-badge">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            <img 
    src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" 
    id="navbarAvatar" 
    class="avatar-circle"
>
        </div>
    </header>

    <!-- USER MANAGEMENT CONTAINER -->
    <div class="user-container">
        <div class="table-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search logs or admin names...">
            </div>
        </div>

        <div class="table-wrapper">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Security Status</th>
                        <th>Last Pass Change</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">

@foreach($users as $user)

<tr id="userRow{{ $user->id }}">

<td class="name-cell">

<div class="user-info-wrapper">

<img 
src="{{ $user->profile_image 
? asset('storage/' . $user->profile_image) 
: asset('images/default-profile.png') }}"
class="user-avatar-sm"
alt="Profile"
>

<div class="user-details">
    <span class="user-fullname">
        {{ $user->first_name }} {{ $user->last_name }}
    </span>
</div>

</div>

</td>

<td class="email-text">
{{ $user->email }}
</td>

<!-- ROLE COLUMN -->
<td>
    <span class="role-pill {{ $user->role }}">
        {{ ucfirst($user->role) }}
    </span>
</td>

<td>

@if($user->failed_attempts >= 5)

<span class="status-pill danger">
{{ $user->failed_attempts }} Failed Attempts
</span>

@else

<span class="status-pill success">
{{ $user->failed_attempts }} Failed Attempts
</span>

@endif

</td>

<td>

{{ $user->password_changed_at
? \Carbon\Carbon::parse($user->password_changed_at)->format('F d, Y h:i A')
: 'Never'
}}

</td>

<td class="actions-cell">

<button class="action-btn key"
onclick="resetPassword({{ $user->id }})">

<i class="fas fa-key"></i>

</button>

<button class="action-btn lock"
onclick="toggleStatus({{ $user->id }})">

<i class="fas fa-user-lock"></i>

</button>

<button class="action-btn delete"
onclick="deleteUser({{ $user->id }})">

<i class="fas fa-trash-alt"></i>

</button>

</td>

</tr>

@endforeach

</tbody>
            </table>
        </div>
    </div>
    
</main>

<!-- MANAGE ACCOUNT MODAL -->
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

            <div id="adjustmentArea">
                <div id="image-editor"></div> 
                <div class="account-actions">
                    <button type="button" class="cancel-btn" onclick="cancelAdjustment()">Cancel</button>
                    <button id="savePhotoBtn" class="save-photo-btn" onclick="uploadCroppedImage()">
                        <span class="material-symbols-outlined">save</span> Save Photo
                    </button>
                </div>
            </div>

            <div id="profileActionButtons" style="display: flex; flex-direction: column; align-items: center; gap: 5px; margin-top: 10px;">
                <p class="upload-text">Click camera to change photo</p>
                <button type="button" onclick="startAdjustingCurrent()" class="re-adjust-btn">
                    <span class="material-symbols-outlined" style="font-size: 14px;">tune</span> Adjust Photo
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

        <!-- FIXED PASSWORD SECTION -->
        <div class="input-group">
            <label>Current Password</label>
            <div class="password-wrapper">
                <!-- Inalis ang value asterisk, nilagyan ng dummy password para may "makita" kapag tinoggle -->
                <input type="password" value="secretpassword" id="currentPassword" readonly style="background: #f9f9f9; cursor: default;">
                <span class="toggle-eye" onclick="togglePassword('currentPassword', 'currentEyeIcon')">
                    <span class="material-symbols-outlined" id="currentEyeIcon">visibility</span>
                </span>
            </div>
        </div>

        <button class="change-btn" id="changeBtn" onclick="showChangeSection()">Change Password</button>

        <div id="changeSection" style="display:none; flex-direction:column; gap:12px;">
            <div class="input-group">
<div class="input-group">
    <label>New Password</label>
    <div class="password-wrapper">
        <input type="password" id="newPassword" placeholder="Enter new password" autocomplete="new-password">
        <span class="toggle-eye" onclick="togglePassword('newPassword', 'newEyeIcon')">
            <span class="material-symbols-outlined" id="newEyeIcon">visibility</span>
        </span>
    </div>
</div>
<div class="input-group">
    <label>Confirm Password</label>
    <div class="password-wrapper">
        <input type="password" id="confirmPassword" placeholder="Confirm new password" autocomplete="new-password">
        <span class="toggle-eye" onclick="togglePassword('confirmPassword', 'confirmEyeIcon')">
            <span class="material-symbols-outlined" id="confirmEyeIcon">visibility</span>
        </span>
    </div>
</div>
            <div class="account-actions">
                <button class="cancel-btn" onclick="hideChangeSection()">Cancel</button>
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
    (function() {
        'use strict';
        
        // ============================================
        // SMOOTH SIDEBAR TOGGLE
        // ============================================
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        
        if (sidebar && toggleBtn) {
            const toggleIcon = toggleBtn.querySelector('i');
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                if (toggleIcon) toggleIcon.className = 'fas fa-chevron-right';
            }
            
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                if (toggleIcon) toggleIcon.className = isCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                setTimeout(() => {
                    window.pieChart?.resize();
                    window.barChart?.resize();
                    window.trendChart?.resize();
                }, 280);
            });
        }
        
        // ============================================
        // LIVE CLOCK
        // ============================================
        function updateDateTime() {
            const now = new Date();
            const dateStr = now.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateTimeElem = document.getElementById('liveDateTime');
            if (dateTimeElem) dateTimeElem.innerText = `${dateStr} | ${timeStr}`;
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
        

        // ============================================
        // AUTO-UPDATE SIMULATION (Inalis ko ito dati, binalik ko na ngayon)
        // ============================================
        function randomUpdate() {
            const keys = Object.keys(programCounts);
            programCounts[keys[Math.floor(Math.random() * keys.length)]]++;
            window.pieChart.data.datasets[0].data = Object.values(programCounts);
            window.pieChart.update('none');
            document.getElementById('totalReg').innerText = Object.values(programCounts).reduce((a, b) => a + b, 0);
        }
        setInterval(randomUpdate, 12000);

        // ============================================
        // NAVIGATION & LOGOUT HANDLING (FIXED)
        // ============================================
        const navItems = document.querySelectorAll('.nav-item');
        
        navItems.forEach(item => {
            item.addEventListener('click', function () {
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });
    })();

// ============================================
    // MODAL LOGIC
    // ============================================
    let croppieInstance = null;
    
    function openAccountModal() { 
        document.getElementById('accountModal').classList.add('show'); 
    }
    
    function closeAccountModal() { 
        document.getElementById('accountModal').classList.remove('show'); 
        cancelAdjustment(); 
        hideChangeSection(); 
    }
    
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input && icon) {
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.innerText = input.type === 'password' ? 'visibility' : 'visibility_off';
        }
    }
    
    function showChangeSection() { 
        const changeBtn = document.getElementById('changeBtn');
        const changeSection = document.getElementById('changeSection');
        if (changeBtn) changeBtn.style.display = 'none';
        if (changeSection) changeSection.style.display = 'flex';
    }
    
    function hideChangeSection() { 
        const changeBtn = document.getElementById('changeBtn');
        const changeSection = document.getElementById('changeSection');
        if (changeBtn) changeBtn.style.display = 'block';
        if (changeSection) changeSection.style.display = 'none';
        // Clear password fields
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        if (newPassword) newPassword.value = '';
        if (confirmPassword) confirmPassword.value = '';
    }
    
    function updatePassword() {
    // .value ang kunin natin agad
    const newPass = document.getElementById('newPassword').value;
    const confirmPass = document.getElementById('confirmPassword').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    if (!newPass || !confirmPass) {
        showToast('Please fill in all fields', 'error');
        return;
    }

    if (newPass !== confirmPass) {
        showToast('Passwords do not match on client side', 'error');
        return;
    }

    fetch('{{ route("update-password") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            password: newPass,
            password_confirmation: confirmPass // DAPAT SAKTO ITONG SPELLING NA ITO
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            // Dito natin kukunin yung error na nakita mo sa screenshot
            let errorMsg = data.message;
            if (data.errors && data.errors.password) {
                errorMsg = data.errors.password[0]; // "The password field confirmation does not match."
            }
            throw new Error(errorMsg || 'Validation failed');
        }
        return data;
    })
    .then(data => {
        showToast('Password updated successfully!', 'success');
        hideChangeSection();
        // Clear inputs
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
    })
    .catch(error => {
        console.error('Final Error Debug:', error);
        showToast(error.message, 'error');
    });
}
    
    // ============================================
    // IMAGE CROP LOGIC
    // ============================================
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => startCroppie(e.target.result);
            reader.readAsDataURL(file);
        }
    }
    
    function startCroppie(src) {
        const defaultAvatar = document.getElementById('defaultAvatarView');
        const profileActions = document.getElementById('profileActionButtons');
        const adjustmentArea = document.getElementById('adjustmentArea');
        const imageEditor = document.getElementById('image-editor');
        
        if (defaultAvatar) defaultAvatar.style.display = 'none';
        if (profileActions) profileActions.style.display = 'none';
        if (adjustmentArea) adjustmentArea.style.display = 'flex';
        
        if (croppieInstance) croppieInstance.destroy();
        
        croppieInstance = new Croppie(imageEditor, {
            viewport: { width: 150, height: 150, type: 'circle' },
            boundary: { width: '100%', height: 250 },
            showZoomer: true
        });
        croppieInstance.bind({ url: src });
    }
    
    function startAdjustingCurrent() {
        const currentImage = document.getElementById('modalProfilePreview').src;
        startCroppie(currentImage);
    }
    
    function uploadCroppedImage() {
        if (!croppieInstance) return;
        
        croppieInstance.result('base64').then(base64 => {
            // Convert base64 to blob
            fetch(base64)
                .then(res => res.blob())
                .then(blob => {
                    const formData = new FormData();
                    formData.append('profile_image', blob, 'profile.jpg');
                    
                    fetch('{{ route("profile.upload.image") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // update modal preview
                            document.getElementById('modalProfilePreview').src = base64;

                            // update navbar avatar (ITO ANG FIX)
                            const navbarAvatar = document.getElementById('navbarAvatar');
                            if (navbarAvatar) {
                                navbarAvatar.src = base64;
                            }

                            showToast("Profile photo updated!", "success");
                            cancelAdjustment();
                        } else {
                            showToast(data.message || 'Failed to update photo', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        showToast('Failed to upload photo', 'error');
                    });
                });
        });
    }
    
    function cancelAdjustment() {
        if (croppieInstance) {
            croppieInstance.destroy();
            croppieInstance = null;
        }
        
        const defaultAvatar = document.getElementById('defaultAvatarView');
        const profileActions = document.getElementById('profileActionButtons');
        const adjustmentArea = document.getElementById('adjustmentArea');
        
        if (adjustmentArea) adjustmentArea.style.display = 'none';
        if (defaultAvatar) defaultAvatar.style.display = 'block';
        if (profileActions) profileActions.style.display = 'flex';
    }
    
    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    function showToast(msg, type) {
        const toast = document.getElementById('toast');
        if (!toast) return;
        
        toast.className = `toast show ${type}`;
        const toastIcon = document.getElementById('toast-icon');
        const toastMessage = document.getElementById('toast-message');
        
        if (toastIcon) toastIcon.innerText = type === 'success' ? 'check_circle' : 'error';
        if (toastMessage) toastMessage.innerText = msg;
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    /* ============================================
   USER SEARCH (REAL-TIME FILTER + NO RESULTS)
============================================ */
const searchInput = document.querySelector('.search-box input');
const tableBody = document.getElementById('userTableBody');

if (searchInput && tableBody) {
    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        let visibleCount = 0;

        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const name = row.querySelector('.user-fullname')?.innerText.toLowerCase() || '';
            const email = row.querySelector('.email-text')?.innerText.toLowerCase() || '';
            const role = row.querySelector('.role-pill')?.innerText.toLowerCase() || '';

            const match =
                name.includes(keyword) ||
                email.includes(keyword) ||
                role.includes(keyword);

            if (match) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // REMOVE OLD "NO RESULTS" ROW IF EXISTS
        const oldNoResult = document.getElementById('noResultsRow');
        if (oldNoResult) oldNoResult.remove();

        // ADD NO RESULTS ROW IF NONE FOUND
        if (visibleCount === 0) {
            const noRow = document.createElement('tr');
            noRow.id = 'noResultsRow';

            noRow.innerHTML = `
                <td colspan="6" style="
                    text-align:center;
                    padding: 20px;
                    color:#888;
                    font-weight:500;
                ">
                    No results found
                </td>
            `;

            tableBody.appendChild(noRow);
        }
    });
}

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

/* ============================================
   RESET PASSWORD
============================================ */
function resetPassword(id) {
    Swal.fire({
        title: 'Reset Password?',
        text: "This will reset the user's password to default.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reset it',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (!result.isConfirmed) return;

        fetch(`/admin/users/${id}/reset-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                showToast('Password reset successfully', 'success');

                Swal.fire({
                    icon: 'success',
                    title: 'Reset Successful',
                    text: `New Password: ${data.new_password}`,
                    timer: 2500,
                    showConfirmButton: false
                });

            } else {
                showToast('Failed to reset password', 'error');
            }
        })
        .catch(() => {
            showToast('Server error occurred', 'error');
        });
    });
}

/* ============================================
   TOGGLE USER STATUS (LOCK / UNLOCK)
============================================ */
function toggleStatus(id) {
    fetch(`/admin/users/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            showToast('Failed to update status', 'error');
            return;
        }

        const statusText = data.status ? 'User suspended' : 'User activated';

        showToast(statusText, 'success');

        Swal.fire({
            icon: 'success',
            title: statusText,
            timer: 1500,
            showConfirmButton: false
        });

        // OPTIONAL: update UI without reload
        const row = document.getElementById(`userRow${id}`);
        if (row) {
            const statusPill = row.querySelector('.status-pill');

            if (statusPill) {
                if (data.status) {
                    statusPill.classList.remove('success');
                    statusPill.classList.add('danger');
                    statusPill.innerText = 'Suspended';
                } else {
                    statusPill.classList.remove('danger');
                    statusPill.classList.add('success');
                    statusPill.innerText = 'Active';
                }
            }
        }
    })
    .catch(() => {
        showToast('Server error', 'error');
    });
}

/* ============================================
   DELETE USER
============================================ */
function deleteUser(id) {
    Swal.fire({
        title: 'Delete User?',
        text: "This cannot be undone.",
        icon: 'error',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (!result.isConfirmed) return;

        fetch(`/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                showToast('User deleted successfully', 'success');

                const row = document.getElementById(`userRow${id}`);
                if (row) row.remove();

                Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    timer: 1200,
                    showConfirmButton: false
                });

            } else {
                showToast('Delete failed', 'error');
            }
        })
        .catch(() => {
            showToast('Server error', 'error');
        });
    });
}
</script>
</body>
</html>