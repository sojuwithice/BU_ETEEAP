<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Dashboard - BU-ETEEAP</title>
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link rel="stylesheet" href="{{ asset('css/reports.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
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

<div class="main-wrapper">
    <div class="sidebar">
        <a href="{{ route('staff.dashboard') }}" class="nav-item">
            <span class="material-symbols-outlined icon">home</span>
            Home
        </a>
        <a href="#" class="nav-item">
            <span class="material-symbols-outlined icon">assignment</span>
            Requirements List
        </a>
        <a href="#" class="nav-item active">
            <span class="material-symbols-outlined icon">bar_chart_4_bars</span>
            Reports
        </a>
    </div>

    <div class="main-bg">
        <div class="reports-container">
            <div class="page-header">
                <h1 class="page-title">Reports & Analytics</h1>
                <div class="header-actions">
                    <button class="export-btn" onclick="generateReport()">
                        <span class="material-symbols-outlined">file_download</span>
                        Generate Report
                    </button>
                    <div class="export-format-dropdown">
                        <button class="format-btn" id="formatBtn">
                            <span class="material-symbols-outlined">arrow_drop_down</span>
                            Export as: <span id="selectedFormat">PDF</span>
                        </button>
                        <div class="format-menu" id="formatMenu">
                            <div onclick="setFormat('PDF')">PDF Document</div>
                            <div onclick="setFormat('DOCX')">Word Document</div>
                            <div onclick="setFormat('CSV')">CSV (Excel)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <span class="material-symbols-outlined">people</span>
                    </div>
                    <div class="stat-info">
                        <h3>Total Applicants</h3>
                        <p class="stat-number" id="totalApplicants">0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                    <div class="stat-info">
                        <h3>Approved</h3>
                        <p class="stat-number" id="approvedCount">0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <span class="material-symbols-outlined">pending</span>
                    </div>
                    <div class="stat-info">
                        <h3>Pending Review</h3>
                        <p class="stat-number" id="pendingCount">0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div class="stat-info">
                        <h3>Payments</h3>
                        <p class="stat-number" id="paymentCount">0</p>
                    </div>
                </div>
            </div>

            <!-- FIRST ROW OF CHARTS (Status & Monthly) -->
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Application Status Distribution</h3>
                    </div>
                    <canvas id="statusChart" width="400" height="300"></canvas>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Monthly Applications</h3>
                    </div>
                    <canvas id="monthlyChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- SECOND ROW OF CHARTS (Program Distribution & Submission Mode) -->
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Total Applicants per Program</h3>
                    </div>
                    <canvas id="programChart" width="400" height="300"></canvas>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Submission Mode Distribution</h3>
                    </div>
                    <canvas id="submissionChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- PROGRAM SUBMISSION TABLE -->
            <div class="program-table-section">
                <h3 class="section-title">Number of Applicants per Program by Mode of Submission</h3>
                <div class="table-wrapper">
                    <table class="program-submission-table" id="programTable">
                        <thead>
                            <tr>
                                <th>Degree Program</th>
                                <th>Online Submission</th>
                                <th>Onsite Submission</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="programTableBody">
                            <tr><td colspan="4" style="text-align:center;">Loading data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            
        </div>
    </div>
</div>















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
let croppieInstance = null;
let confirmCallback = null;

let statusChart = null;
let monthlyChart = null;
let programChart = null;
let submissionChart = null;

let allData = [];
let currentData = [];
let currentPage = 1;
let rowsPerPage = 10;
let totalPages = 1;
let selectedFormat = 'PDF';

document.addEventListener('DOMContentLoaded', () => {
    initClock();
    initProfileDropdown();
    initConfirmModal();
    initCharts();
    loadReports();
});

// ================= TOAST =================
function showToast(message, type = 'success') {
    const toast = document.getElementById("toast");
    if (!toast) return;

    document.getElementById("toast-message").innerText = message;
    document.getElementById("toast-icon").innerText =
        type === 'success' ? 'check_circle' : 'error';

    toast.className = `toast show ${type}`;
    setTimeout(() => toast.classList.remove("show"), 3000);
}

// ================= CLOCK =================
function initClock() {
    updateClock();
    setInterval(updateClock, 1000);
}

