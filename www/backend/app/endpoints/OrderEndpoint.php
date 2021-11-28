<?php

    require_once dirname(__DIR__).'/DAOs/Order.php';
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
                    print_r($orderItem->quantity);
                    array_push(
                        $queries,
                        "UPDATE products SET quantity = quantity - $orderItem->quantity WHERE id = '$orderItem->productId'"
                    );

                }
                
                $query = implode(';', $queries);
                $result = $this->db->perform_query($query, true);
                

                $order_dao = new OrderDAO($this->db);
                foreach ($body as $__ => $orderItem) {

                    $order_result = $order_dao->create(array(
                        "productName" => $orderItem->productName,
                        "price" => $orderItem->price,
                        "quantity" => $orderItem->quantity,
                        "total" => $orderItem->quantity * $orderItem->price
                    ));
                }

                if (!$order_result) {
                    return mysqli_error($this->db);
                    throw new UnableToFulFillRequest();
                }

                // print_r($result);
                // print_r($query);

                return $body;
            }
        }

        return new UserEndpoint($c['db']);
    });

    $context->register_endpoint('/orders', function ($c) {
        class UserEndpoint {

            private $db;

            public function __construct($database) {
                $this->db = $database;
            }

            public function get($body, $params) {

                $order_dao = new OrderDAO($this->db);
                $result = $order_dao->getOrders();
                $entries = [];

                while ($entry = $result->fetch_object()) {
                    array_push($entries, $entry);
                }

                return $entries;
            }
        }

        return new UserEndpoint($c['db']);
    });
?>