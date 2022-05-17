<?php

namespace EmployeeManagement\Services;

use EmployeeManagement\Repositories\BaseRepository;
class EmployeeDepartmentService
{
    public function get_all_departments(){
        return BaseRepository::get_base_repository()->get_department_repository()->get_departments();
    }
}