<?php
/**
 * @package CronJob
 * @author aur1mas <aur1mas@devnet.lt>
 * @copyright pozicijos.lt <info@pozicjos.lt>
 */
interface Job_Interface
{
    /**
     * main CronJob logic
     */
    public function execute();
}