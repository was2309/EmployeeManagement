<?php

namespace EmployeeManagement\Repositories;


class BaseRepository
{
    const EMPLOYEE_TABLE_NAME = 'employee_management_employee';
    const EMPLOYEE_DEPARTMENT_TABLE_NAME = 'employee_management_department';

    private $db;

    private static $base_repository;
    private $employee_repository;
    private $department_repository;

    private function __construct(){
        global $wpdb;
        $this->db = $wpdb;
    }

    public static function get_base_repository(){
        if(null === self::$base_repository){
            self::$base_repository = new BaseRepository();
        }
        return self::$base_repository;
    }

    /**
     * @return mixed
     */
    public function get_employee_repository(){
        if ($this->employee_repository){
            return $this->employee_repository;
        }
        return new EmployeeRepository();
    }

    /**
     * @return mixed
     */
    public function get_department_repository(){
        if($this->department_repository){
            return $this->department_repository;
        }
        return new EmployeeDepartmentRepository();
    }



    public function is_plugin_initialized(){
        $table_name_employees = $this->db->prefix . self::EMPLOYEE_TABLE_NAME;
        $table_name_department = $this->db->prefix . self::EMPLOYEE_DEPARTMENT_TABLE_NAME;


        return $this->db->get_var('SHOW TABLES LIKE ' . $table_name_employees) === $table_name_employees
            && $this->db->get_var('SHOW TABLES LIKE ' . $table_name_department) === $table_name_department;

    }

    public function initialize_movie_plugin_tables(){
        $this->department_repository = new EmployeeDepartmentRepository();
        $this->department_repository->check_database_and_fill_table();

        $this->employee_repository = new EmployeeRepository();
        $this->employee_repository->check_employee_table();
    }
}