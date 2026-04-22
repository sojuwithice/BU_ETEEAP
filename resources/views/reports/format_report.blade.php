<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>BU-ETEEAP Report</title>

<style>
/* ================= BASE ================= */
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
    margin: 15px;
    color: #333;
}

h1, h2, h3 {
    margin: 0;
}

.page-break {
    page-break-before: always;
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

/* ================= SUMMARY ================= */
.summary-box {
    background: #f5f5f5;
    padding: 12px;
    margin-bottom: 15px;
}

.summary-box h3 {
    font-size: 14px;
    color: #223381;
    margin-bottom: 8px;
}

.summary-table {
    width: 100%;
    border-collapse: collapse;
}

.summary-table td {
    background: #fff;
    padding: 8px;
    text-align: center;
    font-size: 10px;
}

.summary-table span {
    display: block;
    font-size: 20px;
    font-weight: bold;
    color: #223381;
}

/* ================= CHART CARDS ================= */
.chart-card {
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 15px;
}

.chart-card h3 {
    font-size: 13px;
    color: #223381;
    margin-bottom: 8px;
}

/* ================= PIE BAR ================= */
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
    border-radius: 7px;
    overflow: hidden;
}

.pie-bar-fill {
    height: 100%;
    font-size: 9px;
    color: #fff;
    font-weight: bold;
    text-align: right;
    padding-right: 5px;
}

/* ================= BAR CHART ================= */
.bar-row {
    margin-bottom: 6px;
}

.bar-label {
    font-size: 10px;
    font-weight: bold;
    margin-bottom: 2px;
}

.bar-bg {
    background: #e0e0e0;
    height: 16px;
    border-radius: 8px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: #223381;
    color: #fff;
    font-size: 9px;
    text-align: right;
    padding-right: 5px;
}

/* ================= TABLES ================= */
h2 {
    font-size: 16px;
    color: #223381;
    margin: 15px 0 8px;
}

table.data-table {
    width: 100%;
    border-collapse: collapse;
}

table.data-table th {
    background: #223381;
    color: #fff;
    font-size: 10px;
    padding: 7px;
    text-align: left;
}

table.data-table td {
    font-size: 9px;
    padding: 6px;
    border-bottom: 1px solid #ddd;
}

/* ================= FOOTER ================= */
.footer {
    text-align: center;
    font-size: 9px;
    color: #999;
    margin-top: 20px;
    border-top: 1px solid #ddd;
    padding-top: 10px;
}
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<div class="header">
    <h1>BICOL UNIVERSITY</h1>
    <p>Open University</p>
    <p>Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP)</p>

    <h2>Accomplishment Report</div>
</div>

<!-- ================= SUMMARY ================= -->
<div class="summary-box">
    <h3>Summary Statistics</h3>
    <table class="summary-table">
        <tr>
            <td>Total Applicants<span>{{ $stats['total'] }}</span></td>
            <td>Approved<span>{{ $stats['approved'] }}</span></td>
            <td>Pending<span>{{ $stats['pending'] }}</span></td>
            <td>Rejected<span>{{ $stats['rejected'] ?? 0 }}</span></td>
            <td>Paid<span>{{ $stats['paid'] }}</span></td>
        </tr>
    </table>
</div>

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
$percent = $statusTotal > 0 ? round(($count / $statusTotal) * 100) : 0;
@endphp
<div class="pie-bar">
    <div class="pie-bar-label">
        {{ $label }} — {{ $count }} ({{ $percent }}%)
    </div>
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
$percent = $submissionTotal > 0 ? round(($count / $submissionTotal) * 100) : 0;
@endphp
<div class="pie-bar">
    <div class="pie-bar-label">
        {{ ucfirst($key) }} — {{ $count }} ({{ $percent }}%)
    </div>
    <div class="pie-bar-bg">
        <div class="pie-bar-fill" style="width: {{ $percent }}%; background: {{ $color }};"></div>
    </div>
</div>
@endforeach
</div>

<!-- ================= MONTHLY BAR ================= -->
@php
$monthly = $chartData['monthlyApplications'] ?? [];
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$keys = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
$max = max($monthly ?: [1]);
@endphp

<div class="chart-card">
<h3>Monthly Applications</h3>
@foreach($months as $i=>$m)
@php $val = $monthly[$keys[$i]] ?? 0; @endphp
<div class="bar-row">
    <div class="bar-label">{{ $m }} ({{ $val }})</div>
    <div class="bar-bg">
        <div class="bar-fill" style="width: {{ $max > 0 ? round(($val/$max)*100) : 0 }}%;">
            @if($val>0){{ $val }}@endif
        </div>
    </div>
</div>
@endforeach
</div>

<div class="page-break"></div>

<!-- ================= PROGRAM TABLE ================= -->
<h2>Applicants per Program</h2>
<table class="data-table">
<thead>
<tr><th>Degree Program</th><th>Count</th></tr>
</thead>
<tbody>
@forelse($programs as $program=>$count)
<tr>
<td>{{ $program }}</td>
<td style="text-align:center">{{ $count }}</td>
</tr>
@empty
<tr><td colspan="2">No data available</td></tr>
@endforelse
</tbody>
</table>

<div class="page-break"></div>

<!-- ================= APPLICANTS ================= -->
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

<div class="footer">
This report is system-generated. For inquiries, please contact BU-ETEEAP office.
</div>

</body>
</html>