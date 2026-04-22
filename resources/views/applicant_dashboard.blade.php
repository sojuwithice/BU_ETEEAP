<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/applicant_dash.css') }}">
 <link rel="icon" type="image/png" href="{{ asset('images/eteeap_logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
<style>
    /* Animation for task removal */
    .req-item.removing {
        animation: slideOut 0.3s ease forwards;
    }
    
    @keyframes slideOut {
        0% {
            opacity: 1;
            transform: translateX(0);
        }
        100% {
            opacity: 0;
            transform: translateX(100%);
            display: none;
        }
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
            <img 
                src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" 
                class="profile"
                id="profileImg"
            >
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

    <!-- WELCOME -->
    <div class="welcome">
        Welcome, {{ auth()->user()->first_name }}!
    </div>

    <!-- NAV -->
    <div class="nav">
        <a href="{{ url('/dashboard') }}" style="text-decoration: none;">
            <button type="button" class="active">Home</button>
        </a>
        <a href="{{ url('/profile') }}" style="text-decoration: none;">
            <button type="button">Profile</button>
        </a>
        <a href="{{ route('applicant.documents') }}" style="text-decoration: none;">
            <button type="button">Documents</button>
        </a>
    </div>

    <!-- CARD CONTAINER -->
    <div class="card-container">
        <div class="dashboard-container">
            <!-- LEFT -->
            <div class="left">
                <h2>Your Progress</h2>
                <div class="progress-card">
                    <p>Profile Progress</p>
                    <h1 id="profileProgress">{{ $profileProgress ?? 0 }}%</h1>
                    <div class="progress">
                        <span id="profileProgressBar" style="width: {{ $profileProgress ?? 0 }}%"></span>
                    </div>
                </div>
                <div class="progress-card">
                    <p>Documents Progress</p>
                    <h1 id="documentsProgress">{{ $documentsProgress ?? 0 }}%</h1>
                    <div class="progress">
                        <span id="documentsProgressBar" style="width: {{ $documentsProgress ?? 0 }}%"></span>
                    </div>
                </div>
                <div class="progress-card">
                    <p>Application Progress</p>
                    <h1 id="applicationProgress">{{ $applicationProgress ?? 0 }}%</h1>
                    <div class="progress">
                        <span id="applicationProgressBar" style="width: {{ $applicationProgress ?? 0 }}%"></span>
                    </div>
                </div>
            </div>

            <div class="center">
                <div class="center-content-wrapper">
                    <div class="center-column">
                        <h2>Incomplete Requirements</h2>
                        <div class="req-box">
                            <div class="task-header">
                                <div class="task-badge">
                                    Tasks <span class="badge-num" id="taskCount">{{ $tasks->count() }}</span>
                                </div>
                                <div class="message-badge" onclick="openMessagesModal()" style="cursor: pointer;">
                                    Messages <span class="badge-num" id="messageCount">{{ $unreadMessagesCount }}</span>
                                </div>
                            </div>
                            <h3 class="todo-title">To do</h3>
                            <div class="req-header">
                                <span>Task</span>
                                <span>Action</span>
                            </div>

                            <div class="req-list-container">
                                <div class="req-list" id="taskList">
                                    @forelse($tasks as $task)
                                    @php
                                        $isPaymentTask = in_array($task->type, ['payment_upload', 'payment']);
                                        $user = auth()->user();
                                        $hasUploadedProof = !empty($user->payment_proof);
                                        $isPendingVerification = strtolower($user->payment_status) == 'pending';
                                        $isPaid = $user->payment_status == 'paid';
                                    @endphp
                                    <div class="req-item {{ $isPaymentTask ? 'payment-task' : '' }}" data-task-id="{{ $task->id }}" data-task-type="{{ $task->type }}">
                                        <div class="task-info">
                                            <span class="task-title">{{ $task->title }}</span>
                                            @if($task->description)
                                                <small class="task-desc">{{ $task->description }}</small>
                                            @endif
                                        </div>

                                        <div class="task-actions">
                                            @if($task->type == 'payment_upload' || $task->type == 'payment')
                                                <a href="{{ route('applicant.download-payment-stub', auth()->id()) }}"
                                                   target="_blank"
                                                   class="task-view-btn">
                                                    View Stub
                                                </a>
                                                
                                                @if($isPaid)
                                                    <button disabled class="task-upload-btn task-verified">
                                                        Payment Verified
                                                    </button>
                                                @elseif($hasUploadedProof && $isPendingVerification)
                                                    <button disabled class="task-upload-btn">
                                                        Waiting for Verification
                                                    </button>
                                                @else
                                                    <button onclick="openUploadModal('{{ $task->id }}')" class="task-upload-btn">
                                                        @if($hasUploadedProof && !$isPendingVerification)
                                                            Re-upload Proof
                                                        @else
                                                            Upload Proof
                                                        @endif
                                                    </button>
                                                @endif
                                            @else
                                                <a href="{{ $task->action_url }}" class="task-view-btn" onclick="markTaskAndRedirect('{{ $task->id }}', this.href); return false;">View</a>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="req-item">
                                        <span>All tasks completed! Great job!</span>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="center-column">
                        <h2 class="rem-title-outside">Reminders</h2>
                        <div class="reminders-card">
                            <h3 class="sched-title">Today's Schedule</h3>
                            <h2 class="calendar-month" id="calendarMonth" style="text-align: left; margin-left: 0; font-size: 1.1rem;">{{ date('F Y') }}</h2>
                            <div class="calendar-grid" id="calendarGrid">
                                <!-- Dynamic calendar will be populated by JavaScript -->
                            </div>
                            <div id="scheduleDisplay">
                                <!-- Dynamic schedule will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="right">
                <h2>Application Status</h2>
                <div class="status-container">
                    <div class="status-row">
                        <div class="timeline-col">
                            <div class="dot {{ strtolower($user->application_status ?? 'pending') == 'approved' ? 'active' : '' }}">1</div>
                            <div class="line"></div>
                        </div>
                        <div class="status {{ strtolower($user->application_status ?? 'pending') == 'approved' ? 'active' : '' }}">
                            <p>Application</p>
                            <small class="status-text">
                                @php
                                    $appStatus = strtolower($user->application_status ?? 'pending');
                                @endphp
                                @if($appStatus == 'approved')
                                    <span class="text-approved">Approved</span>
                                @elseif($appStatus == 'rejected')
                                    <span class="text-rejected">Rejected</span>
                                @else
                                    <span class="text-pending">{{ ucfirst($user->application_status ?? 'Pending') }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="timeline-col">
                            <div class="dot {{ strtolower($user->document_status ?? 'pending') == 'verified' || strtolower($user->document_status ?? 'pending') == 'approved' ? 'active' : '' }}">2</div>
                            <div class="line"></div>
                        </div>
                        <div class="status {{ strtolower($user->document_status ?? 'pending') == 'verified' || strtolower($user->document_status ?? 'pending') == 'approved' ? 'active' : '' }}">
                            <p>Documents</p>
                            <small class="status-text">
                                @php
                                    $docStatus = strtolower($user->document_status ?? 'pending');
                                @endphp
                                @if($docStatus == 'verified' || $docStatus == 'approved')
                                    <span class="text-approved">Verified</span>
                                @elseif($docStatus == 'rejected')
                                    <span class="text-rejected">Rejected</span>
                                @elseif($docStatus == 'incomplete')
                                    <span class="text-incomplete">Incomplete</span>
                                @else
                                    <span class="text-pending">{{ ucfirst($user->document_status ?? 'Pending') }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="timeline-col">
                            <div class="dot {{ in_array(strtolower($user->interview_result ?? 'pending'), ['passed', 'failed']) ? 'active' : '' }}">3</div>
                            <div class="line"></div>
                        </div>
                        <div class="status {{ in_array(strtolower($user->interview_result ?? 'pending'), ['passed', 'failed']) ? 'active' : '' }}">
                            <p>Interview</p>
                            <small class="status-text">
                                @php
                                    $interviewResult = strtolower($user->interview_result ?? 'pending');
                                @endphp
                                @if($interviewResult == 'passed')
                                    <span class="text-approved">Passed</span>
                                @elseif($interviewResult == 'failed')
                                    <span class="text-rejected">Failed</span>
                                @elseif($interviewResult == 'scheduled')
                                    <span class="text-scheduled">Scheduled</span>
                                @else
                                    <span class="text-pending">Pending</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="timeline-col">
                            <div class="dot {{ strtolower($user->payment_status ?? 'pending') == 'paid' || strtolower($user->payment_status ?? 'pending') == 'completed' ? 'active' : '' }}">4</div>
                            <div class="line"></div>
                        </div>
                        <div class="status {{ strtolower($user->payment_status ?? 'pending') == 'paid' || strtolower($user->payment_status ?? 'pending') == 'completed' ? 'active' : '' }}">
                            <p>Payment</p>
                            <small class="status-text">
                                @php
                                    $paymentStatus = strtolower($user->payment_status ?? 'pending');
                                @endphp
                                @if($paymentStatus == 'paid' || $paymentStatus == 'completed')
                                    <span class="text-approved">Paid</span>
                                @elseif($paymentStatus == 'partial')
                                    <span class="text-partial">Partial</span>
                                @else
                                    <span class="text-pending">{{ ucfirst($user->payment_status ?? 'Pending') }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="timeline-col">
                            <div class="dot {{ strtolower($user->final_status ?? 'pending') == 'approved' || strtolower($user->final_status ?? 'pending') == 'completed' ? 'active' : '' }}">5</div>
                        </div>
                        <div class="status {{ strtolower($user->final_status ?? 'pending') == 'approved' || strtolower($user->final_status ?? 'pending') == 'completed' ? 'active' : '' }}">
                            <p>Final Review</p>
                            <small class="status-text">
                                @php
                                    $finalStatus = strtolower($user->final_status ?? 'pending');
                                @endphp
                                @if($finalStatus == 'approved')
                                    <span class="text-approved">Approved</span>
                                @elseif($finalStatus == 'rejected')
                                    <span class="text-rejected">Rejected</span>
                                @else
                                    <span class="text-pending">{{ ucfirst($user->final_status ?? 'Pending') }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <!-- ACTIVITIES -->
                <div class="activities">
                    <h3>Recent Activities</h3>
                    <div class="activity-list" id="activityList">
                        <div class="activity">Loading activities...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="account-modal" id="accountModal">
    <div class="account-box">
        <span class="close-modal" onclick="closeAccountModal()">&times;</span>
        <h2>Manage Account</h2>
        <div class="input-group">
            <label>Email</label>
            <input type="text" value="{{ auth()->user()->email }}" disabled>
        </div>
        <div class="input-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" value="{{ session('raw_password') ?? auth()->user()->password_plain ?? '' }}" id="currentPassword" readonly>
                <span class="toggle-eye" onclick="togglePassword('currentPassword', 'currentEyeIcon')">
                    <span class="material-symbols-outlined" id="currentEyeIcon">visibility</span>
                </span>
            </div>
        </div>
        <button class="change-btn" id="changeBtn" onclick="showChangeSection()">Change Password</button>
        <div id="changeSection" style="display:none;">
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

<div id="messagesModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Messages from Staff</h2>
            <div class="modal-header-actions">
                <button id="selectModeBtn" class="header-btn">
                    <span class="material-symbols-outlined">select_check_box</span> Select
                </button>
                <button id="selectAllBtn" class="header-btn" style="display: none;">
                    <span class="material-symbols-outlined">select_all</span> Select All
                </button>
                <button id="deleteSelectedBtn" class="header-btn delete-btn" style="display: none;">
                    <span class="material-symbols-outlined">delete</span> Delete
                </button>
                <button id="cancelSelectBtn" class="header-btn cancel-select-btn" style="display: none;">
                    <span class="material-symbols-outlined">close</span> Cancel
                </button>
                <span class="close-btn" onclick="closeMessagesModal()">&times;</span>
            </div>
        </div>
        <div class="messages-toolbar" id="messagesToolbar" style="display: none;">
            <div class="selection-info">
                <span id="selectedCount">0</span> message(s) selected
            </div>
            <button id="deleteSelectedToolbarBtn" class="delete-selected-btn">
                <span class="material-symbols-outlined">delete</span> Delete Selected
            </button>
        </div>
        <div class="messages-list" id="messagesList">
            <div style="text-align: center; padding: 20px;">Loading messages...</div>
        </div>
    </div>
</div>

<!-- Upload Payment Proof Modal -->
<div id="uploadModal" class="modal-upload">
    <div class="modal-upload-content">
        <div class="modal-upload-header">
            <h2>Upload Payment Proof</h2>
            <span class="close-upload-modal" onclick="closeUploadModal()">&times;</span>
        </div>
        <div class="modal-upload-body">
            <div class="upload-area" id="uploadArea">
                <div class="upload-icon">
                    <span class="material-symbols-outlined" style="font-size: 48px;">cloud_upload</span>
                </div>
                <div class="upload-text">
                    Click or drag file here to upload
                </div>
                <div class="upload-text" style="font-size: 12px; margin-top: 8px;">
                    Supports: JPG, PNG, PDF (Max 5MB)
                </div>
                <input type="file" id="paymentProof" accept=".jpg,.jpeg,.png,.pdf" style="display: none;">
            </div>
            
            <div class="preview-area" id="previewArea" style="display: none;">
                <div class="preview-header">
                    <div class="preview-file-info">
                        <span class="material-symbols-outlined" style="font-size: 24px;">description</span>
                        <div>
                            <div class="preview-file-name" id="previewFileName"></div>
                            <div class="preview-file-size" id="previewFileSize"></div>
                        </div>
                    </div>
                    <button type="button" class="preview-change-btn" onclick="resetUploadArea()">
                        <span class="material-symbols-outlined" style="font-size: 18px;">change_circle</span> Change
                    </button>
                </div>
                <div class="preview-content" id="previewContent">
                </div>
            </div>
        </div>
        <div class="modal-upload-footer">
            <button class="btn-cancel" onclick="closeUploadModal()">Cancel</button>
            <button class="btn-upload" id="uploadBtn" onclick="uploadPaymentProof()">Upload</button>
        </div>
    </div>
</div>

<script>
    // ================= SCHEDULES FROM BACKEND =================
    const schedules = @json($schedules ?? []);
    
    // ================= REAL-TIME CALENDAR FUNCTIONS =================
    function getToday() {
        const now = new Date();
        return {
            year: now.getFullYear(),
            month: now.getMonth(),
            date: now.getDate(),
            day: now.getDay(),
            fullDate: now
        };
    }
    
    function formatDate(year, month, date) {
        return `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
    }
    
    function getDaysInMonth(year, month) {
        return new Date(year, month + 1, 0).getDate();
    }
    
    function getFirstDayOfMonth(year, month) {
        return new Date(year, month, 1).getDay();
    }
    
    function generateCalendar() {
        const today = getToday();
        const year = today.year;
        const month = today.month;
        const currentDate = today.date;
        
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        document.getElementById('calendarMonth').innerText = `${monthNames[month]} ${year}`;
        
        const firstDayOfMonth = getFirstDayOfMonth(year, month);
        const daysInMonth = getDaysInMonth(year, month);
        
        const todayObj = new Date(year, month, currentDate);
        const currentDayOfWeek = todayObj.getDay();
        const sundayDate = currentDate - currentDayOfWeek;
        
        let calendarHTML = '';
        const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        
        for (let i = 0; i < 7; i++) {
            let dateValue = sundayDate + i;
            let displayDate = dateValue;
            
            if (dateValue < 1) {
                const prevMonth = month - 1;
                const prevMonthYear = prevMonth < 0 ? year - 1 : year;
                const prevMonthIndex = prevMonth < 0 ? 11 : prevMonth;
                const daysInPrevMonth = getDaysInMonth(prevMonthYear, prevMonthIndex);
                displayDate = daysInPrevMonth + dateValue;
            } else if (dateValue > daysInMonth) {
                displayDate = dateValue - daysInMonth;
            }
            
            const isToday = (dateValue === currentDate);
            const activeClass = isToday ? 'active-cal' : '';
            
            calendarHTML += `
                <div class="cal-item ${activeClass}">
                    <span>${dayNames[i]}</span>
                    <strong>${displayDate}</strong>
                </div>
            `;
        }
        
        document.getElementById('calendarGrid').innerHTML = calendarHTML;
    }
    
    function getUpcomingSchedules() {
        const today = getToday();
        const todayStr = formatDate(today.year, today.month, today.date);
        const futureSchedules = schedules.filter(schedule => schedule.date > todayStr);
        return futureSchedules.sort((a, b) => a.date.localeCompare(b.date));
    }
    
    function hasTodaySchedule() {
        const today = getToday();
        const todayStr = formatDate(today.year, today.month, today.date);
        return schedules.some(schedule => schedule.date === todayStr);
    }
    
    function getTodaySchedule() {
        const today = getToday();
        const todayStr = formatDate(today.year, today.month, today.date);
        return schedules.find(schedule => schedule.date === todayStr);
    }
    
    function formatDisplayDate(dateStr) {
        const date = new Date(dateStr);
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
    }
    
    function updateScheduleDisplay() {
        const scheduleDisplay = document.getElementById('scheduleDisplay');
        
        if (hasTodaySchedule()) {
            const todaySchedule = getTodaySchedule();
            if (todaySchedule) {
                const isZoomLink = todaySchedule.location && 
                    (todaySchedule.location.toLowerCase().includes('zoom') || 
                     todaySchedule.location.toLowerCase().includes('zoom.us') ||
                     todaySchedule.location.startsWith('http'));
                
                const locationIcon = isZoomLink ? 'videocam' : 'location_on';
                let locationDisplay;
                
                if (isZoomLink && (todaySchedule.location.startsWith('http') || todaySchedule.location.includes('zoom.us'))) {
                    locationDisplay = `<a href="${todaySchedule.location}" target="_blank" style="color: #0066cc; text-decoration: none; display: inline-flex; align-items: flex-start; gap: 8px; width: 100%;">
                                            <span class="material-symbols-outlined" style="flex-shrink: 0;">${locationIcon}</span> 
                                            <span style="text-decoration: underline; word-wrap: break-word; overflow-wrap: break-word; word-break: break-all; white-space: normal; flex: 1;">${todaySchedule.location}</span>
                                        </a>`;
                } else {
                    locationDisplay = `<span style="display: inline-flex; align-items: flex-start; gap: 8px; width: 100%;">
                                           <span class="material-symbols-outlined" style="flex-shrink: 0;">${locationIcon}</span> 
                                           <span style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-all; white-space: normal; flex: 1;">${todaySchedule.location}</span>
                                       </span>`;
                }
                
                scheduleDisplay.innerHTML = `
                    <div class="interview-box">
                        <div class="blue-line"></div>
                        <div class="interview-info">
                            <h4>${todaySchedule.title}</h4>
                            <p style="margin: 5px 0; display: flex; align-items: flex-start; gap: 8px;">${locationDisplay}</p>
                            <p style="margin: 5px 0; display: flex; align-items: center; gap: 8px;">
                                <span class="material-symbols-outlined" style="flex-shrink: 0;">calendar_month</span> 
                                <span>${formatDisplayDate(todaySchedule.date)} | ${todaySchedule.time}</span>
                            </p>
                        </div>
                    </div>
                `;
            }
        } else {
            const upcoming = getUpcomingSchedules();
            if (upcoming.length > 0) {
                let upcomingHTML = '';
                upcoming.forEach((schedule, index) => {
                    const isZoomLink = schedule.location && 
                        (schedule.location.toLowerCase().includes('zoom') || 
                         schedule.location.toLowerCase().includes('zoom.us') ||
                         schedule.location.startsWith('http'));
                    
                    const locationIcon = isZoomLink ? 'videocam' : 'location_on';
                    let locationDisplay;
                    
                    if (isZoomLink && (schedule.location.startsWith('http') || schedule.location.includes('zoom.us'))) {
                        locationDisplay = `<a href="${schedule.location}" target="_blank" style="color: #0066cc; text-decoration: none; display: inline-flex; align-items: flex-start; gap: 8px; flex-wrap: wrap;">
                                                <span class="material-symbols-outlined" style="flex-shrink: 0;">${locationIcon}</span> 
                                                <span style="flex: 1; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; text-decoration: underline;">${schedule.location}</span>
                                            </a>`;
                    } else {
                        locationDisplay = `<span style="display: inline-flex; align-items: flex-start; gap: 8px; flex-wrap: wrap;">
                                               <span class="material-symbols-outlined" style="flex-shrink: 0;">${locationIcon}</span> 
                                               <span style="flex: 1; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">${schedule.location}</span>
                                           </span>`;
                    }
                    
                    upcomingHTML += `
                        <div class="interview-box" style="margin-bottom: ${index < upcoming.length - 1 ? '15px' : '0'};">
                            <div class="blue-line"></div>
                            <div class="interview-info" style="flex: 1; min-width: 0; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">
                                <h4 style="margin: 0 0 8px 0;">UPCOMING: ${schedule.title}</h4>
                                <p style="margin: 5px 0;">${locationDisplay}</p>
                                <p style="margin: 5px 0; display: flex; align-items: center; gap: 8px;">
                                    <span class="material-symbols-outlined" style="flex-shrink: 0;">calendar_month</span> 
                                    <span>${formatDisplayDate(schedule.date)} | ${schedule.time}</span>
                                </p>
                            </div>
                        </div>
                    `;
                });
                scheduleDisplay.innerHTML = upcomingHTML;
            } else {
                scheduleDisplay.innerHTML = `
                    <div class="interview-box" style="background: #fff3e0; border-width: 1px; border-style: solid; border-color: #EF7631;">
                        <div class="interview-info">
                            <h4 style="color: #EF7631;">No Scheduled Events</h4>
                            <p>No interviews or events scheduled at this time.</p>
                            <p style="margin-top: 5px; font-size: 0.7rem;">Check back later for updates!</p>
                        </div>
                    </div>
                `;
            }
        }
    }

    // ================= TOAST NOTIFICATION =================
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
        
        generateCalendar();
        updateScheduleDisplay();
    }

    setInterval(updateClock, 1000);
    updateClock();

    // ================= PROFILE DROPDOWN =================
    const profileWrapper = document.getElementById("profileWrapper");
    const dropdown = document.getElementById("profileDropdown");

    profileWrapper.addEventListener("contextmenu", (e) => {
        e.preventDefault();
        dropdown.classList.toggle("show");
    });

    profileWrapper.addEventListener("click", () => {
        dropdown.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!profileWrapper.contains(e.target)) {
            dropdown.classList.remove("show");
        }
    });

    // ================= ACCOUNT MODAL LOGIC =================
    function openAccountModal() {
        document.getElementById("accountModal").classList.add("show");
    }

    function showChangeSection() {
        document.getElementById("changeBtn").style.display = "none";
        document.getElementById("changeSection").style.display = "flex";
    }

    function closeAccountModal() {
        const modal = document.getElementById("accountModal");
        modal.classList.remove("show");
        setTimeout(() => {
            document.getElementById("changeBtn").style.display = "block";
            document.getElementById("changeSection").style.display = "none";
            document.getElementById("newPassword").value = "";
            document.getElementById("confirmPassword").value = "";
            ['newPassword', 'confirmPassword', 'currentPassword'].forEach(id => {
                document.getElementById(id).type = "password";
            });
            document.getElementById("newEyeIcon").innerText = "visibility";
            document.getElementById("confirmEyeIcon").innerText = "visibility";
            document.getElementById("currentEyeIcon").innerText = "visibility";
        }, 300);
    }

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        input.type = (input.type === "password") ? "text" : "password";
        icon.innerText = (input.type === "password") ? "visibility" : "visibility_off";
    }

    async function updatePassword() {
        const newPass = document.getElementById("newPassword").value;
        const confirmPass = document.getElementById("confirmPassword").value;
        const saveBtn = document.querySelector('.save-btn');

        if (!newPass || !confirmPass) {
            showToast("Please fill all fields", "error");
            return;
        }
        if (newPass !== confirmPass) {
            showToast("Passwords do not match", "error");
            return;
        }

        saveBtn.disabled = true;
        saveBtn.innerText = "Saving...";

        try {
            const response = await fetch("/update-password", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ password: newPass, password_confirmation: confirmPass })
            });
            const data = await response.json();
            if (response.ok) {
                document.getElementById("currentPassword").value = newPass;
                showToast("Password updated successfully!", "success");
                closeAccountModal();
            } else {
                showToast(data.message || "Invalid password requirements", "error");
            }
        } catch (error) {
            showToast("Server connection failed", "error");
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerText = "Save";
        }
    }

    // ================= TASK AUTO-REMOVAL FUNCTION =================
    async function markTaskAndRedirect(taskId, redirectUrl) {
        try {
            // Mark task as completed
            const response = await fetch('/applicant/tasks/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ task_id: taskId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Remove task from UI with animation
                const taskElement = document.querySelector(`.req-item[data-task-id="${taskId}"]`);
                if (taskElement) {
                    taskElement.classList.add('removing');
                    setTimeout(() => {
                        taskElement.remove();
                        updateTaskCount();
                        // Redirect after animation
                        window.location.href = redirectUrl;
                    }, 300);
                } else {
                    window.location.href = redirectUrl;
                }
            } else {
                // If marking fails, just redirect
                window.location.href = redirectUrl;
            }
        } catch (error) {
            console.error('Error:', error);
            window.location.href = redirectUrl;
        }
    }
    
    function updateTaskCount() {
        const remainingTasks = document.querySelectorAll('.req-item').length;
        const taskCountSpan = document.getElementById('taskCount');
        if (taskCountSpan) {
            taskCountSpan.textContent = remainingTasks;
        }
        
        // Show message if no tasks left
        if (remainingTasks === 0) {
            const taskList = document.getElementById('taskList');
            if (taskList && taskList.querySelectorAll('.req-item').length === 0) {
                taskList.innerHTML = '<div class="req-item"><span>All tasks completed! Great job! 🎉</span></div>';
            }
        }
    }

    // ================= MESSAGES FUNCTIONS =================
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    let isSelectionMode = false;
    let selectedMessageIds = new Set();

    function formatMessageTime(dateString) {
        if (!dateString) return 'Unknown date';
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) return 'Just now';
        else if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
        else if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
        else if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
        else return date.toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true });
    }

    function loadMessages() {
        fetch('/applicant/messages', {
            method: 'GET',
            headers: { 
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': csrfToken, 
                'Content-Type': 'application/json' 
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messageCountSpan = document.getElementById('messageCount');
                if (messageCountSpan) {
                    const unreadCount = data.unread_count || 0;
                    messageCountSpan.innerText = unreadCount;
                    
                    if (unreadCount > 0) {
                        messageCountSpan.classList.add('has-unread');
                    } else {
                        messageCountSpan.classList.remove('has-unread');
                    }
                }
                
                window.messagesList = data.messages;
                
                const modal = document.getElementById('messagesModal');
                if (modal && modal.style.display === 'flex') {
                    displayMessagesInModal();
                }
            }
        })
        .catch(error => console.error('Error loading messages:', error));
    }

    function displayMessagesInModal() {
        const messagesList = document.getElementById('messagesList');
        if (!messagesList) return;
        
        if (window.messagesList && window.messagesList.length > 0) {
            messagesList.innerHTML = window.messagesList.map(msg => `
                <div class="message-item ${!msg.is_read ? 'unread' : ''} ${selectedMessageIds.has(msg.id) ? 'selected' : ''}" 
                     data-message-id="${msg.id}" 
                     onclick="toggleMessageSelection(${msg.id}, event)">
                    <div class="message-checkbox" style="display: ${isSelectionMode ? 'flex' : 'none'}">
                        <input type="checkbox" 
                            class="message-checkbox"
                            value="${msg.id}"
                            ${selectedMessageIds.has(msg.id) ? 'checked' : ''}
                            onclick="event.stopPropagation(); toggleMessageCheckbox(${msg.id})">
                    </div>
                    <div class="message-content">
                        <div class="message-sender">From: ${escapeHtml(msg.sender_name)}</div>
                        <div class="message-text">${escapeHtml(msg.message)}</div>
                        <div class="message-time">${formatMessageTime(msg.created_at)}</div>
                        ${!msg.is_read ? '<span class="unread-badge">New</span>' : ''}
                    </div>
                </div>
            `).join('');
        } else {
            messagesList.innerHTML = '<div style="text-align: center; padding: 20px;">No messages yet</div>';
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function openMessagesModal() {
        const modal = document.getElementById('messagesModal');
        const messagesList = document.getElementById('messagesList');
        const messageCountSpan = document.getElementById('messageCount');

        if (messagesList) messagesList.innerHTML = '<div style="text-align: center; padding: 20px;">Loading messages...</div>';
        
        isSelectionMode = false;
        selectedMessageIds.clear();
        
        fetch('/applicant/messages', {
            method: 'GET',
            headers: { 
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.messagesList = data.messages;
                displayMessagesInModal();
                modal.style.display = 'flex';
                
                if (data.unread_count > 0) {
                    markMessagesAsRead();
                }
                
                if (messageCountSpan) {
                    messageCountSpan.innerText = '0';
                    messageCountSpan.classList.remove('has-unread');
                }
                
                const elementsToHide = ['selectAllBtn', 'deleteSelectedBtn', 'cancelSelectBtn', 'messagesToolbar'];
                elementsToHide.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                });
                
            } else {
                if (messagesList) messagesList.innerHTML = '<div style="text-align: center; padding: 20px;">Error loading messages</div>';
                modal.style.display = 'flex';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (messagesList) messagesList.innerHTML = '<div style="text-align: center; padding: 20px;">Error loading messages</div>';
            modal.style.display = 'flex';
        });
    }

    function markMessagesAsRead() {
        fetch('/applicant/messages/mark-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Messages marked as read:', data.updated_count, 'message(s)');
                if (window.messagesList) {
                    window.messagesList = window.messagesList.map(msg => ({
                        ...msg,
                        is_read: true
                    }));
                    displayMessagesInModal();
                }
            }
        })
        .catch(error => console.error('Error marking messages as read:', error));
    }

    function closeMessagesModal() {
        const modal = document.getElementById('messagesModal');
        if (modal) modal.style.display = 'none';
        isSelectionMode = false;
        selectedMessageIds.clear();
    }

    window.onclick = function(event) {
        const modal = document.getElementById('messagesModal');
        if (event.target === modal) closeMessagesModal();
    }

    function toggleSelectionMode() {
        isSelectionMode = !isSelectionMode;
        
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const cancelSelectBtn = document.getElementById('cancelSelectBtn');
        const messagesToolbar = document.getElementById('messagesToolbar');
        
        if (isSelectionMode) {
            if (selectAllBtn) selectAllBtn.style.display = 'inline-flex';
            if (deleteSelectedBtn) deleteSelectedBtn.style.display = 'inline-flex';
            if (cancelSelectBtn) cancelSelectBtn.style.display = 'inline-flex';
            if (messagesToolbar) messagesToolbar.style.display = 'flex';
        } else {
            if (selectAllBtn) selectAllBtn.style.display = 'none';
            if (deleteSelectedBtn) deleteSelectedBtn.style.display = 'none';
            if (cancelSelectBtn) cancelSelectBtn.style.display = 'none';
            if (messagesToolbar) messagesToolbar.style.display = 'none';
            selectedMessageIds.clear();
        }
        
        displayMessagesInModal();
    }

    function toggleMessageSelection(messageId, event) {
        if (event.target.type === 'checkbox') return;
        
        if (!isSelectionMode) {
            toggleSelectionMode();
        }
        
        if (selectedMessageIds.has(messageId)) {
            selectedMessageIds.delete(messageId);
        } else {
            selectedMessageIds.add(messageId);
        }
        
        updateSelectionUI();
        displayMessagesInModal();
    }

    function toggleMessageCheckbox(messageId) {
        if (selectedMessageIds.has(messageId)) {
            selectedMessageIds.delete(messageId);
        } else {
            selectedMessageIds.add(messageId);
        }
        updateSelectionUI();
        displayMessagesInModal();
    }

    function toggleSelectAll() {
        if (!window.messagesList) return;
        
        if (selectedMessageIds.size === window.messagesList.length) {
            selectedMessageIds.clear();
        } else {
            window.messagesList.forEach(msg => {
                selectedMessageIds.add(msg.id);
            });
        }
        updateSelectionUI();
        displayMessagesInModal();
    }

    function cancelSelection() {
        isSelectionMode = false;
        selectedMessageIds.clear();
        updateSelectionUI();
        displayMessagesInModal();
        
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const cancelSelectBtn = document.getElementById('cancelSelectBtn');
        const messagesToolbar = document.getElementById('messagesToolbar');
        
        if (selectAllBtn) selectAllBtn.style.display = 'none';
        if (deleteSelectedBtn) deleteSelectedBtn.style.display = 'none';
        if (cancelSelectBtn) cancelSelectBtn.style.display = 'none';
        if (messagesToolbar) messagesToolbar.style.display = 'none';
    }

    function updateSelectionUI() {
        const selectedCountSpan = document.getElementById('selectedCount');
        const selectAllBtn = document.getElementById('selectAllBtn');
        
        if (selectedCountSpan) {
            selectedCountSpan.innerText = selectedMessageIds.size;
        }
        
        if (selectAllBtn && window.messagesList) {
            if (selectedMessageIds.size === window.messagesList.length && window.messagesList.length > 0) {
                selectAllBtn.innerHTML = '<span class="material-symbols-outlined">deselect</span> Deselect All';
            } else {
                selectAllBtn.innerHTML = '<span class="material-symbols-outlined">select_all</span> Select All';
            }
        }
    }

    function deleteSelectedMessages() {
        if (selectedMessageIds.size === 0) {
            showToast('Please select messages to delete', 'error');
            return;
        }

        fetch('/applicant/messages/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message_ids: Array.from(selectedMessageIds)
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Messages deleted successfully', 'success');
                
                selectedMessageIds.forEach(id => {
                    const el = document.querySelector(`[data-message-id="${id}"]`);
                    if (el) el.remove();
                });
                
                if (window.messagesList) {
                    window.messagesList = window.messagesList.filter(msg => !selectedMessageIds.has(msg.id));
                }
                
                selectedMessageIds.clear();
                updateSelectionUI();
                loadMessages();
            } else {
                showToast(data.message || 'Failed to delete messages', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Something went wrong', 'error');
        });
    }

    // ================= FILE UPLOAD HANDLING =================
    let selectedFile = null;
    let currentTaskId = null;

    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('paymentProof');

    if (uploadArea) {
        uploadArea.addEventListener('click', () => fileInput.click());
        uploadArea.addEventListener('dragover', (e) => e.preventDefault());
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            if (e.dataTransfer.files.length > 0) handleFileSelect(e.dataTransfer.files[0]);
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) handleFileSelect(e.target.files[0]);
        });
    }

    function handleFileSelect(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!validTypes.includes(file.type)) {
            showToast('Invalid file type. Please upload JPG, PNG, or PDF files only.', 'error');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            showToast('File is too large. Maximum size is 5MB.', 'error');
            return;
        }
        
        selectedFile = file;
        document.getElementById('uploadArea').style.display = 'none';
        document.getElementById('previewArea').style.display = 'block';
        document.getElementById('previewFileName').textContent = file.name;
        document.getElementById('previewFileSize').textContent = formatFileSize(file.size);
        
        const previewContent = document.getElementById('previewContent');
        const fileExt = file.name.split('.').pop().toLowerCase();
        
        if (fileExt === 'pdf') {
            previewContent.innerHTML = `<iframe src="${URL.createObjectURL(file)}" class="preview-pdf" style="width: 100%; height: 250px; border: none; border-radius: 8px;"></iframe>`;
        } else if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContent.innerHTML = `<img src="${e.target.result}" class="preview-image" style="max-width: 100%; max-height: 250px; border-radius: 8px; object-fit: contain;">`;
            };
            reader.readAsDataURL(file);
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function resetUploadArea() {
        selectedFile = null;
        document.getElementById('paymentProof').value = '';
        document.getElementById('uploadArea').style.display = 'block';
        document.getElementById('previewArea').style.display = 'none';
        document.getElementById('uploadBtn').disabled = false;
        document.getElementById('previewContent').innerHTML = '';
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').style.display = 'none';
        resetUploadArea();
        currentTaskId = null;
    }

    function openUploadModal(taskId) {
        currentTaskId = taskId;
        document.getElementById('uploadModal').style.display = 'flex';
        resetUploadArea();
    }

    async function uploadPaymentProof() {
        if (!selectedFile) {
            showToast('Please select a file to upload', 'error');
            return;
        }
        
        const uploadBtn = document.getElementById('uploadBtn');
        const originalText = uploadBtn.textContent;
        uploadBtn.disabled = true;
        uploadBtn.textContent = 'Uploading...';
        
        const formData = new FormData();
        formData.append('payment_proof', selectedFile);
        
        try {
            const response = await fetch('/applicant/upload-payment-proof', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(data.message, 'success');
                closeUploadModal();
                setTimeout(() => location.reload(), 2000);
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            showToast('Failed to upload payment proof', 'error');
        } finally {
            uploadBtn.disabled = false;
            uploadBtn.textContent = originalText;
        }
    }

    async function fetchProgressData() {
        try {
            const response = await fetch('/applicant/progress', {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('profileProgress').textContent = data.profile_progress + '%';
                document.getElementById('profileProgressBar').style.width = data.profile_progress + '%';
                document.getElementById('documentsProgress').textContent = data.documents_progress + '%';
                document.getElementById('documentsProgressBar').style.width = data.documents_progress + '%';
                document.getElementById('applicationProgress').textContent = data.application_progress + '%';
                document.getElementById('applicationProgressBar').style.width = data.application_progress + '%';
            }
        } catch (error) {
            console.error('Error fetching progress:', error);
        }
    }

    async function loadActivities() {
        try {
            const response = await fetch('/applicant/activities', {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await response.json();
            if (data.success && data.activities.length > 0) {
                const activityList = document.getElementById('activityList');
                activityList.innerHTML = data.activities.map(activity => `
                    <div class="activity">
                        <div class="activity-date">${activity.date}</div>
                        <div class="activity-text">${activity.activity}</div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading activities:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadMessages();
        fetchProgressData();
        loadActivities();
        
        setInterval(loadMessages, 10000);
        setInterval(loadActivities, 30000);
        
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                loadMessages();
                loadActivities();
            }
        });

        const deleteBtn = document.getElementById('deleteSelectedBtn');
        const deleteToolbarBtn = document.getElementById('deleteSelectedToolbarBtn');
        const selectModeBtn = document.getElementById('selectModeBtn');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const cancelSelectBtn = document.getElementById('cancelSelectBtn');

        if (deleteBtn) {
            deleteBtn.addEventListener('click', deleteSelectedMessages);
        }

        if (deleteToolbarBtn) {
            deleteToolbarBtn.addEventListener('click', deleteSelectedMessages);
        }

        if (selectModeBtn) {
            selectModeBtn.addEventListener('click', toggleSelectionMode);
        }

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', toggleSelectAll);
        }

        if (cancelSelectBtn) {
            cancelSelectBtn.addEventListener('click', cancelSelection);
        }
    });
</script>

</body>
</html>