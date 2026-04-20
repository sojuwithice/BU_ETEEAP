<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Applicant Information - BU-ETEEAP</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
    <link rel="stylesheet" href="{{ asset('css/info.css') }}">
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

    <div class="info-container">
        <div class="profile-header-section">
            <div class="profile-circle">
                <img src="{{ $applicant->profile_image ? asset('storage/' . $applicant->profile_image) : asset('images/default-profile.png') }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="profile-details">
                <h1>{{ $applicant->first_name }} {{ $applicant->last_name }}</h1>
                <div class="info-grid-wrapper">
                    <div class="info-grid main-info">
                        <div class="info-item"><span>First Name:</span> <div class="info-box">{{ $applicant->first_name }}</div></div>
                        <div class="info-item"><span>Middle Name:</span> <div class="info-box">{{ $applicant->middle_name ?? 'N/A' }}</div></div>
                        <div class="info-item"><span>Last Name:</span> <div class="info-box">{{ $applicant->last_name }}</div></div>
                        <div class="info-item"><span>Extension:</span> <div class="info-box">{{ $applicant->extension_name ?? 'N/A' }}</div></div>
                        <div class="info-item"><span>Birthdate:</span> <div class="info-box">{{ $applicant->birthdate ? date('F d, Y', strtotime($applicant->birthdate)) : 'Not specified' }}</div></div>
                        <div class="info-item sex-item"><span>Sex:</span> <div class="info-box">{{ ucfirst(strtolower($applicant->sex ?? 'N/A')) }}</div></div>
                        <div class="info-item"><span>Email Address:</span> <div class="info-box">{{ $applicant->email }}</div></div>
                        <div class="info-item"><span>Degree Program:</span> <div class="info-box">{{ $applicant->degree_program ?? 'Not specified' }}</div></div>
                    </div>
                    <div class="info-grid address-info">
                        <div class="info-item full-width"><span>Permanent Address:</span> <div class="info-box wide">{{ $applicant->permanent_address ?? 'Not specified' }}</div></div>
                        <div class="info-item full-width"><span>Current Address:</span> <div class="info-box wide">{{ $applicant->current_address ?? 'Not specified' }}</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-container">
            <a href="{{ route('staff.applicant.info', $applicant->id) }}" class="tab-btn orange-tab">
                Application
            </a>
            <a href="{{ route('staff.applicant.documents', $applicant->id) }}" class="tab-btn blue-tab">
                Document
            </a>
            <a href="{{ route('staff.dashboard') }}" class="tab-btn back-btn">
                <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span> Back to Dashboard
            </a>
        </div>

        <div class="status-message-grid">
            <div class="status-control">
                <h2>Status Control</h2>
                <div class="step-list">
                    
                    <div class="step-item active" onclick="toggleStep(this, event)">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-header">
                                <h3>Application</h3>
                                <span class="material-symbols-outlined arrow-icon">expand_less</span>
                            </div>
                            <div class="step-details">
                                <p>Application review and assessment.</p>
                                <div class="status-selector">
                                    <span>Status:</span>
                                    <div class="custom-dropdown">
                                        <div class="selected-option" onclick="toggleDropdown(this, event)">
                                            <span class="selected-value">{{ $applicant->application_status ?? 'Pending' }}</span>
                                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="option" onclick="updateApplicationStatus(this, 'Pending')">Pending</div>
                                            <div class="option" onclick="updateApplicationStatus(this, 'Approved')">Approved</div>
                                            <div class="option" onclick="updateApplicationStatus(this, 'Rejected')">Rejected</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-item" onclick="toggleStep(this, event)">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-header">
                                <h3>Documents</h3>
                                <span class="material-symbols-outlined arrow-icon">expand_more</span>
                            </div>
                            <div class="step-details">
                                <p>Documents uploaded: {{ $documentCount ?? 0 }} / {{ $requiredCount ?? 0 }}</p>
                                <div class="status-selector">
                                    <span>Status:</span>
                                    <div class="custom-dropdown">
                                        <div class="selected-option" onclick="toggleDropdown(this, event)">
                                            <span class="selected-value">{{ $applicant->document_status ?? 'Pending' }}</span>
                                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="option" onclick="updateDocumentStatus(this, 'Pending')">Pending</div>
                                            <div class="option" onclick="updateDocumentStatus(this, 'Verified')">Verified</div>
                                            <div class="option" onclick="updateDocumentStatus(this, 'Rejected')">Rejected</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-item" onclick="toggleStep(this, event)">
    <div class="step-number">3</div>
    <div class="step-content">
        <div class="step-header">
            <h3>Interview</h3>
            <span class="material-symbols-outlined arrow-icon">expand_more</span>
        </div>
        <div class="step-details">
            <div id="interviewInfo">
                @if($applicant->interview_date)
                    <p class="interview-scheduled">
                        <strong>Scheduled:</strong> {{ date('F d, Y', strtotime($applicant->interview_date)) }}<br>
                        <strong>Time:</strong> {{ $applicant->interview_time }}<br>
                        <strong>Setup:</strong> {{ $applicant->interview_setup }}<br>
                        <strong>Location:</strong> {{ $applicant->interview_location }}
                    </p>
                @else
                    <p>No interview scheduled yet.</p>
                @endif
            </div>
            
            <!-- Container for buttons and result dropdown -->
            <div id="interviewActionsContainer">
                @if($applicant->interview_date)
                    @php
                        $hasResult = in_array(strtolower($applicant->interview_result ?? ''), ['passed', 'failed']);
                    @endphp
                    
                    @if(!$hasResult)
                        <!-- Buttons: Cancel on LEFT, Mark as Done on RIGHT -->
                        <div class="action-buttons" id="actionButtons" style="display: flex; align-items: center; justify-content: space-between; margin-top: 15px;">
                            <button class="btn-cancel" onclick="cancelInterview(event)">Cancel Interview</button>
                            <button class="btn-done" id="btnDoneInterview" onclick="showResultDropdown()">Mark as Done</button>
                        </div>
                        <!-- Hidden Result Dropdown (will appear in place of Cancel button) -->
                        <div id="resultDropdownArea" style="display: none; margin-top: 15px;">
                            <div class="result-selector-wrapper" style="display: flex; align-items: center; gap: 10px;">
                                <div class="status-selector">
                                    <span>Result:</span>
                                    <div class="custom-dropdown">
                                        <div class="selected-option" onclick="toggleDropdown(this, event)">
                                            <span class="selected-value">Select Result</span>
                                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="option" onclick="saveInterviewResult(this, 'Passed')">Passed</div>
                                            <div class="option" onclick="saveInterviewResult(this, 'Failed')">Failed</div>
                                        </div>
                                    </div>
                                </div>
                                <div style="flex: 1;"></div>
                            </div>
                        </div>
                    @else
                        <!-- Already has result - show result dropdown on LEFT -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 15px;">
                            <div class="status-selector">
                                <span>Result:</span>
                                <div class="custom-dropdown">
                                    <div class="selected-option" onclick="toggleDropdown(this, event)">
                                        <span class="selected-value">{{ $applicant->interview_result ?? 'Pending' }}</span>
                                        <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                    </div>
                                    <div class="dropdown-menu">
                                        <div class="option" onclick="updateInterviewResult(this, 'Pending')">Pending</div>
                                        <div class="option" onclick="updateInterviewResult(this, 'Passed')">Passed</div>
                                        <div class="option" onclick="updateInterviewResult(this, 'Failed')">Failed</div>
                                    </div>
                                </div>
                            </div>
                            <div></div>
                        </div>
                    @endif
                @else
                    <div style="margin-top: 15px;">
                        <button class="btn-interview" onclick="handleInterview(event)">Set an Interview</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
            

                    <div class="step-item" onclick="toggleStep(this, event)">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <div class="step-header">
                                <h3>Payment</h3>
                                <span class="material-symbols-outlined arrow-icon">expand_more</span>
                            </div>
                            <div class="step-details">
                                <p>Payment status: {{ $applicant->payment_status ?? 'Pending' }}</p>
                                <div class="status-selector">
                                    <span>Status:</span>
                                    <div class="custom-dropdown">
                                        <div class="selected-option" onclick="toggleDropdown(this, event)">
                                            <span class="selected-value">{{ $applicant->payment_status ?? 'Pending' }}</span>
                                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="option" onclick="updatePaymentStatus(this, 'Pending')">Pending</div>
                                            <div class="option" onclick="updatePaymentStatus(this, 'Paid')">Paid</div>
                                            <div class="option" onclick="updatePaymentStatus(this, 'Partial')">Partial</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-item" onclick="toggleStep(this, event)">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <div class="step-header">
                                <h3>Final Review</h3>
                                <span class="material-symbols-outlined arrow-icon">expand_more</span>
                            </div>
                            <div class="step-details">
                                <p>Final assessment of candidacy.</p>
                                <div class="status-selector">
                                    <span>Status:</span>
                                    <div class="custom-dropdown">
                                        <div class="selected-option" onclick="toggleDropdown(this, event)">
                                            <span class="selected-value">{{ $applicant->final_status ?? 'Pending' }}</span>
                                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="option" onclick="updateFinalStatus(this, 'Pending')">Pending</div>
                                            <div class="option" onclick="updateFinalStatus(this, 'Approved')">Approved</div>
                                            <div class="option" onclick="updateFinalStatus(this, 'Rejected')">Rejected</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="message-section">
                <h2>Message</h2>
                <div class="message-box">
                    <textarea id="messageText" placeholder="Add your message here..."></textarea>
                    <div class="send-container">
                        <button class="btn-send" onclick="sendMessage()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="interviewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Schedule Interview</h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-left">
                    <div class="input-group">
                        <label>Setup</label>
                        <div class="custom-dropdown">
                            <div class="selected-option" onclick="toggleDropdown(this, event)">
                                <span class="setup-val">Select Setup</span> <span class="material-symbols-outlined">expand_more</span>
                            </div>
                            <div class="dropdown-menu">
                                <div class="option" onclick="selectSetup('Zoom Meeting')">Zoom Meeting</div>
                                <div class="option" onclick="selectSetup('Onsite')">Onsite</div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="locationGroup" class="input-group" style="display: none;">
                        <label id="locationLabel">Link/Location:</label>
                        <input type="text" id="locationInput" class="custom-input" placeholder="Enter link or address">
                    </div>

                    <div class="calendar-section">
                        <label>Date</label>
                        <div class="custom-calendar">
                            <div class="cal-header">
                                <span class="cal-arrow" onclick="changeMonth(-1)">&#10094;</span>
                                <span id="monthDisplay">April</span>
                                <span class="cal-arrow" onclick="changeMonth(1)">&#10095;</span>
                            </div>
                            <div class="cal-weekdays">
                                <span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span><span>SUN</span>
                            </div>
                            <div class="cal-days" id="calendarDays"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-divider"></div>

                <div class="modal-right">
                    <label>Time</label>
                    <div class="time-picker-container">
                        <div class="time-display-box">
                            <div class="time-input-group">
                                <input type="text" id="hourInput" value="09" maxlength="2" oninput="validateHour(this)">
                                <span>:</span>
                                <input type="text" id="minuteInput" value="00" maxlength="2" oninput="validateMinute(this)">
                            </div>
                            <div class="am-pm-toggle">
                                <span class="material-symbols-outlined" onclick="toggleAMPM()">expand_less</span>
                                <span id="ampmLabel">AM</span>
                                <span class="material-symbols-outlined" onclick="toggleAMPM()">expand_more</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn-set" onclick="saveInterviewSchedule()">Schedule Interview</button>
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
    // ======================== GLOBAL VARIABLES ========================
    let croppieInstance = null;
    let confirmCallback = null;
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedDay = new Date().getDate();
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const applicantId = {{ $applicant->id ?? 'null' }};

    // ======================== TOAST NOTIFICATION ========================
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const icon = document.getElementById('toast-icon');
        const msg = document.getElementById('toast-message');
        
        if (!toast) return;
        
        toast.classList.remove('success', 'error', 'show');
        toast.classList.add(type, 'show');
        msg.innerText = message;
        icon.innerText = type === 'success' ? 'check_circle' : 'error';
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 5000);
    }

    // ======================== CLOCK LOGIC ========================
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const iconElement = document.getElementById('time-icon');
        const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

        if (!iconElement) return;

        if (hours >= 5 && hours < 12) {
            iconElement.className = 'material-symbols-outlined icon-morning';
            iconElement.innerText = 'light_mode';
        } else if (hours >= 12 && hours < 18) {
            iconElement.className = 'material-symbols-outlined icon-afternoon';
            iconElement.innerText = 'wb_sunny';
        } else {
            iconElement.className = 'material-symbols-outlined icon-night';
            iconElement.innerText = 'dark_mode';
        }

        document.getElementById('cur-time').innerText = `${hours % 12 || 12}:${minutes}`;
        document.getElementById('cur-period').innerText = ampm;
        document.getElementById('cur-month').innerText = months[now.getMonth()];
        document.getElementById('cur-day').innerText = now.getDate();
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    // ======================== PROFILE DROPDOWN ========================
    const profileWrapper = document.getElementById("profileWrapper");
    const dropdown = document.getElementById("profileDropdown");
    
    if (profileWrapper) {
        profileWrapper.onclick = () => dropdown.classList.toggle("show");
        document.addEventListener("click", (e) => {
            if (!profileWrapper.contains(e.target)) dropdown.classList.remove("show");
        });
    }

    // ======================== MODAL CONTROLS ========================
    function openAccountModal() { 
        document.getElementById("accountModal")?.classList.add("show"); 
    }

    function closeAccountModal() {
        const modal = document.getElementById("accountModal");
        if (!modal) return;
        
        modal.classList.remove("show");
        setTimeout(() => {
            document.getElementById("changeBtn").style.display = "block";
            document.getElementById("changeSection").style.display = "none";
            cancelAdjustment();
            
            const passFields = ['currentPassword', 'newPassword', 'confirmPassword'];
            const iconFields = ['currentEyeIcon', 'newEyeIcon', 'confirmEyeIcon'];
            
            passFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.type = "password";
            });
            iconFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.innerText = "visibility_off";
            });
            
            ['currentPassword', 'newPassword', 'confirmPassword'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = "";
            });
        }, 300);
    }

    // ======================== IMAGE / CROPPIE LOGIC ========================
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file || !file.type.startsWith('image/')) {
            showToast("Invalid image file", "error");
            return;
        }

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
        if (croppieInstance) {
            croppieInstance.destroy();
            croppieInstance = null;
        }
        
        const uploadInput = document.getElementById('profileUpload');
        if (uploadInput) uploadInput.value = "";
        
        const defaultView = document.getElementById('defaultAvatarView');
        const actionBtns = document.getElementById('profileActionButtons');
        const adjustmentArea = document.getElementById('adjustmentArea');
        
        if (defaultView) defaultView.style.display = 'block';
        if (actionBtns) actionBtns.style.display = 'flex';
        if (adjustmentArea) adjustmentArea.style.display = 'none';
    }

    async function uploadCroppedImage() {
        if (!croppieInstance) return;
        
        const saveBtn = document.getElementById('savePhotoBtn');
        const originalContent = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerText = "Saving...";

        const base64 = await croppieInstance.result({ 
            type: 'base64', 
            size: 'viewport', 
            format: 'jpeg' 
        });
        
        try {
            const res = await fetch("/profile/upload-image", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
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
                if (dropAv) dropAv.src = data.path;
                
                cancelAdjustment();
            } else {
                showToast("Upload failed", "error");
            }
        } catch (e) {
            showToast("Upload failed", "error");
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalContent;
        }
    }

    // ======================== PASSWORD FUNCTIONS ========================
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
                body: JSON.stringify({
                    password: newPass,
                    password_confirmation: confirmPass
                })
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

    // ======================== CALENDAR FUNCTIONS ========================
    function renderCalendar() {
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const monthDisplay = document.getElementById('monthDisplay');
        const calendarDays = document.getElementById('calendarDays');
        
        if (!monthDisplay || !calendarDays) return;
        
        monthDisplay.textContent = monthNames[currentMonth];
        
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        calendarDays.innerHTML = '';
        let offset = (firstDay === 0) ? 6 : firstDay - 1;
        
        for (let i = 0; i < offset; i++) {
            calendarDays.innerHTML += `<div></div>`;
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const activeClass = (day === selectedDay) ? 'active-date' : '';
            calendarDays.innerHTML += `<div class="cal-day ${activeClass}" onclick="selectDate(${day})">${day}</div>`;
        }
    }

    function selectDate(day) {
        selectedDay = day;
        renderCalendar();
    }

    function changeMonth(dir) {
        currentMonth += dir;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    }

    // ======================== INTERVIEW FUNCTIONS ========================
