<?php
    require_once dirname(__FILE__).'/utils/require_all_from_dir.php';
    require_once dirname(__FILE__).'/Errors/App/EndpointNotRegistered.php';
    require_once dirname(__FILE__).'/Errors/App/MethodNotAllowed.php';



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
            
            $request_path = str_replace("/api", "", $_SERVER['REQUEST_URI']);
            $handler = $this->get_endpoint_handler($request_path);
            $method = strtolower($_SERVER['REQUEST_METHOD']);

            if (!method_exists($handler['instance'], $method)) throw new MethodNotAllowed();
            
            $request_body = json_decode(file_get_contents('php://input'));
            $params = $this->extract_url_params(
                $this->parse_path($request_path),
                $handler['path']
            );

            $response_body = $handler['instance']->$method($request_body, $params);
            echo json_encode($response_body);
        }


        private function get_endpoint_handler($endpoint) {

            $parsed_path = $this->parse_path($endpoint);
            $registered_path = $this->get_matching_path($parsed_path);
            return array(
                'path' => $registered_path,
                'instance' => $this->get_endpoint_handler_instance(join('/',$registered_path))
            );
        }

        private function get_matching_path($path_arr) {
            
            foreach ($this->get_registered_paths() as $key => $path) {
                
                $registered_arr = $this->parse_path($path);
                if ($this->path_match($path_arr, $registered_arr)) return $registered_arr; 
            }

            throw new EndpointNotRegistered();
        }

        private function parse_path($path) {

            $path_arr = explode('/', $path);
            return $path_arr;
        }

        private function path_match($request_path, $registered_path) {
            
            if (count($request_path) != count($registered_path)) return false;

            $match = true;

            foreach ($registered_path as $key => $path_part) {

                if ($path_part == $request_path[$key] || $this->is_path_param($path_part)) continue;

                $match = false;
                break;
            }

            return $match;
        }

        private function extract_url_params($request_path, $registered_path) {

            $params = array();

            foreach ($request_path as $key => $value) {

                if ($this->is_path_param($registered_path[$key])) {
                    $params[substr($registered_path[$key], 1)] = $value;
                }
            }

            return $params;
        }

        private function get_registered_paths() {
            return array_keys($this->endpoints);
        }

        private function get_endpoint_handler_instance($path) {
            return $this->endpoints[$path](array('db' => $this->db));
        }

        private function is_path_param($path_part) {
            return substr($path_part, 0, 1) == ':';
        }

    }
?>