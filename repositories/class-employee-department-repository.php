<?php

namespace EmployeeManagement\Repositories;

class EmployeeDepartmentRepository
{
    private $table_name;
    private $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table_name = $this->db->prefix . BaseRepository::EMPLOYEE_DEPARTMENT_TABLE_NAME;
    }

    public function check_database_and_fill_table(){
        $this->create_database_table();
        $this->fill_table();
    }

    private function create_database_table()
    {
        $query = 'CREATE TABLE IF NOT EXISTS `'
            . $this->table_name . '` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `department_name` VARCHAR(50) NOT NULL,
            `department_name_abbreviation` VARCHAR(5) NOT NULL,
            UNIQUE (`department_name_abbreviation`)
        ) DEFAULT CHARACTER SET utf8';

        $this->db->query($query);

    }

    private function fill_table()
    {
        $query = "SELECT `department_name`, `department_name_abbreviation`  FROM `" . $this->table_name . "`";

        $result = $this->db->get_results($query);

        if($result){
            return;
        }

        foreach ($this->departments() as $department){
            $this->db->insert($this->table_name, ['department_name' => $department[0], 'department_name_abbreviation' => $department[1]]);
        }

    }

    private function departments()
    {
        return[
                ['Marketing', 'MAR'],
                ['Finance', 'FIN'],
                ['Human Resources', 'HR'],
                ['Technical Support', 'TS'],
                ['Security', 'SEC'],
                ['Sales', 'SAL']
        ];
    }

    public function get_departments(){
        $query = 'SELECT * FROM  ' . $this->table_name;
        return $this->db->get_results($query, ARRAY_A);
    }

    public function get_department_by_id($id){
        $query = "SELECT `department_name`, `department_name_abbreviation` FROM  $this->table_name  WHERE `id`=%d ";

        $sql = $this->db->prepare($query, [$id]);

        return $this->db->get_row($sql, ARRAY_A);
    }

    public function get_department_by_abbreviation($abb){
        $query = "SELECT `department_name`, `department_name_abbreviation` FROM  $this->table_name  WHERE `department_name_abbreviation`=%s ";

        $sql = $this->db->prepare($query, [$abb]);

        return $this->db->get_row($sql, ARRAY_A);
    }


}