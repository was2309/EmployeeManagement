<?php

namespace EmployeeManagement\ViewModel\EmployeeList;


use EmployeeManagement\Services\EmployeeService;

class EmployeeListVM
{
    private $employee_service;

    public function __construct(){
        $this->employee_service = new EmployeeService();
    }

    public function get_employee_list(){
        $employee_data = null;

        if(isset($_REQUEST['page']) && isset($_REQUEST['s'])){
            $name = trim(esc_html($_REQUEST['s']));

            $employee_data = $this->employee_service->find_employee_by_name($name);
        }else{
            $employee_data = $this->employee_service->get_employee_data_for_list_table();
        }

        return new WPEmployeeListTable($employee_data, null);
    }

}