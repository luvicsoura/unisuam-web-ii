<?php

    class UserDAO {

        private $table;
        private $table_name = 'users';

        public function __construct($database) {
            $this->table = $database->get_table($this->table_name);
        }

        public function create($user) {
            return $this->table->insert($user);
        }

        public function get_by_email($email) {
            return $this->table->filter_by(
                array(
                    'email' => $email
                )
            );
        }

        public function get_by_username($username) {
            return $this->table->filter_by(
                array(
                    'username' => $username
                )
            );
        }
    }
?>