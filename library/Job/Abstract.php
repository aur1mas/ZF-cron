<?php
/**
 * @package CronJob
 * @author aur1mas <aur1mas@devnet.lt>
 * @copyright pozicijos.lt <info@pozicijos.lt>
 */
class Job_Abstract
{

    /**
     * passed parameters
     * @var array
     */
    protected $_params;
    
    /**
     * default logger
     *
     * @var string
     */
    protected $_logger = null;

    public function __construct(array $params = array())
    {
        $this->_params = $params;
        
        $this->_logger = new Zend_Log(new Zend_Log_Writer_Stream("php://output"));
    }
    
    /**
     * get logger
     *
     * @return Zend_Log
     * @author aur1mas <aur1mas@devnet.lt>
     */
    public function getLogger()
    {
        return $this->_logger;
    }    
}