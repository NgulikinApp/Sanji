<?php
    function generateJSON($data){
        header('Content-Type: application/json');
		http_response_code('200');
        if(function_exists('ob_gzhandler')) ob_start('ob_gzhandler');else ob_start();
		echo json_encode($data);
		ob_end_flush();
    }
?>