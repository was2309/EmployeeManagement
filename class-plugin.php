<?php
namespace EmployeeManagement;

use EmployeeManagement\Services\PluginService;
use EmployeeManagement\Components\Setup\Update;
use wpdb;
class Plugin
{
    public $db;
    protected static $instance;
    private $plugin_file;

    private function __construct($wpdb, $plugin_file)
    {
        $this->db = $wpdb;
        $this->plugin_file = $plugin_file;
    }

    public static function instance($wpdb, $plugin_file){
        if(null === self::$instance){
            self::$instance = new self($wpdb, $plugin_file);
        }

        self::$instance->initialize();

        return self::$instance;
    }

    private function initialize()
    {
        register_activation_hook($this->plugin_file, [$this, 'activate']);


    }

    public function activate(){
        if(!PluginService::is_woocommerce_active()){
            wp_die(esc_html(__('Please install and activate WooCommerce plugin', 'movie-plugin')),
                'Plugin active check',
                ['back_link' => true]);
        }

        $updater = new Update();
        $updater->init_or_update_plugin();
    }

}