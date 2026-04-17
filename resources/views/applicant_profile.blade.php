<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/heic2any/0.0.4/heic2any.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
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
            <button type="button" class="active">Profile</button>
        </a>
        <a href="{{ route('applicant.documents') }}" style="text-decoration: none;">
        <button type="button">Documents</button>
    </a>
    </div>

    <!-- CARD CONTAINER -->
    <div class="card-container">

       <div class="profile-container">
    <div class="profile-sidebar">
    <h3>Profile Picture</h3>
    <div class="pic-wrapper">
    <div class="pic-circle" id="picCircle">
        <img id="imagePreview" 
             src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" 
             alt="Profile Preview" 
             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; {{ auth()->user()->profile_image ? '' : 'display: none;' }}">
    </div>
</div>
    
    <input type="file" id="fileUpload" accept="image/*" style="display: none;">
    
    <button class="edit-btn" id="editBtn">
        <span class="icon">✎</span> Edit
    </button>
</div>

    <div id="uploadModal" class="modal">
    <div class="modal-content">
        <h3 style="color: #233a8e;">Adjust Profile Picture</h3>
        <div id="image-demo"></div>
        <div class="modal-actions">
            <button type="button" class="btn-save" onclick="document.getElementById('fileUpload').click()">Change Photo</button>
            <button type="button" class="btn-save" id="saveCropBtn">Save Changes</button>
            <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
        </div>
    </div>
</div>


    <div class="profile-main">
        <h2 class="form-title">Personal Information</h2>
        <p class="form-desc">
            This will serve as your application form for the ETEEAP program. Please fill out all required fields carefully and accurately.
        </p>

        <form class="profile-form">
            <div class="form-row four-cols">
                <div class="field-group">
                    <label>First Name <span>*</span></label>
                    <input type="text">
                </div>
                <div class="field-group">
                <label>Middle Name <span>*</span> <small class="label-note">(Write "N/A" if none)</small></label>
                <input type="text" name="middle_name">
            </div>
                <div class="field-group">
                    <label>Last Name <span>*</span></label>
                    <input type="text">
                </div>
                <div class="field-group">
                    <label>Extension Name <small class="label-note">(Ex. Jr., Sr.)</small></label>
                    <input type="text">
                </div>
            </div>

            
             <div class="form-row four-cols">
            <div class="field-group">
                <label>Birthdate <span>*</span></label>
                <input type="date" class="form-date">
            </div>

            <div class="field-group">
                <label>Sex <span>*</span></label>
                <div class="custom-select" id="sexSelect">
                    <div class="select-trigger">
                        <span id="selectedSex"></span> 
                        <div class="arrow-icon"></div>
                    </div>
                    <div class="select-options">
                        <div class="option" data-value="male">Male</div>
                        <div class="option" data-value="female">Female</div>
                    </div>
                </div>
                <input type="hidden" name="sex" id="sexInput">
            </div>

    <div class="field-group">
        <label>Email Address <span>*</span></label>
        <input type="email">
    </div>

    <div class="field-group">
        <label>Degree Program <span>*</span></label>
        <div class="custom-select" id="degreeSelect">
            <div class="select-trigger">
                <span id="selectedText"></span> 
                <div class="arrow-icon"></div>
            </div>
            <div class="select-options">
                <div class="option" data-value="fisheries">BS Fisheries</div>
                <div class="option" data-value="cs">BS Computer Science</div>
                <div class="option" data-value="comm">AB Communication</div>
                <div class="option" data-value="auto">BS Automotive Technology</div>
                <div class="option" data-value="nursing">BS Nursing</div>
            </div>
        </div>
        <input type="hidden" name="degree_program" id="degreeInput">
    </div>
</div>

            <div class="form-row two-cols">
    <div class="field-group">
        <label>Permanent Address <span>*</span></label>
        <input type="text" name="permanent_address">
    </div>
    <div class="field-group">
        <label>Current Address <span>*</span></label>
        <input type="text" name="current_address">
    </div>
