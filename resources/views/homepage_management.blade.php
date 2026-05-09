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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/tinymce.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('success') }}', 'success');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('error') }}', 'error');
        });
    </script>
@endif

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
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin-dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('user_management') }}" class="nav-item {{ request()->routeIs('user_management') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <span>User Management</span>
        </a>
        <a href="{{ route('audit_logs') }}" class="nav-item {{ request()->routeIs('audit_logs') ? 'active' : '' }}">
            <i class="fas fa-user-check"></i> <span>Audit Logs</span>
        </a>
        <a href="{{ route('homepage_management') }}" class="nav-item {{ request()->routeIs('homepage_management') ? 'active' : '' }}">
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
    <header class="header">
        <div class="header-left">
            <h1>BU ETEEAP Homepage Management</h1>
            <p class="date-display" id="liveDateTime"></p>
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
            <form id="homepageForm" method="POST" action="{{ route('homepage.update') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- HERO SECTION -->
                <div id="hero" class="tab-panel active">
                    <div class="admin-card">
                        <div class="card-header"><h3>Hero Settings</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Headline</label>
                                <input type="text" name="hero_headline" class="admin-input" value="{{ $settings->hero_headline ?? '' }}" placeholder="Earn your Degree through">
                            </div>
                            <div class="form-group">
                                <label>Highlight Text</label>
                                <input type="text" name="hero_highlight" class="admin-input" style="border-color:#2f3c86; font-weight:bold;" value="{{ $settings->hero_highlight ?? '' }}" placeholder="ETEEAP">
                            </div>
                            <div class="form-group">
                                <label>Hero Background Image</label>
                                @if($settings->hero_image ?? false)
                                    <div class="upload-preview" style="width: 200px; height: 100px; border: 1px solid #ddd; margin-bottom: 10px; background: url('{{ asset('storage/' . $settings->hero_image) }}') center/cover;"></div>
                                @else
                                    <div class="upload-preview" style="width: 200px; height: 100px; border: 1px solid #ddd; margin-bottom: 10px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">No Image</div>
                                @endif
                                <input type="file" name="hero_image" class="admin-input">
                            </div>
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
<textarea name="about_main" class="rich-text">
{{ $settings->about_main ?? '' }}
</textarea>
                            </div>
                            <div class="form-group">
                                <label>Read More Content (Hidden Part)</label>
<textarea name="about_more" class="rich-text">
{{ $settings->about_more ?? '' }}
</textarea>
                                <p style="font-size: 11px; color: orange;">*Tip: This text will only appear when the "Read More" button is clicked on the landing page.</p>
                            </div>
                            <div class="form-row" style="display:flex; gap:15px;">
                                <div style="flex:1;">
                                    <label>Dean's Image</label>
                                    @if($settings->dean_image ?? false)
                                        <img src="{{ asset('storage/' . $settings->dean_image) }}" style="width: 80px; border-radius: 8px; display: block; margin-bottom: 5px;">
                                    @endif
                                    <input type="file" name="dean_image" class="admin-input">
                                </div>
                                <div style="flex:2;">
                                    <label>Dean's Name</label>
                                    <input type="text" name="dean_name" class="admin-input" value="{{ $settings->dean_name ?? '' }}" placeholder="Dr. Benedicto B. Balilo Jr.">
                                    <label style="margin-top:5px; display:block;">Dean's Title</label>
                                    <input type="text" name="dean_title" class="admin-input" value="{{ $settings->dean_title ?? '' }}" placeholder="Dean, Open University">
                                </div>
                            </div>
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
                                <input type="text" name="news_rss" class="admin-input" value="{{ $settings->news_rss ?? '' }}" placeholder="https://rss.app/feeds/...">
                                <p style="font-size: 11px; color: #64748b; margin-top:5px;">Admin does not need to post manually here. It will automatically fetch posts from this Facebook link.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HOW TO APPLY -->
<div id="apply" class="tab-panel" style="display: none;">
    <div class="admin-card">
        <div class="card-header">
            <h3>Application Steps & Examples</h3>
        </div>

        <div class="card-body">

            <!-- ONSITE CONTENT (TINY MCE) -->
            <label>Onsite Submission Content</label>
            <textarea name="apply_on_site" class="rich-text">
    {!! $settings->apply_on_site ?? '' !!}
</textarea>

<!-- EXAMPLE IMAGES (ONSITE / HOW TO APPLY EXAMPLES) -->
<label>Onsite Submission Examples (Multiple Upload)</label>

<div style="margin-top:10px; border: 1px dashed #ccc; padding: 20px; border-radius: 8px; background: #fafafa;">
    <div id="preview-container" style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 15px;">
        
        <div id="wrapper-toc" style="text-align: center; position: relative; {{ empty($settings->apply_example_toc) ? 'display:none;' : '' }}">
            <img id="prev-toc" src="{{ $settings->apply_example_toc ? asset('storage/' . $settings->apply_example_toc) : '' }}" 
                 width="120" style="border-radius: 5px; border: 2px solid #2f3c86; display: block;">
            <small>TOC Example</small>
            <button type="button" onclick="removeImage('toc')" 
                    style="position: absolute; top: -10px; right: -10px; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-weight: bold;">×</button>
            <input type="hidden" name="delete_toc" id="delete_toc" value="0">
        </div>

        <div id="wrapper-folder" style="text-align: center; position: relative; {{ empty($settings->apply_example_folder) ? 'display:none;' : '' }}">
            <img id="prev-folder" src="{{ $settings->apply_example_folder ? asset('storage/' . $settings->apply_example_folder) : '' }}" 
                 width="120" style="border-radius: 5px; border: 2px solid #2f3c86; display: block;">
            <small>Folder Example</small>
            <button type="button" onclick="removeImage('folder')" 
                    style="position: absolute; top: -10px; right: -10px; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-weight: bold;">×</button>
            <input type="hidden" name="delete_folder" id="delete_folder" value="0">
        </div>
    </div>

    <input type="file" id="onsite_input" name="onsite_examples[]" class="admin-input" multiple accept="image/*" onchange="previewImages(this)">
    <p style="font-size: 11px; color: #666; margin-top: 8px;">
        <i>Max 2 images. Ang unang napili ay TOC, ang ikalawa ay Folder.</i>
    </p>
</div>

<br><br>
            <br><br>

            <!-- ONLINE CONTENT (TINY MCE) -->
            <label>Online Submission Content</label>
            <textarea name="apply_online" class="rich-text">
    {!! $settings->apply_online ?? '' !!}
</textarea>

            <br><br>

            <!-- QR CODE -->
            <label>QR Code Image</label>

            @if(!empty($settings->apply_qr))
                <img src="{{ asset('storage/' . $settings->apply_qr) }}"
                     width="100"
                     style="display:block; margin-bottom:10px;">
            @endif

            <input type="file" name="apply_qr" class="admin-input">

        </div>
    </div>
</div>

                <!-- PROGRAMS -->
                <div id="programs" class="tab-panel" style="display: none;">
                    <div class="admin-card">
                        <div class="card-header"><h3>Offered Programs</h3></div>
                        <div class="card-body">
                            <div id="programList">
                                @php
                                    $programs = $settings->programs ?? [];
                                @endphp
                                @if(count($programs) > 0)
                                    @foreach($programs as $program)
                                        <div class="list-item-dynamic">
                                            <input type="text" name="programs[]" class="admin-input" value="{{ $program }}">
                                            <button type="button" onclick="this.parentElement.remove()">×</button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="list-item-dynamic">
                                        <input type="text" name="programs[]" class="admin-input" placeholder="Enter Program Name">
                                        <button type="button" onclick="this.parentElement.remove()">×</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="add-btn-outline" onclick="addProgram()">+ Add Program</button>
                        </div>
                    </div>
                </div>

                <!-- FAQS -->
                <div id="faq" class="tab-panel" style="display: none;">
    <div class="admin-card">
        <div class="card-header"><h3>FAQ Management</h3></div>
        <div class="card-body">
            <div id="faqList">
                @php
                    $faqs = $settings->faqs ?? [];
                @endphp
                @if(count($faqs) > 0)
                    @foreach($faqs as $index => $faq)
                        <div class="faq-admin-item">
                            <input type="text" name="faq_questions[]" class="admin-input-bold" placeholder="Question" value="{{ $faq['question'] }}">
                            <textarea name="faq_answers[]" class="rich-text-faq" id="faq_answer_{{ $index }}" rows="5" placeholder="Answer...">{{ $faq['answer'] }}</textarea>
                            <button type="button" class="btn-remove-faq" onclick="removeFaqItem(this)">Delete FAQ</button>
                        </div>
                    @endforeach
                @else
                    <div class="faq-admin-item">
                        <input type="text" name="faq_questions[]" class="admin-input-bold" placeholder="Question" value="">
                        <textarea name="faq_answers[]" class="rich-text-faq" id="faq_answer_0" rows="5" placeholder="Answer..."></textarea>
                        <button type="button" class="btn-remove-faq" onclick="removeFaqItem(this)">Delete FAQ</button>
                    </div>
                @endif
            </div>
            <button type="button" class="add-btn-outline" onclick="addFAQ()">+ Add New FAQ</button>
        </div>
    </div>
</div>

                <!-- CONTACT -->
                <div id="contact" class="tab-panel" style="display: none;">
                    <div class="admin-card">
                        <div class="card-header"><h3>Contact Details</h3></div>
                        <div class="card-body">
                            <label>Email Address</label>
                            <input type="email" name="contact_email" class="admin-input" value="{{ $settings->contact_email ?? '' }}" placeholder="bu_eteeap@bicol-u.edu.ph">
                            
                            <label style="margin-top:10px; display:block;">Facebook Page URL</label>
                            <input type="text" name="contact_fb" class="admin-input" value="{{ $settings->contact_fb ?? '' }}" placeholder="https://www.facebook.com/...">
                            
                            <!-- ADDRESS FIELD -->
                            <label style="margin-top:10px; display:block;">Office Address (Display Text)</label>
                            <input type="text" name="contact_address" class="admin-input" value="{{ $settings->contact_address ?? '' }}" placeholder="Open University, Bicol University, Legazpi City">
                            
                            <label style="margin-top:10px; display:block;">Google Maps Link (Destination URL)</label>
                            <input type="text" name="contact_map" class="admin-input" value="{{ $settings->contact_map ?? '' }}" placeholder="https://www.google.com/maps/search/...">
                        </div>
                    </div>
                </div>

                <div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
                    <button type="submit" class="save-btn" style="padding: 12px 24px; font-size: 16px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">Save All Changes</button>
                </div>
            </form>
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
                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-profile.png') }}" id="modalProfilePreview" class="modal-avatar">
                <label for="profileUpload" class="upload-badge">
                    <span class="material-symbols-outlined">photo_camera</span>
                </label>
                <input type="file" id="profileUpload" hidden accept="image/*" onchange="previewImage(event)">
            </div>
            <div id="adjustmentArea" style="display:none;">
                <div id="image-editor"></div> 
                <div class="account-actions">
                    <button type="button" class="cancel-btn" onclick="cancelAdjustment()">Cancel</button>
                    <button id="savePhotoBtn" class="save-photo-btn" onclick="uploadCroppedImage()">Save Photo</button>
                </div>
            </div>
            <div id="profileActionButtons" style="display: flex; flex-direction: column; align-items: center; gap: 5px; margin-top: 10px;">
                <p class="upload-text">Click camera to change photo</p>
                <button type="button" onclick="startAdjustingCurrent()" class="re-adjust-btn">Adjust Photo</button>
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
        <button class="change-btn" id="changeBtn" onclick="showChangeSection()">Change Password</button>
        <div id="changeSection" style="display:none; flex-direction:column; gap:12px;">
            <div class="input-group">
                <label>New Password</label>
                <input type="password" id="newPassword" placeholder="Enter new password">
            </div>
            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" id="confirmPassword" placeholder="Confirm new password">
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
// =================================================
// SIDEBAR TOGGLE
// =================================================
(function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');

    if (sidebar && toggleBtn) {
        const icon = toggleBtn.querySelector('i');
        const saved = localStorage.getItem('sidebarCollapsed') === 'true';

        if (saved) {
            sidebar.classList.add('collapsed');
            if (icon) icon.className = 'fas fa-chevron-right';
        }

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('collapsed');
            const collapsed = sidebar.classList.contains('collapsed');
            if (icon) icon.className = collapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
            localStorage.setItem('sidebarCollapsed', collapsed);
        });
    }
})();

