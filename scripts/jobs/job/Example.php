<?php
/**
 * example job
 *
 * @package Job
 * @author aur1mas <aur1mas@devnet.lt>
 */
class Job_Example extends Job_Abstract
{
    
    /**
     * executes job
     *
     * @return void
     * @author aur1mas <aur1mas@devnet.lt>
     */
    public function execute()
    {
        $this->getLogger()->info("Example Job");
    }
}