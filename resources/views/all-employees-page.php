<?php

use EmployeeManagement\ViewModel\EmployeeList\EmployeeListVM;

$employee_list_vm = new EmployeeListVM();
$employee_list_table = $employee_list_vm->get_employee_list();

?>

<div class="wrap">
    <h2><b><?=__('All employees', 'employee-management')?></b></h2>
    <form method="get">
        <input type="hidden" name="page" value="employee">
        <button class="button-primary" type="submit"><?=__('New employee', 'employee-management')?></button>
    </form>


    <?php $employee_list_table->prepare_items();?>
    <form method="post">
        <p class="search-box">
            <?php $employee_list_table->search_box(esc_html(__('Find employee', 'employee-management')), 'search-employee');?>
        </p>
    </form>

    <?php
        $employee_list_table->display();
    ?>


</div>
