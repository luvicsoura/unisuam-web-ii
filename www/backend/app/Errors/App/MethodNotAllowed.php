<?php 

    require_once dirname(__DIR__).'/RequestError.php';

    class MethodNotAllowed extends RequestError {

        protected $message = 'Method not allowed';
        protected int $error_code = 405;
    }
?>