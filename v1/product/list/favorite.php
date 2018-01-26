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
            
            /*
                Function location in : /model/general/functions.php
            */
            if(checkingAuthKey($con,$user_id,$key) == 0){
                return invalidKey();
            }
            
            $stmt = $con->prepare("SELECT 
                                        product.product_id,
                                        product_name,
                                        product_image,
                                        product_price,
                                        username,
                                        DATEDIFF(CURDATE(),CAST(product_createdate AS DATE)) AS difdate,
                                        user_product_favorites AS count_product
                                    FROM 
                                        product
                                        LEFT JOIN product_favorite ON product_favorite.product_id = product.product_id
                                        LEFT JOIN `user` ON `user`.user_id = product_favorite.user_id
                                    WHERE
                                        product_favorite.user_id = ?
                                    ORDER BY 
                                        product_favorite.product_id DESC
                                    	LIMIT ?,?");
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