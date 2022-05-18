<?php

namespace EmployeeManagement\Controllers;

use EmployeeManagement\Services\EmployeeService;

class EmployeeController extends BaseController
{

    private $employee_service;

    public function __construct()
    {
        $this->employee_service = new EmployeeService();
    }

    public function handle_action($action){
        switch ($action){
            case 'save':
                break;
            case 'delete':
                break;
            case 'findAll':
                break;
            case 'findByID':
                break;
            case 'findByLastName':
                break;
            case 'update':
                break;
            case 'printAll':
                break;
            case 'printOne':
                break;
            default: return;
        }
    }

    private function save_employee(){


    }

}