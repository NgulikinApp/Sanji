<?php
    /*
        This API used in ngulikin.com/js/module-home.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/api/model/general/get_auth.php';
    include $_SERVER['DOCUMENT_ROOT'].'/api/model/beanoflink.php';
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
    $search = param($_GET['search']);
    //$page = param(@$_GET['page']);
    //$pagesize = param(@$_GET['pagesize']);
    
    /*
        Function location in : /model/general/get_auth.php
    */
    $token = bearer_auth();
    
    $con->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);
    
    if($token == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel getting from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            if(isset($_SESSION['user_admin'])){
                $user_id = $_SESSION['user_admin']["user_id"]; 
                $key = $_SESSION['user_admin']["key"];
            }else{
                $user_id = '';
                $key = '';
            }
            
            /*
                Function location in : /model/general/functions.php
            */
            if(checkingAuthKey($con,$user_id,$key,1,$cache) == 0){
                return invalidKey();
            }
            
            $sql = "SELECT 
                        `user`.user_id,
                        username,
                        photo_card,
                        photo_selfie,
                        shop_name,
                        shop_icon
                    FROM 
                        `user`
                        LEFT JOIN shop ON shop.user_id=`user`.user_id
                    WHERE 
                        user_seller='1'";
            
            if($search != ''){
                $sql .= "AND
                        (
                            username LIKE ?
                            OR
                            shop_name LIKE ?
                        )";
            }
            
            $stmt = $con->prepare($sql);
            
            if($search != ''){
                $param = "%{$search}%";
                $stmt->bind_param("ss", $param,$param);
            }
            /*
                Function location in : functions.php
            */
            listPendingUser($stmt);
        }catch(Exception $e){
            /*
                Function location in : /model/general/functions.php
            */
            tokenExpired();
        }
    }
    
    $con->commit();
    
    /*
        Function location in : /model/connection.php
    */
    conn_close($con);
?>