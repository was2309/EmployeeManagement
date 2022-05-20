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
    $fileNameArray = explode('\\', $fileName  );
    $lastFileName = end($fileNameArray);
    array_pop($fileNameArray);

    foreach ($fileNameArray as &$item){
        $item = strtolower($item);
    }

    $fileNameArray[]=$lastFileName;
    $fileName = implode('/', $fileNameArray);

    $filePath = __DIR__ . '/../' . $fileName . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    }

}