<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/documents.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
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


<!-- MAIN BG -->
<div class="main-bg">

    <!-- WELCOME -->
    <div class="welcome">
        Welcome, {{ auth()->user()->first_name }}!
    </div>

    <!-- NAV -->
    <div class="nav">
        <a href="{{ url('/dashboard') }}" style="text-decoration: none;">
            <button type="button">Home</button>
        </a>
        <a href="{{ url('/profile') }}" style="text-decoration: none;">
            <button type="button">Profile</button>
        </a>
        <a href="{{ route('applicant.documents') }}" style="text-decoration: none;">
        <button type="button" class="active">Documents</button>
    </a>
    </div>

    <!-- CARD CONTAINER -->
    <div class="card-container">

    <div class="documents-container">
  <div class="docs-list-section">

    <div class="docs-header">
      <h2 class="section-title">Documents List</h2>

      <button class="onsite-btn">
        Already Submitted Onsite
      </button>
</div>
        <ul class="docs-items">
            <li class="doc-item completed">
                <div class="check-icon">✔</div>
                <span class="doc-name">Birth Certificate</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">High School Card/Form 13A/PEPT or ALS Certificate (1st year college placement)/Most recent TOR</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">NBI/Barangay/Police Clearance</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">Resume/Curriculum Vitae/Notarized PDS</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">Letter of Intent</span>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <div class="doc-details">
                    <span class="doc-name">Detailed Functions and Responsibilities</span>
                    <small>Note: If candidate has been employed to more than one company, DFR should be obtained from present and past jobs; signed by present and past supervisors/employers</small>
                </div>
            </li>
            <li class="doc-item">
                <div class="circle-icon"></div>
                <span class="doc-name">Service Record/Certificate of Employment (from each employer of company reflected in the DFR)</span>
            </li>
        </ul>
    </div>

    <div class="upload-section">
        <h2 class="section-title">File Upload Files</h2>
        
        <div class="upload-box">
            <div class="upload-content">
                <div class="upload-icon">⬆</div>
                <p>Upload your file here</p>
            </div>
        </div>

        <h3 class="recent-title">Recently Upload Files</h3>
        <div class="table-responsive">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Upload Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Birth Certificate</td>
                        <td>2026-03-11</td>
                        <td class="status-verified">Verified</td>
                        <td>
                            <button class="btn-view">View</button>
                            <button class="btn-reupload">Re-upload</button>
                        </td>
                    </tr>
                    <tr>
                        <td>File 2</td>
                        <td>2026-03-11</td>
                        <td class="status-verified">Verified</td>
                        <td>
                            <button class="btn-view">View</button>
                            <button class="btn-reupload">Re-upload</button>
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