// =================================================
// DATE & TIME
// =================================================
function updateDateTime() {
    const el = document.getElementById('liveDateTime');
    if (el) {
        const now = new Date();
        el.innerText = now.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) + ' | ' + now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }
}
updateDateTime();
setInterval(updateDateTime, 1000);

// =================================================
// TAB SWITCHING
// =================================================
function openTab(evt, tabId) {
    const tabcontents = document.getElementsByClassName("tab-panel");
    for (let i = 0; i < tabcontents.length; i++) {
        tabcontents[i].style.display = "none";
    }
    const tablinks = document.getElementsByClassName("admin-tab");
    for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabId).style.display = "block";
    evt.currentTarget.className += " active";
}

// =================================================
// TOAST NOTIFICATION
// =================================================
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    
    if (!toast) return;
    
    toastMessage.innerText = message;
    toastIcon.innerText = type === 'success' ? 'check_circle' : 'error';
    toast.className = `toast show ${type}`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// =================================================
// PROGRAMS MANAGEMENT
// =================================================
function addProgram() {
    const list = document.getElementById('programList');
    if (!list) return;
    
    const div = document.createElement('div');
    div.className = 'list-item-dynamic';
    div.innerHTML = `
        <input type="text" name="programs[]" class="admin-input" placeholder="Enter Program Name">
        <button type="button" onclick="this.parentElement.remove()">×</button>
    `;
    list.appendChild(div);
}

