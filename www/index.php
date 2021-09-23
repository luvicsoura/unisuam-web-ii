<?php 
    require_once './app/app.php';

    try {
        header('Access-Control-Allow-Origin: *');

        $app = App::getInstance();
        $app->init();
    
    } catch (Throwable $error) {
        echo $error;
        http_response_code(500);
    }
?>