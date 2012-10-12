<?php
/**
 * @package Cron
 * @author aur1mas <aur1mas@devnet.lt>
 * @copyright pozicijos.lt <info@pozicijos.lt>
 */
ini_set('memory_limit', '512M');


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

array_shift($_SERVER['argv']); // popout executed filename
$env = array_shift($_SERVER['argv']); // get environment

try {
    if (!in_array($env, array('production', 'staging', 'development'))) {
        throw new Exception("Wrong environment provided");
    }

    // Define application environment
    define('APPLICATION_ENV', $env);

    /** Zend_Application */
    require_once 'Zend/Application.php';

    // Create application, bootstrap, and run
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $application->getBootstrap()->bootstrap('autoload');

    if ($_SERVER['argc'] === 1) {
        throw new Exception("Job is not specified");
    }

    $cronName = array_shift($_SERVER['argv']);
    $params = $_SERVER['argv'];
    
    Job_Executor::execute($cronName, $params);
    
} catch (Exception $e) {
    echo $e->getMessage() . "\r\n";
}