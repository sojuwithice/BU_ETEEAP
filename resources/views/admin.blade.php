<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | ETEEAP Analytics</title>
    <link rel="icon" type="image/png" href="{{ asset('images/eteeap_logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Raleway:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
       
        .sidebar {
            transition: width 0.28s cubic-bezier(0.2, 0.9, 0.4, 1.1), padding 0.2s ease;
            will-change: width;
            backface-visibility: hidden;
            transform: translateZ(0);
        }
        
        .sidebar.collapsed {
            width: 85px;
        }
        
        .sidebar.collapsed .brand-name,
        .sidebar.collapsed .nav-item span {
            display: none;
        }
        
        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 15px 0;
        }
        
        .sidebar.collapsed .nav-item i {
            margin: 0 auto;
        }
        
        .sidebar.collapsed .logo-section {
            justify-content: center;
        }
        
        .sidebar.collapsed .logo-placeholder {
            width: 45px;
            height: 45px;
        }
        
        /* Toggle button smooth */
        .sidebar-toggle {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .sidebar-toggle:hover {
            transform: scale(1.08);
            background: var(--accent-orange, #f57c1f);
            color: white;
        }
        
        /* Main content smooth transition */
        .main-content {
            transition: padding 0.25s ease;
        }
        
        /* Pie chart wrapper styles */
        .pie-chart-card .pie-content-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex: 1;
            gap: 20px;
            min-height: 0;
        }
        
        .pie-chart-card .chart-container {
            flex: 1.5;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .pie-chart-card canvas {
            max-height: 180px !important;
            max-width: 100% !important;
        }
        
        .pie-chart-card .legend-grid {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: center;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dark, #1a3c7a);
        }
        
        .legend-count {
            margin-left: auto;
            font-weight: 800;
            color: var(--accent-orange, #f57c1f);
            background: #f0f4f8;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }
        
        /* Chart containers */
        .chart-container {
            flex: 1;
            min-height: 0;
            position: relative;
            width: 100%;
        }
        
        .chart-container canvas {
            max-height: 100%;
            width: 100%;
        }
        
        /* Bar chart and trend card heights */
        .bar-chart-card .chart-container,
        .trend-card .chart-container {
            min-height: 200px;
        }
        
        /* Stat card adjustments */
        .stat-number {
            font-size: clamp(28px, 4vh, 44px);
        }
        
        .percent-sign {
            font-size: 24px;
        }
        
        /* Toast notification */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary-blue, #1b2e63);
            color: white;
            padding: 10px 20px;
            border-radius: 40px;
            font-weight: bold;
            z-index: 999;
            font-size: 14px;
            font-family: 'Raleway', sans-serif;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
    </style>
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
       class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
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
            <h1>Dashboard Overview</h1>
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

    <div class="dashboard-grid">
        <!-- Stat Cards -->
        <div class="card stat-card">
            <div class="stat-label">
                <span class="icon-bg"><i class="fas fa-users"></i></span>
                Total Registration
            </div>
            <div class="stat-number" id="totalReg">105</div>
        </div>

        <div class="card stat-card">
            <div class="stat-label">
                <span class="icon-bg"><i class="fas fa-user-plus"></i></span>
                Approved Applicants
            </div>
            <div class="stat-number" id="approvedCount">96</div>
        </div>

        <!-- Program Statistics -->
        <div class="card pie-chart-card">
            <h3>Program Statistics</h3>
            <div class="pie-content-wrapper">
                <div class="chart-container">
                    <canvas id="programPieChart"></canvas>
                </div>
                <div class="legend-grid" id="legendGrid">
                    <div class="legend-item">
                        <span class="dot" style="background:#f57c1f"></span> 
                        BS Computer Science 
                        <span id="bscsCount" class="legend-count">0</span>
                    </div>
                    <div class="legend-item">
                        <span class="dot" style="background:#82c8f8"></span> 
                        BS Fisheries 
                        <span id="bsfCount" class="legend-count">0</span>
                    </div>
                    <div class="legend-item">
                        <span class="dot" style="background:#e66a00"></span> 
                        BS Nursing 
                        <span id="bsnCount" class="legend-count">0</span>
                    </div>
                    <div class="legend-item">
                        <span class="dot" style="background:#1b2e63"></span> 
                        BS Automotive Technology 
                        <span id="bsatCount" class="legend-count">0</span>
                    </div>
                    <div class="legend-item">
                        <span class="dot" style="background:#111e42"></span> 
                        AB Communication 
                        <span id="abcCount" class="legend-count">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="stat-label">
                <span class="icon-bg"><i class="fas fa-hourglass-half"></i></span>
                Pending Status
            </div>
            <div class="stat-number" id="pendingCount">9</div>
        </div>

        <div class="card stat-card">
            <div class="stat-label">
                <span class="icon-bg"><i class="fas fa-clipboard-check"></i></span>
                System Health
            </div>
            <div class="stat-number" id="sysHealth">100<span class="percent-sign">%</span></div>
        </div>

        <!-- Monthly Application (Bar Chart) -->
        <div class="card bar-chart-card">
            <h3>Monthly Application</h3>
            <div class="chart-container">
                <canvas id="monthlyBarChart"></canvas>
            </div>
        </div>

        <!-- Trend Chart (Line Chart) -->
        <div class="card trend-card">
            <h3>User Registration Trend</h3>
            <div class="chart-container">
                <canvas id="trendCanvas"></canvas>
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
        
        // Program mapping
        let programCounts = {
            'BS Computer Science': 0,
            'BS Fisheries': 0,
            'BS Nursing': 0,
            'BS Automotive Technology': 0,
            'AB Communication': 0
        };
        let monthlyData = [];
        let trendData = [];
        let chartLabels = [];
        
        // ============================================
        // SIDEBAR TOGGLE
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
        // FETCH DASHBOARD DATA
        // ============================================
        async function fetchDashboardData() {
            try {
                const response = await fetch('/admin/dashboard/stats', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const data = result.data;
                    
                    // Update stat numbers
                    document.getElementById('totalReg').innerText = data.totalRegistrations || 0;
                    document.getElementById('approvedCount').innerText = data.approvedCount || 0;
                    document.getElementById('pendingCount').innerText = data.pendingCount || 0;
                    document.getElementById('sysHealth').innerHTML = (data.systemHealth || 100) + '<span class="percent-sign">%</span>';
                    
                    // Update program counts
                    programCounts = data.programCounts;
                    document.getElementById('bscsCount').innerText = programCounts['BS Computer Science'] || 0;
                    document.getElementById('bsfCount').innerText = programCounts['BS Fisheries'] || 0;
                    document.getElementById('bsnCount').innerText = programCounts['BS Nursing'] || 0;
                    document.getElementById('bsatCount').innerText = programCounts['BS Automotive Technology'] || 0;
                    document.getElementById('abcCount').innerText = programCounts['AB Communication'] || 0;
                    
                    // Update chart data
                    chartLabels = data.monthlyLabels;
                    monthlyData = data.monthlyData;
                    trendData = data.trendData;
                    
                    updateCharts();
                } else {
                    console.error('API Error:', result.message);
                }
            } catch (error) {
                console.error('Fetch error:', error);
            }
        }
        
        // ============================================
        // UPDATE CHARTS
        // ============================================
        function updateCharts() {
            // Update Pie Chart
            if (window.pieChart) {
                const pieData = [
                    programCounts['BS Computer Science'] || 0,
                    programCounts['BS Fisheries'] || 0,
                    programCounts['BS Nursing'] || 0,
                    programCounts['BS Automotive Technology'] || 0,
                    programCounts['AB Communication'] || 0
                ];
                window.pieChart.data.datasets[0].data = pieData;
                window.pieChart.update();
            }
            
            // Update Bar Chart
            if (window.barChart && chartLabels.length > 0) {
                window.barChart.data.labels = chartLabels;
                window.barChart.data.datasets[0].data = monthlyData;
                window.barChart.update();
            }
            
            // Update Trend Chart
            if (window.trendChart && chartLabels.length > 0) {
                window.trendChart.data.labels = chartLabels;
                window.trendChart.data.datasets[0].data = trendData;
                window.trendChart.update();
            }
        }
        
        // ============================================
        // INITIALIZE CHARTS
        // ============================================
        window.pieChart = new Chart(document.getElementById('programPieChart'), {
            type: 'pie',
            data: {
                labels: ['BS Computer Science', 'BS Fisheries', 'BS Nursing', 'BS Automotive Technology', 'AB Communication'],
                datasets: [{
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: ['#f57c1f', '#82c8f8', '#e66a00', '#1b2e63', '#111e42'],
                    borderWidth: 0
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: true,
                plugins: { legend: { display: false } }
            }
        });
        
        window.barChart = new Chart(document.getElementById('monthlyBarChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{ 
                    label: 'Applications', 
                    data: [0, 0, 0, 0, 0, 0], 
                    backgroundColor: '#f57c1f', 
                    borderRadius: 6 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        
        window.trendChart = new Chart(document.getElementById('trendCanvas'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{ 
                    label: 'Cumulative Registrations', 
                    data: [0, 0, 0, 0, 0, 0], 
                    borderColor: '#1b2e63', 
                    tension: 0.3, 
                    pointBackgroundColor: '#f57c1f',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    fill: true, 
                    backgroundColor: 'rgba(27, 46, 99, 0.05)' 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        
        // Load data
        fetchDashboardData();
        setInterval(fetchDashboardData, 30000);
        
        // Navigation active state
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
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
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (!newPassword.value || !confirmPassword.value) {
        showToast('Please fill in all fields', 'error');
        return;
    }
    
    if (newPassword.value !== confirmPassword.value) {
        showToast('Passwords do not match', 'error');
        return;
    }
    
    if (newPassword.value.length < 8) {
        showToast('Password must be at least 8 characters', 'error');
        return;
    }
    
    // Show loading state
    const saveBtn = document.querySelector('#changeSection .save-btn');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = 'Saving...';
    saveBtn.disabled = true;
    
    // Send to backend using correct endpoint
    fetch('/update-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            password: newPassword.value,
            password_confirmation: confirmPassword.value 
        })
    })
    .then(response => response.json())
    .then(data => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        
        if (data.success) {
            showToast('Password updated successfully!', 'success');
            hideChangeSection();
            // Clear fields
            newPassword.value = '';
            confirmPassword.value = '';
        } else {
            showToast(data.message || 'Failed to update password', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        showToast('An error occurred. Please try again.', 'error');
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
    
    const saveBtn = document.getElementById('savePhotoBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = 'Saving...';
    saveBtn.disabled = true;
    
    croppieInstance.result('base64').then(base64 => {
        // Convert base64 to blob
        fetch(base64)
            .then(res => res.blob())
            .then(blob => {
                const formData = new FormData();
                formData.append('profile_image', blob, 'profile.jpg');
                
                fetch('/profile/upload-image', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    
                    if (data.success) {
                        const timestamp = new Date().getTime();
                        const newImageUrl = data.path + '?t=' + timestamp;

                        document.getElementById('modalProfilePreview').src = newImageUrl;

                        const navbarAvatar = document.getElementById('navbarAvatar');
                        if (navbarAvatar) {
                            navbarAvatar.src = newImageUrl;
                        }
                        
                        showToast("Profile photo updated!", "success");
                        cancelAdjustment();
                    } else {
                        showToast(data.message || 'Failed to update photo', 'error');
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
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
    
    // Force remove any existing inline styles
    toast.style.backgroundColor = '';
    
    // Set class
    
    toast.classList.add('show');
    
    if (type === 'success') {
        toast.classList.add('success');
        toast.style.backgroundColor = '#25c14a'; // Force green
    } else if (type === 'error') {
        toast.classList.add('error');
        toast.style.backgroundColor = '#e03d4d'; // Force red
    }
    
    const toastIcon = document.getElementById('toast-icon');
    const toastMessage = document.getElementById('toast-message');
    
    if (toastIcon) toastIcon.innerText = type === 'success' ? 'check_circle' : 'error';
    if (toastMessage) toastMessage.innerText = msg;
    
    console.log('Toast type:', type, 'Background:', toast.style.backgroundColor);
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>
</body>
</html>