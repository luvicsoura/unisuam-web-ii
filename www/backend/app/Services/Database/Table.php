<?php 

    class Table {

        private $db;
        private $table_name;

        public function __construct($database, $table_name) {
            $this->db = $database;
            $this->table_name = $table_name;
        }

        public function filter_by($filter) {

            $query = "SELECT * FROM $this->table_name";

            if ($filter) {

                $attributes = array_keys($filter);
                $values = array_values($filter);

                $query .= " WHERE $attributes[0] = '$values[0]'";
            }

            return $this->db->perform_query($query);
        }

        public function delete_by_filter($filter) {
            $query = "DELETE FROM $this->table_name";

            if ($filter) {

                $attributes = array_keys($filter);
                $values = array_values($filter);

                $query .= " WHERE $attributes[0] = '$values[0]'";
            }

            echo $query;

            return $this->db->perform_query($query);
        }

        public function update_by_filter($new_data, $filter) {
            
            $query = "UPDATE $this->table_name SET";

            $query_data = $this->map_data_to_query_data($new_data);
            $setters = array();

            foreach ($query_data['keys'] as $index => $key) {
                array_push($setters, "$key = '".$query_data['values'][$index]."'");
            }

            $query .= " ".implode(', ', $setters);

            if ($filter) {

                $attributes = array_keys($filter);
                $values = array_values($filter);

                $query .= " WHERE $attributes[0] = '$values[0]'";
            }

            return $this->db->perform_query($query);
        }

        public function insert($data) {

            $query_data = $this->map_data_to_query_data($data);

            $keys = implode(', ', $query_data['keys']);
            $values = implode(', ', array_map(fn($value) => "'$value'", $query_data['values']));
            
            $query = "INSERT INTO $this->table_name ($keys) VALUES ($values)";
            return $this->db->perform_query($query);
        }

        private function map_data_to_query_data($data) {

            if (empty($data)) throw new Exception("Empty parameter", 1);
            
            switch (gettype($data)) {
                case 'object':
                    $input_data = get_object_vars($data);
                    break;

                case 'array':
                    $input_data = $data;
                    break;

                default:
                    throw new Exception("Parameter type mismatch", 1);
                    
            }

            return array(
                'keys' => array_keys($input_data),
                'values' => array_values($input_data)
            );
        }
    }
?>