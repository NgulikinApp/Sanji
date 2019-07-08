<?php
	namespace handler\output; 
	
	function GenerateJson($data,$code,$status,$message){
		header('Content-Type: application/json');
		http_response_code($code);
		$output = array(
						'success' =>  $status,
						'message' => $message,
						'result' => $data
						);

		if(function_exists('ob_gzhandler')) ob_start('ob_gzhandler');else ob_start();
		echo json_encode($output);
		ob_end_flush();
	}

	function GenerateJsonWithoutHeader($data,$status,$message){
		$output = array(
						'success' =>  $status,
						'message' => $message,
						'result' => $data
						);

		if(function_exists('ob_gzhandler')) ob_start('ob_gzhandler');else ob_start();
		echo json_encode($output);
		ob_end_flush();
	}

	function GenerateJsonWithAuth($data,$code,$status,$message,$token){
		header('Content-Type: application/json');
		header('Authorization: Bearer '.$token);
		http_response_code($code);
		$output = array(
						'success' =>  $status,
						'message' => $message,
						'result' => $data
						);

		if(function_exists('ob_gzhandler')) ob_start('ob_gzhandler');else ob_start();
		echo json_encode($output);
		ob_end_flush();
	}