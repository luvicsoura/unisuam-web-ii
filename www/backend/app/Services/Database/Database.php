<?php 

    require_once dirname(__FILE__)."/Table.php";

    class Database {

        private $uri;
        private $user;
        private $password;
        private $database;
        public $connection;

        public function __construct($uri, $user, $password, $database) {
        
            $this->uri = $uri;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;
        }
        
        public function connect() {
            $this->connection = mysqli_connect(
                $this->uri,
                $this->user,
                $this->password,
                $this->database
            );
        }

        public function get_table($table) {
            return new Table($this, $table);
        }

        public function perform_query($query, $multi = false) {

            if ($multi) {
                return $this->connection->multi_query($query);
            }

            return $this->connection->query($query);
        }
    }
?>