<?php

    require_once dirname(__FILE__).'/utils/get_files_in_dir.php';

    class App {
        
        public static $instance;

        private $db;
        private $servlet;
        private $config = array(
            'db_uri' => 'db',
            'db_user'=> 'mariadb',
            'db_password' => 'mariadb',
            'db_name' => 'mariadb'
        );

        public static function getInstance() {
            if (!isset(self::$instance)) {
                self::$instance = new App();
            }

            return self::$instance;
        }

        public function init() {
            
            require_once 'Services/Database/Database.php';

            $this->db = new Database(
                $this->config['db_uri'],
                $this->config['db_user'],
                $this->config['db_password'],
                $this->config['db_name']
            );

            $this->db->connect();
            $this->setup_database();
            $this->setup_servlet();
        }

        private function setup_database() {
            
            $queries_dir = dirname(__DIR__).'/config/db/tables';
            $query_files = get_files_in_dir($queries_dir);

            foreach($query_files as $query_file) {

                $query = file_get_contents($query_file);
                $response = $this->db->perform_query($query, true);
            }
        }

        private function setup_servlet() {
            
            require_once dirname(__FILE__).'/server.php';
            $this->servlet = new Servlet(
                array(
                    'db' => $this->db
                )
            );
            $this->servlet->init();
        }
    }
?>