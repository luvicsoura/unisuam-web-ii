<?php

    require_once dirname(__DIR__).'/DAOs/User.php';

    $context->register_endpoint('/user', function ($c) {
        class UserEndpoint {

            private $db;

            public function __construct($database) {
                $this->db = $database;
            }
            
            public function post($body) {

                $user_dao = new UserDAO($this->db);
                $result = $user_dao->create($body);
                
                // if (!$result) throw new Exception('Unable to fulfill request', 500);

                $result = $user_dao->get_by_username($body->username)->fetch_object();
                return $result;
            }
        }

        return new UserEndpoint($c['db']);
    });
?>