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
    <link rel="stylesheet" href="{{ asset('css/homepage_management.css') }}">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        class="nav-item {{ request()->routeIs('admin-dashboard') ? 'active' : '' }}">
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
    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            <h1>BU ETEEAP Homepage Management</h1>
            <p class="date-display" id="liveDateTime">May 4, 2026 | 3:19 PM</p>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                <span class="admin-badge">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            <div class="avatar-circle" id="headerAvatar" style="background-image: url('{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}'); background-size: cover;"></div>
        </div>
    </header>

    <div class="dashboard-container" style="padding: 20px;">
        <!-- TABS -->
        <div class="admin-tabs-container" style="display: flex; gap: 10px; background: white; padding: 10px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto;">
            <button class="admin-tab active" onclick="openTab(event, 'hero')">Hero</button>
            <button class="admin-tab" onclick="openTab(event, 'about')">About Us</button>
            <button class="admin-tab" onclick="openTab(event, 'news')">News Settings</button>
            <button class="admin-tab" onclick="openTab(event, 'apply')">How to Apply</button>
            <button class="admin-tab" onclick="openTab(event, 'programs')">Programs</button>
            <button class="admin-tab" onclick="openTab(event, 'faq')">FAQs</button>
            <button class="admin-tab" onclick="openTab(event, 'contact')">Contact & Map</button>
        </div>

        <div class="tab-content-wrapper">
            
            <!-- HERO SECTION -->
            <div id="hero" class="tab-panel active">
                <div class="admin-card">
                    <div class="card-header"><h3>Hero Settings</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Headline & Highlight</label>
                            <input type="text" class="admin-input" value="Earn your Degree through">
                            <input type="text" class="admin-input" style="margin-top:10px; border-color:#2f3c86; font-weight:bold;" value="ETEEAP">
                        </div>
                        <div class="form-group">
                            <label>Hero Background Image</label>
                            <div class="upload-preview" style="width: 200px; height: 100px; border: 1px solid #ddd; margin-bottom: 10px; background: url('{{ asset('images/hero-bg.jpg') }}') center/cover;"></div>
                            <input type="file" class="admin-input">
                        </div>
                        <button class="save-btn" onclick="showToast('Hero updated!', 'success')">Save Hero</button>
                    </div>
                </div>
            </div>

            <!-- ABOUT SECTION -->
            <div id="about" class="tab-panel" style="display: none;">
                <div class="admin-card">
                    <div class="card-header"><h3>About Us Content</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Main Text (Visible)</label>
                            <textarea class="admin-textarea" rows="3">Bicol University, under the leadership of former President Emiliano A. Aberin...</textarea>
                        </div>
                        <div class="form-group">
                            <label>Read More Content (Hidden Part)</label>
                            <textarea class="admin-textarea" rows="5">ETEEAP OBJECTIVES... General Objective... Specific Objectives...</textarea>
                            <p style="font-size: 11px; color: orange;">*Tip: This text will only appear when the "Read More" button is clicked on the landing page.</p>
                        </div>
                        <div class="form-row" style="display:flex; gap:15px;">
                            <div style="flex:1;">
                                <label>Dean's Image</label>
                                <img src="{{ asset('images/balilo.jpeg') }}" style="width: 80px; border-radius: 8px; display: block; margin-bottom: 5px;">
                                <input type="file" class="admin-input">
                            </div>
                            <div style="flex:2;">
                                <label>Dean's Name & Title</label>
                                <input type="text" class="admin-input" value="Dr. Benedicto B. Balilo Jr.">
                                <input type="text" class="admin-input" style="margin-top:5px;" value="Dean, Open University">
                            </div>
                        </div>
                        <button class="save-btn" onclick="showToast('About section saved!', 'success')">Save About Us</button>
                    </div>
                </div>
            </div>

            <!-- NEWS SETTINGS -->
            <div id="news" class="tab-panel" style="display: none;">
                <div class="admin-card">
                    <div class="card-header"><h3>News Feed Configuration</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>FB News RSS Feed URL</label>
                            <input type="text" class="admin-input" value="https://rss.app/feeds/TSVH8gf7g2LBoh2x.xml">
                            <p style="font-size: 11px; color: #64748b; margin-top:5px;">Admin does not need to post manually here. It will automatically fetch posts from this Facebook link.</p>
                        </div>
                        <button class="save-btn" onclick="showToast('News Feed Link Updated!', 'success')">Update Feed</button>
                    </div>
                </div>
            </div>

            <!-- HOW TO APPLY -->
            <div id="apply" class="tab-panel" style="display: none;">
                <div class="admin-card">
                    <div class="card-header"><h3>Application Steps & Examples</h3></div>
                    <div class="card-body">
                        <label>Onsite Submission Address</label>
                        <input type="text" class="admin-input" value="3rd Floor, University Library Building, Bicol University Main Campus, Daraga, Albay">
                        
                        <div style="margin-top:20px; padding:15px; background:#f8fafc; border-radius:10px;">
                            <label><strong>Onsite Examples (See Example Images)</strong></label>
                            <div style="display:flex; gap:10px; margin-top:10px;">
                                <div class="img-edit">
                                    <img src="{{ asset('images/toc.png') }}" width="100">
                                    <button class="btn-delete-small">Remove</button>
                                </div>
                                <div class="img-edit">
                                    <img src="{{ asset('images/folder.png') }}" width="100">
                                    <button class="btn-delete-small">Remove</button>
                                </div>
                            </div>
                            <input type="file" class="admin-input" style="margin-top:10px;">
                        </div>

                        <div style="margin-top:20px;">
                            <label>Online Submission Link</label>
                            <input type="text" class="admin-input" value="https://bit.ly/BUETEEAPApplication">
                            <label style="margin-top:10px; display:block;">QR Code Image</label>
                            <img src="{{ asset('images/qr.png') }}" width="80" style="display:block; margin-bottom:5px;">
                            <input type="file" class="admin-input">
                        </div>
                        <button class="save-btn" onclick="showToast('Apply settings saved!', 'success')">Save Apply Info</button>
                    </div>
                </div>
            </div>

            <!-- PROGRAMS -->
            <div id="programs" class="tab-panel" style="display: none;">
                <div class="admin-card">
                    <div class="card-header"><h3>Offered Programs & PDF Guide</h3></div>
                    <div class="card-body">
                        <div id="programList">
                            <!-- Existing List -->
                            <div class="list-item-dynamic"><input type="text" class="admin-input" value="BS Information Technology"><button onclick="this.parentElement.remove()">×</button></div>
                            <div class="list-item-dynamic"><input type="text" class="admin-input" value="BS Nursing"><button onclick="this.parentElement.remove()">×</button></div>
                        </div>
                        <button class="add-btn-outline" onclick="addProgram()">+ Add Program</button>

                        <div style="margin-top:30px; border-top:1px solid #eee; padding-top:20px;">
                            <label><strong>Program Details (PDF File)</strong></label>
                            <p style="font-size:12px; color:#64748b;">Current: BU_ETEEAP_Guide.pdf</p>
                            <input type="file" class="admin-input" accept=".pdf">
                        </div>
                        <button class="save-btn" onclick="showToast('Programs updated!', 'success')">Save Programs</button>
                    </div>
                </div>
            </div>

            <!-- FAQS -->
            <div id="faq" class="tab-panel" style="display: none;">
                <div class="admin-card">
                    <div class="card-header"><h3>FAQ Management</h3></div>
                    <div class="card-body">
                        <div id="faqList">
                            <div class="faq-admin-item">
                                <input type="text" class="admin-input-bold" placeholder="Question" value="What is ETEEAP?">
                                <textarea class="admin-textarea" rows="2">The Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP) is an assessment scheme...</textarea>
                                <button class="btn-remove-faq" onclick="this.parentElement.remove()">Delete FAQ</button>
                            </div>
                        </div>
                        <button class="add-btn-outline" onclick="addFAQ()">+ Add New FAQ</button>
                        <button class="save-btn" onclick="showToast('FAQs saved!', 'success')">Save All FAQs</button>
                    </div>
                </div>
            </div>

            <!-- CONTACT -->
            <div id="contact" class="tab-panel" style="display: none;">
                <div class="admin-card">
                    <div class="card-header"><h3>Contact Details & Google Map</h3></div>
                    <div class="card-body">
                        <label>Email Address</label>
                        <input type="text" class="admin-input" value="bu_eteeap@bicol-u.edu.ph">
                        <label style="margin-top:10px; display:block;">Facebook Page URL</label>
                        <input type="text" class="admin-input" value="https://www.facebook.com/profile.php?id=61569718135798">
                        <label style="margin-top:10px; display:block;">Google Maps Embed Link (Iframe URL)</label>
                        <input type="text" class="admin-input" value="https://www.google.com/maps/search/?api=1&query=Bicol+University+Main+Library+Daraga+Albay">
                        
                        <div style="margin-top:15px; height: 150px; background: #eee; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #888;">
                            <i class="fas fa-map-marker-alt" style="margin-right:10px;"></i> Map Preview Area
                        </div>
                        <button class="save-btn" onclick="showToast('Contact info saved!', 'success')">Save Contact Info</button>
                    </div>
                </div>
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
                <!-- Inalis ang value asterisk, nilagyan ng dummy password para may "makita" kapag tinoggle -->
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
'use strict';

