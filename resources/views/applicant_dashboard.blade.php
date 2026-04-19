<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/applicant_dash.css') }}">
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
                Messages <span class="badge-num-gray" id="messageCount">{{ $unreadMessagesCount }}</span>
            </div>
        </div>

        <h3 class="todo-title">To do</h3>

        <div class="req-list" id="taskList">
            <div class="req-header">
                <span>Task</span>
                <span>Action</span>
            </div>
            @forelse($tasks as $task)
            <div class="req-item" data-task-id="{{ $task->id }}">
                <div class="task-info">
                    <span class="task-title">{{ $task->title }}</span>
                    @if($task->description)
                    <small class="task-desc">{{ $task->description }}</small>
                    @endif
                </div>
                <a href="{{ $task->action_url }}" class="task-view-btn">View</a>
            </div>
            @empty
            <div class="req-item">
                <span>🎉 All tasks completed! Great job!</span>
            </div>
            @endforelse
        </div>
    </div>
</div>

        <div class="center-column">
            <h2 class="rem-title-outside">Reminders</h2>
            <div class="reminders-card">
                <h3 class="sched-title">Today's Schedule</h3>

                <div class="calendar-grid">
                    <div class="cal-item"><span>SUN</span><strong>5</strong></div>
                    <div class="cal-item"><span>MON</span><strong>6</strong></div>
                    <div class="cal-item active-cal"><span>TUE</span><strong>7</strong></div>
                    <div class="cal-item"><span>WED</span><strong>8</strong></div>
                    <div class="cal-item"><span>THU</span><strong>9</strong></div>
                    <div class="cal-item"><span>FRI</span><strong>10</strong></div>
                    <div class="cal-item"><span>SAT</span><strong>11</strong></div>
                </div>

                <div class="interview-box">
                    <div class="blue-line"></div>
                    <div class="interview-info">
                        <h4>INTERVIEW</h4>
                        <p>
                            <span class="material-symbols-outlined">location_on</span> 
                            BU Open University
                        </p>
                        <p>
                            <span class="material-symbols-outlined">calendar_month</span> 
                            April 7, 2026 | 10:00 AM
                        </p>
                    </div>
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
            <div class="dot active">1</div>
            <div class="line"></div>
        </div>
        <div class="status active">
            <p>Application</p>
            <small>March 16, 2026 | 10:50 am</small>
        </div>
    </div>

    <div class="status-row">
        <div class="timeline-col">
            <div class="dot">2</div>
            <div class="line"></div>
        </div>
        <div class="status">
            <p>Documents</p>
            <small>Ongoing</small>
        </div>
    </div>

    <div class="status-row">
        <div class="timeline-col">
            <div class="dot">3</div>
            <div class="line"></div>
        </div>
        <div class="status">
            <p>Interview</p>
            <small>Ongoing</small>
        </div>
    </div>

    <div class="status-row">
        <div class="timeline-col">
            <div class="dot">4</div>
            <div class="line"></div>
        </div>
        <div class="status">
            <p>Payment</p>
            <small>Ongoing</small>
        </div>
    </div>

    <div class="status-row">
        <div class="timeline-col">
            <div class="dot">5</div>
        </div>
        <div class="status">
            <p>Final Review</p>
            <small>Waiting</small>
        </div>
    </div>
</div>
            <!-- ACTIVITIES -->
            <div class="activities">
            <h3>Recent Activities</h3> <div class="activity-list"> <div class="activity">March 22, 2026 | 08:30 AM : Uploaded Birth Certificate</div>
                <div class="activity">March 22, 2026 | 08:30 AM : Uploaded Birth Certificate</div>
                <div class="activity">March 22, 2026 | 08:30 AM : Uploaded Birth Certificate</div>
                <div class="activity">March 22, 2026 | 08:30 AM : Uploaded Birth Certificate</div>
                <div class="activity">March 22, 2026 | 08:30 AM : Uploaded Birth Certificate</div>
                <div class="activity">March 22, 2026 | 08:30 AM : Uploaded Birth Certificate</div>
                <div class="activity">March 22, 2026 | 08:30 AM : Uploaded Birth Certificate</div>
            </div>
        </div>

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


<!-- Messages Modal -->
<div id="messagesModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Messages from Staff</h2>
            <span class="close-btn" onclick="closeMessagesModal()">&times;</span>
        </div>
        <div class="messages-list" id="messagesList">
            <div style="text-align: center; padding: 20px;">Loading messages...</div>
        </div>
    </div>
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


    // ================= TASKS FUNCTIONS =================
