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
    $type = param($_GET['type']);
    
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
            
            if($type == 0){
                $sql = "SELECT 
                            count(user_id) as sumUser,
                            CONCAT(MONTHNAME(time_signup),' ',YEAR(time_signup)) AS date 
                    FROM 
                            user
                    WHERE 
                            user_isactive=1
                    GROUP BY 
                            MONTHNAME(time_signup),
                            YEAR(time_signup)
                    ORDER BY 
                            time_signup DESC
                    LIMIT 7";
            }else if($type == 1){
                $sql = "SELECT 
                            count(user_id) as sumUserSeller,
                            CONCAT(MONTHNAME(user_admin_confirm_seller_date),' ',YEAR(user_admin_confirm_seller_date)) AS date 
                        FROM 
                            user
                        WHERE 
                            user_seller='2'
                        GROUP BY 
                            MONTHNAME(user_admin_confirm_seller_date),
                            YEAR(user_admin_confirm_seller_date)
                        ORDER BY 
                            user_admin_confirm_seller_date DESC
                        LIMIT 7";
            }else if($type == 2){
                $sql = "SELECT 
                                count(user_id) as sumProduct,
                                CONCAT(MONTHNAME(product_createdate),' ',YEAR(product_createdate)) AS date 
                        FROM 
                                product
                        GROUP BY 
                                MONTHNAME(product_createdate),YEAR(product_createdate)
                        ORDER BY 
                                product_createdate DESC
                        LIMIT 7";
            }else{
                $sql = "SELECT 
                            count(invoice_status_detail_id) as sumTransactions,
                                CONCAT(MONTHNAME(invoice_status_detail_createdate),' ',YEAR(invoice_status_detail_createdate)) AS date 
                        FROM 
                            invoice_status_detail
                        WHERE
                            status_id=2
                        GROUP BY 
                            MONTHNAME(invoice_status_detail_createdate),
                            YEAR(invoice_status_detail_createdate)
                        ORDER BY 
                            invoice_status_detail_createdate DESC
                        LIMIT 7";
            }
            
            $stmt = $con->prepare($sql);

            /*
                Function location in : functions.php
            */
            graphStatistics($stmt);
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