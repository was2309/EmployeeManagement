<?php

namespace EmployeeManagement\Services;

use EmployeeManagement\Repositories\BaseRepository;
class EmployeeDepartmentService
{
    public static function get_all_departments(){
        return BaseRepository::get_base_repository()->get_department_repository()->get_departments();
    }

    public static function get_department_by_id($id){
        return BaseRepository::get_base_repository()->get_department_repository()->get_department_by_id($id);
    }

}