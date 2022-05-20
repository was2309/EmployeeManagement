<?php

namespace EmployeeManagement;

use EmployeeManagement\Controllers\FrontendController;
use EmployeeManagement\Services\PluginService;
use EmployeeManagement\Components\Setup\Update;
use EmployeeManagement\ViewModel\EmployeeList\WP_Employee_List_Table;
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

    public static function instance($wpdb, $plugin_file)
    {
        if (null === self::$instance) {
            self::$instance = new self($wpdb, $plugin_file);
        }

        self::$instance->initialize();

        return self::$instance;
    }

    private function initialize()
    {
        register_activation_hook($this->plugin_file, [$this, 'activate']);

        add_action('init', [$this, 'register_new_wc_order_statuses']);
        add_action('init', [$this, 'load_plugin_text_domain']);

        add_action('admin_init', [$this, 'employee_register_settings'], 9);
        add_action('admin_init', [$this, 'employee_management_plugin_controller_action_trigger']);

        add_action('admin_menu', [$this, 'create_employee_menu']);
        add_action('admin_menu', [$this, 'create_all_employees_page']);
        add_action('admin_menu', [$this, 'employee_page']);
        add_action('admin_menu', [$this, 'employee_view_page']);
        add_action('admin_menu', [$this, 'employee_settings_page']);

        add_filter('wc_order_statuses', [$this, 'add_new_registered_wc_order_statuses']);

        add_action('woocommerce_admin_order_actions_start', [$this, 'add_get_order_information_button']);
        add_action('woocommerce_admin_order_actions_start', [$this, 'add_print_button_to_order_in_list_table']);

        add_filter('set-screen-option', function ($status, $option, $value) {
            return $value;
        }, 10, 3);


    }

    public function activate()
    {
        if (!PluginService::is_woocommerce_active()) {
            wp_die(esc_html(__('Please install and activate WooCommerce plugin', 'movie-plugin')),
                'Plugin active check',
                ['back_link' => true]);
        }

        $updater = new Update();
        $updater->init_or_update_plugin();
    }


    public function create_employee_menu()
    {
        add_menu_page(
            'Employee Management Plugin',
            'Employee Management Plugin',
            null,
            'employee-management',
            null,
            plugin_dir_url(__FILE__) . './resources/images/icon-profile-em-plugin.png',
            55.5
        );
    }

    public function create_all_employees_page()
    {
        $hook = add_submenu_page(
            'employee-management',
            'Employees',
            __('All employees', 'employee-management'),
            'manage_options',
            'employees',
            [new FrontendController(), 'render']
        );

        add_action("load-$hook", function () {
            add_screen_option('per_page', [
                'label' => "Employees",
                'default' => 2,
                'option' => 'employees_per_page'
            ]);
            $employeeList = new WP_Employee_List_Table(null, null);
        });
    }


}