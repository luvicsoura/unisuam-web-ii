<?php 

    class RequestError extends Error {

        protected $message = '';
        protected int $error_code = 500;

        public function __construct($message = '', $error_code = 500) {
            $this->$message = $message || $this->message;
            $this->$error_code = $error_code || $this->error_code;
        }

        public function get_message() {
            return $this->message;
        }

        public function get_code() {
            return $this->error_code;
        }

        public function to_json() {
            return json_encode(
                array(
                    'message' => $this->message
                )
            );
        }
    }
?>