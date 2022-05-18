<?php

namespace EmployeeManagement\ViewModel\EmployeeList;

use WP_List_Table;

class WP_Employee_List_Table extends WP_List_Table
{

    private $employee_data;

    public function __construct( $employee_data, $args = array()){
        parent::__construct($args);
        $this->employee_data = $employee_data;
    }

    //TODO: ...
}