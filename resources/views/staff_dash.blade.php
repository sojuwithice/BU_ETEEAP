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

    <div class="header">
        <div class="left-head">
            <img src="{{ asset('images/eteeap_logo.png') }}" alt="BU Logo">
            <h2>BU-ETEEAP</h2>
        </div>
        <div class="profile"></div>
    </div>

    <div class="main-wrapper">

        <div class="sidebar">
            <a href="#" class="nav-item active">
                <span class="material-symbols-outlined icon">home</span>
                Home
            </a>
            <a href="#" class="nav-item">
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
</body>
</html>