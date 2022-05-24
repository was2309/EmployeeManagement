<?php

namespace EmployeeManagement\Services\Printers\Interfaces;

interface PrinterInterface
{
    public function print_document($document, $outputdir);
}