// =================================================
// FAQ MANAGEMENT
// =================================================
// Counter for FAQ indices to ensure unique IDs
let faqCounter = document.querySelectorAll('.faq-admin-item').length;

// Updated addFAQ function with TinyMCE
function addFAQ() {
    const list = document.getElementById('faqList');
    if (!list) return;
    
    const div = document.createElement('div');
    div.className = 'faq-admin-item';
    
    const currentIndex = faqCounter++;
    
    div.innerHTML = `
        <input type="text" name="faq_questions[]" class="admin-input-bold" placeholder="Question">
        <textarea name="faq_answers[]" class="rich-text-faq" id="faq_answer_${currentIndex}" rows="5" placeholder="Answer..."></textarea>
        <button type="button" class="btn-remove-faq" onclick="removeFaqItem(this)">Delete FAQ</button>
    `;
    
    list.appendChild(div);
    
    // Initialize TinyMCE for the new textarea
    tinymce.init({
    selector: '.rich-text-faq', // Gamitin ang class para sa lahat
    height: 200,
    menubar: false,
    plugins: 'lists advlist link',
    toolbar: 'undo redo | bold italic underline | forecolor | bullist numlist outdent indent | alignleft aligncenter alignright | link | removeformat',
    // Idinagdag ang 'outdent indent' sa toolbar para sa manual indentation
    
    advlist_bullet_styles: 'disc circle square',
    content_style: `
        body { font-family:Raleway, sans-serif; font-size:14px; padding: 10px; }
        ul, ol { padding-left: 30px; } 
        li { margin-bottom: 5px; }
    `,
    setup: function (editor) {
        editor.on('change', function () {
            tinymce.triggerSave();
        });
    }
});
}

