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

    public function __construct(array $params = array())
    {
        $this->_params = $params;
    }
    
    /**
     * handle catched exception
     *
     * @param Job_Exception $e 
     * @return void
     * @author aur1mas <aur1mas@devnet.lt>
     */
    protected function _handleException(Exception $e)
    {
        $mail = new Zend_Mail('utf-8');
        $mail->addTo(Zend_Registry::get('params')->email->debug);
        $mail->setFrom('no-reply@cronjob');
        $mail->setSubject(APPLICATION_ENV . ': ' . get_class($this));
        $mail->setBodyHtml($e->getMessage());
        $mail->send();
    }
}