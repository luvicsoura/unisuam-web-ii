<?php

    require_once dirname(__DIR__).'/utils/slugify.php';
    require_once dirname(__DIR__).'/DAOs/Product.php';
    require_once dirname(__DIR__).'/Errors/App/UnableToFulfillRequest.php';

    $context->register_endpoint('/product/:slug', function ($c) {
        class UserEndpoint {

            private $db;
            private $product_dao;

            public function __construct($database) {
                $this->db = $database;
                $this->product_dao = new ProductDAO($this->db);
            }
            
            public function get($body, $params) {

                return $this->product_dao->get_by_slug($params['slug'])->fetch_object();
            }

            public function patch($body, $params) {

                $update_result = $this->product_dao->edit_by_slug($params['slug'], $body);

                if (!$update_result) throw new UnableToFulFillRequest();

                return $this->product_dao->get_by_slug($params['slug'])->fetch_object();
            }

            public function delete($body, $params) {

                $result = $this->product_dao->delete($params['slug']);

                if (!$result) throw new UnableToFulFillRequest();

                return;
            }
        }

        return new UserEndpoint($c['db']);
    });

    $context->register_endpoint('/product', function ($c) {
        class UserEndpoint {

            private $db;

            public function __construct($database) {
                $this->db = $database;
            }

            public function get($body, $params) {

                $product_dao = new ProductDAO($this->db);
                $result = $product_dao->get_by_filter(Array());
                
                $products = [];

                while ($product = $result->fetch_object()) {
                    array_push($products, $product);
                }

                return $products;
            }
            
            public function post($body) {

                $product_dao = new ProductDAO($this->db);

                $slug = slugify($body->name);
                
                $result = $product_dao->get_by_slug($slug)->fetch_object();

                if ($result) $slug .= '-'.(new DateTime())->getTimestamp();

                $body->slug = $slug;

                $result = $product_dao->create($body);
                
                if (!$result) throw new UnableToFulFillRequest();

                $result = $product_dao->get_by_slug($slug)->fetch_object();
                return $result;
            }
        }

        return new UserEndpoint($c['db']);
    });
?>