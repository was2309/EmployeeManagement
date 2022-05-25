<?php

namespace EmployeeManagement\Components\Setup;

use EmployeeManagement\Repositories\BaseRepository;
class Update
{
    private function is_plugin_initialized(){

        return BaseRepository::get_base_repository()->is_plugin_initialized();

    }

    private function initialize_plugin() {

        BaseRepository::get_base_repository()->initialize_employee_plugin_tables();
    }

    public function init_or_update_plugin(){

        if(!$this->is_plugin_initialized()){

            $this->initialize_plugin();
        }
    }
}