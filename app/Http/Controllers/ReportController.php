<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DocumentUpload;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Cell;

class ReportController extends Controller
{
    public function getReportsData(Request $request)
    {
        try {
            \Log::info('Report data requested', $request->all());

            $query = User::query()
                ->whereIn('role', ['applicant', 'student'])
                ->select(
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'degree_program',
                    'application_status',
                    'payment_status',
                    'created_at',
                    'onsite_verified'
                );

            if ($request->date_range == 'month') {
                $query->whereMonth('created_at', now()->month);
            } elseif ($request->date_range == 'quarter') {
                $query->whereBetween('created_at', [now()->subMonths(3), now()]);
            } elseif ($request->date_range == 'year') {
                $query->whereYear('created_at', now()->year);
            }

            if ($request->status && $request->status != 'all') {
                $statusMap = [
                    'approved' => 'Approved',
                    'pending' => 'Pending', 
                    'rejected' => 'Rejected',
                    'in_review' => 'In Review'
                ];
                $dbStatus = $statusMap[$request->status] ?? $request->status;
                $query->where('application_status', $dbStatus);
            }

            if ($request->sort_by == 'date_desc') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort_by == 'date_asc') {
                $query->orderBy('created_at', 'asc');
            } elseif ($request->sort_by == 'name') {
                $query->orderBy('last_name')->orderBy('first_name');
            }

            $applicants = $query->get();

            $totalRequirements = Requirement::count();
            if ($totalRequirements == 0) $totalRequirements = 1;

            $applicantsData = $applicants->map(function ($applicant) use ($totalRequirements) {
                $documentsCount = DocumentUpload::where('user_id', $applicant->id)->count();
                return [
                    'id' => $applicant->id,
                    'name' => trim(($applicant->first_name ?? '') . ' ' . ($applicant->last_name ?? '')),
                    'degree_program' => $applicant->degree_program ?? 'N/A',
                    'application_status' => $applicant->application_status ?? 'Pending',
                    'payment_status' => strtoupper($applicant->payment_status ?? 'unpaid'),
                    'documents_count' => $documentsCount,
                    'total_requirements' => $totalRequirements,
                    'created_at' => $applicant->created_at?->toISOString() ?? now()->toISOString()
                ];
            });

            $stats = [
                'total' => $applicants->count(),
                'approved' => $applicants->where('application_status', 'Approved')->count(),
                'pending' => $applicants->where('application_status', 'Pending')->count(),
                'rejected' => $applicants->where('application_status', 'Rejected')->count(),
                'paid' => $applicants->where('payment_status', 'paid')->count(),
            ];

            $chartData = [
                'statusDistribution' => [
                    'approved' => $applicants->where('application_status', 'Approved')->count(),
                    'pending' => $applicants->where('application_status', 'Pending')->count(),
                    'rejected' => $applicants->where('application_status', 'Rejected')->count(),
                    'in_review' => $applicants->where('application_status', 'In Review')->count(),
                ],
                'monthlyApplications' => $this->getMonthlyDataForChart($request),
                'programs' => $this->getProgramCounts($applicants),
                'submissionModes' => $this->getSubmissionModes($applicants)
            ];

            $programData = [
                'programs' => $this->getProgramCounts($applicants),
                'submissionModesByProgram' => $this->getSubmissionModesByProgram($applicants),
                'submissionModes' => $this->getSubmissionModes($applicants)
            ];

            return response()->json([
                'success' => true,
                'applicants' => $applicantsData,
                'stats' => $stats,
                'chartData' => $chartData,
                'programData' => $programData
            ]);

        } catch (\Exception $e) {
            \Log::error('Report Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getMonthlyDataForChart($request)
    {
        $monthlyData = [
            'jan' => 0, 'feb' => 0, 'mar' => 0, 'apr' => 0, 'may' => 0, 'jun' => 0,
            'jul' => 0, 'aug' => 0, 'sep' => 0, 'oct' => 0, 'nov' => 0, 'dec' => 0
        ];

        $query = User::whereIn('role', ['applicant', 'student']);

        if ($request->date_range == 'year') {
            $query->whereYear('created_at', now()->year);
        } elseif ($request->date_range == 'month') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($request->date_range == 'quarter') {
            $query->whereBetween('created_at', [now()->subMonths(3), now()]);
        }

        if ($request->status && $request->status != 'all') {
            $query->where('application_status', $request->status);
        }

        $results = $query
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->get();

        $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];

        foreach ($results as $row) {
            if ($row->month >= 1 && $row->month <= 12) {
                $monthlyData[$months[$row->month - 1]] = $row->total;
            }
        }

        return $monthlyData;
    }

    private function getProgramCounts($applicants)
    {
        $counts = [];
        foreach ($applicants as $applicant) {
            $program = $applicant->degree_program ?? 'Not Specified';
            if (empty($program) || $program == '') {
                $program = 'Not Specified';
            }
            $counts[$program] = ($counts[$program] ?? 0) + 1;
        }
        arsort($counts);
        return $counts;
    }

    private function getSubmissionModes($applicants)
    {
        $online = 0;
        $onsite = 0;
        
        foreach ($applicants as $applicant) {
            $isOnsite = $applicant->onsite_verified ?? false;
            if ($isOnsite) {
                $onsite++;
            } else {
                $online++;
            }
        }
        
        return ['online' => $online, 'onsite' => $onsite];
    }

    private function getSubmissionModesByProgram($applicants)
    {
        $modes = [];
        
        foreach ($applicants as $applicant) {
            $program = $applicant->degree_program ?? 'Not Specified';
            if (empty($program) || $program == '') {
                $program = 'Not Specified';
            }
            
            if (!isset($modes[$program])) {
                $modes[$program] = ['online' => 0, 'onsite' => 0];
            }
            
            $isOnsite = $applicant->onsite_verified ?? false;
            if ($isOnsite) {
                $modes[$program]['onsite']++;
            } else {
                $modes[$program]['online']++;
            }
        }
        
        return $modes;
    }

    // ================= EXPORT =================
    public function exportReport(Request $request)
    {
        try {
            $format = $request->format;
            $dateRange = $request->date_range;
            $status = $request->status;
            $sortBy = $request->sort_by;

            $query = User::whereIn('role', ['applicant', 'student'])
                ->select(
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'degree_program',
                    'application_status',
                    'payment_status',
                    'created_at',
                    'onsite_verified'
                );

            if ($dateRange == 'month') {
                $query->whereMonth('created_at', now()->month);
            } elseif ($dateRange == 'quarter') {
                $query->whereBetween('created_at', [now()->subMonths(3), now()]);
            } elseif ($dateRange == 'year') {
                $query->whereYear('created_at', now()->year);
            }

            if ($status && $status != 'all') {
                $statusMap = [
                    'approved' => 'Approved',
                    'pending' => 'Pending',
                    'rejected' => 'Rejected',
                    'in_review' => 'In Review'
                ];
                $dbStatus = $statusMap[$status] ?? $status;
                $query->where('application_status', $dbStatus);
            }

            if ($sortBy == 'date_desc') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sortBy == 'date_asc') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sortBy == 'name') {
                $query->orderBy('last_name')->orderBy('first_name');
            }

            $applicants = $query->get();

            $stats = [
                'total' => $applicants->count(),
                'approved' => $applicants->where('application_status', 'Approved')->count(),
                'pending' => $applicants->where('application_status', 'Pending')->count(),
                'rejected' => $applicants->where('application_status', 'Rejected')->count(),
                'paid' => $applicants->where('payment_status', 'paid')->count(),
            ];

            $programCounts = $this->getProgramCounts($applicants);
            
            $chartData = [
                'statusDistribution' => [
                    'approved' => $stats['approved'],
                    'pending' => $stats['pending'],
                    'rejected' => $stats['rejected'],
                    'in_review' => 0,
                ],
                'monthlyApplications' => $this->getMonthlyDataForChart($request),
                'programs' => $programCounts,
                'submissionModes' => $this->getSubmissionModes($applicants)
            ];

            if ($format == 'CSV') {
                return $this->exportCSV($applicants, $stats, $programCounts);
            } elseif ($format == 'DOCX') {
                return $this->exportWord($applicants, $stats, $programCounts, $chartData);
            } else {
                return $this->exportPDF($applicants, $stats, $programCounts, $chartData);
            }

        } catch (\Exception $e) {
            \Log::error('Export Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ================= PDF EXPORT =================
private function exportPDF($applicants, $stats, $programCounts, $chartData)
{
    $data = [
        'title' => 'BU-ETEEAP Reports',
        'date' => now()->format('F d, Y'),
        'stats' => $stats,
        'programs' => $programCounts,
        'applicants' => $applicants,
        'chartData' => $chartData,
        'generated_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name
    ];
    
    $pdf = Pdf::loadView('reports.format_report', $data);
    $pdf->setPaper('A4', 'portrait');  // Changed from landscape to portrait
    
    return $pdf->download('eteeap_report_' . date('Y-m-d') . '.pdf');
}
    // ================= CSV EXPORT =================
    private function exportCSV($applicants, $stats, $programCounts)
    {
        $filename = "eteeap_report_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w');
        fputs($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['=== BU-ETEEAP REPORT SUMMARY ===']);
        fputcsv($handle, ['Generated Date:', now()->format('F d, Y H:i:s')]);
        fputcsv($handle, ['Total Applicants:', $stats['total']]);
        fputcsv($handle, ['Approved:', $stats['approved']]);
        fputcsv($handle, ['Pending:', $stats['pending']]);
        fputcsv($handle, ['Rejected:', $stats['rejected']]);
        fputcsv($handle, ['Paid:', $stats['paid']]);
        fputcsv($handle, []);
        
        fputcsv($handle, ['=== APPLICANTS PER PROGRAM ===']);
        fputcsv($handle, ['Program', 'Count']);
        foreach ($programCounts as $program => $count) {
            fputcsv($handle, [$program, $count]);
        }
        fputcsv($handle, []);
        
        fputcsv($handle, ['=== DETAILED APPLICANTS LIST ===']);
        fputcsv($handle, ['Name', 'Email', 'Degree Program', 'Application Status', 'Payment Status', 'Submission Mode', 'Date Applied']);

        foreach ($applicants as $a) {
            $submissionMode = ($a->onsite_verified ?? false) ? 'Onsite' : 'Online';
            fputcsv($handle, [
                trim(($a->first_name ?? '') . ' ' . ($a->last_name ?? '')),
                $a->email,
                $a->degree_program ?? 'N/A',
                $a->application_status ?? 'Pending',
                ucfirst($a->payment_status ?? 'Unpaid'),
                $submissionMode,
                $a->created_at?->format('Y-m-d')
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // ================= WORD EXPORT with Charts =================
    private function exportWord($applicants, $stats, $programCounts, $chartData)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::EN_US));
        
        $section = $phpWord->addSection(['orientation' => 'portrait']);

        // ================= HEADER (Centered) =================
    $headerTitle = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $headerTitle->addText('BICOL UNIVERSITY', ['bold' => true, 'size' => 14]);
    
    $section->addTextBreak(1);
    
    $openUniv = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $openUniv->addText('Open University', ['size' => 12]);
    
    $section->addTextBreak(1);
    
    $eteeap = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $eteeap->addText('Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP)', ['size' => 11]);
    
    $section->addTextBreak(1);
    
    $reportTitle = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $reportTitle->addText('Accomplishment Report', ['bold' => true, 'size' => 18, 'color' => '223381']);
    
    $section->addTextBreak(1);
    

        // Summary Statistics Table
        $section->addTitle('Summary Statistics', 2);
        $summaryTable = $section->addTable(['borderSize' => 1, 'borderColor' => 'cccccc']);
        $summaryTable->addRow();
        $summaryTable->addCell(4000)->addText('Total Applicants');
        $summaryTable->addCell(2000)->addText((string)$stats['total']);
        $summaryTable->addRow();
        $summaryTable->addCell(4000)->addText('Approved');
        $summaryTable->addCell(2000)->addText((string)$stats['approved']);
        $summaryTable->addRow();
        $summaryTable->addCell(4000)->addText('Pending');
        $summaryTable->addCell(2000)->addText((string)$stats['pending']);
        $summaryTable->addRow();
        $summaryTable->addCell(4000)->addText('Rejected');
        $summaryTable->addCell(2000)->addText((string)$stats['rejected']);
        $summaryTable->addRow();
        $summaryTable->addCell(4000)->addText('Paid');
        $summaryTable->addCell(2000)->addText((string)$stats['paid']);
        
        $section->addTextBreak(1);

        // Application Status Distribution (with visual bars)
        $section->addTitle('Application Status Distribution', 2);
        $statusData = $chartData['statusDistribution'];
        $maxStatus = max($statusData) ?: 1;
        
        $statusColors = [
            'approved' => '25c14a',
            'pending' => 'EF7631',
            'rejected' => 'e03d4d',
            'in_review' => '223381'
        ];
        
        foreach ($statusData as $status => $count) {
            $percentage = ($count / $maxStatus) * 100;
            $barWidth = max(10, $percentage);
            
            $section->addText(ucfirst($status) . ': ' . $count);
            // Add a simple text-based bar
            $bar = str_repeat('█', round($percentage / 5));
            $section->addText($bar . ' ' . $percentage . '%');
        }
        $section->addTextBreak(1);

        // Submission Mode Distribution
        $section->addTitle('Submission Mode Distribution', 2);
        $submissionData = $chartData['submissionModes'];
        $maxSub = max($submissionData) ?: 1;
        
        foreach ($submissionData as $mode => $count) {
            $percentage = ($count / $maxSub) * 100;
            $bar = str_repeat('█', round($percentage / 5));
            $section->addText(ucfirst($mode) . ': ' . $count);
            $section->addText($bar . ' ' . $percentage . '%');
        }
        $section->addTextBreak(1);

        // Monthly Applications
        $section->addTitle('Monthly Applications', 2);
        $monthlyData = $chartData['monthlyApplications'];
        $maxMonthly = max($monthlyData) ?: 1;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        foreach ($months as $index => $month) {
            $count = $monthlyData[strtolower($month)];
            if ($count > 0) {
                $percentage = ($count / $maxMonthly) * 100;
                $bar = str_repeat('█', round($percentage / 5));
                $section->addText($month . ': ' . $count);
                $section->addText($bar . ' ' . $percentage . '%');
            }
        }
        $section->addTextBreak(1);

        // Program Summary Table
        $section->addTitle('Applicants per Program', 2);
        $programTable = $section->addTable(['borderSize' => 1, 'borderColor' => 'cccccc']);
        $programTable->addRow();
        $programTable->addCell(5000)->addText('Degree Program');
        $programTable->addCell(2000)->addText('Count');
        
        foreach ($programCounts as $program => $count) {
            $programTable->addRow();
            $programTable->addCell(5000)->addText($program);
            $programTable->addCell(2000)->addText((string)$count);
        }
        $section->addTextBreak(1);

        // Detailed Applicants List Table
        $section->addTitle('Detailed Applicants List', 2);
        $table = $section->addTable(['borderSize' => 1, 'borderColor' => 'cccccc']);
        
        $table->addRow();
        $table->addCell(3000)->addText('Name');
        $table->addCell(4000)->addText('Email');
        $table->addCell(3000)->addText('Degree Program');
        $table->addCell(2000)->addText('Status');
        $table->addCell(2000)->addText('Payment');
        $table->addCell(2000)->addText('Submission');
        $table->addCell(2000)->addText('Date');

        foreach ($applicants as $a) {
            $submissionMode = ($a->onsite_verified ?? false) ? 'Onsite' : 'Online';
            $table->addRow();
            $table->addCell(3000)->addText(trim(($a->first_name ?? '') . ' ' . ($a->last_name ?? '')));
            $table->addCell(4000)->addText($a->email);
            $table->addCell(3000)->addText($a->degree_program ?? 'N/A');
            $table->addCell(2000)->addText($a->application_status ?? 'Pending');
            $table->addCell(2000)->addText(ucfirst($a->payment_status ?? 'Unpaid'));
            $table->addCell(2000)->addText($submissionMode);
            $table->addCell(2000)->addText($a->created_at?->format('Y-m-d'));
        }

        $filename = storage_path('app/eteeap_report_' . date('Y-m-d') . '.docx');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}