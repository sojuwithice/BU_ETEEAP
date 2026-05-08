<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AuditLogController extends Controller
{
    // LOAD PAGE
    public function index()
    {
        return view('audit_logs');
    }

    // FETCH DATA (AJAX)
    public function fetch(Request $request)
{
    $query = AuditLog::with('user');

    // SEARCH
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            // SEARCH SA ACTION
            $q->where('action', 'LIKE', "%{$search}%")

            // SEARCH SA DETAILS
            ->orWhere('details', 'LIKE', "%{$search}%")

            // SEARCH SA CATEGORY
            ->orWhere('category', 'LIKE', "%{$search}%")

            // SEARCH SA USER
            ->orWhereHas('user', function ($userQuery) use ($search) {

                $userQuery->where('email', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhereRaw(
                        "CONCAT(first_name, ' ', last_name) LIKE ?",
                        ["%{$search}%"]
                    );

            });

        });
    }

    // FILTER CATEGORY
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // DATE FILTER
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    // LATEST FIRST
    $logs = $query->latest()->paginate(10);

    return response()->json($logs);
}

    // EXPORT CSV
    public function export()
{
    $logs = AuditLog::with('user')->latest()->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // TITLE
$sheet->mergeCells('A1:E2');
$sheet->setCellValue('A1', 'BU ETEEAP Audit Logs');

$sheet->getStyle('A1')->applyFromArray([
    'font' => [
        'bold' => true,
        'size' => 18,
        'color' => ['rgb' => '1F1F1F'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
]);

$sheet->getRowDimension(1)->setRowHeight(30);

    // HEADERS
    $headers = ['Date', 'User', 'Category', 'Action', 'Details'];

    $sheet->fromArray($headers, null, 'A3');

    // HEADER STYLE
    $sheet->getStyle('A3:E3')->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size' => 12,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '1F4E78'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ]);

    $row = 4;

    foreach ($logs as $log) {

        $sheet->setCellValue("A{$row}",
            $log->created_at
                ? $log->created_at->format('M d, Y h:i A')
                : 'N/A'
        );

        $sheet->setCellValue("B{$row}", $log->user->email ?? 'System');
        $sheet->setCellValue("C{$row}", $log->category ?? '');
        $sheet->setCellValue("D{$row}", $log->action ?? '');
        $sheet->setCellValue("E{$row}", $log->details ?? '');

        $row++;
    }

    // AUTO SIZE COLUMNS
    foreach (range('A', 'E') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // WRAP TEXT
    $sheet->getStyle("A1:E{$row}")
        ->getAlignment()
        ->setWrapText(true);

    // VERTICAL ALIGN
    $sheet->getStyle("A1:E{$row}")
        ->getAlignment()
        ->setVertical(Alignment::VERTICAL_TOP);

    // BORDERS
    $sheet->getStyle("A1:E" . ($row - 1))
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    // ROW HEIGHT AUTO
    for ($i = 1; $i <= $row; $i++) {
        $sheet->getRowDimension($i)->setRowHeight(-1);
    }

    $filename = 'audit_logs.xlsx';

    $writer = new Xlsx($spreadsheet);

    $temp_file = tempnam(sys_get_temp_dir(), $filename);

    $writer->save($temp_file);

    return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
}
}
