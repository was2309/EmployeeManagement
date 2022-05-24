<?php

namespace EmployeeManagement\Services\Printers;

use EmployeeManagement\Services\Printers\Interfaces\PrinterInterface;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class WordEmployeePrinter implements PrinterInterface
{

    public function print_document($employee, $outputdir)
    {
        $file = $employee['employee_first_name'] . $employee['employee_last_name'] . '-Employee.doc';

        $document = new PhpWord();

        $section = $document->addSection();

        $section->addTitle( $employee['employee_first_name'] .' '. $employee['employee_last_name']);
        $section->addText('Date of birth: ' . $employee['employee_birthday']);
        $section->addText('Department: ' . $employee['department_name']);
        $section->addText('Department abbreviation: ' . $employee['department_name_abbreviation']);

        $objWriter = IOFactory::createWriter($document, 'Word2007');
        $objWriter->save($outputdir . '/' .$file);

        return $file;

    }
}