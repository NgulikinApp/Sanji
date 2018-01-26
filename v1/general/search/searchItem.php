<?php
    /*
        This API used in ngulikin.com/js/module-forgotpassword.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/beanoflink.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/general/get_auth.php';
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
        Function location in : /model/general/get_auth.php
    */
    $token = bearer_auth();
    
    /*
        Parameters
    */
    $name = @$_GET['name'];
    $selsort = @$_GET['selsort'];
    $pricemax = @$_GET['pricemax'];
    $pricemin = @$_GET['pricemin'];
    $rate = @$_GET['rate'];
    $type = @$_GET['type'];
    $page = @$_GET['page'];
    $pagesize = @$_GET['pagesize'];
    $category = @$_GET['category'];
    
    if($token == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel getting from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $query = "";
            if($type == 'product'){
                $query .= "SELECT 
                                @count_product := COUNT(product_id) 
                            FROM 
                                product
                            WHERE
                                1=1";
                
                /*
                    Function location in : function.php
                */            
                $query .= buildCondition_item(@$name,@$pricemax,@$pricemin,@$rate,@$category);
                
                $query .= ";";
                
                $query .= "SELECT 
                                product_id,
                                product_name,
                                product_image,
                                product_price,
                                username,
                                DATEDIFF(CURDATE(),CAST(product_createdate AS DATE)) AS difdate,
                                @count_product AS count_product
                            FROM 
                                product
                                LEFT JOIN shop ON shop.shop_id = product.shop_id
                                LEFT JOIN `user` ON `user`.user_id = shop.user_id
                            WHERE
                                1=1";
                
                /*
                    Function location in : function.php
                */
                $query .= buildCondition_item(@$name,@$pricemax,@$pricemin,@$rate,@$category);
                
                $query .= ' AND product_isactive=1';
                
                $query .= ' ORDER BY';
                if(@$selsort == 2){
                    $query .= ' product_price';
                }else if(@$selsort == '3'){
                    $query .= ' product_id';
                }else if(@$selsort == '4'){
                    $query .= ' product_price DESC';
                }else{
                    $query .= ' product_id DESC';
                }
            }else{
                $query .= "SELECT 
                                @count_shop := COUNT(shop_id) 
                            FROM 
                                shop
                            WHERE
                                1=1";
                if(@$name != ''){
                    $query .= " AND shop_name='".$name."'";
                }
                
                $query .= ";";
                
                $query .= "SELECT 
                                    shop_id, 
                                    shop_name,
                                    shop_icon,
                                    username,
                                    DATEDIFF(CURDATE(),CAST(shop_createdate AS DATE)) AS difdate,
                                    @count_shop AS count_shop
                            FROM 
                                    shop
                                    LEFT JOIN `user` ON `user`.user_id = shop.user_id
                            WHERE
                                    1=1";
                
                if(@$name != ''){
                    $query .= " AND shop_name='".$name."'";
                }
                
                $query .= ' ORDER BY shop_id DESC';
            }
            
            
            $query .= ' LIMIT '.$page.','.$pagesize;
            
            /*
                Function location in : functions.php
            */                            
            dosearch($query,$con,$type,$pagesize);
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