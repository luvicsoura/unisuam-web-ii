<?php 

    require_once dirname(__DIR__).'/RequestError.php';

    class UnableToFulfillRequest extends RequestError {

        protected $message = 'Unable to fulfill request';
        protected int $error_code = 500;
    }
?>