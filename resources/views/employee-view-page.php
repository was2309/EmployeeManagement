<?php

use EmployeeManagement\ViewModel\Employee\EmployeeVM;

$employee_vm = new EmployeeVM();
$employee = $employee_vm->get_employee();
?>

<div class="wrap">
    <div class="blog-container">
        <div class="blog-body">
            <div class="blog-title">
                <h1><?= $employee['employee_first_name'] . ' ' .$employee['employee_last_name']?></h1>
            </div>
            <div class="blog-summary">
                <p>Birthday: <?= $employee['employee_birthday']?></p>
            </div>
            <div class="blog-summary">
                <p>Department: <?= $employee['department_name']?></p>
            </div>
            <div class="blog-summary">
                <p>Department abbreviation: <?= $employee['department_name_abbreviation']?></p>
            </div>

            <div class="blog-tags">
                <ul>
                    <li>
                        <form method="get">
                            <input type="hidden" name="page" value="employee">
                            <input type="hidden" name="employee_id" value="<?= $employee['employee_id']?>">
                            <button class="btn-update" type="submit">Update</button>
                        </form>
                    </li>
                    <li>
                        <form method="post">
                            <input type="hidden" name="controller_name" value="Employee">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name='movie_id' value="<?= $employee['employee_id'] ?>">
                            <button class="btn-delete" type="submit">Delete</button>
                        </form>
                    </li>
                    <li>
                        <form method="get">
                            <input type="hidden" name="page" value="employees">
                            <button class="btn-update" type="submit">Cancel</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="blog-footer ">
            <form method="post">
                <div>Choose printing format: </div>
                <input type="hidden" name="controller_name" value="employee"">
                <input type="hidden" name="action" value="print">
                <input type="hidden" name="movie_id" value="<?=$employee['employee_id']?>">
                <div class="form-printers">
                    <div>
                        <input type="radio" name="printer" value="word">Word
                    </div>
                    <div>
                        <input type="radio" name="printer" value="pdf">PDF
                    </div>
                </div>

                <div class="div-form-print-btn">
                    <button class="btn-update" type="submit">Print</button>
                </div>
            </form>

        </div>
    </div>
</div>