function updateClock() {
    const now = new Date();
    const hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');

    const timeEl = document.getElementById('cur-time');
    const periodEl = document.getElementById('cur-period');
    const monthEl = document.getElementById('cur-month');
    const dayEl = document.getElementById('cur-day');
    const icon = document.getElementById('time-icon');

    if (!timeEl) return;

    const months = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];

    if (icon) {
        if (hours < 12) {
            icon.innerText = 'light_mode';
            icon.className = 'material-symbols-outlined icon-morning';
        } else if (hours < 18) {
            icon.innerText = 'wb_sunny';
            icon.className = 'material-symbols-outlined icon-afternoon';
        } else {
            icon.innerText = 'dark_mode';
            icon.className = 'material-symbols-outlined icon-night';
        }
    }

    timeEl.innerText = `${hours % 12 || 12}:${minutes}`;
    if (periodEl) periodEl.innerText = hours >= 12 ? 'PM' : 'AM';
    if (monthEl) monthEl.innerText = months[now.getMonth()];
    if (dayEl) dayEl.innerText = now.getDate();
}

// ================= PROFILE DROPDOWN =================
function initProfileDropdown() {
    const wrapper = document.getElementById("profileWrapper");
    const dropdown = document.getElementById("profileDropdown");

    if (!wrapper || !dropdown) return;

    wrapper.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!wrapper.contains(e.target)) {
            dropdown.classList.remove("show");
        }
    });
}

