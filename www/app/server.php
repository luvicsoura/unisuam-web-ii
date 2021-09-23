<?php
    require_once dirname(__FILE__).'/utils/require_all_from_dir.php';


    class Servlet {

        private $db;
        private $endpoints;

        public function __construct($config) {

            $this->db = $config['db'];
        }

        public function init() {
            $this->setup_endpoints();
            $this->handle_request();
        }

        public function register_endpoint($path, $handler) {
            
            if (!empty($this->endpoints[$path])) throw new Exception("Endpoint already registered", 1);
            $this->endpoints[$path] = $handler;
        }

        private function setup_endpoints() {

            $server = $this;
            require_all_from_dir(dirname(__FILE__).'/endpoints', $server);
        }

        private function handle_request() {
            
            $request_path = $_SERVER['REQUEST_URI'];
            if (empty($this->endpoints[$request_path])) throw new Exception("Endpoint not registered", 1);
            
            $handler = $this->get_endpoint_handler($request_path);
            $method = strtolower($_SERVER['REQUEST_METHOD']);

            if (!method_exists($handler, $method)) throw new Exception("Method not allowed", 501);
            
            $request_body = json_decode(file_get_contents('php://input'));
            $response_body = $handler->$method($request_body);
            echo json_encode($response_body);
        }

        private function get_endpoint_handler($endpoint) {
            return $this->endpoints[$endpoint](array('db' => $this->db));
        }
    }
?>