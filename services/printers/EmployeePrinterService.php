<?php

namespace EmployeeManagement\Services\Printers;

use Exception;



class EmployeePrinterService
{
    private $output_dir;

    public function __construct(){
        $this->output_dir = plugin_dir_path(__FILE__) . '../../temp-files';
    }

    private function get_printer($format){

        $printer = null;

        switch ($format){
            case 'word':
                $printer = new WordEmployeePrinter();
                break;
            case 'pdf':
                $printer = new PdfEmployeePrinter();
                break;
            case 'word-order':
                $printer = new WordOrderPrinter();
                break;
            default:
                throw new Exception('Invalid printing format!');
        }
        return $printer;
    }

    public function print_document($format, $document){
        $printer = $this->get_printer($format);

        $file = $printer->print_document($document, $this->output_dir);
        return $file;
    }

    public function can_user_print_order(){

        $user = wp_get_current_user();

        foreach ($user->roles as $role){
            if('administrator'===$role || 'shop_manager'==$role){
                return true;
            }
        }
        return false;

    }

}