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
            
            $month = date('m');
            $year = date('Y');
            
            $prevmonth = ($month-1 != 0)?$month:12;
            $prevyear = ($prevmonth != 12)?$year:$year-1;
            
            $stmt = $con->prepare("SELECT (
                            			SELECT 
                                           count(user_id) as sumUser 
                                        FROM 
                                           user
                                        WHERE
                            				YEAR(time_signup) = ?
                            				AND
                            				MONTH(time_signup) = ?
                            				AND 
                            				user_isactive=1) AS sumUser,
                            		(
                            			SELECT 
                                           count(user_id) as lastSumUser 
                                        FROM 
                                           user
                                        WHERE
                            				YEAR(time_signup) = ?
                            				AND
                            				MONTH(time_signup) = ?
                            				AND 
                            				user_isactive=1
                            			   
                            		) AS lastSumUser,
                                    (
                            			SELECT 
                                           count(user_id) as sumUserSeller 
                                        FROM 
                                           user
                                        WHERE
                            				YEAR(user_admin_confirm_seller_date) = ?
                            				AND
                            				MONTH(user_admin_confirm_seller_date) = ?
                            				AND
                            				user_seller='2'
                            			   
                            		) AS sumUserSeller,
                            		(
                            			SELECT 
                                           count(user_id) as lastSumUserSeller 
                                        FROM 
                                           user
                                        WHERE
                            				YEAR(user_admin_confirm_seller_date) = ?
                            				AND
                            				MONTH(user_admin_confirm_seller_date) = ?
                            				AND
                            				user_seller='2'
                            			   
                            		) AS lastSumUserSeller,
                                    (
                            			SELECT 
                                           count(product_id) as sumProduct 
                                        FROM 
                                           product
                                        WHERE
                            				YEAR(product_createdate) = ?
                            				AND
                            				MONTH(product_createdate) = ?  
                            		) AS sumProduct,
                            		(
                            			SELECT 
                                           count(product_id) as lastSumProduct 
                                        FROM 
                                           product
                                        WHERE
                            				YEAR(product_createdate) = ?
                            				AND
                            				MONTH(product_createdate) = ?  
                            		) AS lastSumProduct,
                                    (
                            			SELECT 
                                           count(invoice_status_detail_id) as sumTransactions 
                                        FROM 
                                           invoice_status_detail
                                        WHERE
                            				YEAR(invoice_status_detail_createdate) = ?
                            				AND
                            				MONTH(invoice_status_detail_createdate) = ?
											AND
                            				status_id=2 
                            		) AS sumTransactions,
                                    (
                            			SELECT 
                                           count(invoice_status_detail_id) as lastSumTransactions 
                                        FROM 
                                           invoice_status_detail
                                        WHERE
                            				YEAR(invoice_status_detail_createdate) = ?
                            				AND
                            				MONTH(invoice_status_detail_createdate) = ?
											AND
                            				status_id=2 
                            		) AS lastSumTransactions");
            
            $stmt->bind_param("iiiiiiiiiiiiiiii",$year,$month,$prevyear,$prevmonth,$year,$month,$prevyear,$prevmonth,$year,$month,$prevyear,$prevmonth,$year,$month,$prevyear,$prevmonth);
            
            $stmt->execute();
            
            $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8);
            
            $stmt->fetch();
            
            $sumUser = $col1;
            $lastSumUser = $col2;
            $sumUserSeller = $col3;
            $lastSumUserSeller = $col4;
            $sumProducts = $col5;
            $lastSumProducts = $col6;
            $sumTransactions = $col7;
            $lastSumTransactions = $col8;
            
            $stmt->close();
            /*
                Function location in : functions.php
            */
            sumStatistics($sumUser,$lastSumUser,$sumUserSeller,$lastSumUserSeller,$sumProducts,$lastSumProducts,$sumTransactions,$lastSumTransactions);
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