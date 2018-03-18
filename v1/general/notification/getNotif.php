<?php
    /*
        This API used in ngulikin.com/js/module-home.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/general/get_auth.php';
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
    $keyword = $_GET['keyword'];
    
    /*
        Function location in : /model/general/get_auth.php
    */
    $token = bearer_auth();
    
    if($token == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel getting from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $sql = "SELECT 
                        notification_id, 
                        sender_id AS user_id,
                        notification_desc,
                        notification_photo,
                        DATE_FORMAT(notification_createdate, '%W, %d %M %Y') AS notification_createdate,
                        username
                    FROM 
                        notification
                        LEFT JOIN `user` ON notification.user_id=`user`.user_id
                    WHERE
                        notification.user_id = ?";
            if($keyword != ''){
                $sql .= " AND notification_desc LIKE '%?%'";
            }
            $sql .= " ORDER BY 
                        notification_id DESC";
            $stmt = $con->prepare($sql);
            
            if($keyword != ''){
                $stmt->bind_param("ss", $id,$keyword);
            }else{
                $stmt->bind_param("s", $id);
            }
            /*
                Function location in : functions.php
            */
            notification($stmt);
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