<?php

    class OrderDAO {

        private $table;
        private $table_name = 'orders';

        public function __construct($database) {
            $this->table = $database->get_table($this->table_name);
        }

        public function create($order) {
            return $this->table->insert($order);
        }

        public function getOrders() {
            return $this->table->filter_by(null);
        }
    }
?>