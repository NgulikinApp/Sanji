<?php
    /*
        This API used in ngulikin.com/js/module-forgotpassword.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/beanoflink.php';
    include $_SERVER['DOCUMENT_ROOT'].'/v1/auth/functions.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/general/get_auth.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/general/postraw.php';
	
	/*
        Function location in : /model/jwt.php
    */
    use \Firebase\JWT\JWT;
    
    /*
        Function location in : /model/connection.php
    */
    $con = conn();
    
    /*
        Function location in : /model/general/get_auth.php
    */
    $token = bearer_auth();
    
    /*
        Function location in : /model/general/postraw.php
    */
    $request = postraw();
    
    if($token == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel got from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $stmt = $con->prepare("SELECT 
                                        user_id
                                    FROM 
                                        user 
                                    WHERE 
                                        email=? 
                                        AND
                                        code_forgotpassword=?");
               
            $stmt->bind_param("ss", $request['email'],$request['code']);
            
            /*
                Function location in : /v1/auth/functions.php
            */
            $verified = code_verified($stmt);
            
            if($verified[0] == ""){
                /*
                    Function location in : /v1/auth/functions.php
                */
                wrong_code_verified();
            }else{
                $result = array(
            			'email' => $request['email']
            	);
            	$data = array(
            			'status' => "OK",
            			'message' => "Valid code",
            			'response' => $result
            	);
            	
            	/*
                    Function location in : /model/generatejson.php
                */
            	generateJSON($data);
            }
        }catch(Exception $e){
            /*
                Function location in : /model/general/functions.php
            */
            tokenExpired();
        }
    }
    
    /*
        Function location in : /model/connection.php
    */
    conn_close($con);
?>