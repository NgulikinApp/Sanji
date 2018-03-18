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
    $type = $_GET['type'];
    
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
            
            if($type == 0){
                $stmt = $con->prepare(" SELECT 
                                            * 
                                        FROM (
                                            SELECT 
                                                shop_review_id,
                                                shop_review.user_id,
                                                shop_review_comment,
                                                username,
                                                user_photo,
                                                fullname,
                                                DATE_FORMAT(shop_review_createdate, '%W, %d %M %Y') AS comment_date,
                                                shop_total_review
                                            FROM 
                                                shop_review
                                                LEFT JOIN `user` ON `user`.user_id = shop_review.user_id
                                                LEFT JOIN `shop` ON `shop`.shop_id = shop_review.shop_id
                                            WHERE
                                                shop_review.shop_id = ?
                                            ORDER BY 
                                                shop_review_id DESC
                                            LIMIT ?
                                        ) sub
                                        ORDER BY 
                                            shop_review_id ASC");
                
                $stmt->bind_param("ii", $id,$pagesize);
            }else{
                $stmt = $con->prepare(" SELECT 
                                                shop_review_id,
                                                shop_review.user_id,
                                                shop_review_comment,
                                                username,
                                                user_photo,
                                                fullname,
                                                DATE_FORMAT(shop_review_createdate, '%W, %d %M %Y') AS comment_date,
                                                shop_total_review
                                            FROM 
                                                shop_review
                                                LEFT JOIN `user` ON `user`.user_id = shop_review.user_id
                                                LEFT JOIN `shop` ON `shop`.shop_id = shop_review.shop_id
                                            WHERE
                                                shop_review.shop_id = ?
                                            ORDER BY 
                                                shop_review_id DESC
                                            LIMIT ?,?");
                
                $stmt->bind_param("iii", $id,$page,$pagesize);
            }
            
            /*
                Function location in : function.php
            */
            review($stmt,$pagesize);
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