function completeTask(taskId) {
    Swal.fire({
        title: 'Complete Task?',
        text: 'Mark this task as done?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#223381',
        confirmButtonText: 'Yes, Complete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/task/${taskId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, "success");
                    // Remove task from list
                    const taskElement = document.querySelector(`.req-item[data-task-id="${taskId}"]`);
                    if (taskElement) taskElement.remove();
                    // Update task count
                    const taskCountSpan = document.getElementById('taskCount');
                    let currentCount = parseInt(taskCountSpan.innerText);
                    taskCountSpan.innerText = currentCount - 1;
                    
                    // If no tasks left, show completion message
                    if (currentCount - 1 === 0) {
                        const taskList = document.getElementById('taskList');
                        taskList.innerHTML = '<div class="req-item"><span>🎉 All tasks completed! Great job!</span></div>';
                    }
                } else {
                    showToast(data.message || "Failed to complete task", "error");
                }
            })
            .catch(error => showToast("Error completing task", "error"));
        }
    });
}

// ================= MESSAGES FUNCTIONS =================
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
let messageRefreshInterval = null;

function formatMessageTime(dateString) {
    if (!dateString) return 'Unknown date';
    
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) {
        return 'Just now';
    } else if (diffMins < 60) {
        return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    } else if (diffHours < 24) {
        return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    } else if (diffDays < 7) {
        return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    } else {
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }
}

function loadMessages() {
    console.log('Loading messages...');
    
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
        console.log('Messages response:', data);
        
        if (data.success) {
            // Update message count badge
            const messageCountSpan = document.getElementById('messageCount');
            if (messageCountSpan) {
                messageCountSpan.innerText = data.unread_count || data.messages.length;
            }
            
            // Store messages for modal display
            window.messagesList = data.messages;
            
            // Also update the messages list in modal if it's open
            const modal = document.getElementById('messagesModal');
            if (modal && modal.style.display === 'flex') {
                displayMessagesInModal();
            }
            
            // Start real-time time refresh
            startMessageTimeRefresh();
        } else {
            console.error('Failed to load messages:', data.message);
        }
    })
    .catch(error => {
        console.error('Error loading messages:', error);
    });
}

function displayMessagesInModal() {
    const messagesList = document.getElementById('messagesList');
    
    if (!messagesList) return;
    
    if (window.messagesList && window.messagesList.length > 0) {
        messagesList.innerHTML = window.messagesList.map(msg => `
            <div class="message-item">
                <div class="message-sender">From: ${escapeHtml(msg.sender_name)}</div>
                <div class="message-text">${escapeHtml(msg.message)}</div>
                <div class="message-time">${formatMessageTime(msg.created_at)}</div>
            </div>
        `).join('');
    } else {
        messagesList.innerHTML = '<div style="text-align: center; padding: 20px;">No messages yet</div>';
    }
}

function startMessageTimeRefresh() {
    if (messageRefreshInterval) clearInterval(messageRefreshInterval);
    
    messageRefreshInterval = setInterval(() => {
        const modal = document.getElementById('messagesModal');
        // Only refresh if modal is open
        if (modal && modal.style.display === 'flex' && window.messagesList) {
            const messagesList = document.getElementById('messagesList');
            if (messagesList) {
                messagesList.innerHTML = window.messagesList.map(msg => `
                    <div class="message-item">
                        <div class="message-sender">From: ${escapeHtml(msg.sender_name)}</div>
                        <div class="message-text">${escapeHtml(msg.message)}</div>
                        <div class="message-time">${formatMessageTime(msg.created_at)}</div>
                    </div>
                `).join('');
            }
        }
    }, 60000); // Update every minute
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function openMessagesModal() {
    const modal = document.getElementById('messagesModal');
    if (!modal) return;
    
    // Show loading state
    const messagesList = document.getElementById('messagesList');
    if (messagesList) {
        messagesList.innerHTML = '<div style="text-align: center; padding: 20px;">Loading messages...</div>';
    }
    
    // First load fresh messages
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
            window.messagesList = data.messages;
            displayMessagesInModal();
            modal.style.display = 'flex';
            
            // Update message count badge to 0 after viewing
            const messageCountSpan = document.getElementById('messageCount');
            if (messageCountSpan) {
                messageCountSpan.innerText = '0';
            }
        } else {
            if (messagesList) {
                messagesList.innerHTML = '<div style="text-align: center; padding: 20px;">Error loading messages</div>';
            }
            modal.style.display = 'flex';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const messagesListElem = document.getElementById('messagesList');
        if (messagesListElem) {
            messagesListElem.innerHTML = '<div style="text-align: center; padding: 20px;">Error loading messages</div>';
        }
        modal.style.display = 'flex';
    });
}

function closeMessagesModal() {
    const modal = document.getElementById('messagesModal');
    if (modal) {
        modal.style.display = 'none';
    }
    // Stop refreshing when modal is closed
    if (messageRefreshInterval) {
        clearInterval(messageRefreshInterval);
        messageRefreshInterval = null;
    }
}

// Close modal on outside click
window.onclick = function(event) {
    const modal = document.getElementById('messagesModal');
    if (event.target === modal) {
        closeMessagesModal();
    }
}

// Load messages every 30 seconds
setInterval(loadMessages, 30000);
// Load messages immediately on page load
document.addEventListener('DOMContentLoaded', function() {
    loadMessages();
});
</script>

</body>
</html>