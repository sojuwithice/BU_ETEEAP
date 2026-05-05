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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* ============================================
           DASHBOARD EXTENSION STYLES
           Smooth animations & responsive layout
           ============================================ */
        
        /* Sidebar Smooth Animation */
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
        <a href="#" class="nav-item active" data-nav="dashboard">
            <i class="fas fa-th-large"></i> <span>Dashboard</span>
        </a>
        <a href="#" class="nav-item" data-nav="usermanagement">
            <i class="fas fa-users"></i> <span>User Management</span>
        </a>
        <a href="#" class="nav-item" data-nav="auditlogs">
            <i class="fas fa-user-check"></i> <span>Audit Logs</span>
        </a>
    </nav>
</aside>

<main class="main-content">
    <header class="header">
        <div class="header-left">
            <h1>Dashboard Overview</h1>
            <p class="date-display" id="liveDateTime">May 4, 2026 | 3:19 PM</p>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name">Icca Balin</span>
                <span class="admin-badge">Admin</span>
            </div>
            <div class="avatar-circle"></div>
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
                    <div class="legend-item"><span class="dot b-orange"></span> BUCS <span id="bucsCount" class="legend-count">42</span></div>
                    <div class="legend-item"><span class="dot b-lblue"></span> BUCN <span id="bucnCount" class="legend-count">18</span></div>
                    <div class="legend-item"><span class="dot b-dorange"></span> BUCAL <span id="bucalCount" class="legend-count">15</span></div>
                    <div class="legend-item"><span class="dot b-dblue"></span> BUCIT <span id="bucitCount" class="legend-count">22</span></div>
                    <div class="legend-item"><span class="dot b-vdark"></span> BUTC <span id="butcCount" class="legend-count">8</span></div>
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

