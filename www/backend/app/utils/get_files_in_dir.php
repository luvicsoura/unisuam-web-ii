<?php 

    function get_files_in_dir($dir) {
        
        $filenames = array_diff(
            scandir($dir),
            array('.', '..')
        );

        return array_map(fn($name) => "$dir/$name", $filenames);
    }

?>