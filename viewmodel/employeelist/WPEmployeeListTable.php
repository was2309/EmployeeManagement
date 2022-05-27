<?php

namespace EmployeeManagement\ViewModel\EmployeeList;

use WP_List_Table;

class WPEmployeeListTable extends WP_List_Table
{

    private $employee_data;
    private $total_number;

    public function __construct( $employee_data, $total_number, $args = array()){
        parent::__construct($args);
        $this->employee_data = $employee_data;
        $this->total_number = $total_number;
    }


    public function get_columns()
    {
        return[
            'cb' => '<input type="checkbox">',
            'employee_first_name' => __('First name','employee-management'),
            'employee_last_name' => __('Last name', 'employee-management'),
            'employee_birthday' => __('Birthday','employee-management'),
            'department_name' => __('Department', 'employee-management'),
            'department_name_abbreviation' => __('Department abbreviation', 'employee-management')
        ];
    }

    protected function column_default($item, $column_name)
    {
        switch ($column_name){
            case 'employee_first_name':
            case 'employee_last_name':
            case 'employee_birthday':
            case 'department_name':
            case 'department_name_abbreviation':
                return $item[$column_name];
            default:
                print_r($item, true);
        }
    }

    protected function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="employee[]" value="%s"/>', $item['employee_id']);
    }

    protected function get_sortable_columns()
    {
        return [
            'employee_last_name' => ['employee_last_name', true],
            'department_name' => ['department_name', true],
        ];
    }

    private function usort_reorder($a, $b) {

        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'employee_last_name'; // by which column
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc'; //ascending or descending
        $result = strcmp($a[$orderby], $b[$orderby]);

        return ($order == 'asc') ? $result : -$result;
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [$columns, $hidden, $sortable];

        $perPage = $this->get_items_per_page('employees_per_page', 5);

        $currentPage = $this->get_pagenum();
//        $totalItems = count($this->employee_data);

        if($this->employee_data){
            usort($this->employee_data, [&$this, 'usort_reorder']);
//            $this->employee_data = array_slice($this->employee_data, (($currentPage - 1) * $perPage), $perPage);
        }

        $this->set_pagination_args([
            'total_items' =>$this->total_number,
            'per_page' => $perPage,
            'total_page' => ceil($this->total_number/$perPage)
        ]);

        $this->items = $this->employee_data;
    }

    function column_employee_first_name($item) {
        $actions = array(
            'view' => sprintf('<a href="?page=%s&%s=%s">%s</a>', 'employeeview', 'employee_id', $item['employee_id'], __('View', 'employee-management')),
            'edit' => sprintf('<a href="?page=%s&%s=%s">%s</a>', 'employee', 'employee_id', $item['employee_id'], __('Edit', 'employee-management')),
            'print' => sprintf('<a href="?page=%s&%s=%s">%s</a>', 'employee', 'employee_id', $item['employee_id'], __('Print', 'employee-management')),
            'print' => sprintf('
        <a>
        <form method="post">
                <input type="hidden" name="controller_name" value="Employee"">
                <input type="hidden" name="action" value="print">
                <input type="hidden" name="printer" value="word">
                <input type="hidden" name="employee_id" value="%s">
                
                    <button style="background: none; border: none;
                            padding: 0!important;
                            /*optional*/
                             font-family: arial, sans-serif;
                             /*input has OS specific font-family*/
                             color: #069;
                             text-decoration: none;
                            cursor: pointer;" 
                    type="submit">%s
                 </button>            
            </form>
        </a>
        ',$item['employee_id'], __('Print', 'employee-management')),
        );

        return sprintf('%1$s %2$s', $item['employee_first_name'], $this->row_actions($actions));
    }



}