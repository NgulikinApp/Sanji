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
    $filter = $_GET['filter'];
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
            //secretKey variabel getting from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $stmt = $con->prepare("SELECT 
                                            product_id, 
                                            username,
                                            product_name,
                                            product_image,
                                            product_price,
                    						(
                                                                SELECT 
                                                                        COUNT(product_favorite_id) 
                                                                FROM 
                                                                        product_favorite 
                                                                WHERE 
                                                                        product_favorite.product_id=product.product_id 
                                                                        AND 
                                                                        user_id = ?
                                                            ) AS product_isfavorite
                                    FROM 
                                            product
                                            LEFT JOIN shop ON shop.shop_id = product.shop_id
                                            LEFT JOIN `user` ON `user`.user_id = shop.user_id
                    				
                    		    ORDER BY 
                                            product_id DESC
                    				LIMIT 6");
            
            $stmt->bind_param("s", $user_id);
            
            /*
                Function location in : functions.php
            */
            feed($stmt);
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