/* =================================================
   SIDEBAR TOGGLE (SAFE)
================================================= */
(() => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');

    if (!sidebar || !toggleBtn) return;

    const icon = toggleBtn.querySelector('i');
    const saved = localStorage.getItem('sidebarCollapsed') === 'true';

    if (saved) {
        sidebar.classList.add('collapsed');
        if (icon) icon.className = 'fas fa-chevron-right';
    }

    toggleBtn.addEventListener('click', e => {
        e.preventDefault();
        sidebar.classList.toggle('collapsed');
        const collapsed = sidebar.classList.contains('collapsed');
        if (icon) icon.className = collapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
        localStorage.setItem('sidebarCollapsed', collapsed);

        setTimeout(() => {
            window.pieChart?.resize?.();
            window.barChart?.resize?.();
            window.trendChart?.resize?.();
        }, 280);
    });
})();

/* =================================================
   LIVE DATE & TIME
================================================= */
function updateDateTime() {
    const el = document.getElementById('liveDateTime');
    if (!el) return;

    const now = new Date();
    el.innerText =
        now.toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' }) +
        ' | ' +
        now.toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
}
updateDateTime();
setInterval(updateDateTime, 1000);

/* =================================================
   NAV ACTIVE STATE
================================================= */
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function () {
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        this.classList.add('active');
    });
});

