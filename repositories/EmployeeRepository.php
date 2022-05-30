<?php

namespace EmployeeManagement\Repositories;

class EmployeeRepository
{

    private $db;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->table_name = $this->db->prefix . BaseRepository::EMPLOYEE_TABLE_NAME;
    }

    public function check_employee_table(){
        $this->create_employee_table();
    }

    private function create_employee_table(){
        $query = 'CREATE TABLE IF NOT EXISTS ' . $this->table_name
            . '  (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        employee_first_name VARCHAR(255) NOT NULL,
        employee_last_name VARCHAR(255) NOT NULL,
        employee_birthday DATE NOT NULL,
        employee_department_id INT NOT NULL,
        FOREIGN KEY (employee_department_id) REFERENCES  ' . $this->db->prefix . BaseRepository::EMPLOYEE_DEPARTMENT_TABLE_NAME . ' (id) )';

        $this->db->query($query);
    }

    public function get_employee_by_id($id){
        $query = "SELECT  e.`id` AS employee_id, 
                            e.employee_first_name,
                            e.employee_last_name,
                            e.employee_birthday, 
                            e.employee_department_id,
                            d.id AS department_id,
                            d.department_name AS department_name, 
                            d.department_name_abbreviation
                            FROM " . $this->table_name . " e
                            INNER JOIN " . $this->db->prefix . BaseRepository::EMPLOYEE_DEPARTMENT_TABLE_NAME . " d 
                            ON e.employee_department_id=d.id
                            WHERE e.id=' " . $id . " ' ";

        return $this->db->get_row($query, ARRAY_A);
    }

    public function get_employee_by_last_name($last_name){
        $query = "SELECT  e.`id` AS employee_id, 
                            e.employee_first_name,
                            e.employee_last_name,
                            e.employee_birthday, 
                            e.employee_department_id,
                            d.id AS department_id,
                            d.department_name AS department_name, 
                            d.department_name_abbreviation
                            FROM " . $this->table_name . " e
                            INNER JOIN " . $this->db->prefix . BaseRepository::EMPLOYEE_DEPARTMENT_TABLE_NAME . " d 
                            ON e.employee_department_id=d.id
                            WHERE e.employee_last_name LIKE '%" . $last_name . "%' ";

        return $this->db->get_results($query, ARRAY_A);
    }

    public function get_employee_by_name($name, $current_page, $per_page ){
        $offset = $per_page * ($current_page-1);
        $query1 = "SELECT COUNT(id) AS total_number FROM ". $this->table_name .
                " e  WHERE  e.employee_last_name LIKE '%" . $name . "%' OR e.employee_first_name LIKE '%" . $name . "%'";
        $total_number =  $this->db->get_row($query1, ARRAY_A);
        $query2 = "SELECT  e.`id` AS employee_id, 
                            e.employee_first_name,
                            e.employee_last_name,
                            e.employee_birthday, 
                            e.employee_department_id,
                            d.id AS department_id,
                            d.department_name AS department_name, 
                            d.department_name_abbreviation
                            FROM " . $this->table_name . " e
                            INNER JOIN " . $this->db->prefix . BaseRepository::EMPLOYEE_DEPARTMENT_TABLE_NAME . " d 
                            ON e.employee_department_id=d.id
                            WHERE e.employee_last_name LIKE '%" . $name . "%' OR e.employee_first_name LIKE '%" . $name ."%'  
                            ORDER BY e.employee_last_name
                            LIMIT " . $per_page ." OFFSET " . $offset ;

        $data_array = $this->db->get_results($query2, ARRAY_A);
        $data_array[] = $total_number;
        return $data_array;
    }

    public function save_new_employee($employee){
        if(!BaseRepository::get_base_repository()->get_department_repository()->get_department_by_id($employee['employee_department_id'])){
            return false;
        }

        $result = $this->db->insert($this->table_name, $employee);

        if ($result) {
            return $this->db->insert_id; //last ID
        }

        return false;
    }

    public function update_employee($id, $employee){
        $result = $this->db->update($this->table_name, $employee, ['id' => $id]);

        if($result){
            return $id;
        }

        return false;

    }

    public function delete_employee($id) {

        return $this->db->delete($this->table_name, ['id' => $id]);
    }

    public function get_employee_data_for_list_table($current_page, $per_page){
        $offset = $per_page * ($current_page-1);
        $query = "SELECT e.id AS employee_id, e.employee_first_name, e.employee_last_name, e.employee_birthday, d.department_name, d.department_name_abbreviation
                   FROM " . $this->table_name . " e
                   INNER JOIN " . $this->db->prefix . BaseRepository::EMPLOYEE_DEPARTMENT_TABLE_NAME . " d
                   ON e.employee_department_id = d.id 
                   ORDER BY e.employee_last_name
                   LIMIT " . $per_page ." OFFSET " . $offset ;

        return $this->db->get_results($query, ARRAY_A);
    }

    public function get_total_number(){
        $query = "SELECT COUNT(id) FROM ". $this->table_name;
        return $this->db->get_row($query, OBJECT);
    }


}