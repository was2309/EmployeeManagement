<?php

namespace EmployeeManagement\Controllers;



class BaseController
{
    public function index($controller_name){
        $controller = null;

        switch ($controller_name){
            case 'settings_controller':
                $controller = new SettingsPageController();
                break;
            case 'employee_controller':
                $controller = new EmployeeController();
                break;
            default:
                return;
        }

        if($controller!== null){
            $action = esc_html($_REQUEST['action']) ? : '';
            $controller->handle_action($action);
        }
    }
}