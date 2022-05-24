<?php

namespace EmployeeManagement\Services\Printers;

use EmployeeManagement\Services\Printers\Interfaces\PrinterInterface;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class WordOrderPrinter implements PrinterInterface
{

    public function print_document($order, $outputdir)
    {
        $file_title = str_replace(' ', '-', $order->get_order_number());
        $file_title = preg_replace('/[^A-Za-z0-9\-]/', '', $file_title);
        $file = $file_title.'-Order.doc';

        $document = new PhpWord();

        $section = $document->addSection();

        $section->addTitle($file_title);
        $section->addText('Order number: ' . $order->get_order_number());
        $section->addText('Order date: ' . $order->get_date_created());
        $section->addText('Status: ' . $order->get_status());
        $section->addText('Customer: ' . $order->get_billing_first_name()." ".$order->get_billing_last_name());
        $section->addText('Shiping to: ' . $order->get_shipping_address_1());
        $section->addText('Total: ' . $order->get_total()." ".$order->get_currency());

        $objWriter = IOFactory::createWriter($document, 'Word2007');
        $objWriter->save($outputdir . '/' .$file);

        return $file;
    }
}