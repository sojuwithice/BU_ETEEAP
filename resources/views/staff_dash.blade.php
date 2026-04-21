    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Dashboard - BU-ETEEAP</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
    <link rel="stylesheet" href="{{ asset('css/staff_dash.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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
            <h1 class="welcome-text">Welcome, {{ auth()->user()->first_name }}!</h1>

            <div class="cards-container">
                <div class="stat-card orange">
                    <h3>Pending Reviews</h3>
                    <p>{{ $stats['pending_reviews'] }}</p>
                </div>
                <div class="stat-card orange">
                    <h3>New Applications</h3>
                    <p>{{ $stats['new_applications'] }}</p>
                </div>
                <div class="stat-card orange">
                    <h3>Document Issues</h3>
                    <p>{{ $stats['document_issues'] }}</p>
                </div>
            </div>

            <div class="search-section-container">
                <div class="search-title">Applicant Name</div>
                <div class="search-action-bar">
                    <div class="search-box">
                        <span class="material-symbols-outlined search-icon">search</span>
                        <input type="text" id="searchInput" placeholder="Search by Name or Program">
                    </div>
                    <div class="filter-group">
                        <div class="filter-group">
                            <div class="custom-menu-dropdown" id="dropdownStatus">
                                <div class="menu-trigger" onclick="toggleMenu('optionsStatus')">
                                    <span id="labelStatus">All Status</span>
                                    <span class="material-symbols-outlined menu-icon">expand_more</span>
                                </div>
                                <div class="menu-options" id="optionsStatus">
                                    <div class="menu-item" onclick="selectMenu('Status', 'all', 'All Status')">All Status</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Not Started', 'Not Started')">Not Started</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'On Going', 'On Going')">On Going</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Completed', 'Completed')">Completed</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Approved', 'Approved')">Approved</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Pending', 'Pending Review')">Pending Review</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Rejected', 'Rejected')">Rejected</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Transmitted to Admission', 'Transmitted to Admission')">Transmitted to Admission</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Case Closed', 'Case Closed')">Case Closed</div>
                                    <div class="menu-item" onclick="selectMenu('Status', 'Will Not Push Through', 'Will Not Push Through')">Will Not Push Through</div>
                                </div>
                                <input type="hidden" id="filterStatus" value="all">
                            </div>

                            <div class="custom-menu-dropdown" id="dropdownSex">
                                <div class="menu-trigger" onclick="toggleMenu('optionsSex')">
                                    <span id="labelSex">All Sex</span>
                                    <span class="material-symbols-outlined menu-icon">expand_more</span>
                                </div>
                                <div class="menu-options" id="optionsSex">
                                    <div class="menu-item" onclick="selectMenu('Sex', 'all', 'All Sex')">All Sex</div>
                                    <div class="menu-item" onclick="selectMenu('Sex', 'Male', 'Male')">Male</div>
                                    <div class="menu-item" onclick="selectMenu('Sex', 'Female', 'Female')">Female</div>
                                </div>
                                <input type="hidden" id="filterSex" value="all">
                            </div>
                        </div>

                        <button class="reset-btn" onclick="resetAllFilters()">
                            <span class="material-symbols-outlined" style="font-size: 18px;">refresh</span> Reset
                        </button>
                    </div>

                    <div class="spacer"></div>
                    <div class="action-icons">
                        <span class="material-symbols-outlined action-icon" onclick="exportToExcel()">download</span>
                        <span class="material-symbols-outlined action-icon" onclick="printTableOnly()">print</span>
                    </div>
                </div>
            </div>

            <div class="applicant-list-container">
    <div class="table-wrapper">
        <table class="applicant-table" id="applicantTable">
            <thead>
                <tr>
                    <th>Fullname</th>
                    <th>Sex</th>
                    <th>Degree Program</th>
                    <th>Mode of Application</th>  <!-- BAGONG COLUMN -->
                    <th>Last Update</th>
                    <th>Current Status</th>
                    <th>Remarks</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody id="applicantTableBody">
                @forelse($applicants as $applicant)
                @php
                    $currentStatus = $applicant->application_status ?? 'Pending';
                    $statusClass = 'orange';
                    if($currentStatus == 'Approved') $statusClass = 'green';
                    elseif($currentStatus == 'Rejected') $statusClass = 'red';
                    elseif($currentStatus == 'Transmitted to Admission') $statusClass = 'transmitted';
                    elseif($currentStatus == 'Case Closed') $statusClass = 'closed';
                    elseif($currentStatus == 'Will Not Push Through') $statusClass = 'not-push';
                    elseif($currentStatus == 'Pending') $statusClass = 'pending';
                    elseif($currentStatus == 'Not Started') $statusClass = 'not-started';
                    elseif($currentStatus == 'On Going') $statusClass = 'ongoing';
                    elseif($currentStatus == 'Completed') $statusClass = 'completed';
                    
                    // Determine mode of application
                    $modeOfApplication = 'Online'; // Default
                    if($applicant->onsite_verified) {
                        $modeOfApplication = 'Onsite (Verified)';
                    } elseif($applicant->onsite_verification_pending) {
                        $modeOfApplication = 'Onsite (Pending)';
                    } else {
                        $modeOfApplication = 'Online';
                    }
                    
                    $modeClass = '';
                    if($modeOfApplication == 'Onsite (Verified)') $modeClass = 'mode-onsite-verified';
                    elseif($modeOfApplication == 'Onsite (Pending)') $modeClass = 'mode-onsite-pending';
                    else $modeClass = 'mode-online';
                @endphp
                <tr data-id="{{ $applicant->id }}"
                    data-fullname="{{ $applicant->first_name }} {{ $applicant->last_name }}"
                    data-sex="{{ $applicant->sex ?? 'N/A' }}"
                    data-degree="{{ $applicant->degree_program ?? 'Not specified' }}"
                    data-status="{{ $applicant->application_status ?? 'Pending' }}"
                    data-remarks="{{ $applicant->decision_notes ?? '' }}">
                    <td><b>{{ $applicant->first_name }} {{ $applicant->last_name }}</b></td>
                    <td>{{ $applicant->sex ?? 'N/A' }}</td>
                    <td><b>{{ $applicant->degree_program ?? 'Not specified' }}</b></td>
                    <td>
                        <span class="mode-badge {{ $modeClass }}">
                            <span class="material-symbols-outlined">
                                @if($modeOfApplication == 'Onsite (Verified)')
                                    verified
                                @elseif($modeOfApplication == 'Onsite (Pending)')
                                    pending_actions
                                @else
                                    computer
                                @endif
                            </span>
                            {{ $modeOfApplication }}
                        </span>
                    </td>
                    <td>{{ $applicant->last_update ? $applicant->last_update->format('m/d/Y | h:i A') : $applicant->created_at->format('m/d/Y | h:i A') }}</td>
                    <td><span class="status-badge {{ $statusClass }}">{{ $applicant->application_status ?? 'Pending' }}</span></td>
                    <td class="remarks-cell" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <span title="{{ $applicant->decision_notes ?? '' }}">{{ Str::limit($applicant->decision_notes ?? 'No remarks', 30) }}</span>
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('staff.applicant.info', $applicant->id) }}" class="btn-view">View</a>
                        <button class="btn-decision" onclick="showDecisionModal({{ $applicant->id }}, '{{ $applicant->first_name }} {{ $applicant->last_name }}', '{{ addslashes($applicant->decision_notes ?? '') }}')">
                            Decision
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 50px; color: #223381;">
                        No applicant recorded in the system.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div id="noResultsMessage" style="display:none;">
            <span class="material-symbols-outlined" style="font-size: 48px; display: block; margin-bottom: 10px;">search_off</span>
            No applicants found matching your criteria.
        </div>
    </div>

    @if(method_exists($applicants, 'links'))
    <div class="pagination-container">
        {{ $applicants->links() }}
    </div>
    @endif
