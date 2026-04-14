<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - BU-ETEEAP</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
    <link rel="stylesheet" href="{{ asset('css/info.css') }}">
</head>
<body>

    <div class="header">
        <div class="left-head">
            <img src="{{ asset('images/eteeap_logo.png') }}" alt="BU Logo">
            <h2>BU-ETEEAP</h2>
        </div>
        <div class="profile"></div>
    </div>

    <div class="info-container">
        <div class="profile-header-section">
            <div class="profile-circle"></div>
            <div class="profile-details">
                <h1>Juan Dela Cruz</h1>
                <div class="info-grid-wrapper">
                    <div class="info-grid main-info">
                        <div class="info-item"><span>First Name:</span> <div class="info-box"></div></div>
                        <div class="info-item"><span>Middle Name:</span> <div class="info-box"></div></div>
                        <div class="info-item"><span>Last Name:</span> <div class="info-box"></div></div>
                        <div class="info-item"><span>Extension:</span> <div class="info-box"></div></div>
                        <div class="info-item"><span>Birthdate:</span> <div class="info-box"></div></div>
                        <div class="info-item sex-item"><span>Sex:</span> <div class="info-box"></div></div>
                        <div class="info-item"><span>Email Address:</span> <div class="info-box"></div></div>
                        <div class="info-item"><span>Degree Program:</span> <div class="info-box"></div></div>
                    </div>
                    <div class="info-grid address-info">
                        <div class="info-item full-width"><span>Permanent Address:</span> <div class="info-box wide"></div></div>
                        <div class="info-item full-width"><span>Current Address:</span> <div class="info-box wide"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-container">
            <a href="{{ route('applicant.info') }}" class="tab-btn {{ Route::is('applicant.info') ? 'orange-tab' : 'blue-tab' }}">
                Application
            </a>
            <a href="{{ route('document.verification') }}" class="tab-btn {{ Route::is('document.verification') ? 'orange-tab' : 'blue-tab' }}">
                Document
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
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <div class="status-selector">
                                    <span>Status:</span>
                                    <div class="custom-dropdown">
                                        <div class="selected-option" onclick="toggleDropdown(this, event)">
                                            <span class="selected-value"></span>
                                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="option" onclick="selectOption(this, 'Pending')">Pending</div>
                                            <div class="option" onclick="selectOption(this, 'Approved')">Approved</div>
                                            <div class="option" onclick="selectOption(this, 'Rejected')">Rejected</div>
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
                                <p>Verification of submitted physical and digital documents.</p>
                                <div class="status-selector">
                                    <span>Status:</span>
                                    <div class="custom-dropdown">
                                        <div class="selected-option" onclick="toggleDropdown(this, event)">
                                            <span class="selected-value"></span>
                                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="option" onclick="selectOption(this, 'Pending')">Pending</div>
                                            <div class="option" onclick="selectOption(this, 'Approved')">Approved</div>
                                            <div class="option" onclick="selectOption(this, 'Rejected')">Rejected</div>
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
                                <p>Scheduled interview with the board.</p>
                                <div class="action-container">
                                    <button class="btn-interview" onclick="handleInterview(event)">Set an Interview</button>
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
                            <div class="step-details"><p>Settlement of processing fees.</p></div>
                        </div>
                    </div>

                    <div class="step-item" onclick="toggleStep(this, event)">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <div class="step-header">
                                <h3>Final Review</h3>
                                <span class="material-symbols-outlined arrow-icon">expand_more</span>
                            </div>
                            <div class="step-details"><p>Final assessment of candidacy.</p></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="message-section">
                <h2>Message</h2>
                <div class="message-box">
                    <textarea placeholder="Add your message here..."></textarea>
                    <div class="send-container">
                        <button class="btn-send">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="interviewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Interview Schedule</h2>
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
            <input type="text" class="custom-input" placeholder="Enter link or address">
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
                <div class="cal-days" id="calendarDays">
                    </div>
            </div>
        </div>
    </div>

    <div class="modal-divider"></div>

    <div class="modal-right">
        <label>Time</label>
        <div class="time-picker-container">
            <div class="time-display-box">
                <div class="time-input-group">
                    <input type="text" id="hourInput" value="09" maxlength="2" oninput="validateTime(this, 12)">
                    <span>:</span>
                    <input type="text" id="minuteInput" value="00" maxlength="2" oninput="validateTime(this, 59)">
                </div>
                <div class="am-pm-toggle">
                    <span class="material-symbols-outlined" onclick="toggleAMPM()">expand_less</span>
                    <span id="ampmLabel">AM</span>
                    <span class="material-symbols-outlined" onclick="toggleAMPM()">expand_more</span>
                </div>
            </div>
        </div>
        <button class="btn-set" onclick="closeModal()">Set</button>
    </div>
