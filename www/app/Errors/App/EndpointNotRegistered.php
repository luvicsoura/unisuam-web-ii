<?php 

    require_once dirname(__DIR__).'/RequestError.php';

    class EndpointNotRegistered extends RequestError {

        protected $message = 'Endpoint not found';
        protected int $error_code = 404;
    }
?>