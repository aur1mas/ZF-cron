<?php
/**
 * @author aur1mas <aur1mas@devnet.lt>
 */
class Job_Executor
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

        require_once 'job/' . ucfirst($name) . '.php';

        $class = implode('_', explode(DIRECTORY_SEPARATOR, $name));
        
        $class = "Job_" . ucfirst($class);
        $object = new $class($params);

        try {
            $object->execute();
        } catch (Exception $e) {
            $message = "Exception: " . $e->getMessage() . "\r\n\r\n";
            $message .= "Stack Trace: " . "\r\n" . $e->getTraceAsString() . "\r\n\r\n";
            
            echo $message . "\r\n";
        }
    }
}