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
                    'employee-page',
                    plugin_dir_url(__FILE__) . '../resources/css/employee-page.css'
                );
                include_once plugin_dir_path(__FILE__) . '../resources/views/employee-page.php';
                break;

            case 'employeeview':
                wp_enqueue_style(
                    'employee-view-page',
                    plugin_dir_path(__FILE__) . '../resources/css/employee-view-page.css'
                );

                include_once plugin_dir_path(__FILE__) . '../resources/views/employee-view-page.php';
                break;
        }


    }

}