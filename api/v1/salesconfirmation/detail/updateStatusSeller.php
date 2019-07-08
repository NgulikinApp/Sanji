<?php
    /*
        This API used in ngulikin.com/js/module-profile.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
	include $_SERVER['DOCUMENT_ROOT'].'/api/model/general/get_auth.php';
	include $_SERVER['DOCUMENT_ROOT'].'/api/model/general/postraw.php';
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
    
    /*
        Function location in : /model/general/postraw.php
    */
    $request = postraw();
    
    /*
        Parameters
    */
    $user_id = param($request['user_id']);
    $action = param($request['action']);
    
    $con->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    
    if($token == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel got from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            if(isset($_SESSION['user'])){
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
            
            $stmt = $con->prepare("SELECT 
                                        email,
                                        fullname
                                    FROM 
                                        user 
                                    WHERE 
                                        user_id=?");
            
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            
            $stmt->bind_result($col1,$col2);
            
            $stmt->fetch();
            
            $email = $col1;
            $fullname = $col2;
            
            $stmt->close();
            
            $status = ($action==0)?'2':'0';
            $stmt = $con->prepare("UPDATE user SET user_seller=?, user_admin_confirm_seller=?, user_admin_confirm_seller_date=NOW() WHERE user_id=?");   
                
            $stmt->bind_param("sss", $status,$user_id,$user_id);
                
            $stmt->execute();
            
            $stmt->close();
            
            $body = ($action==0)?'Selamat anda sekarang sudah bisa berjualan di Ngulikin':'Maaf, permintaan anda menjadi penjual ditolak';
            
            $statusNotif = ($action==0)?2:3;
            
            $stmt = $con->prepare("INSERT INTO notifications(user_id,notifications_type,notifications_title,notifications_desc) VALUES('1PrAX8uP6UYLW4yu', ?, 'Permintaan konfirmasi sebagai penjual', ?)");   
                
            $stmt->bind_param("is", $statusNotif,$body);
                
            $stmt->execute();
            
            $stmt->close();
            /*
                Function location in : /model/general/functions.php
            */
            sendEmail("info@ngulikin.com","Ngulikin",$email,$fullname,"Permintaan konfirmasi sebagai penjual",$body);
            
            /*
                Function location in : functions.php
            */
            updateStatusSeller($user_id);
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