</div>
    </div>

    <script>
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedDay = new Date().getDate();
    let clockInterval; 

    document.addEventListener('DOMContentLoaded', () => {
        renderCalendar();
    });

    // --- REAL-TIME CLOCK LOGIC ---
    function updateClock() {
        const ngayon = new Date();
        let hours = ngayon.getHours();
        const minutes = ngayon.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        
        hours = hours % 12;
        hours = hours ? hours : 12; 

        const hrInput = document.getElementById('hourInput');
        const minInput = document.getElementById('minuteInput');
        const ampmLabel = document.getElementById('ampmLabel');

        if (document.activeElement !== hrInput && document.activeElement !== minInput) {
            hrInput.value = hours.toString().padStart(2, '0');
            minInput.value = minutes.toString().padStart(2, '0');
            ampmLabel.textContent = ampm;
        }
    }

    // --- ACCORDION LOGIC ---
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

    // --- MODAL LOGIC ---
    function handleInterview(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const modal = document.getElementById('interviewModal');
        if (modal) {
            modal.style.display = 'flex';
            updateClock(); 
            clockInterval = setInterval(updateClock, 1000); 
        }
    }

    function closeModal() {
        const modal = document.getElementById('interviewModal');
        if (modal) {
            modal.style.display = 'none';
            clearInterval(clockInterval); 
        }
    }

    // --- INPUT VALIDATION ---
    function validateInput(input, max) {
        let val = input.value.replace(/\D/g, ''); 
        if (val !== '') {
            if (parseInt(val) > max) val = max;
        }
        input.value = val.slice(0, 2);
    }

    // --- DROPDOWN LOGIC ---
    function toggleDropdown(element, event) {
        if (event) event.stopPropagation();
        const dropdown = element.closest('.custom-dropdown');
        dropdown.classList.toggle('open');
    }

    function selectOption(optionElement, value) {
        const dropdown = optionElement.closest('.custom-dropdown');
        const display = dropdown.querySelector('.selected-value');
        if (display) display.textContent = value;
        dropdown.classList.remove('open');
    }

    function selectSetup(val) {
    const setupDisplay = document.querySelector('.setup-val');
    if (setupDisplay) {
        setupDisplay.textContent = val;
    }

    const group = document.getElementById('locationGroup');
    const label = document.getElementById('locationLabel');
    if (group && label) {
        group.style.display = 'block';
        label.textContent = (val === 'Zoom Meeting') ? 'Zoom Link:' : 'Location/Address:';
    }

    const dropdown = document.querySelector('.setup-val').closest('.custom-dropdown');
    if (dropdown) {
        dropdown.classList.remove('open');
    }
}

    // --- CALENDAR LOGIC ---
    function renderCalendar() {
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        document.getElementById('monthDisplay').textContent = monthNames[currentMonth];
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const container = document.getElementById('calendarDays');
        container.innerHTML = '';
        let offset = (firstDay === 0) ? 6 : firstDay - 1;
        for (let i = 0; i < offset; i++) container.innerHTML += `<div></div>`;
        for (let day = 1; day <= daysInMonth; day++) {
            const activeClass = (day === selectedDay) ? 'active-date' : '';
            container.innerHTML += `<div class="cal-day ${activeClass}" onclick="selectDate(${day})">${day}</div>`;
        }
    }

    function selectDate(day) {
        selectedDay = day;
        renderCalendar();
    }

    function changeMonth(dir) {
        currentMonth += dir;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        else if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar();
    }

    function toggleAMPM() {
        const ampmLabel = document.getElementById('ampmLabel');
        ampmLabel.textContent = (ampmLabel.textContent.trim() === 'AM') ? 'PM' : 'AM';
        clearInterval(clockInterval); 
    }

    window.onclick = function(event) {
        const modal = document.getElementById('interviewModal');
        if (event.target === modal) closeModal();
        if (!event.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('open'));
        }
    };
</script>
</body>
</html>