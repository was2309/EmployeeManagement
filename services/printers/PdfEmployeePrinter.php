<?php

namespace EmployeeManagement\Services\Printers;

use EmployeeManagement\Services\Printers\Interfaces\PrinterInterface;
use FPDF;
class PdfEmployeePrinter implements PrinterInterface
{

    public function print_document($employee, $outputdir)
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 16 );
        $pdf->Cell(1,10, $employee['employee_first_name'] . ' ' . $employee['employee_last_name']);

        $pdf->SetFont('Times', 'B', 12 );
        $pdf->Cell(1,30,'Birthday: ' . $employee['employee_birthday'],0,1, 'L');
        $pdf->Cell(1,35, 'Department: ' . $employee['department_name'], 0,1, 'L');
        $pdf->Cell(1,40, 'Department abbreviation: ' . $employee['department_name_abbreviation'], 0,1, 'L');

        $file_name = $outputdir . '/' . $employee['employee_first_name'] . $employee['employee_last_name'];
        $pdf->Output($file_name, 'F');
        return $employee['employee_first_name'] . $employee['employee_last_name'];

    }
}