</div>
        </div>
    </div>

    <!-- Applicant Details Modal -->
    <div id="applicantModal" class="modal-overlay" style="display:none;">
        <div class="modal-box" style="width: 800px; max-width: 90%; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h2>Applicant Details</h2>
                <span class="close-modal-btn" onclick="closeApplicantModal()">&times;</span>
            </div>
            <div id="applicantDetailsContent"></div>
        </div>
    </div>

    <!-- Decision Modal -->
    <div id="decisionModal" class="modal-overlay" style="display:none;">
        <div class="modal-box" style="width: 450px;">
            <div class="modal-header">
                <h2>Set Decision</h2>
                <span class="close-modal-btn" onclick="closeDecisionModal()">&times;</span>
            </div>
            <div id="decisionContent">
                <input type="hidden" id="decisionApplicantId">
                <div class="form-group">
                    <label>Applicant: <span id="decisionApplicantName"></span></label>
                </div>
                <div class="form-group">
                    <label>Status Decision</label>
                    <!-- Custom Dropdown -->
                    <div class="custom-select-dropdown" id="statusDropdown">
                        <div class="select-trigger" onclick="toggleStatusDropdown()">
                            <span id="selectedStatus">Approved</span>
                            <span class="material-symbols-outlined dropdown-arrow">expand_more</span>
                        </div>
                        <div class="select-options" id="statusOptions">
                            <div class="select-option" data-value="Approved" data-color="#10b981">
                                <span class="status-dot" style="background: #10b981;"></span> Approved
                            </div>
                            <div class="select-option" data-value="Pending" data-color="#f59e0b">
                                <span class="status-dot" style="background: #f59e0b;"></span> Pending Review
                            </div>
                            <div class="select-option" data-value="Rejected" data-color="#ef4444">
                                <span class="status-dot" style="background: #ef4444;"></span> Rejected
                            </div>
                            <div class="select-option" data-value="On Going" data-color="#f47c20">
                                <span class="status-dot" style="background: #f47c20;"></span> On Going
                            </div>
                            <div class="select-option" data-value="Transmitted to Admission" data-color="#3b82f6">
                                <span class="status-dot" style="background: #3b82f6;"></span> Transmitted to Admission
                            </div>
                            <div class="select-option" data-value="Case Closed" data-color="#6b7280">
                                <span class="status-dot" style="background: #6b7280;"></span> Case Closed
                            </div>
                            <div class="select-option" data-value="Will Not Push Through" data-color="#dc2626">
                                <span class="status-dot" style="background: #dc2626;"></span> Will Not Push Through
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="decisionStatus" value="Approved">
                </div>
                <div class="form-group">
                    <label>Remarks / Notes</label>
                    <textarea id="decisionNotes" rows="3" class="form-control" placeholder="Add remarks or notes..."></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancel" onclick="closeDecisionModal()">Cancel</button>
                    <button class="btn-save" onclick="submitDecision()">Save Decision</button>
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function showToast(message, type = 'success') {
    const toast = document.getElementById("toast");
    const icon = document.getElementById("toast-icon");
    const msg = document.getElementById("toast-message");
    
    if (!toast) {
        console.error('Toast element not found');
        return;
    }
    
    // Clear any existing timeout
    if (window.toastTimeout) {
        clearTimeout(window.toastTimeout);
    }
    
    // Set the message and icon
    msg.innerText = message;
    icon.innerText = type === 'success' ? 'check_circle' : 'error';
    
    // Remove existing classes and add new ones
    toast.classList.remove('success', 'error', 'show');
    toast.classList.add(type, 'show');
    
    // Auto hide after 3 seconds
    window.toastTimeout = setTimeout(() => {
        toast.classList.remove('show');
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

        profileWrapper.addEventListener("click", () => {
            dropdown.classList.toggle("show");
        });

        document.addEventListener("click", (e) => {
            if (!profileWrapper.contains(e.target)) {
                dropdown.classList.remove("show");
            }
        });

        // ================= FILTER FUNCTION =================
        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filterStatus = document.getElementById('filterStatus').value;
            const filterSex = document.getElementById('filterSex').value;
            
            const rows = document.querySelectorAll('#applicantTableBody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const fullname = row.getAttribute('data-fullname')?.toLowerCase() || '';
                const degree = row.getAttribute('data-degree')?.toLowerCase() || '';
                const status = row.getAttribute('data-status') || '';
                const sex = row.getAttribute('data-sex') || '';
                
                const matchesSearch = searchTerm === '' || fullname.includes(searchTerm) || degree.includes(searchTerm) || status.toLowerCase().includes(searchTerm);
                const matchesStatus = filterStatus === 'all' || status === filterStatus;
                const matchesSex = filterSex === 'all' || sex === filterSex;
                
                if (matchesSearch && matchesStatus && matchesSex) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            const noResultsMsg = document.getElementById('noResultsMessage');
            if (visibleCount === 0) {
                noResultsMsg.style.display = 'block';
            } else {
                noResultsMsg.style.display = 'none';
            }
        }
        
        // Real-time search
        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('filterStatus').addEventListener('change', filterTable);
        document.getElementById('filterSex').addEventListener('change', filterTable);
        
        function resetAllFilters() {
            Swal.fire({
                title: 'Reset Filters?',
                text: 'This will reset all search and filter settings.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#223381',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Reset',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('searchInput').value = '';
                    document.getElementById('filterStatus').value = 'all';
                    document.getElementById('filterSex').value = 'all';
                    filterTable();
                    showToast("All filters reset", "success");
                }
            });
        }
        
        // Export to Excel
        function exportToExcel() {
            let csv = [];
            const rows = document.querySelectorAll('#applicantTableBody tr');
            
            csv.push(['Fullname', 'Sex', 'Degree Program', 'Last Update', 'Current Status', 'Remarks'].join(','));
            
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const remarks = row.querySelector('.remarks-cell span')?.getAttribute('title') || '';
                    const rowData = [
                        row.cells[0].innerText.replace(/,/g, ''),
                        row.cells[1].innerText,
                        row.cells[2].innerText.replace(/,/g, ''),
                        row.cells[3].innerText,
                        row.cells[4].innerText,
                        remarks.replace(/,/g, '')
                    ];
                    csv.push(rowData.join(','));
                }
            });
            
            const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `applicants_list_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            URL.revokeObjectURL(url);
            showToast("Export successful", "success");
        }
        
        // Print only the table
        function printTableOnly() {
            const table = document.getElementById('applicantTable').cloneNode(true);
            
            // Remove action column from print
            const actionHeaders = table.querySelectorAll('th');
            if (actionHeaders[6]) actionHeaders[6].remove();
            
            const actionCells = table.querySelectorAll('.action-buttons');
            actionCells.forEach(cell => {
                const parentRow = cell.parentNode;
                if (parentRow) parentRow.removeChild(cell);
            });
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Applicants List - BU-ETEEAP</title>
                    <style>
                        body {
                            font-family: 'Raleway', sans-serif;
                            padding: 20px;
                            margin: 0;
                        }
                        .print-header {
                            text-align: center;
                            margin-bottom: 30px;
                        }
                        .print-header h1 {
                            color: #223381;
                            margin: 0;
                            font-size: 24px;
                        }
                        .print-header p {
                            color: #666;
                            margin: 5px 0;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 10px;
                            text-align: left;
                        }
                        th {
                            background-color: #223381;
                            color: white;
                            font-weight: 700;
                        }
                        .status-badge {
                            display: inline-block;
                            padding: 4px 10px;
                            border-radius: 12px;
                            font-size: 10px;
                            font-weight: 800;
                            background: #f47c20;
                            color: white;
                        }
                        .print-footer {
                            text-align: center;
                            margin-top: 30px;
                            font-size: 12px;
                            color: #999;
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>BU-ETEEAP</h1>
                        <h2>Applicants List</h2>
                        <p>Generated on: ${new Date().toLocaleString()}</p>
                    </div>
                    ${table.outerHTML}
                    <div class="print-footer">
                        <p>This is an official document generated from BU-ETEEAP Staff Dashboard</p>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
            showToast("Print preview opened", "success");
        }

        // ================= VIEW APPLICANT DETAILS =================
        function viewApplicant(id) {
            const modal = document.getElementById('applicantModal');
            const content = document.getElementById('applicantDetailsContent');
            
            content.innerHTML = '<div style="text-align:center; padding:40px;">Loading...</div>';
            modal.style.display = 'flex';
            
            fetch(`/staff/applicant/${id}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const applicant = data.applicant;
                    const documents = data.documents || [];
                    
                    let documentsHtml = '';
                    if (documents.length > 0) {
                        documentsHtml = `
                            <div class="form-group">
                                <label>Uploaded Documents</label>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <table style="width:100%; border-collapse: collapse;">
                                        <thead><tr style="background:#f5f5f5;">
                                            <th style="padding:8px;">Document</th>
                                            <th style="padding:8px;">Upload Date</th>
                                            <th style="padding:8px;">Status</th>
                                            <th style="padding:8px;">Action</th>
                                        </tr></thead>
                                        <tbody>
                                            ${documents.map(doc => `
                                                <tr>
                                                    <td style="padding:8px;">${doc.name}</td>
                                                    <td style="padding:8px;">${doc.upload_date}</td>
                                                    <td style="padding:8px;"><span class="status-badge orange">${doc.status}</span></td>
                                                    <td style="padding:8px;"><button class="btn-view" onclick="window.open('${doc.file_path}')">View</button></td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    } else {
                        documentsHtml = `<div class="form-group"><label>Uploaded Documents</label><p>No documents uploaded yet.</p></div>`;
                    }
                    
                    content.innerHTML = `
                        <div class="profile-upload-section" style="margin-bottom:20px;">
                            <div class="avatar-wrapper" style="width:100px; height:100px; margin:0 auto;">
                                <img src="${applicant.profile_image || '{{ asset('images/default-profile.png') }}'}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                            </div>
                        </div>
                        <div class="form-group"><label>Full Name</label><p><strong>${applicant.first_name} ${applicant.middle_name ? applicant.middle_name + ' ' : ''}${applicant.last_name}</strong></p></div>
                        <div class="form-group"><label>Email</label><p>${applicant.email}</p></div>
                        <div class="form-group"><label>Sex</label><p>${applicant.sex}</p></div>
                        <div class="form-group"><label>Birth Date</label><p>${applicant.birthdate}</p></div>
                        <div class="form-group"><label>Degree Program</label><p><strong>${applicant.degree_program}</strong></p></div>
                        <div class="form-group"><label>Application Date</label><p>${applicant.created_at}</p></div>
                        <div class="form-group"><label>Remarks</label><p>${applicant.decision_notes || 'No remarks'}</p></div>
                        ${documentsHtml}
                        <div class="form-group"><label>Current Status</label><p><span class="status-badge orange">${applicant.status}</span></p></div>
                    `;
                } else {
                    content.innerHTML = '<div style="text-align:center; padding:40px; color:red;">Error loading applicant details</div>';
                }
            }).catch(error => {
                content.innerHTML = '<div style="text-align:center; padding:40px; color:red;">Failed to load data</div>';
            });
        }
        
        function closeApplicantModal() {
            document.getElementById('applicantModal').style.display = 'none';
        }
        
        // ================= DECISION MODAL =================
        let currentApplicantId = null;
        
        function showDecisionModal(id, name, remarks) {
    document.getElementById('decisionApplicantId').value = id;
    document.getElementById('decisionApplicantName').innerText = name;
    document.getElementById('decisionNotes').value = remarks === 'null' ? '' : remarks;
    
    // Kunin ang current status mula sa row at i-set sa dropdown
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const currentStatus = row.getAttribute('data-status');
    if (currentStatus) {
        document.getElementById('selectedStatus').innerText = currentStatus;
        document.getElementById('decisionStatus').value = currentStatus;
    }

    document.getElementById('decisionModal').style.display = 'flex';
}
        
        function closeDecisionModal() {
            document.getElementById('decisionModal').style.display = 'none';
            currentApplicantId = null;
        }
        
        function submitDecision() {
    const id = document.getElementById('decisionApplicantId').value;
    const status = document.getElementById('decisionStatus').value;
    const notes = document.getElementById('decisionNotes').value;

    // 1. ISARA AGAD ang decision modal
    closeDecisionModal();

    // 2. MAGTANONG NG CONFIRMATION
    Swal.fire({
        title: 'Confirm Decision?',
        text: `Set status to "${status}" for this applicant?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#223381',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Save'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            // 3. AJAX CALL - FIXED URL
            fetch(`/staff/applicant/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: status,
                    decision: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close(); // Close the loading Swal
                if (data.success) {
                    // 4. UPDATE UI MANUALLY
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        // Update status badge
                        const badge = row.querySelector('.status-badge');
                        badge.innerText = status;
                        
                        // Update badge class for color
                        let statusClass = 'orange';
                        if (status === 'Approved') statusClass = 'green';
                        else if (status === 'Rejected') statusClass = 'red';
                        else if (status === 'Transmitted to Admission') statusClass = 'transmitted';
                        else if (status === 'Case Closed') statusClass = 'closed';
                        else if (status === 'Will Not Push Through') statusClass = 'not-push';
                        else if (status === 'Pending') statusClass = 'pending';
                        else if (status === 'Not Started') statusClass = 'not-started';
                        else if (status === 'On Going') statusClass = 'ongoing';
                        else if (status === 'Completed') statusClass = 'completed';
                        
                        badge.className = `status-badge ${statusClass}`;
                        
                        // Update remarks
                        const remarksSpan = row.querySelector('.remarks-cell span');
                        const displayNotes = notes && notes !== 'null' ? notes : '';
                        remarksSpan.innerText = displayNotes.length > 30 ? displayNotes.substring(0, 30) + '...' : (displayNotes || 'No remarks');
                        remarksSpan.title = displayNotes || '';
                        
                        // Update data attributes
                        row.setAttribute('data-status', status);
                        row.setAttribute('data-remarks', displayNotes);
                    }
                    // 5. TOAST NOTIFICATION
                    showToast("Decision saved successfully!", "success");
                } else {
                    showToast(data.message || "Failed to update status", "error");
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                showToast("Failed to save decision", "error");
            });
        }
    });
}

        // ================= CUSTOM DROPDOWN FOR DECISION MODAL =================
        function toggleStatusDropdown() {
            const dropdown = document.getElementById('statusDropdown');
            if (dropdown) dropdown.classList.toggle('open');
        }

        function selectStatus(value, element) {
            // Update selected text
            const selectedSpan = document.getElementById('selectedStatus');
            if (selectedSpan) {
                selectedSpan.innerText = element ? element.innerText.trim() : value;
            }
            
            // Update hidden input
            const statusInput = document.getElementById('decisionStatus');
            if (statusInput) statusInput.value = value;
            
            // Remove selected class from all options
            document.querySelectorAll('.select-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            if (element) element.classList.add('selected');
            
            // Close dropdown
            const dropdown = document.getElementById('statusDropdown');
            if (dropdown) dropdown.classList.remove('open');
        }

        // Initialize the dropdown options with click handlers
        function initStatusDropdown() {
            const options = document.querySelectorAll('.select-option');
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    selectStatus(value, this);
                });
            });
        }

        // ================= ACCOUNT MODAL LOGIC =================
        function openAccountModal() { document.getElementById("accountModal").classList.add("show"); }
        function showChangeSection() { document.getElementById("changeBtn").style.display = "none"; document.getElementById("changeSection").style.display = "flex"; }
        function closeAccountModal() {
            const modal = document.getElementById("accountModal");
            modal.classList.remove("show");
            setTimeout(() => {
                document.getElementById("changeBtn").style.display = "block";
                document.getElementById("changeSection").style.display = "none";
                document.getElementById("newPassword").value = "";
                document.getElementById("confirmPassword").value = "";
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
            
            if (!newPass || !confirmPass) {
                showToast("Please fill all fields", "error");
                return;
            }
            if (newPass !== confirmPass) {
                showToast("Passwords do not match", "error");
                return;
            }
            if (newPass.length < 6) {
                showToast("Password must be at least 6 characters", "error");
                return;
            }
            
            Swal.fire({
                title: 'Change Password?',
                text: 'Are you sure you want to change your password?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#223381',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Change',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Updating...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    
                    try {
                        const response = await fetch("/update-password", {
                            method: "POST",
                            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                            body: JSON.stringify({ password: newPass, password_confirmation: confirmPass })
                        });
                        const data = await response.json();
                        Swal.close();
                        if (response.ok) {
                            document.getElementById("currentPassword").value = newPass;
                            showToast("Password updated successfully!", "success");
                            closeAccountModal();
                        } else {
                            showToast(data.message || "Invalid password requirements", "error");
                        }
                    } catch (error) {
                        Swal.close();
                        showToast("Server connection failed", "error");
                    }
                }
            });
        }
        
        window.onclick = function(event) {
            const applicantModal = document.getElementById('applicantModal');
            const decisionModal = document.getElementById('decisionModal');
            if (event.target === applicantModal) closeApplicantModal();
            if (event.target === decisionModal) closeDecisionModal();
        }

        function toggleMenu(menuId) {
            document.querySelectorAll('.menu-options').forEach(menu => {
                if(menu.id !== menuId) menu.classList.remove('active');
            });
            document.getElementById(menuId).classList.toggle('active');
        }

        function selectMenu(type, value, text) {
            document.getElementById('label' + type).innerText = text;
            
            const hiddenInput = document.getElementById('filter' + type);
            hiddenInput.value = value;
            
            document.getElementById('options' + type).classList.remove('active');
            
            if (typeof filterTable === "function") {
                filterTable(); 
            }
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-menu-dropdown')) {
                document.querySelectorAll('.menu-options').forEach(menu => {
                    menu.classList.remove('active');
                });
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('statusDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // ================= INITIALIZE EVERYTHING =================
        document.addEventListener("DOMContentLoaded", function() {
            initStatusDropdown();
        });
    </script>
    </body>
    </html>