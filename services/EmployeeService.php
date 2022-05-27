<?php

namespace EmployeeManagement\Services;

use EmployeeManagement\Repositories\BaseRepository;

class EmployeeService
{

    public function save_employee($employee){
        return BaseRepository::get_base_repository()->get_employee_repository()->save_new_employee($employee);
    }

    public function find_employee_by_id($id){
        return BaseRepository::get_base_repository()->get_employee_repository()->get_employee_by_id($id);
    }

    public function find_employee_by_last_name($last_name){
        return BaseRepository::get_base_repository()->get_employee_repository()->get_employee_by_last_name($last_name);
    }

    public function find_employee_by_name($name){
        return BaseRepository::get_base_repository()->get_employee_repository()->get_employee_by_name($name);
    }


    public function update_employee($id, $employee){
        if(!$employee){
            return false;
        }

        return BaseRepository::get_base_repository()->get_employee_repository()->update_employee($id, $employee);
    }

    public function delete_employee($id){
        BaseRepository::get_base_repository()->get_employee_repository()->delete_employee($id);
    }

    public function get_employee_data_for_list_table(){
        return BaseRepository::get_base_repository()->get_employee_repository()->get_employee_data_for_list_table();
    }

}