/* =================================================
   ACCOUNT MODAL
================================================= */
let croppieInstance = null;

function openAccountModal() {
    document.getElementById('accountModal')?.classList.add('show');
}

function closeAccountModal() {
    document.getElementById('accountModal')?.classList.remove('show');
    cancelAdjustment();
    hideChangeSection();
}

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;

    input.type = input.type === 'password' ? 'text' : 'password';
    icon.innerText = input.type === 'password' ? 'visibility' : 'visibility_off';
}

function showChangeSection() {
    document.getElementById('changeBtn').style.display = 'none';
    document.getElementById('changeSection').style.display = 'flex';
}

function hideChangeSection() {
    document.getElementById('changeBtn').style.display = 'block';
    document.getElementById('changeSection').style.display = 'none';
}

/* =================================================
   IMAGE CROPPING
================================================= */
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => startCroppie(e.target.result);
    reader.readAsDataURL(file);
}

function startCroppie(src) {
    document.getElementById('defaultAvatarView').style.display = 'none';
    document.getElementById('profileActionButtons').style.display = 'none';
    document.getElementById('adjustmentArea').style.display = 'flex';

    const el = document.getElementById('image-editor');
    if (croppieInstance) croppieInstance.destroy();

    croppieInstance = new Croppie(el, {
        viewport: { width: 150, height: 150, type: 'circle' },
        boundary: { width: '100%', height: 250 },
        showZoomer: true
    });

    croppieInstance.bind({ url: src });
}

