<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/applicant_dash.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
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

        <!-- LEFT -->
        <div class="left">
            <h2>Your Progress</h2>
            <div class="progress-card">
                <p>Profile Progress</p>
                <h1>65%</h1>
                <div class="progress"><span style="width:65%"></span></div>
            </div>

            <div class="progress-card">
                <p>Documents Progress</p>
                <h1>22%</h1>
                <div class="progress"><span style="width:22%"></span></div>
            </div>

            <div class="progress-card">
                <p>Application Progress</p>
                <h1>0%</h1>
                <div class="progress"><span style="width:0%"></span></div>
            </div>
        </div>

        <div class="center">
    <div class="center-content-wrapper">
        
        <div class="center-column">
            <h2>Incomplete Requirements</h2>
            <div class="req-box">
                <div class="task-header">
                    <div class="task-badge">
                        Tasks <span class="badge-num">10</span>
                    </div>
                    <div class="message-badge">
                        Messages <span class="badge-num-gray">5</span>
                    </div>
                </div>

                <h3 class="todo-title">To do</h3>

                <div class="req-list">
                    <div class="req-header">
                        <span>Task</span>
                    </div>
                    <div class="req-item">
                        <span>Complete Profile</span>
                    </div>
                    <div class="req-item">
                        <span>Complete Profile</span>
                    </div>
                    <div class="req-item">
                        <span>Upload Docs</span>
                    </div>
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

</body>
</html>