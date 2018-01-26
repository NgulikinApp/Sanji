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
                                            count(product_rate_id) AS product_israte,
                                            (
                                                SELECT 
                                                        COUNT(product_favorite_id) 
                                                FROM 
                                                        product_favorite 
                                                WHERE 
                                                        product_favorite.product_id=inner1.product_id 
                                                        AND 
                                                        user_id = ?
                                            ) AS product_isfavorite,
                                            IFNULL(product_rate_value,0) AS product_rate_value 
                                    FROM(
                                        SELECT 
                                            product_id,
                                            `user`.user_id,
                                            username,
                                            product_name,
                                            product_image,
                                            product_price,
                                            product_description,
                                            product_rate,
                                            product_stock,
                                            shop_icon,
                                            shop_name,
    					                    product_count_favorite,
                                            product_average_rate
                                        FROM 
                                            product
                                            LEFT JOIN shop ON shop.shop_id = product.shop_id
                                            LEFT JOIN `user` ON `user`.user_id = shop.user_id
                                        WHERE
                                            product_id=?
                                    ) AS inner1 
                                        LEFT JOIN product_rate ON inner1.product_id=product_rate.product_id 
                                    WHERE 
                                        product_rate.user_id = ?");
            $stmt->bind_param("sis", $user_id,$id,$user_id);
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