// Function to remove FAQ item and destroy TinyMCE instance
function removeFaqItem(button) {
    const faqItem = button.parentElement;
    // Find and destroy TinyMCE instance for this FAQ
    const textarea = faqItem.querySelector('textarea');
    if (textarea && textarea.id) {
        const editor = tinymce.get(textarea.id);
        if (editor) {
            editor.remove();
        }
    }
    faqItem.remove();
}

// Initialize TinyMCE for existing FAQ answers on page load
document.addEventListener('DOMContentLoaded', function () {
    // Initialize regular rich text editors
    tinymce.init({
        selector: 'textarea.rich-text:not(.rich-text-faq)',
        height: 300,
        menubar: false,
        plugins: 'lists advlist link image',
        toolbar: 'undo redo | bold italic underline | forecolor | bullist numlist | alignleft aligncenter alignright | link | removeformat',
        link_default_target: '_blank',
        link_title: false,
        advlist_bullet_styles: 'disc circle square',
        advlist_number_styles: 'default lower-alpha lower-roman',
        content_style: "body { font-family:Raleway, sans-serif; font-size:14px }",
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
    
    // Initialize TinyMCE for FAQ answers
    tinymce.init({
        selector: 'textarea.rich-text-faq',
        height: 200,
        menubar: false,
        plugins: 'lists advlist link image',
        toolbar: 'undo redo | bold italic underline | forecolor | bullist numlist | alignleft aligncenter alignright | link | removeformat',
        link_default_target: '_blank',
        link_title: false,
        advlist_bullet_styles: 'disc circle square',
        advlist_number_styles: 'default lower-alpha lower-roman',
        content_style: "body { font-family:Raleway, sans-serif; font-size:14px }",
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
});

// =================================================
// ACCOUNT MODAL FUNCTIONS
// =================================================
function openAccountModal() {
    const modal = document.getElementById('accountModal');
    if (modal) modal.style.display = 'flex';
}

function closeAccountModal() {
    const modal = document.getElementById('accountModal');
    if (modal) modal.style.display = 'none';
    cancelAdjustment();
    hideChangeSection();
}

function showChangeSection() {
    document.getElementById('changeBtn').style.display = 'none';
    document.getElementById('changeSection').style.display = 'flex';
}

function hideChangeSection() {
    document.getElementById('changeBtn').style.display = 'block';
    document.getElementById('changeSection').style.display = 'none';
}

function updatePassword() {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        showToast('Passwords do not match!', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showToast('Password must be at least 6 characters!', 'error');
        return;
    }
    
    // Send password update via AJAX
    fetch('{{ route("update.password") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ password: newPassword })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Password updated successfully!', 'success');
            hideChangeSection();
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        } else {
            showToast(data.message || 'Error updating password', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating password', 'error');
    });
}

// =================================================
// IMAGE CROPPING FUNCTIONS
// =================================================
let croppieInstance = null;

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        startCroppie(e.target.result);
    };
    reader.readAsDataURL(file);
}

function startCroppie(imageSrc) {
    document.getElementById('defaultAvatarView').style.display = 'none';
    document.getElementById('profileActionButtons').style.display = 'none';
    document.getElementById('adjustmentArea').style.display = 'block';
    
    const el = document.getElementById('image-editor');
    
    if (croppieInstance) {
        croppieInstance.destroy();
    }
    
    croppieInstance = new Croppie(el, {
        viewport: { width: 150, height: 150, type: 'circle' },
        boundary: { width: 300, height: 300 },
        showZoomer: true,
        enableOrientation: true
    });
    
    croppieInstance.bind({ url: imageSrc });
}

function startAdjustingCurrent() {
    const currentImage = document.getElementById('modalProfilePreview').src;
    startCroppie(currentImage);
}

function cancelAdjustment() {
    if (croppieInstance) {
        croppieInstance.destroy();
        croppieInstance = null;
    }
    
    document.getElementById('adjustmentArea').style.display = 'none';
    document.getElementById('defaultAvatarView').style.display = 'block';
    document.getElementById('profileActionButtons').style.display = 'flex';
}

function uploadCroppedImage() {
    if (!croppieInstance) return;
    
    croppieInstance.result({ type: 'blob', size: { width: 300, height: 300 } })
        .then(function(blob) {
            const formData = new FormData();
            formData.append('profile_image', blob, 'avatar.jpg');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            fetch('{{ route("update.avatar") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const imageUrl = URL.createObjectURL(blob);
                    document.getElementById('modalProfilePreview').src = imageUrl;
                    document.getElementById('headerAvatar').style.backgroundImage = `url('${imageUrl}')`;
                    showToast('Profile photo updated!', 'success');
                    cancelAdjustment();
                } else {
                    showToast('Error updating photo', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error uploading image', 'error');
            });
        });
}

// =================================================
// FORM SUBMISSION
// =================================================
document.getElementById('homepageForm')?.addEventListener('submit', function() {
    showToast('Saving settings...', 'success');
});

// =================================================
// CLOSE MODAL WHEN CLICKING OUTSIDE
// =================================================
window.onclick = function(event) {
    const modal = document.getElementById('accountModal');
    if (event.target === modal) {
        closeAccountModal();
    }
}

// =================================================
// NAV ACTIVE STATE
// =================================================
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
    });
});

