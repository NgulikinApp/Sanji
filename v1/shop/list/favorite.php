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
    $user_id = $_GET['user_id'];
    $page = $_GET['page'];
    $pagesize = $_GET['pagesize'];
    $key = $_GET['key'];
    
    if(checkingAuthKey($con,$user_id,$key) == 0){
        invalidKey();
    }
    
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
            
            $query = "SELECT 
                                    shop.shop_id, 
                                    shop_name,
                                    shop_icon,
                                    username,
                                    DATEDIFF(CURDATE(),CAST(shop_createdate AS DATE)) AS difdate,
                                    user_shop_favorites AS count_shop
                        FROM 
                                    shop
                                    LEFT JOIN shop_favorite ON shop_favorite.shop_id = shop.shop_id
                                    LEFT JOIN `user` ON `user`.user_id = shop_favorite.user_id
                                WHERE
                                    shop_favorite.user_id = ?
                        		ORDER BY 
                                    shop_favorite.shop_id DESC
                        		LIMIT ?,?";
            
            $stmt->bind_param("sii", $user_id,$page,$pagesize);
            
            /*
                Function location in : functions.php
            */
            
            favorite($stmt,$pagesize);
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