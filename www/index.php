<?php 
    require_once './app/app.php';

    try {
        
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $app = App::getInstance();
        $app->init();
    
    } catch (Throwable $error) {

        if (is_subclass_of($error, 'RequestError')) {
            http_response_code($error->get_code());
            echo $error->to_json();
            exit();
        }
        
        http_response_code(500);
    }
?>