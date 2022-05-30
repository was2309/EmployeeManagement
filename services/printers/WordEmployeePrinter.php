<?php

namespace EmployeeManagement\Services\Printers;

use EmployeeManagement\Services\Printers\Interfaces\PrinterInterface;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;

class WordEmployeePrinter implements PrinterInterface
{

    public function print_document($employee, $outputdir)
    {
        $file = $employee['employee_first_name'] . $employee['employee_last_name'] . '-Employee.doc';

        $document = new PhpWord();

        $section = $document->addSection();

        $document->addTitleStyle(1, ['bold'=>true, 'name'=>'Arial', 'size'=>18], ['spaceAfter'=>Converter::pointToTwip(12)]);
        $section->addTitle( $employee['employee_first_name'] .' '. $employee['employee_last_name']);
        $section->addText('Date of birth: ' . $employee['employee_birthday'], ['size'=>12]);
        $section->addText('Department: ' . $employee['department_name'], ['size'=>12]);
        $section->addText('Department abbreviation: ' . $employee['department_name_abbreviation'], ['size'=>12]);

        $objWriter = IOFactory::createWriter($document, 'Word2007');
        $objWriter->save($outputdir . '/' .$file);

        return $file;

    }
}