function uploadCroppedImage() {
    if (!croppieInstance) return;

    croppieInstance.result('base64').then(base64 => {
        document.getElementById('modalProfilePreview').src = base64;
        document.getElementById('headerAvatar').style.backgroundImage = `url('${base64}')`;
        showToast('Profile photo updated!', 'success');
        cancelAdjustment();
    });
}

function cancelAdjustment() {
    if (croppieInstance) croppieInstance.destroy();
    croppieInstance = null;

    document.getElementById('adjustmentArea').style.display = 'none';
    document.getElementById('defaultAvatarView').style.display = 'block';
    document.getElementById('profileActionButtons').style.display = 'flex';
}

/* =================================================
   TOAST
================================================= */
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return alert(msg);

    document.getElementById('toast-icon').innerText = 'check_circle';
    document.getElementById('toast-message').innerText = msg;
    toast.className = `toast show ${type}`;
    setTimeout(() => toast.classList.remove('show'), 3000);
}

/* =================================================
   TAB SWITCHING (SINGLE)
================================================= */
function openTab(evt, tabId) {
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));

    document.getElementById(tabId).style.display = 'block';
    evt.currentTarget.classList.add('active');
}

/* =================================================
   HERO IMAGE PREVIEW
================================================= */
(() => {
    const input = document.querySelector('#hero input[type="file"]');
    const preview = document.querySelector('#hero .upload-preview');
    if (!input || !preview) return;

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => preview.style.backgroundImage = `url('${e.target.result}')`;
        reader.readAsDataURL(file);
    });
})();

/* =================================================
   DEAN IMAGE PREVIEW
================================================= */
(() => {
    const input = document.querySelector('#about input[type="file"]');
    const img = document.querySelector('#about img');
    if (!input || !img) return;

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => img.src = e.target.result;
        reader.readAsDataURL(file);
    });
})();

/* =================================================
   APPLY – ONSITE MULTI IMAGE
================================================= */
(() => {
    const apply = document.getElementById('apply');
    if (!apply) return;

    const inputs = apply.querySelectorAll('input[type="file"]');
    if (inputs.length < 2) return;

    const onsiteInput = inputs[0];
    const container = apply.querySelector('.img-edit')?.parentElement;
    if (!container) return;

    onsiteInput.addEventListener('change', () => {
        [...onsiteInput.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'img-edit';
                div.innerHTML = `
                    <img src="${e.target.result}" width="100">
                    <button class="btn-delete-small" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        onsiteInput.value = '';
    });
})();

/* =================================================
   APPLY – QR PREVIEW
================================================= */
(() => {
    const apply = document.getElementById('apply');
    if (!apply) return;

    const inputs = apply.querySelectorAll('input[type="file"]');
    const qrImg = apply.querySelector('img[width="80"]');
    if (!qrImg || inputs.length < 2) return;

    const qrInput = inputs[inputs.length - 1];
    qrInput.addEventListener('change', () => {
        const file = qrInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => qrImg.src = e.target.result;
        reader.readAsDataURL(file);
    });
})();

/* =================================================
   PROGRAMS
================================================= */
function addProgram() {
    const list = document.getElementById('programList');
    if (!list) return;

    const div = document.createElement('div');
    div.className = 'list-item-dynamic';
    div.innerHTML = `
        <input type="text" class="admin-input" placeholder="Enter Program Name">
        <button onclick="this.parentElement.remove()">×</button>
    `;
    list.appendChild(div);
}

/* =================================================
   FAQ
================================================= */
function addFAQ() {
    const list = document.getElementById('faqList');
    if (!list) return;

    const div = document.createElement('div');
    div.className = 'faq-admin-item';
    div.innerHTML = `
        <input type="text" class="admin-input-bold" placeholder="New Question">
        <textarea class="admin-textarea" rows="2" placeholder="Answer here..."></textarea>
        <button class="btn-remove-faq" onclick="this.parentElement.remove()">Delete FAQ</button>
    `;
    list.appendChild(div);
}
</script>
</body>
</html>