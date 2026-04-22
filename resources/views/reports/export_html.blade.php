<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>BU-ETEEAP Report</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    font-size: 11px;
    color: #333;
}

h1, h2, h3 {
    color: #223381;
}

/* ================= HEADER ================= */
.header {
    text-align: center;
    border-bottom: 2px solid #223381;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.header h1 {
    font-size: 15px;
    color: #000000ff;
}

.header p {
    font-size: 15px;
    color: #000000ff;
    margin: 2px 0;
}

.header h2{
    font-size: 20px;
    color: #223381;
}

.chart-card {
    border: 1px solid #ddd;
    padding: 12px;
    margin: 15px 0;
}

.summary-table,
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.summary-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.summary-table span {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #223381;
}

.data-table th {
    background: #223381;
    color: #fff;
    padding: 8px;
    font-size: 10px;
}

.data-table td {
    border: 1px solid #ddd;
    padding: 6px;
    font-size: 9px;
}

/* ===== PIE-BAR STYLE (WORD SAFE) ===== */
.pie-bar {
    margin-bottom: 8px;
}

.pie-bar-label {
    font-size: 10px;
    font-weight: bold;
    margin-bottom: 3px;
}

.pie-bar-bg {
    background: #e0e0e0;
    height: 14px;
}

.pie-bar-fill {
    height: 14px;
    color: #fff;
    font-size: 9px;
    font-weight: bold;
    text-align: right;
    padding-right: 5px;
}

/* ===== MONTHLY BAR (TABLE BASED) ===== */
.monthly-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.monthly-table td {
    padding: 4px;
    font-size: 10px;
}

.month-bar-bg {
    background: #e0e0e0;
    height: 14px;
}

.month-bar-fill {
    height: 14px;
    background: #223381;
    color: #fff;
    font-size: 9px;
    text-align: right;
    padding-right: 4px;
}
</style>
</head>

<body>

<div class="header">
    <h1>BICOL UNIVERSITY</h1>
    <p>Open University</p>
    <p>Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP)</p>

    <h2>Accomplishment Report</div>
</div>

<h2>Summary Statistics</h2>
<table class="summary-table">
<tr>
<td>Total Applicants<span>{{ $stats['total'] }}</span></td>
<td>Approved<span>{{ $stats['approved'] }}</span></td>
<td>Pending<span>{{ $stats['pending'] }}</span></td>
<td>Rejected<span>{{ $stats['rejected'] ?? 0 }}</span></td>
<td>Paid<span>{{ $stats['paid'] }}</span></td>
</tr>
</table>

<!-- ================= STATUS DISTRIBUTION ================= -->
@php
$statusData = $chartData['statusDistribution'] ?? [];
$statusTotal = array_sum($statusData);
$statusMap = [
    'approved' => ['Approved', '#25c14a'],
    'pending' => ['Pending', '#EF7631'],
    'rejected' => ['Rejected', '#e03d4d'],
    'in_review' => ['In Review', '#223381'],
];
@endphp

<div class="chart-card">
<h3>Application Status Distribution</h3>

@foreach($statusMap as $key => [$label,$color])
@php
$count = $statusData[$key] ?? 0;
$percent = $statusTotal > 0 ? round(($count/$statusTotal)*100) : 0;
@endphp
<div class="pie-bar">
<div class="pie-bar-label">{{ $label }} — {{ $count }} ({{ $percent }}%)</div>
<div class="pie-bar-bg">
<div class="pie-bar-fill" style="width: {{ $percent }}%; background: {{ $color }};"></div>
</div>
</div>
@endforeach
</div>

<!-- ================= SUBMISSION MODE ================= -->
@php
$submissionData = $chartData['submissionModes'] ?? [];
$submissionTotal = array_sum($submissionData);
@endphp

<div class="chart-card">
<h3>Submission Mode Distribution</h3>

@foreach(['online'=>'#223381','onsite'=>'#EF7631'] as $key=>$color)
@php
$count = $submissionData[$key] ?? 0;
$percent = $submissionTotal > 0 ? round(($count/$submissionTotal)*100) : 0;
@endphp
<div class="pie-bar">
<div class="pie-bar-label">{{ ucfirst($key) }} — {{ $count }} ({{ $percent }}%)</div>
<div class="pie-bar-bg">
<div class="pie-bar-fill" style="width: {{ $percent }}%; background: {{ $color }};"></div>
</div>
</div>
@endforeach
</div>

<!-- ================= MONTHLY APPLICATIONS ================= -->
@php
$monthly = $chartData['monthlyApplications'] ?? [];
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$keys = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
$max = max($monthly ?: [1]);
@endphp

<div class="chart-card">
<h3>Monthly Applications</h3>
<table class="monthly-table">
@foreach($months as $i=>$m)
@php $val = $monthly[$keys[$i]] ?? 0; @endphp
<tr>
<td width="60">{{ $m }}</td>
<td>
<div class="month-bar-bg">
<div class="month-bar-fill" style="width: {{ $max>0 ? round(($val/$max)*100) : 0 }}%;">{{ $val }}</div>
</div>
</td>
</tr>
@endforeach
</table>
</div>

<h2>Applicants per Program</h2>
<table class="data-table">
<thead>
<tr><th>Degree Program</th><th>Count</th></tr>
</thead>
<tbody>
@forelse($programs as $program=>$count)
<tr><td>{{ $program }}</td><td>{{ $count }}</td></tr>
@empty
<tr><td colspan="2">No data available</td></tr>
@endforelse
</tbody>
</table>

<h2>Detailed Applicants List</h2>
<table class="data-table">
<thead>
<tr>
<th>Name</th><th>Email</th><th>Program</th>
<th>Status</th><th>Payment</th><th>Submission</th><th>Date</th>
</tr>
</thead>
<tbody>
@forelse($applicants as $a)
<tr>
<td>{{ $a->first_name }} {{ $a->last_name }}</td>
<td>{{ $a->email }}</td>
<td>{{ $a->degree_program ?? 'N/A' }}</td>
<td>{{ $a->application_status ?? 'Pending' }}</td>
<td>{{ ucfirst($a->payment_status ?? 'Unpaid') }}</td>
<td>{{ $a->onsite_verified ? 'Onsite' : 'Online' }}</td>
<td>{{ $a->created_at?->format('Y-m-d') }}</td>
</tr>
@empty
<tr><td colspan="7">No applicants found</td></tr>
@endforelse
</tbody>
</table>

</body>
</html>