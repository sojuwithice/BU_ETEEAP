<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Dashboard - BU-ETEEAP</title>
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link rel="stylesheet" href="{{ asset('css/staff_dash.css') }}">
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
            <button type="submit" class="dropdown-item">
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
            <a href="#" class="nav-item active">
                <span class="material-symbols-outlined icon">home</span>
                Home
            </a>
           <a href="{{ route('requirements.index') }}" class="nav-item">
                <span class="material-symbols-outlined icon">assignment</span>
                Requirements List
            </a>
            <a href="#" class="nav-item">
                <span class="material-symbols-outlined icon">bar_chart_4_bars</span>
                Reports
            </a>
        </div>

        <div class="main-bg">
            <h1 class="welcome-text">Welcome, Staff!</h1>

            <div class="cards-container">
                <div class="stat-card orange">
                    <h3>Pending Reviews</h3>
                    <p>10</p>
                </div>
                <div class="stat-card orange">
                    <h3>New Applications</h3>
                    <p>3</p>
                </div>
                <div class="stat-card orange">
                    <h3>Document Issues</h3>
                    <p>2</p>
                </div>
            </div>

            <div class="search-section-container">
                <div class="search-title">Applicant Name</div>
                <div class="search-action-bar">
                    <div class="search-box">
                        <span class="material-symbols-outlined search-icon">search</span>
                        <input type="text" placeholder="Search">
                    </div>
                    <div class="action-icons">
                        <span class="material-symbols-outlined filter-icon">filter_alt</span>
                        <div class="divider"></div>
                        <span class="material-symbols-outlined action-icon">download</span>
                        <span class="material-symbols-outlined action-icon">print</span>
                    </div>
                </div>
            </div>

            <div class="applicant-list-container">
                <div class="table-wrapper">
                    <table class="applicant-table">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Sex</th>
                                <th>Degree Program</th>
                                <th>Last Update</th>
                                <th>Current Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Juan Dela Cruz</b></td>
                                <td>M</td>
                                <td><b>BS in Computer Science</b></td>
                                <td>04/06/2026 | 10:00 am</td>
                                <td><span class="status-badge orange">On going</span></td>
                                <td class="action-buttons">
                                    <button class="btn-view">View</button>
                                    <button class="btn-decision">Set Decision <span class="material-symbols-outlined">expand_more</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Jane Mengorio</b></td>
                                <td>F</td>
                                <td><b>BS in Computer Science</b></td>
                                <td>04/06/2026 | 10:00 am</td>
                                <td><span class="status-badge orange">On going</span></td>
                                <td class="action-buttons">
                                    <button class="btn-view">View</button>
                                    <button class="btn-decision">Set Decision <span class="material-symbols-outlined">expand_more</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Duang Batumbakal</b></td>
                                <td>M</td>
                                <td><b>BS in Fisheries</b></td>
                                <td>04/06/2026 | 10:00 am</td>
                                <td><span class="status-badge orange">On going</span></td>
                                <td class="action-buttons">
                                    <button class="btn-view">View</button>
                                    <button class="btn-decision">Set Decision <span class="material-symbols-outlined">expand_more</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Qin Salazar</b></td>
                                <td>M</td>
                                <td><b>BS in Fisheries</b></td>
                                <td>04/06/2026 | 10:00 am</td>
                                <td><span class="status-badge orange">On going</span></td>
                                <td class="action-buttons">
                                    <button class="btn-view">View</button>
                                    <button class="btn-decision">Set Decision <span class="material-symbols-outlined">expand_more</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Sea Dechchart</b></td>
                                <td>M</td>
                                <td><b>BS in Computer Science</b></td>
                                <td>04/06/2026 | 10:00 am</td>
                                <td><span class="status-badge orange">On going</span></td>
                                <td class="action-buttons">
                                    <button class="btn-view">View</button>
                                    <button class="btn-decision">Set Decision <span class="material-symbols-outlined">expand_more</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Keen Suvijak</b></td>
                                <td>M</td>
                                <td><b>BS in Computer Science</b></td>
                                <td>04/06/2026 | 10:00 am</td>
                                <td><span class="status-badge orange">On going</span></td>
                                <td class="action-buttons">
                                    <button class="btn-view">View</button>
                                    <button class="btn-decision">Set Decision <span class="material-symbols-outlined">expand_more</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Icca Balin</b></td>
                                <td>F</td>
                                <td><b>BS in Computer Science</b></td>
                                <td>04/06/2026 | 10:00 am</td>
                                <td><span class="status-badge orange">On going</span></td>
                                <td class="action-buttons">
                                    <button class="btn-view">View</button>
                                    <button class="btn-decision">Set Decision <span class="material-symbols-outlined">expand_more</span></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div> 
        </div> 
    </div> 



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


<script>
    // ================= TOAST NOTIFICATION =================
    function showToast(message, type = 'success') {
        const toast = document.getElementById("toast");
        const icon = document.getElementById("toast-icon");
        const msg = document.getElementById("toast-message");

        // Set type (success/error)
        toast.className = `toast show ${type}`;
        msg.innerText = message;
        icon.innerText = type === 'success' ? 'check_circle' : 'error';

        // Auto-hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
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
            
            // Clear inputs
            document.getElementById("newPassword").value = "";
            document.getElementById("confirmPassword").value = "";
            
            // Reset icons and types
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

    // ================= AJAX UPDATE PASSWORD =================
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
                body: JSON.stringify({
                    password: newPass,
                    password_confirmation: confirmPass
                })
            });

            const data = await response.json();

            if (response.ok) {
                // Update live display
                document.getElementById("currentPassword").value = newPass;
                showToast("Password updated successfully!", "success");
                closeAccountModal();
            } else {
                // Laravel validation errors
                showToast(data.message || "Invalid password requirements", "error");
            }
        } catch (error) {
            showToast("Server connection failed", "error");
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerText = "Save";
        }
    }
</script>
</body>
</html>