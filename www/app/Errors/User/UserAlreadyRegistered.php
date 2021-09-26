<?php 

    require_once dirname(__DIR__).'/RequestError.php';

    class UserAlreadyRegistered extends RequestError {

        protected $message = 'User already Registered';
        protected int $error_code = 409;
    }
?>