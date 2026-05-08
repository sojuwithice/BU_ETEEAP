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
    <link rel="stylesheet" href="{{ asset('css/audit.css') }}">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    
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
            <h1>Audit Logs</h1>
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

    <!-- AUDIT LOGS CONTAINER -->
    <div class="audit-container">
        <div class="table-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search logs or admin names...">
            </div>
            <div class="filter-group">
                <select class="filter-select">
                    <option value="">All</option>
                    <option value="Security">Security</option>
                    <option value="User Management">User Management</option>
                    <option value="System">System</option>
                </select>
                <input type="date" class="filter-date">
                <button class="export-btn" onclick="exportLogs()">
                    <i class="fas fa-file-export"></i> Export CSV
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="audit-table">
                <thead>
                    <tr>
                        <th>Timestamp <i class="fas fa-sort"></i></th>
                        <th>Admin User</th>
                        <th>Action Category</th>
                        <th>Specific Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="auditTableBody">
    <!-- AJAX LOADED LOGS -->
</tbody>
            </table>
        </div>

        <div class="table-footer">
            <span>Showing 1 to 10 of 247 logs</span>
            <div class="pagination">
                <button disabled><i class="fas fa-chevron-left"></i></button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button><i class="fas fa-chevron-right"></i></button>
            </div>
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

    // --- MODAL LOGIC ---
    let croppieInstance = null;

    function openAccountModal() { document.getElementById('accountModal').classList.add('show'); }
    function closeAccountModal() { document.getElementById('accountModal').classList.remove('show'); cancelAdjustment(); hideChangeSection(); }
    
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.innerText = input.type === 'password' ? 'visibility' : 'visibility_off';
    }

    function showChangeSection() { document.getElementById('changeBtn').style.display = 'none'; document.getElementById('changeSection').style.display = 'flex'; }
    function hideChangeSection() { document.getElementById('changeBtn').style.display = 'block'; document.getElementById('changeSection').style.display = 'none'; }

    // --- IMAGE CROP LOGIC ---
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = (e) => startCroppie(e.target.result);
        reader.readAsDataURL(event.target.files[0]);
    }

    function startCroppie(src) {
        document.getElementById('defaultAvatarView').style.display = 'none';
        document.getElementById('profileActionButtons').style.display = 'none';
        document.getElementById('adjustmentArea').style.display = 'flex';
        const el = document.getElementById('image-editor');
        if (croppieInstance) croppieInstance.destroy();
        croppieInstance = new Croppie(el, {
            viewport: { width: 150, height: 150, type: 'circle' },
            boundary: { width: '100%', height: 250 },
            showZoomer: true
        });
        croppieInstance.bind({ url: src });
    }

    function uploadCroppedImage() {
        croppieInstance.result('base64').then(base64 => {
            // Update Modal Image
            document.getElementById('modalProfilePreview').src = base64;
            // UPDATE DASHBOARD HEADER IMAGE (TAAS)
            document.getElementById('headerAvatar').style.backgroundImage = `url('${base64}')`;
            
            showToast("Profile photo updated!", "success");
            cancelAdjustment();
        });
    }

    function cancelAdjustment() {
        if (croppieInstance) croppieInstance.destroy();
        document.getElementById('adjustmentArea').style.display = 'none';
        document.getElementById('defaultAvatarView').style.display = 'block';
        document.getElementById('profileActionButtons').style.display = 'flex';
    }

    function showToast(msg, type) {
        const toast = document.getElementById('toast');
        toast.className = `toast show ${type}`;
        document.getElementById('toast-icon').innerText = 'check_circle';
        document.getElementById('toast-message').innerText = msg;
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    function exportLogs() {
    window.location.href = "/audit_logs/export";
}

// Replace your existing loadLogs function with this:
function loadLogs(page = 1) {
    const search = document.querySelector('.search-box input')?.value || '';
    const category = document.querySelector('.filter-select')?.value || '';
    const date = document.querySelector('.filter-date')?.value || '';

    fetch(`/audit_logs/fetch?page=${page}&search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            let rows = '';

            if (!data.data || data.data.length === 0) {
                rows = `<tr><td colspan="5" style="text-align:center;">No logs found</td></tr>`;
            } else {
                data.data.forEach(log => {
                    const userEmail = log.user?.email || 'System';
                    const userName = log.user ? `${log.user.first_name || ''} ${log.user.last_name || ''}`.trim() : 'System';
                    const profileImage = log.user?.profile_image
    ? `/storage/${log.user.profile_image}`
    : `/images/default-profile.png`;
                    
                    // Format date properly
                    const logDate = new Date(log.created_at);
                    const formattedDate = logDate.toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    rows += `
                    <tr>
                        <td class="timestamp">${formattedDate}</td>
                        <td class="user-cell">
    <img 
        src="${profileImage}" 
        alt="Profile"
        class="audit-profile-img"
    >

    <div class="user-meta">
        <div class="user-name-text">${userName}</div>
        <div class="user-email-text">${userEmail}</div>
    </div>
</td>
                        <td><span class="cat-badge">${log.category || 'System'}</span></td>
                        <td>${log.action || ''}</td>
                        <td class="details">${log.details || ''}</td>
                    </tr>`;
                });
            }

            document.getElementById('auditTableBody').innerHTML = rows;
            
            // Update pagination info
            if (data.total) {
                const from = ((data.current_page - 1) * data.per_page) + 1;
                const to = Math.min(data.current_page * data.per_page, data.total);
                document.querySelector('.table-footer span:first-child').innerText = 
                    `Showing ${from} to ${to} of ${data.total} logs`;
            }
        })
        .catch(error => {
            console.error('Error loading logs:', error);
            document.getElementById('auditTableBody').innerHTML = 
                '<tr><td colspan="5" style="text-align:center; color:red;">Error loading logs</td></tr>';
        });
}

// Add event listeners with null checks
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-box input');
    const filterSelect = document.querySelector('.filter-select');
    const filterDate = document.querySelector('.filter-date');
    
    if (searchInput) searchInput.addEventListener('input', () => loadLogs());
    if (filterSelect) filterSelect.addEventListener('change', () => loadLogs());
    if (filterDate) filterDate.addEventListener('change', () => loadLogs());
    
    // Initial load
    loadLogs();

    setInterval(() => {
    loadLogs();
}, 5000);
});

</script>
</body>
</html>