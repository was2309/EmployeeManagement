<?php

try {
    spl_autoload_register('employee_management_autoloader');
} catch (Exception $e) {
    wp_die(esc_html($e->getMessage()));
}

function employee_management_autoloader($class_name)
{
    if (false === strpos($class_name, 'EmployeeManagement\\')) {
        return;
    }

    $fileName = str_replace('EmployeeManagement\\', '', $class_name);

    $filePath = __DIR__ . '/../' . $fileName . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    }

}