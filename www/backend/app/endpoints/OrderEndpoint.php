<?php

    require_once dirname(__DIR__).'/Errors/App/UnableToFulfillRequest.php';

    $context->register_endpoint('/order', function ($c) {
        class UserEndpoint {

            private $db;

            public function __construct($database) {
                $this->db = $database;
            }
            
            public function post($body) {

                if (!count($body)) throw new UnableToFulFillRequest();

                $queries = [];

                foreach ($body as $__ => $orderItem) {
                    array_push(
                        $queries,
                        "UPDATE products SET quantity = quantity - $orderItem->quantity WHERE id = '$orderItem->productId'"
                    );
                }

                $query = implode(';', $queries);
                $result = $this->db->perform_query($query, true);

                print_r($result);
                print_r($query);

                // return $body;
            }
        }

        return new UserEndpoint($c['db']);
    });
?>