<?php
    /*
        This API used in ngulikin.com/js/module-home.js
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
        Parameters
    */
    $linkArray = explode('/',$actual_link);
    $id = intval(array_values(array_slice($linkArray, -1))[0]);
    
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
            //secretKey variabel getting from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $stmt = $con->prepare("INSERT INTO shop_discuss(shop_id,user_id,shop_discuss_comment) VALUES(?,?,?)");
               
            $stmt->bind_param("iss", $id,$request['user_id'],$request['comment']);
            
            /*
                Function location in : /model/general/functions.php
            */
            runQuery($stmt);
            
            $shop_discuss_id = $con->insert_id;
            
            $stmt = $con->prepare("UPDATE shop SET shop_total_discuss=shop_total_discuss+1 where shop_id=?");
               
            $stmt->bind_param("i", $id);
            
            /*
                Function location in : /model/general/functions.php
            */
            runQuery($stmt);
            
            $stmt = $con->prepare("SELECT 
                                        username,
                                        user_photo,
                                        fullname,
                                        DATE_FORMAT(shop_discuss_createdate, '%W, %d %M %Y') AS comment_date
                                    FROM 
                                        shop_discuss
                                        LEFT JOIN `user` ON `user`.user_id = shop_discuss.user_id
                                    WHERE
                                        shop_discuss_id = ?");
            
            $stmt->bind_param("i", $shop_discuss_id);
            
            comment($stmt,$shop_discuss_id,$id,$request['user_id'],$request['comment']);
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