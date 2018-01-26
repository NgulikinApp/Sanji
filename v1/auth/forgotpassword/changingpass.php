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
            
            $stmt = $con->prepare("UPDATE 
                                        user
                                    SET
                                        password = ?
                                    WHERE 
                                        email = ?");
               
            $stmt->bind_param("ss", $request['password'],$request['email']);
            
            /*
                Function location in : /model/general/function.php
            */
            runQuery($stmt);
            
            $data = array(
            			'status' => "OK",
            			'message' => "Password has been changed, you can login now"
            );
            
            /*
                Function location in : /model/generatejson.php
            */
            generateJSON($data);
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