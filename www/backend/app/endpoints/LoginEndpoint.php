<?php

    require_once dirname(__DIR__).'/DAOs/User.php';    
    require_once dirname(__DIR__).'/Errors/App/UnableToFulfillRequest.php';

    $context->register_endpoint('/auth/login', function ($c) {
        class UserEndpoint {

            private $db;

            public function __construct($database) {
                $this->db = $database;
            }
            
            public function post($body) {

                if (empty($body->email) || empty($body->password)) throw new UnableToFulfillRequest();

                $user_dao = new UserDAO($this->db);
                $result = $user_dao->get_by_email($body->email)->fetch_object();

                if (!$result) throw new UnableToFulfillRequest();

                // Pior segurança do mundo por não ser um código para ir à produção
                if ($result->password != $body->password) throw new UnableToFulfillRequest();

                return $result; //E ainda retorna a senha em plain-text para o front. Stonks
            }
        }

        return new UserEndpoint($c['db']);
    });
?>