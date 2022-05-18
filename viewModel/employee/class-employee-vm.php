<?php

namespace EmployeeManagement\ViewModel\Employee;


use EmployeeManagement\Services\EmployeeDepartmentService;
use EmployeeManagement\Services\EmployeeService;

class EmployeeVM
{

    private $employee_service;
    private $employee_department_service;

    public function __construct(){
        $this->employee_service = new EmployeeService();
        $this->employee_department_service = new EmployeeDepartmentService();
    }

    public function get_employee(){
        if(!empty($_GET['employee_id'])){
            $employee_id = esc_html($_GET['employee_id']);
            $employee = $this->employee_service->find_employee_by_id($employee_id);
        }

        if(!empty($employee)){
            return $employee;
        }

        return [
          'employee_id' => '',
          'employee_first_name' => '',
          'employee_last_name' => '',
          'employee_birthday' => '',
          'department_id' => '',
          'department_name' => '',
          'department_name_abbreviation' => ''
        ];
    }

    public function get_departments(){
        return $this->employee_department_service->get_all_departments();
    }


}