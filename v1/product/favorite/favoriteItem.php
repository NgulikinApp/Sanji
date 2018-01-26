<?php
    /*
        This API used in ngulikin.com/js/module-product.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
	include $_SERVER['DOCUMENT_ROOT'].'/model/general/get_auth.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/general/postraw.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/beanoflink.php';
    include 'functions.php';
    
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
            
            /*
                Function location in : /model/general/functions.php
            */
            if(checkingAuthKey($con,$request['user_id'],$request['key']) == 0){
                return invalidKey();
            }
            
            $stmt = $con->prepare("SELECT 
                                    1
                                    FROM 
                                        product_favorite 
                                    WHERE 
                                        product_id=? AND user_id=?");
               
            $stmt->bind_param("is", $request['product_id'],$request['user_id']);
            
             /*
                Function location in : /model/general/functions.php
            */
            $count_rows = count_rows($stmt);
            
            if($count_rows > 0){
                return userDone("favorite");
            }
            
            $stmt = $con->prepare("INSERT INTO product_favorite(product_id,user_id) VALUES(?,?)");
               
            $stmt->bind_param("is", $request['product_id'],$request['user_id']);
            
            /*
                Function location in : /model/general/functions.php
            */
            runQuery($stmt);
            
            $stmt = $con->prepare("UPDATE product SET product_count_favorite=product_count_favorite+1 where product_id=?");
               
            $stmt->bind_param("i", $request['product_id']);
            
            /*
                Function location in : /model/general/functions.php
            */
            runQuery($stmt);
            
            $stmt = $con->prepare("UPDATE user SET user_product_favorites=user_product_favorites+1 where user_id=?");
               
            $stmt->bind_param("s", $request['user_id']);
            
            /*
                Function location in : /model/general/functions.php
            */
            runQuery($stmt);
            
            $stmt = $con->prepare("SELECT 
                                        count(1) AS product_count
                                    FROM 
                                        product_favorite 
                                    WHERE 
                                        product_id=?");
               
            $stmt->bind_param("i", $request['product_id']);
            
            /*
                Function location in : /model/general/functions.php
            */
            $count_val = calc_val($stmt);
            
            favorite($request['product_id'],$request['user_id'],$count_val);
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