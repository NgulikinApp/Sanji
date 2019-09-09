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
            
            $sql = "SELECT 
                        invoice_id,
                        fullname,
                        email,
                        invoice_no,
                        invoice_photopayment,
                        DATE_FORMAT(FROM_UNIXTIME(invoice_paiddate), '%e %b %Y') AS paiddate,
                        username
                    FROM 
                        invoice
                        LEFT JOIN user ON user.user_id=invoice.user_id
                    WHERE 
                        invoice_isuploaded_payment='1'";
            
            $stmt = $con->prepare($sql);
            /*
                Function location in : functions.php
            */
            uploadedProof($stmt);
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