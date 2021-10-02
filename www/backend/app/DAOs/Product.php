<?php

    class ProductDAO {

        private $table;
        private $table_name = 'products';

        public function __construct($database) {
            $this->table = $database->get_table($this->table_name);
        }

        public function create($product) {
            return $this->table->insert($product);
        }

        public function edit_by_slug($slug, $new_data) {
            return $this->table->update_by_filter($new_data, array('slug' => $slug));
        }

        public function get_by_slug($slug) {
            return $this->get_by_filter(
                array(
                    'slug' => $slug
                )
            );
        }

        public function get_by_filter($filter) {
            return $this->table->filter_by($filter);
        }

        public function delete($slug) {
            return $this->table->delete_by_filter(array('slug' => $slug));
        }
    }
?>