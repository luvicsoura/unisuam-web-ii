<?php
    require_once dirname(__FILE__).'/get_files_in_dir.php';


    function require_all_from_dir($dir, $context) {

        $filenames = get_files_in_dir($dir);

        foreach($filenames as $filename) {
            require_once $filename;
        }
    }
?>