document.addEventListener('DOMContentLoaded', function () {

    tinymce.init({
        selector: 'textarea.rich-text',
        height: 300,
        menubar: false,

        // Siguraduhin na kasama ang 'link' sa plugins
        plugins: 'lists advlist link',

        // Idinagdag ang 'link' sa toolbar list sa ibaba
        toolbar: 'undo redo | bold italic underline | forecolor | bullist numlist | alignleft aligncenter alignright | link | removeformat',

        // Pinapayagan ang admin na i-set kung mag-oopen sa bagong tab ang link
        link_default_target: '_blank',
        link_title: false,

        advlist_bullet_styles: 'disc circle square',
        advlist_number_styles: 'default lower-alpha lower-roman',

        content_style: "body { font-family:Raleway, sans-serif; font-size:14px }",
        
        // Mahalaga: Para ma-update ang textarea bago i-submit ang form
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });

});

function previewImages(input) {
    const files = input.files;
    
    // Limit to 2 files only for TOC and Folder logic
    if (files.length > 2) {
        Swal.fire({
            icon: 'warning',
            title: 'Too many files',
            text: 'Please select only up to 2 images (TOC and Folder Example).',
            confirmButtonColor: '#2f3c86'
        });
        input.value = ""; // Clear the input
        return;
    }

    if (files) {
        // Reset delete flags when new files are uploaded
        document.getElementById('delete_toc').value = "0";
        document.getElementById('delete_folder').value = "0";

        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (i === 0) {
                    document.getElementById('prev-toc').src = e.target.result;
                    document.getElementById('wrapper-toc').style.display = 'block';
                } else if (i === 1) {
                    document.getElementById('prev-folder').src = e.target.result;
                    document.getElementById('wrapper-folder').style.display = 'block';
                }
            }
            reader.readAsDataURL(files[i]);
        }
    }
}

function removeImage(type) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This image will be marked for deletion.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2f3c86',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            if (type === 'toc') {
                document.getElementById('wrapper-toc').style.display = 'none';
                document.getElementById('delete_toc').value = "1"; // Mark for deletion
            } else {
                document.getElementById('wrapper-folder').style.display = 'none';
                document.getElementById('delete_folder').value = "1"; // Mark for deletion
            }
            
            // Clear input if all items are removed
            if (document.getElementById('delete_toc').value === "1" && document.getElementById('delete_folder').value === "1") {
                document.getElementById('onsite_input').value = "";
            }

            Swal.fire({
                title: 'Removed!',
                text: 'The image has been removed from the preview.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}
</script>
</body>
</html>