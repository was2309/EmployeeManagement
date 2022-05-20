<?php

namespace EmployeeManagement\Controllers;

class FrontendController
{
    public function render(){
        switch ($_GET['page']){

            case 'employees':
                include_once plugin_dir_path(__FILE__) . '../resources/views/all-employees-page.php';
                break;

            case 'employee':
                wp_enqueue_style(
                    'employee_page',
                    plugin_dir_url(__FILE__) . '../resources/css/employee_page.css'
                );
                include_once plugin_dir_path(__FILE__) . '../resources/views/employee-page.php';
                break;
        }


    }

}