<?php

namespace EmployeeManagement\ViewModel\EmployeeList;


use EmployeeManagement\Services\EmployeeService;

class EmployeeListVM
{
    private $employee_service;

    public function __construct()
    {
        $this->employee_service = new EmployeeService();
    }

    public function get_employee_list()
    {
        $employee_data = null;
        $per_page_array = get_user_meta(get_current_user_id(), 'employees_per_page');
        $per_page = intval(reset($per_page_array));
        $current_page = 1;
        if (isset($_REQUEST['paged'])) {
            $current_page = intval($_REQUEST['paged']);
        }

        if (isset($_REQUEST['s'])) {
            $name = trim(esc_html($_REQUEST['s']));

            $employee_data = $this->employee_service->find_employee_by_name($name, $current_page, $per_page);
            $first_element = reset($employee_data);
            $total_number = intval($first_element['total_number']);

        }
        else{
            $total_number_array = $this->employee_service->get_total_number();
            $total_number = intval(reset($total_number_array));
            $employee_data = $this->employee_service->get_employee_data_for_list_table($current_page, $per_page);
        }



        return new WPEmployeeListTable($employee_data, $total_number, null);
    }

}