// ================= CHARTS =================
function initCharts() {
    const statusCanvas = document.getElementById('statusChart');
    const monthlyCanvas = document.getElementById('monthlyChart');
    const programCanvas = document.getElementById('programChart');
    const submissionCanvas = document.getElementById('submissionChart');

    if (statusCanvas) {
        if (statusChart) statusChart.destroy();
        statusChart = new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected', 'In Review'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#25c14a', '#EF7631', '#e03d4d', '#223381'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    if (monthlyCanvas) {
        if (monthlyChart) monthlyChart.destroy();
        monthlyChart = new Chart(monthlyCanvas, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Applications',
                    data: Array(12).fill(0),
                    backgroundColor: '#223381',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // Program Pie Chart
    if (programCanvas) {
        if (programChart) programChart.destroy();
        programChart = new Chart(programCanvas, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ['#223381', '#EF7631', '#25c14a', '#9b59b6', '#3498db', '#e74c3c', '#f39c12', '#1abc9c', '#2ecc71', '#e67e22', '#95a5a6', '#34495e'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Submission Mode Chart
    if (submissionCanvas) {
        if (submissionChart) submissionChart.destroy();
        submissionChart = new Chart(submissionCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Online Submission', 'Onsite Submission'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['#223381', '#EF7631'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

function updateCharts(chartData = {}) {
    console.log('updateCharts called with:', chartData);
    
    // Update Status Chart
    if (statusChart && chartData.statusDistribution) {
        const approved = Number(chartData.statusDistribution.approved || 0);
        const pending = Number(chartData.statusDistribution.pending || 0);
        const rejected = Number(chartData.statusDistribution.rejected || 0);
        const inReview = Number(chartData.statusDistribution.in_review || 0);
        
        console.log('Status Chart Data:', { approved, pending, rejected, inReview });
        
        statusChart.data.datasets[0].data = [approved, pending, rejected, inReview];
        statusChart.update();
    } else {
        console.log('statusChart not available or no statusDistribution data');
    }

    // Update Monthly Chart
    if (monthlyChart && chartData.monthlyApplications) {
        monthlyChart.data.datasets[0].data = [
            Number(chartData.monthlyApplications.jan || 0),
            Number(chartData.monthlyApplications.feb || 0),
            Number(chartData.monthlyApplications.mar || 0),
            Number(chartData.monthlyApplications.apr || 0),
            Number(chartData.monthlyApplications.may || 0),
            Number(chartData.monthlyApplications.jun || 0),
            Number(chartData.monthlyApplications.jul || 0),
            Number(chartData.monthlyApplications.aug || 0),
            Number(chartData.monthlyApplications.sep || 0),
            Number(chartData.monthlyApplications.oct || 0),
            Number(chartData.monthlyApplications.nov || 0),
            Number(chartData.monthlyApplications.dec || 0)
        ];
        monthlyChart.update();
    }

    // Update Program Chart
    if (programChart && chartData.programs) {
        const programs = chartData.programs;
        programChart.data.labels = Object.keys(programs);
        programChart.data.datasets[0].data = Object.values(programs);
        programChart.update();
    }

    // Update Submission Mode Chart
    if (submissionChart && chartData.submissionModes) {
        submissionChart.data.datasets[0].data = [
            Number(chartData.submissionModes.online || 0),
            Number(chartData.submissionModes.onsite || 0)
        ];
        submissionChart.update();
    }
}

// ================= REPORTS DATA =================
async function loadReports() {
    try {
        const dateRange = document.getElementById('dateRange')?.value || 'all';
        const status = document.getElementById('statusFilter')?.value || 'all';
        const sortBy = document.getElementById('sortBy')?.value || 'date_desc';

        const params = new URLSearchParams({
            date_range: dateRange,
            status: status,
            sort_by: sortBy
        });

        console.log('Fetching reports with params:', params.toString());

        const res = await fetch(`/staff/reports/data?${params.toString()}`, {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }

        const data = await res.json();
        console.log('Fetched report data:', data);

        if (!data.success) {
            showToast('Failed to load reports data', 'error');
            return;
        }

        allData = Array.isArray(data.applicants) ? data.applicants : [];
        currentData = [...allData];
        currentPage = 1;
        totalPages = Math.max(1, Math.ceil(currentData.length / rowsPerPage));

        updateStats(data.stats || {});
        updateCharts(data.chartData || {});
        updateProgramTable(data.programData || {});
        renderTable();
        updatePagination();

        showToast('Reports loaded successfully', 'success');

    } catch (err) {
        console.error('Load reports error:', err);
        showToast('Error loading reports data: ' + err.message, 'error');
        
        const tbody = document.getElementById('reportsTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color: red;">Error loading data. Please check console.</td></tr>';
        }
    }
}

// ================= PROGRAM TABLE =================
function updateProgramTable(programData = {}) {
    const tbody = document.getElementById('programTableBody');
    if (!tbody) return;

    const programs = programData.programs || {};
    const submissionModesByProgram = programData.submissionModesByProgram || {};

    if (Object.keys(programs).length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No data found.</td></tr>';
        return;
    }

    tbody.innerHTML = Object.keys(programs).map(program => {
        const online = submissionModesByProgram[program]?.online || 0;
        const onsite = submissionModesByProgram[program]?.onsite || 0;
        const total = programs[program] || 0;
        return `
            <tr>
                <td>${escapeHtml(program)}</td>
                <td class="text-center">${online}</td>
                <td class="text-center">${onsite}</td>
                <td class="text-center"><strong>${total}</strong></td>
            </tr>
        `;
    }).join('');
}

// ================= STATS =================
function updateStats(stats = {}) {
    console.log('Updating Stats:', stats);
    
    const totalEl = document.getElementById('totalApplicants');
    const approvedEl = document.getElementById('approvedCount');
    const pendingEl = document.getElementById('pendingCount');
    const paymentEl = document.getElementById('paymentCount');
    
    if (totalEl) totalEl.innerText = stats.total || 0;
    if (approvedEl) approvedEl.innerText = stats.approved || 0;
    if (pendingEl) pendingEl.innerText = stats.pending || 0;
    if (paymentEl) paymentEl.innerText = stats.paid || 0;
}

// ================= TABLE =================
function renderTable() {
    const tbody = document.getElementById('reportsTableBody');
    if (!tbody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const rows = currentData.slice(start, start + rowsPerPage);

    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No data found.</td></tr>';
        return;
    }

    tbody.innerHTML = rows.map(app => `
        <tr>
            <td title="${escapeHtml(app.name || '')}">${escapeHtml(app.name || 'N/A')}</td>
            <td title="${escapeHtml(app.degree_program || '')}">${escapeHtml(app.degree_program || 'N/A')}</td>
            <td>${formatStatusBadge(app.application_status || 'N/A')}</td>
            <td>${formatPaymentBadge(app.payment_status || 'N/A')}</td>
            <td class="text-center">${Number(app.documents_count || 0)}</td>
            <td>${formatDate(app.created_at)}</td>
        </tr>
    `).join('');
}

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    if (isNaN(date.getTime())) return 'N/A';
    return date.toLocaleDateString();
}

function formatStatusBadge(status) {
    const normalized = String(status).toLowerCase();
    let cls = 'status-default';
    let displayText = status;

    if (normalized.includes('approved')) {
        cls = 'status-approved';
        displayText = 'Approved';
    } else if (normalized.includes('pending')) {
        cls = 'status-pending';
        displayText = 'Pending';
    } else if (normalized.includes('reject')) {
        cls = 'status-rejected';
        displayText = 'Rejected';
    } else if (normalized.includes('review')) {
        cls = 'status-review';
        displayText = 'In Review';
    }

    return `<span class="status-badge ${cls}">${escapeHtml(displayText)}</span>`;
}

function formatPaymentBadge(status) {
    const normalized = String(status).toLowerCase();
    let cls = 'status-default';
    let displayText = status;

    if (normalized.includes('paid') || normalized.includes('complete')) {
        cls = 'status-approved';
        displayText = 'Paid';
    } else if (normalized.includes('pending')) {
        cls = 'status-pending';
        displayText = 'Pending';
    } else if (normalized.includes('unpaid')) {
        cls = 'status-rejected';
        displayText = 'Unpaid';
    }

    return `<span class="status-badge ${cls}">${escapeHtml(displayText)}</span>`;
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// ================= PAGINATION =================
function updatePagination() {
    const currentPageEl = document.getElementById('currentPage');
    const totalPagesEl = document.getElementById('totalPages');
    const pagination = document.getElementById('pagination');

    if (currentPageEl) currentPageEl.innerText = currentPage;
    if (totalPagesEl) totalPagesEl.innerText = totalPages;

    const buttons = pagination?.querySelectorAll('.page-btn');
    if (buttons && buttons.length >= 2) {
        buttons[0].disabled = currentPage <= 1;
        buttons[1].disabled = currentPage >= totalPages;
    }
}

function changePage(dir) {
    const next = currentPage + dir;
    if (next < 1 || next > totalPages) return;

    currentPage = next;
    renderTable();
    updatePagination();
}

// ================= FILTER =================
function filterReports() {
    loadReports();
}

// ================= EXPORT =================
function setFormat(format) {
    selectedFormat = format;
    const selected = document.getElementById('selectedFormat');
    const menu = document.getElementById('formatMenu');

    if (selected) selected.innerText = format;
    if (menu) menu.classList.remove('show');
}

function generateReport() {
    window.location.href = `/staff/reports/export?format=${encodeURIComponent(selectedFormat)}`;
}

// ================= CONFIRM MODAL =================
function initConfirmModal() {
    const btn = document.getElementById("confirmBtn");
    if (!btn) return;

    btn.addEventListener("click", async () => {
        if (typeof confirmCallback === "function") {
            await confirmCallback();
        }

        const modal = document.getElementById("confirmModal");
        if (modal) modal.classList.remove("show");

        confirmCallback = null;
    });
}

// ================= PASSWORD / MODAL =================
function togglePassword(id, iconId) {
    const input = document.getElementById(id);
    const icon = document.getElementById(iconId);

    if (!input || !icon) return;

    input.type = input.type === "password" ? "text" : "password";
    icon.innerText = input.type === "password" ? "visibility" : "visibility_off";
}

function openAccountModal() {
    const modal = document.getElementById("accountModal");
    if (modal) modal.classList.add("show");
}

function closeAccountModal() {
    const modal = document.getElementById("accountModal");
    if (modal) modal.classList.remove("show");
}

// Add CSS for text-center
const style = document.createElement('style');
style.textContent = `
    .text-center { text-align: center; }
`;
document.head.appendChild(style);


// ================= EXPORT DROPDOWN =================
// Toggle dropdown on click
document.addEventListener('DOMContentLoaded', function() {
    const formatBtn = document.getElementById('formatBtn');
    const formatMenu = document.getElementById('formatMenu');
    
    if (formatBtn && formatMenu) {
        // Toggle dropdown on button click
        formatBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            formatMenu.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!formatBtn.contains(e.target) && !formatMenu.contains(e.target)) {
                formatMenu.classList.remove('show');
            }
        });
    }
});

function setFormat(format) {
    selectedFormat = format;
    const selected = document.getElementById('selectedFormat');
    const menu = document.getElementById('formatMenu');
    
    if (selected) selected.innerText = format;
    if (menu) menu.classList.remove('show');
    
    // Optional: Show toast notification
    showToast(`Export format changed to ${format}`, 'success');
}

function generateReport() {
    // Get current filters
    const dateRange = document.getElementById('dateRange')?.value || 'all';
    const status = document.getElementById('statusFilter')?.value || 'all';
    const sortBy = document.getElementById('sortBy')?.value || 'date_desc';
    
    // Build URL with all parameters
    const url = `/staff/reports/export?format=${encodeURIComponent(selectedFormat)}&date_range=${dateRange}&status=${status}&sort_by=${sortBy}`;
    
    // For CSV, DOCX, PDF - download directly
    window.location.href = url;
}
</script>