<script>
    (function() {
        'use strict';
        
        // ============================================
        // SMOOTH SIDEBAR TOGGLE (no lag)
        // ============================================
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        
        if (sidebar && toggleBtn) {
            const toggleIcon = toggleBtn.querySelector('i');
            
            // Load saved state from localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            }
            
            // Toggle with smooth transition
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const isCollapsed = sidebar.classList.contains('collapsed');
                
                if (isCollapsed) {
                    sidebar.classList.remove('collapsed');
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-left');
                    }
                    localStorage.setItem('sidebarCollapsed', 'false');
                } else {
                    sidebar.classList.add('collapsed');
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-left');
                        toggleIcon.classList.add('fa-chevron-right');
                    }
                    localStorage.setItem('sidebarCollapsed', 'true');
                }
                
                // Resize charts after animation completes
                setTimeout(() => {
                    if (window.pieChart) window.pieChart.resize();
                    if (window.barChart) window.barChart.resize();
                    if (window.trendChart) window.trendChart.resize();
                }, 280);
            });
        }
        
        // ============================================
        // LIVE CLOCK & DATE (updates every second)
        // ============================================
        function updateDateTime() {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', options);
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            const timeStr = `${hours}:${minutes} ${ampm}`;
            
            const dateTimeElem = document.getElementById('liveDateTime');
            if (dateTimeElem) {
                dateTimeElem.innerText = `${dateStr} | ${timeStr}`;
            }
        }
        
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // ============================================
        // DATA MODELS
        // ============================================
        let programCounts = {
            BUCS: 42,
            BUCN: 18,
            BUCAL: 15,
            BUCIT: 22,
            BUTC: 8
        };
        
        let monthlyData = [65, 80, 45, 90, 75];
        let trendData = [42, 58, 72, 88, 94, 105];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        
        // ============================================
        // UPDATE STAT CARDS FUNCTION
        // ============================================
        function updateStatCards() {
            const total = Object.values(programCounts).reduce((a, b) => a + b, 0);
            const approved = Math.floor(total * 0.91);
            const pending = total - approved;
            
            const totalElem = document.getElementById('totalReg');
            const approvedElem = document.getElementById('approvedCount');
            const pendingElem = document.getElementById('pendingCount');
            
            if (totalElem) totalElem.innerText = total;
            if (approvedElem) approvedElem.innerText = approved;
            if (pendingElem) pendingElem.innerText = pending;
            
            // Update legend counts
            if (document.getElementById('bucsCount')) {
                document.getElementById('bucsCount').innerText = programCounts.BUCS;
                document.getElementById('bucnCount').innerText = programCounts.BUCN;
                document.getElementById('bucalCount').innerText = programCounts.BUCAL;
                document.getElementById('bucitCount').innerText = programCounts.BUCIT;
                document.getElementById('butcCount').innerText = programCounts.BUTC;
            }
        }
        
        // ============================================
        // PIE CHART (Program Statistics)
        // ============================================
        const pieCtx = document.getElementById('programPieChart');
        let pieChart = null;
        
        if (pieCtx) {
            pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['BUCS', 'BUCN', 'BUCAL', 'BUCIT', 'BUTC'],
                    datasets: [{
                        data: Object.values(programCounts),
                        backgroundColor: ['#f57c1f', '#82c8f8', '#e66a00', '#1b2e63', '#111e42'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.label}: ${ctx.raw} registrants`
                            }
                        }
                    }
                }
            });
        }
        
        // ============================================
        // BAR CHART (Monthly Applications)
        // ============================================
        const barCtx = document.getElementById('monthlyBarChart');
        let barChart = null;
        
        if (barCtx) {
            barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                    datasets: [{
                        label: 'Applications',
                        data: monthlyData,
                        backgroundColor: '#f57c1f',
                        borderRadius: 6,
                        barPercentage: 0.65,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.raw} applications`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e9ecef', drawBorder: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: '600', size: 12 } }
                        }
                    }
                }
            });
        }
        
        // ============================================
        // TREND CHART (Line Chart)
        // ============================================
        const trendCtx = document.getElementById('trendCanvas');
        let trendChart = null;
        
        if (trendCtx) {
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Registrations',
                        data: trendData,
                        borderColor: '#1b2e63',
                        backgroundColor: 'rgba(27, 46, 99, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#f57c1f',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.raw} users`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e9ecef' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: '600', size: 12 } }
                        }
                    }
                }
            });
        }
        
        // ============================================
        // UPDATE TREND CHART FUNCTION
        // ============================================
        function updateTrendChart() {
            if (!trendChart) return;
            const total = Object.values(programCounts).reduce((a, b) => a + b, 0);
            trendData.push(total);
            if (trendData.length > 6) trendData.shift();
            trendChart.data.datasets[0].data = [...trendData];
            trendChart.update('none');
        }
        
        // ============================================
        // UPDATE BAR CHART FUNCTION
        // ============================================
        function updateBarChart() {
            if (!barChart) return;
            barChart.data.datasets[0].data = [...monthlyData];
            barChart.update('none');
        }
        
        // ============================================
        // AUTO-UPDATE SIMULATION (Live Data Feel)
        // ============================================
        function randomUpdate() {
            const programs = Object.keys(programCounts);
            const randomProgram = programs[Math.floor(Math.random() * programs.length)];
            const increment = Math.random() > 0.6 ? 1 : 0;
            
            if (increment > 0) {
                programCounts[randomProgram] += 1;
            }
            
            let total = Object.values(programCounts).reduce((a, b) => a + b, 0);
            if (total > 210) {
                programCounts[randomProgram] = Math.max(5, programCounts[randomProgram] - 2);
            }
            
            if (pieChart) {
                pieChart.data.datasets[0].data = Object.values(programCounts);
                pieChart.update('none');
            }
            
            updateStatCards();
            updateTrendChart();
            
            for (let i = 0; i < monthlyData.length; i++) {
                let change = (Math.random() - 0.5) * 4;
                monthlyData[i] = Math.min(98, Math.max(12, monthlyData[i] + change));
            }
            updateBarChart();
        }
        
        // Run random updates every 12 seconds for live feel
        setInterval(randomUpdate, 12000);
        
        // Initial stat card update
        updateStatCards();
        
        // ============================================
        // NAVIGATION ITEMS (Demo toast notifications)
        // ============================================
        const navItems = document.querySelectorAll('.nav-item');
        
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerText = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        }
        
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class from all nav items
                navItems.forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');
                
                const menuText = item.querySelector('span')?.innerText || item.innerText;
                if (menuText !== 'Dashboard') {
                    showToast(`${menuText} section - demo preview`);
                }
            });
        });
        
        // ============================================
        // WINDOW RESIZE HANDLER (Maintain chart quality)
        // ============================================
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (pieChart) pieChart.resize();
                if (barChart) barChart.resize();
                if (trendChart) trendChart.resize();
            }, 150);
        });
        
        // Store chart instances globally for resize access
        window.pieChart = pieChart;
        window.barChart = barChart;
        window.trendChart = trendChart;
        
        console.log('Dashboard initialized with smooth sidebar animation');
    })();
</script>
</body>
</html>