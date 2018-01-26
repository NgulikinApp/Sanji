<?php
    /*
        This API used in ngulikin.com/js/module-product.js
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
    
    $user_id = $_GET['user_id'];
    
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
            
            $stmt = $con->prepare("
                                    SELECT 
                                            inner1.*,
                                            COUNT(shop_favorite_id) AS shop_isfavorite 
                                    FROM(
                                            SELECT 
                                                    shop_id,
                                                    `user`.user_id,
                                                    username,
                                                    shop_name,
                                                    shop_icon,
                                                    shop_description,
                                                    shop_banner,
                                                    IFNULL(university,'') AS university,
                                        			user_photo
                                            FROM 
                                                    shop
                                                    LEFT JOIN `user` ON `user`.user_id = shop.user_id
                                            WHERE
                                                    shop_id=?
                                    ) AS inner1 
                                        LEFT JOIN shop_favorite ON inner1.shop_id=shop_favorite.shop_id
                                    WHERE 
                                        shop_favorite.user_id = ?");
            $stmt->bind_param("is", $id,$user_id);
            /*
                Function location in : functions.php
            */
            detail($stmt);
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