<?php

namespace EmployeeManagement\Controllers;

use EmployeeManagement\Services\EmployeeDepartmentService;
use EmployeeManagement\Services\EmployeeService;
use EmployeeManagement\Services\Printers\EmployeePrinterService;


class EmployeeController extends BaseController
{

    private $employee_service;
    private $employee_department_service;
    private $employee_printer_service;

    public function __construct()
    {
        $this->employee_service = new EmployeeService();
        $this->employee_department_service = new EmployeeDepartmentService();
        $this->employee_printer_service = new EmployeePrinterService();
    }

    public function handle_action($action){
        switch ($action){
            case 'save':
                $id = $this->save_employee();
                $url = 'admin.php?page=employee';

                if($id){
                    $url = 'admin.php?page=employeeview&employee_id=' . $id;
                }
                wp_redirect(admin_url($url));
                break;
            case 'delete':
                $this->delete_employee();
                wp_redirect(admin_url('admin.php?page=employees'));
                break;
            case 'print':
                $this->print_employee();
                break;
            case 'print-order':
                $this->print_order();
                break;
            case 'order-information':
                $this->get_order_information();
                break;
            default: return;
        }
    }

    private function save_employee(){
        $id = '';

        $employee = $this ->validate_employee();

        if(!empty($_POST['employee_id'])){
            $id = esc_html($_POST['employee_id']);
            $result = $this->employee_service->update_employee($id, $employee);
        }else{
            $result = $this->employee_service->save_employee($employee);
        }

        if($result){
            $id = $result;
        }

        return $id;
    }

    private function delete_employee(){
        $id = empty($_POST['employee_id']) ? '' : esc_html($_POST['employee_id']);
        $this->employee_service->delete_employee($id);
    }

    private function findAll(){
        return $this->employee_service->get_employee_data_for_list_table();
    }

    private function validate_employee()
    {
        if(empty($_POST['employee_first_name'])){
            return false;
        }

        if(empty($_POST['employee_last_name'])){
            return false;
        }

        if(empty($_POST['employee_birthday'])){
            return false;
        }

        if(empty($_POST['department_id'])){
            return false;
        }

        $department_id = esc_html($_POST['department_id']);

        $department = EmployeeDepartmentService::get_department_by_id($department_id);

        if(!$department){
            return false;
        }

        return [
            'employee_first_name' => esc_html($_POST['employee_first_name']),
            'employee_last_name' => esc_html($_POST['employee_last_name']),
            'employee_birthday' => esc_html($_POST['employee_birthday']),
            'employee_department_id' => $department_id
        ];
    }


    private function get_order_information() {
        if (empty($_REQUEST['order_id']))
            return;

        $order_id = esc_html($_REQUEST['order_id']);
        $order = wc_get_order($order_id);

        echo json_encode($order);

    }

    private function print_employee(){
        if(empty($_POST['printer']) || empty($_POST['employee_id'])){
            return;
        }

        $format = esc_html($_POST['printer']);

        $employee = $this->employee_service->find_employee_by_id(esc_html($_POST['employee_id']));
        if(!$employee){
            return;
        }

        try{
            $file = $this->employee_printer_service->print_document($format, $employee);

            $file_path = plugin_dir_path(__FILE__) . '../temp-files/' . $file;

            $this->download_file($file_path);
        } catch (Exception $e) {

            return;
        }
    }

    private function print_order() {

        if (!$this->employee_print_service->can_user_print_order()) {

            return;
        }

        if (empty($_REQUEST['printer']) ||
            empty($_REQUEST['order_id'])) {

            return;
        }
        $order_id = esc_html($_REQUEST['order_id']);
        $format = esc_html($_REQUEST['printer']);

        $order = wc_get_order($order_id);

        if (!$order) {

            return;
        }

        try {

            $file = $this->employee_printer_service->print_document($format, $order);

            $file_path = plugin_dir_path(__FILE__) . '../temp-files/' . $file;

            $this->download_file($file_path);


        } catch (Exception $e) {

            return;
        }

    }

    private function download_file($file_path) {

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Pragma: public');

        //Clear system output buffer
        flush();

        readfile($file_path);

        unlink($file_path);
    }

}