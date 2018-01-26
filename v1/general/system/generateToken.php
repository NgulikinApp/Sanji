<?php
    header('Access-Control-Allow-Origin: http://init.ngulikin.com');
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/jwt.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/generatejson.php';
    
    /*
        Function location in : /model/jwt.php
    */
    use \Firebase\JWT\JWT;
    
    //set time expired
    $payload = array(
        "exp" => time() + (60 * 60)
    );
    
    $jwt = JWT::encode(
                    $payload, //Data to be encoded in the JWT
                    $secretKey 
                );
                
    $data = array('result' => $jwt);
    
    /*
        Function location in : /model/generatejson.php
    */
    generateJSON($data);
?>