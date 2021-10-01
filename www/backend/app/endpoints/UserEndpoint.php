<?php

    require_once dirname(__DIR__).'/DAOs/User.php';
    require_once dirname(__DIR__).'/Errors/User/UserAlreadyRegistered.php';
    require_once dirname(__DIR__).'/Errors/App/UnableToFulfillRequest.php';

    $context->register_endpoint('/user/:username', function ($c) {
        class UserEndpoint {

            private $db;

            public function __construct($database) {
                $this->db = $database;
            }
            
            public function get($body, $params) {

                $user_dao = new UserDAO($this->db);
                $result = $user_dao->get_by_username($params['username'])->fetch_object();

                return $result;
            }
        }

        return new UserEndpoint($c['db']);
    });

    $context->register_endpoint('/user', function ($c) {
        class UserEndpoint {

            private $db;

            public function __construct($database) {
                $this->db = $database;
            }
            
            public function post($body) {

                $user_dao = new UserDAO($this->db);

                $result = $user_dao->get_by_username($body->username)->fetch_object();

                if ($result) throw new UserAlreadyRegistered();

                $result = $user_dao->create($body);
                
                if (!$result) throw new UnableToFulFillRequest();

                $result = $user_dao->get_by_username($body->username)->fetch_object();
                return $result;
            }
        }

        return new UserEndpoint($c['db']);
    });
?>