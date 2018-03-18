<?php
    /*
        This API used in ngulikin.com/js/module-shop.js
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
    
    //Parameters
    $linkArray = explode('/',$actual_link);
    $id = intval(array_values(array_slice($linkArray, -1))[0]);
    $page = $_GET['page'];
    $pagesize = $_GET['pagesize'];
    
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
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $stmt = $con->prepare(" SELECT 
                                        * 
                                    FROM (
                                        SELECT 
                                            shop_discuss_id,
                                            shop_discuss.user_id,
                                            shop_discuss_comment,
                                            username,
                                            user_photo,
                                            fullname,
                                            DATE_FORMAT(shop_discuss_createdate, '%W, %d %M %Y') AS comment_date,
                                            shop_total_discuss
                                        FROM 
                                            shop_discuss
                                            LEFT JOIN `user` ON `user`.user_id = shop_discuss.user_id
                                            LEFT JOIN `shop` ON `shop`.shop_id = shop_discuss.shop_id
                                        WHERE
                                            shop_discuss.shop_id = ?
                                        ORDER BY 
                                            shop_discuss_id DESC
                                        LIMIT ?
                                    ) sub
                                    ORDER BY 
                                        shop_discuss_id ASC");
            
            $stmt->bind_param("ii", $id,$pagesize);
            
            /*
                Function location in : function.php
            */
            discuss($stmt,$pagesize);
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