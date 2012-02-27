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
        throw new Exception("CronJob is not specified");
    }

    $cronName = array_shift($_SERVER['argv']);
    $params = $_SERVER['argv'];
    
    CronJob::execute($cronName, $params);
    
} catch (Exception $e) {
    echo $e->getMessage() . "\r\n";
}

/**
 * @author aur1mas <aur1mas@devnet.lt>
 */
class CronJob
{

    /**
     *
     * executes cron job
     *
     * @author aur1mas <aur1mas@devnet.lt>
     * @param string $name
     * @param array $params
     * @throws Exception
     * @return void
     */
    public static function execute($name, array $params = array())
    {
        if (!file_exists('job/' . ucfirst($name) . '.php')) {
            throw new Exception("CronJob: '" . $name . "' doesn't exist");
        }

        require_once 'job/Abstract.php';
        require_once 'job/Interface.php';
        require_once 'job/' . ucfirst($name) . '.php';

        $class = implode('_', explode(DIRECTORY_SEPARATOR, $name));
        
        $class = "Job_" . ucfirst($class);
        $object = new $class($params);

        try {
            $object->execute();
        } catch (Exception $e) {
            $message = "Exception: " . $e->getMessage() . "\r\n\r\n";
            $message .= "Stack Trace: " . "\r\n" . $e->getTraceAsString() . "\r\n\r\n";
            
            $mail = new Zend_Mail('utf-8');
            $mail->addTo(Zend_Registry::get('params')->email->debug);
            $mail->setFrom('no-reply@cronjob');
            $mail->setSubject(APPLICATION_ENV . ': CronJob [generic]');
            $mail->setBodyHtml($message);
            $mail->send();
            
            echo $e->getMessage() . "\r\n";
        }
    }
}