</div>

            <div class="form-actions">
                <button type="submit" class="main-save-btn">Save Changes</button>
            </div>
        </form>
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

window.showToast = function(message, type = 'success') {
    const toast = document.getElementById("toast");
    const icon = document.getElementById("toast-icon");
    const msg = document.getElementById("toast-message");

    if (!toast) return;

    // Set type (success/error)
    toast.className = `toast show ${type}`;
    msg.innerText = message;
    
    // Palitan ang icon base sa type
    if (icon) {
        icon.innerText = type === 'success' ? 'check_circle' : 'error';
    }

    // Auto-hide after 3 seconds
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
};

document.addEventListener('DOMContentLoaded', function() {
    const allCustomSelects = document.querySelectorAll('.custom-select');
    const allInputs = document.querySelectorAll('input:not([type="hidden"]), select');

    function saveToStorage(name, value) {
        localStorage.setItem('form_' + name, value);
    }

    function loadFromStorage() {
        allInputs.forEach(input => {
            const savedValue = localStorage.getItem('form_' + (input.name || input.type + "_" + Array.from(allInputs).indexOf(input)));
            if (savedValue) {
                input.value = savedValue;
            }
        });

        allCustomSelects.forEach(select => {
            const hiddenInput = select.parentNode.querySelector('input[type="hidden"]');
            const savedValue = localStorage.getItem('form_' + hiddenInput.name);
            if (savedValue) {
                hiddenInput.value = savedValue;
                const option = select.querySelector(`.option[data-value="${savedValue}"]`);
                if (option) {
                    select.querySelector('.select-trigger span').innerText = option.innerText;
                }
            }
        });
    }

    allCustomSelects.forEach(select => {
        const trigger = select.querySelector('.select-trigger');
        const options = select.querySelectorAll('.option');
        const hiddenInput = select.parentNode.querySelector('input[type="hidden"]');

        trigger.addEventListener('click', function(e) {
            allCustomSelects.forEach(s => {
                if (s !== select) s.classList.remove('active');
            });
            select.classList.toggle('active');
            e.stopPropagation();
        });

        options.forEach(option => {
            option.addEventListener('click', function() {
                let value = this.getAttribute('data-value');
                let text = this.innerText;

                select.querySelector('.select-trigger span').innerText = text;
                if (hiddenInput) {
                    hiddenInput.value = value;
                    saveToStorage(hiddenInput.name, value);
                }

                select.classList.remove('active');
            });
        });
    });

    allInputs.forEach(input => {
        input.addEventListener('input', function() {
            const storageName = input.name || input.type + "_" + Array.from(allInputs).indexOf(input);
            saveToStorage(storageName, input.value);
        });
    });

    loadFromStorage();

    document.addEventListener('click', function(e) {
        allCustomSelects.forEach(select => {
            if (!select.contains(e.target)) {
                select.classList.remove('active');
            }
        });
    });

    const form = document.querySelector('.profile-form');
    if(form) {
        form.addEventListener('submit', function() {
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('editBtn');
    const fileUpload = document.getElementById('fileUpload');
    const imagePreview = document.getElementById('imagePreview');
    const uploadModal = document.getElementById('uploadModal');
    
    // Initialize Croppie
    let croppieInstance = new Croppie(document.getElementById('image-demo'), {
        viewport: { width: 180, height: 180, type: 'circle' },
        boundary: { width: 250, height: 250 },
        showZoomer: true,
        enableOrientation: true
    });

    const savedCropped = localStorage.getItem('profileImage');
    if (savedCropped) {
        imagePreview.src = savedCropped;
        imagePreview.style.display = 'block';
    }

    editBtn.addEventListener('click', function() {
        const original = localStorage.getItem('originalImage');
        
        if (original) {
            uploadModal.style.display = 'flex';
            croppieInstance.bind({
                url: original
            });
        } else {
            fileUpload.click();
        }
    });

    fileUpload.addEventListener('change', async function() {
        let file = this.files[0];
        if (!file) return;

        if (file.name.toLowerCase().endsWith('.heic')) {
            try {
                const convertedBlob = await heic2any({ blob: file, toType: "image/jpeg", quality: 0.7 });
                file = new File([convertedBlob], "original.jpg", { type: "image/jpeg" });
            } catch (err) { console.error(err); }
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const originalBase64 = e.target.result;
            
            try {
                localStorage.setItem('originalImage', originalBase64);
            } catch (e) {
                console.warn("Original image is too large for localStorage.");
            }
            
            uploadModal.style.display = 'flex';
            croppieInstance.bind({
                url: originalBase64
            });
        }
        reader.readAsDataURL(file);
    });

    document.getElementById('saveCropBtn').addEventListener('click', function() {
    // Kunin ang result mula sa Croppie
    croppieInstance.result({
        type: 'base64',
        size: 'viewport',
        format: 'jpeg',
        quality: 0.8
    }).then(function(croppedBase64) {
        
        // Hanapin ang mga elements sa UI
        const imagePreview = document.getElementById('imagePreview');
        const profileImgHeader = document.getElementById('profileImg'); 
        const dropdownAvatar = document.querySelector('.dropdown-avatar');

        fetch("{{ route('profile.upload.image') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')?.value || "{{ csrf_token() }}"
            },
            body: JSON.stringify({ image: croppedBase64 })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if(data.success) {
                if(imagePreview) {
                    imagePreview.src = croppedBase64;
                    imagePreview.style.display = 'block';
                }

                if(profileImgHeader) {
                    profileImgHeader.src = croppedBase64;
                }

                if(dropdownAvatar) {
                    dropdownAvatar.src = croppedBase64;
                }
                
                localStorage.setItem('profileImage', croppedBase64);
                
                if (window.showToast) {
                    window.showToast("Profile picture updated successfully!", "success");
                } else {
                    alert("Profile picture updated!");
                }

                const modal = document.getElementById('uploadModal');
                if(modal) modal.style.display = 'none';
                
                const fileInput = document.getElementById('fileUpload');
                if(fileInput) fileInput.value = '';

            } else {
                if (window.showToast) window.showToast(data.message || "Upload failed.", "error");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            if (window.showToast) window.showToast("Server error. Please try again.", "error");
        });
    });
});

    document.getElementById('cancelBtn').addEventListener('click', () => {
        uploadModal.style.display = 'none';
        fileUpload.value = '';
    });

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
    window.openAccountModal = function () {
        document.getElementById("accountModal").classList.add("show");
    };

    window.showChangeSection = function () {
        document.getElementById("changeBtn").style.display = "none";
        document.getElementById("changeSection").style.display = "flex";
    };

    window.closeAccountModal = function () {
        const modal = document.getElementById("accountModal");
        modal.classList.remove("show");

        setTimeout(() => {
            document.getElementById("changeBtn").style.display = "block";
            document.getElementById("changeSection").style.display = "none";

            document.getElementById("newPassword").value = "";
            document.getElementById("confirmPassword").value = "";

            ['newPassword','confirmPassword','currentPassword'].forEach(id => {
                document.getElementById(id).type = "password";
            });

            document.getElementById("newEyeIcon").innerText = "visibility";
            document.getElementById("confirmEyeIcon").innerText = "visibility";
            document.getElementById("currentEyeIcon").innerText = "visibility";
        }, 300);
    };

    window.togglePassword = function (inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        input.type = (input.type === "password") ? "text" : "password";
        icon.innerText = (input.type === "password") ? "visibility" : "visibility_off";
    };

    window.updatePassword = async function () {
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
                document.getElementById("currentPassword").value = newPass;
                showToast("Password updated successfully!", "success");
                closeAccountModal();
            } else {
                showToast(data.message || "Invalid password", "error");
            }
        } catch (error) {
            showToast("Server connection failed", "error");
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerText = "Save";
        }
    };
    });
</script>

</body>
</html>