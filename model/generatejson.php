<?php
    function generateJSON($data){
        if(function_exists('ob_gzhandler')) ob_start('ob_gzhandler');else ob_start();
		echo json_encode($data);
		ob_end_flush();
    }
?>