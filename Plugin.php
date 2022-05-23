<?php

namespace EmployeeManagement;

use EmployeeManagement\Components\Util\EmployeeHelper;
use EmployeeManagement\Controllers\FrontendController;
use EmployeeManagement\Services\PluginService;
use EmployeeManagement\Components\Setup\Update;
use EmployeeManagement\ViewModel\EmployeeList\WPEmployeeListTable;
use wpdb;

class Plugin
{
    public $db;
    public static $instance;
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

        add_action('admin_init', [$this, 'employee_management_plugin_controller_action_trigger']);

        add_action('admin_menu', [$this, 'create_employee_menu']);
        add_action('admin_menu', [$this, 'create_all_employees_page']);
        add_action('admin_menu', [$this, 'employee_page']);
        add_action('admin_menu', [$this, 'employee_view_page']);

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
            $employeeList = new WPEmployeeListTable(null, null);
        });
    }

    public function employee_page(){
        add_submenu_page(
          'employee_management',
          'Employee',
          __('New employee', 'employee_management'),
          'manage_options',
          'employee',
          [new FrontendController(), 'render']
        );
    }

    public function employee_view_page(){
        add_submenu_page(
            null,
            'Employee',
            'View employee',
            'manage_options',
            'employeeview',
            [new FrontendController(), 'render']
        );
    }

    public function employee_management_plugin_controller_action_trigger() {

        if (empty($_REQUEST['controller_name'])) {

            return;
        }

        $controller_name = esc_html($_REQUEST['controller_name']) . 'Controller';

        if (!class_exists($controller_name))
            return;

        $controller = new $controller_name;

        $action = esc_html($_REQUEST['action']) ?: '';

        $controller->handle_action($action);
    }

    public function load_plugin_text_domain() {

        load_plugin_textdomain(
            'employee_management',
            false,
            plugin_basename(dirname(__FILE__)) . '/i18n/languages'
        );
    }

    public function add_new_registered_wc_order_statuses($order_statuses) {

        $order_statuses['wc-new_status_1'] = __('New status 1', ',employee_management');
        $order_statuses['wc-new_status_2'] = __('New status 2', ',employee_management');

        return $order_statuses;

    }

    public function register_new_wc_order_statuses() {
        //prefix wc- needed for woocommerce to read statuses
        $order_statuses = [
            'wc-new_status_1' => [
                'label' => _x('New status 1', 'employee-management'),
                'public' => true,
                'exclude_from_search' => false,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('New status 1 <span class="count">(%s)</span>',
                    'New status 1 <span class="count">(%s)</span>')
            ],
            'wc-new_status_2' => [
                'label' => _x('New status 2', 'employee-management'),
                'public' => true,
                'exclude_from_search' => false,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('New status 2 <span class="count">(%s)</span>',
                    'New status 2<span class="count">(%s)</span>')
            ],
        ];

        foreach ($order_statuses as $order_status => $values) {
            register_post_status($order_status, $values);
        }

    }

    public function add_get_order_information_button($order){

        $order_id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;

        $url = EmployeeHelper::get_controller('Employee','order-information',[
            'order_id'=>$order_id
        ]);

        wp_enqueue_script(
            'employee-management-print-order-btn',
            plugins_url('/resources/js/employee-management-print-order.js', __FILE__),
            ['jquery'],
            '1.0.0',
            true
        );

        echo "<button class='get-order-infromation-button button' url=' " . $url . " ' >" . __('Get info', 'employee-management') . "</button>";

    }

    public function add_print_button_to_order_in_list_table($order) {

        $order_id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;

        $url = EmployeeHelper::get_controller(
            'Employee',
            'print-order',
            [
                'printer' => 'word-order',
                'order_id' => $order_id
            ]);

        wp_enqueue_script(
            'employee-management-print-order-btn',
            plugins_url('/resources/js/employee-management-print-order.js', __FILE__),
            ['jquery'],
            '1.0.0',
            true
        );

        echo "<button class='print-button button' url-print=' " . $url . " ' >" . __('Print', 'employee-management') . "</button>";

    }



}