function handleInterview(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    resetInterviewForm();
    
    const modal = document.getElementById('interviewModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function resetInterviewForm() {
    const setupVal = document.querySelector('.setup-val');
    if (setupVal) setupVal.textContent = 'Select Setup';
    
    const locationGroup = document.getElementById('locationGroup');
    if (locationGroup) locationGroup.style.display = 'none';
    
    const locationInput = document.getElementById('locationInput');
    if (locationInput) locationInput.value = '';
    
    const today = new Date();
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    selectedDay = today.getDate();
    renderCalendar();
    
    const hourInput = document.getElementById('hourInput');
    const minuteInput = document.getElementById('minuteInput');
    const ampmLabel = document.getElementById('ampmLabel');
    
    if (hourInput) hourInput.value = '09';
    if (minuteInput) minuteInput.value = '00';
    if (ampmLabel) ampmLabel.textContent = 'AM';
}

function closeModal() {
    const modal = document.getElementById('interviewModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function validateHour(input) {
    let val = input.value.replace(/\D/g, '');
    
    if (val.length > 2) {
        val = val.slice(0, 2);
    }
    
    let num = parseInt(val);
    if (!isNaN(num)) {
        if (num > 12) num = 12;
        if (num < 1 && val.length > 0) num = 1;
        val = String(num);
    }
    
    input.value = val;
}

function validateMinute(input) {
    let val = input.value.replace(/\D/g, '');
    
    if (val.length > 2) {
        val = val.slice(0, 2);
    }
    
    let num = parseInt(val);
    if (!isNaN(num)) {
        if (num > 59) num = 59;
        if (num < 0) num = 0;
        val = String(num);
    }
    
    input.value = val;
}

function toggleAMPM() {
    const ampmLabel = document.getElementById('ampmLabel');
    if (ampmLabel) {
        ampmLabel.textContent = (ampmLabel.textContent.trim() === 'AM') ? 'PM' : 'AM';
    }
}

function saveInterviewSchedule() {
    const setup = document.querySelector('.setup-val')?.textContent;
    const location = document.getElementById('locationInput')?.value || '';
    
    const year = currentYear;
    const month = String(currentMonth + 1).padStart(2, '0');
    const day = String(selectedDay).padStart(2, '0');
    const date = `${year}-${month}-${day}`;
    
    let hour = document.getElementById('hourInput')?.value || '09';
    let minute = document.getElementById('minuteInput')?.value || '00';
    const ampm = document.getElementById('ampmLabel')?.textContent || 'AM';
    
    if (hour.length === 1) hour = '0' + hour;
    if (hour === '00') hour = '12';
    
    if (minute.length === 1) minute = '0' + minute;
    
    let hourNum = parseInt(hour);
    if (isNaN(hourNum) || hourNum < 1) hourNum = 1;
    if (hourNum > 12) hourNum = 12;
    hour = String(hourNum).padStart(2, '0');
    
    let minuteNum = parseInt(minute);
    if (isNaN(minuteNum) || minuteNum < 0) minuteNum = 0;
    if (minuteNum > 59) minuteNum = 59;
    minute = String(minuteNum).padStart(2, '0');
    
    let hour24 = hourNum;
    if (ampm === 'PM' && hour24 !== 12) hour24 += 12;
    if (ampm === 'AM' && hour24 === 12) hour24 = 0;
    const time = `${String(hour24).padStart(2, '0')}:${minute}`;
    
    if (setup === 'Select Setup') {
        showToast("Please select an interview setup", "error");
        return;
    }
    
    // Format time for display (12-hour format)
    const displayTime = `${hour}:${minute} ${ampm}`;
    
    // Format date for display
    const displayDate = new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Close modal first
    closeModal();
    
    // Show confirmation SweetAlert
    Swal.fire({
        title: 'Schedule Interview',
        html: `Schedule interview on <strong>${displayDate}</strong> at <strong>${displayTime}</strong> via <strong>${setup}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#223381',
        confirmButtonText: 'Yes, Schedule',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Scheduling...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { 
                    Swal.showLoading(); 
                }
            });
            
            fetch(`/staff/applicant/${applicantId}/interview`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    setup: setup, 
                    location: location, 
                    date: date,
                    time: time 
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    // Update UI immediately with the new interview details
                    updateInterviewDisplay({
                        date: displayDate,
                        time: displayTime,
                        setup: setup,
                        location: location
                    });
                    showToast(data.message, "success");
                } else {
                    showToast(data.message || "Failed to set interview", "error");
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                showToast("Error setting interview", "error");
            });
        }
    });
}

function cancelInterview(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    Swal.fire({
        title: 'Cancel Interview',
        text: 'Are you sure you want to cancel this interview?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, Cancel',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Cancelling...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { 
                    Swal.showLoading(); 
                }
            });
            
            fetch(`/staff/applicant/${applicantId}/cancel-interview`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cancel: true })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    // Update UI immediately - remove interview details
                    updateInterviewDisplay(null);
                    showToast(data.message, "success");
                } else {
                    showToast(data.message || "Failed to cancel interview", "error");
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                showToast("Error cancelling interview", "error");
            });
        }
    });
}

// Helper function to update the UI with interview details
function updateInterviewDisplay(interviewData) {
    const interviewInfoDiv = document.getElementById('interviewInfo');
    const container = document.getElementById('interviewActionsContainer');
    
    if (interviewData) {
        // Has interview - show details
        interviewInfoDiv.innerHTML = `
            <p class="interview-scheduled">
                <strong>Scheduled:</strong> ${interviewData.date}<br>
                <strong>Time:</strong> ${interviewData.time}<br>
                <strong>Setup:</strong> ${interviewData.setup}<br>
                <strong>Location:</strong> ${interviewData.location}
            </p>
        `;
        
        // Show Cancel and Done buttons (Cancel on LEFT, Mark as Done on RIGHT)
        container.innerHTML = `
            <div class="action-buttons" id="actionButtons" style="display: flex; align-items: center; justify-content: space-between; margin-top: 15px;">
                <button class="btn-cancel" onclick="cancelInterview(event)">Cancel Interview</button>
                <button class="btn-done" id="btnDoneInterview" onclick="showResultDropdown()">Mark as Done</button>
            </div>
            <div id="resultDropdownArea" style="display: none; margin-top: 15px;">
                <div class="result-selector-wrapper" style="display: flex; align-items: center; gap: 10px;">
                    <div class="status-selector">
                        <span>Result:</span>
                        <div class="custom-dropdown">
                            <div class="selected-option" onclick="toggleDropdown(this, event)">
                                <span class="selected-value">Select Result</span>
                                <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                            </div>
                            <div class="dropdown-menu">
                                <div class="option" onclick="saveInterviewResult(this, 'Passed')">Passed</div>
                                <div class="option" onclick="saveInterviewResult(this, 'Failed')">Failed</div>
                                <div class="option" onclick="saveInterviewResult(this, 'Pending')">Pending</div>
                            </div>
                        </div>
                    </div>
                    <div style="flex: 1;"></div>
                </div>
            </div>
        `;
        
    } else {
        // No interview - show message and set button
        interviewInfoDiv.innerHTML = `<p>No interview scheduled yet.</p>`;
        
        container.innerHTML = `
            <div style="margin-top: 15px;">
                <button class="btn-interview" onclick="handleInterview(event)">Set an Interview</button>
            </div>
        `;
    }
}

    // ======================== STATUS UPDATE FUNCTIONS ========================
    function toggleDropdown(element, event) {
        if (event) event.stopPropagation();
        const dropdown = element.closest('.custom-dropdown');
        if (dropdown) dropdown.classList.toggle('open');
    }

    function updateApplicationStatus(element, value) {
        const dropdown = element.closest('.custom-dropdown');
        const display = dropdown.querySelector('.selected-value');
        if (display) display.textContent = value;
        dropdown.classList.remove('open');
        
        fetch(`/staff/applicant/${applicantId}/application-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, "success");
            } else {
                showToast(data.message || "Failed to update", "error");
            }
        })
        .catch(error => showToast("Error updating status", "error"));
    }

    function updateDocumentStatus(element, value) {
        const dropdown = element.closest('.custom-dropdown');
        const display = dropdown.querySelector('.selected-value');
        if (display) display.textContent = value;
        dropdown.classList.remove('open');
        
        fetch(`/staff/applicant/${applicantId}/document-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ document_status: value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, "success");
            } else {
                showToast(data.message || "Failed to update", "error");
            }
        })
        .catch(error => showToast("Error updating status", "error"));
    }

    function updatePaymentStatus(element, value) {
        const dropdown = element.closest('.custom-dropdown');
        const display = dropdown.querySelector('.selected-value');
        if (display) display.textContent = value;
        dropdown.classList.remove('open');
        
        fetch(`/staff/applicant/${applicantId}/payment-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ payment_status: value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, "success");
            } else {
                showToast(data.message || "Failed to update", "error");
            }
        })
        .catch(error => showToast("Error updating status", "error"));
    }

    function updateFinalStatus(element, value) {
        const dropdown = element.closest('.custom-dropdown');
        const display = dropdown.querySelector('.selected-value');
        if (display) display.textContent = value;
        dropdown.classList.remove('open');
        
        fetch(`/staff/applicant/${applicantId}/final-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ final_status: value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, "success");
            } else {
                showToast(data.message || "Failed to update", "error");
            }
        })
        .catch(error => showToast("Error updating status", "error"));
    }

    function selectSetup(val) {
        const setupDisplay = document.querySelector('.setup-val');
        if (setupDisplay) setupDisplay.textContent = val;

        const group = document.getElementById('locationGroup');
        const label = document.getElementById('locationLabel');
        if (group && label) {
            group.style.display = 'block';
            label.textContent = (val === 'Zoom Meeting') ? 'Zoom Link:' : 'Location/Address:';
        }

        const dropdown = document.querySelector('.setup-val')?.closest('.custom-dropdown');
        if (dropdown) dropdown.classList.remove('open');
    }

    function toggleStep(currentStep, event) {
        if (event.target.closest('.btn-interview') || event.target.closest('.custom-dropdown')) return;
        
        const isActive = currentStep.classList.contains('active');
        document.querySelectorAll('.step-item').forEach(step => {
            step.classList.remove('active');
            const icon = step.querySelector('.arrow-icon');
            if (icon) icon.textContent = 'expand_more';
        });
        
        if (!isActive) {
            currentStep.classList.add('active');
            const icon = currentStep.querySelector('.arrow-icon');
            if (icon) icon.textContent = 'expand_less';
        }
    }

    function sendMessage() {
        const message = document.getElementById('messageText')?.value;
        
        if (!message || !message.trim()) {
            showToast("Please enter a message", "error");
            return;
        }
        
        fetch(`/staff/applicant/${applicantId}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, "success");
                document.getElementById('messageText').value = '';
            } else {
                showToast(data.message || "Failed to send message", "error");
            }
        })
        .catch(error => showToast("Error sending message", "error"));
    }

    // ======================== WINDOW CLICK HANDLER ========================
    window.onclick = function(event) {
        const modal = document.getElementById('interviewModal');
        if (event.target === modal) closeModal();
        
        if (!event.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('open'));
        }
    };

    // Initialize calendar on page load
    document.addEventListener("DOMContentLoaded", () => {
        const calendarDays = document.getElementById('calendarDays');
        if (calendarDays) renderCalendar();
    });

    function updateInterviewResult(element, value) {
    const dropdown = element.closest('.custom-dropdown');
    const display = dropdown.querySelector('.selected-value');
    if (display) display.textContent = value;
    dropdown.classList.remove('open');
    
    fetch(`/staff/applicant/${applicantId}/interview-result`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ interview_result: value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, "success");
            // Refresh page or update UI as needed
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || "Failed to update interview result", "error");
        }
    })
    .catch(error => showToast("Error updating interview result", "error"));
}


// Show the result dropdown when Mark as Done is clicked
function showResultDropdown() {
    const actionButtons = document.getElementById('actionButtons');
    const resultDropdownArea = document.getElementById('resultDropdownArea');
    
    if (actionButtons && resultDropdownArea) {
        // Hide the buttons container
        actionButtons.style.display = 'none';
        
        // Show the result dropdown area
        resultDropdownArea.style.display = 'block';
    }
}

// Save the interview result (Passed/Failed)
function saveInterviewResult(element, value) {
    // Close the dropdown
    const dropdown = element.closest('.custom-dropdown');
    if (dropdown) dropdown.classList.remove('open');
    
    // Show loading
    Swal.fire({
        title: 'Saving Result...',
        text: 'Please wait',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/staff/applicant/${applicantId}/interview-result`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ interview_result: value })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.success) {
            showToast(data.message, "success");
            // Reload to show the updated UI
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || "Failed to update interview result", "error");
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        showToast("Error updating interview result", "error");
    });
}
    </script>
</body>
</html>