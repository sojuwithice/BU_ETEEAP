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
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="left-head">
        <img src="{{ asset('images/eteeap_logo.png') }}">
        <h2>BU-ETEEAP</h2>
    </div>
    <div class="profile"></div>
</div>

<!-- MAIN BG -->
<div class="main-bg">

    <!-- WELCOME -->
    <div class="welcome">
        Welcome, Applicant!
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
            <img id="imagePreview" src="" alt="" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: none;">
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
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

</div>

<script>
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

    // 1. I-load ang cropped version sa dashboard pag-refresh
    const savedCropped = localStorage.getItem('profileImage');
    if (savedCropped) {
        imagePreview.src = savedCropped;
        imagePreview.style.display = 'block';
    }

    // 2. LOGIC SA EDIT BUTTON
    editBtn.addEventListener('click', function() {
        const original = localStorage.getItem('originalImage');
        
        if (original) {
            // Kung may original, i-load ang BUONG picture sa modal, hindi yung tabas na
            uploadModal.style.display = 'flex';
            croppieInstance.bind({
                url: original
            });
        } else {
            // Kung wala pa, pabuksan ang folder
            fileUpload.click();
        }
    });

    // 3. PAGKAPILI NG BAGONG FILE
    fileUpload.addEventListener('change', async function() {
        let file = this.files[0];
        if (!file) return;

        // HEIC Conversion para sa iPhone
        if (file.name.toLowerCase().endsWith('.heic')) {
            try {
                const convertedBlob = await heic2any({ blob: file, toType: "image/jpeg", quality: 0.7 });
                file = new File([convertedBlob], "original.jpg", { type: "image/jpeg" });
            } catch (err) { console.error(err); }
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const originalBase64 = e.target.result;
            
            // I-save ang BUONG picture para magamit sa future edits
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

    // 4. SAVE CROP BUTTON
    document.getElementById('saveCropBtn').addEventListener('click', function() {
        croppieInstance.result({
            type: 'base64',
            size: 'viewport',
            format: 'jpeg',
            quality: 0.8
        }).then(function(croppedBase64) {
            // I-preview sa dashboard yung tinabas na version
            imagePreview.src = croppedBase64;
            imagePreview.style.display = 'block';
            
            // I-save ang cropped version para sa mabilis na loading
            localStorage.setItem('profileImage', croppedBase64);
            
            uploadModal.style.display = 'none';
            fileUpload.value = ''; // Reset input para pwedeng piliin ulit ang same file
        });
    });

    // 5. CANCEL BUTTON
    document.getElementById('cancelBtn').addEventListener('click', () => {
        uploadModal.style.display = 'none';
        fileUpload.value = '';
    });
});
</script>

</body>
</html>