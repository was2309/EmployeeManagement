<?php

use EmployeeManagement\ViewModel\Employee\EmployeeVM;

$employee_vm = new EmployeeVM();
$employee = $employee_vm->get_employee();
$departments = $employee_vm->get_departments();

?>

<div class="wrap">
    <div class="content">
        <h1>Add new employee</h1>
        <div class="buttons-above-form-wrapper">
            <div>
                <form method="get">
                    <input type="hidden" name="page" value="employees">
                    <button class="btn-cancel" type="submit">Cancel</button>
                </form>
            </div>

            <div>
                <form method="post">
                    <input type="hidden" name="controller_name" value="Employee">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="employee_id" value="<?= $employee['employee_id']?>">
                    <button class="btn-delete" type="submit">Delete</button>
                </form>
            </div>
        </div>

        <form class="form" method="post">
            <input type="hidden" name="controller_name" value="Employee">
            <input type="hidden" name="action" value="save">

            <input type="hidden" name="employee_id" value="<?= $employee['employee_id']?>">
            <div class="form-left-side">
                <div class="input-div-wrapper">
                    <label>First name:</label>
                    <input type="text" name="employee_first_name" placeholder="First name..."
                        value="<?= $employee['employee_first_name']?>">
                </div>
                <div class="input-div-wrapper">
                    <label>Last name:</label>
                    <input type="text" name="employee_last_name" placeholder="Last name..."
                           value="<?= $employee['employee_last_name']?>">
                </div>
                <div class="input-div-wrapper">
                    <label>Date of birth:</label>
                    <input type="date" name="employee_last_name"
                           value="<?= $employee['employee_birthday']?>">
                </div>
            </div>
            <div class="form-right-side">
                <label for="departments">Department</label>
                <select name="departments">
                <?php
                    foreach($departments as $department){
                        echo "<option value='". $department['department_name'] . "'>" . $department['id'] . "</option>";
                    }
                ?>
                </select>
            </div>
            <div class="form-bottom-div">
                <div class="form-button-wrapper">
                    <button class="button-primary" type="submit">Save</button>
                </div>
            </div>

        </form>
    </div>
</div>
