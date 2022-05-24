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
        $pdf->Cell(-1,20, $employee['employee_birthday']);
        $pdf->Cell(1,30, $employee['department_name']);
        $pdf->Cell(-1,40, $employee['department_name_abbreviation']);

        $file_name = $outputdir . '/' . $employee['employee_first_name'].$employee['employee_last_name'];
        $pdf->Output($file_name, 'F');
        return $employee['employee_first_name'] . ' ' . $employee['employee_last_name'];
    }
}