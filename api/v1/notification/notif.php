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
    $desc = param($_POST['desc']);
    
    /*
        Function location in : /model/general/get_auth.php
    */
    $token = bearer_auth();
    
    $con->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    
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
            
            $photo_name='';
            if (!empty($_FILES["file"])){
                
                $target_dir = dirname($_SERVER["DOCUMENT_ROOT"]).'/public_html/images/admin/announcement';
                
                $photo_name = uniqid().".jpg";
                $target_file = $target_dir ."/". $photo_name;
                
                //upload file into 'temp' directory
                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            }
            
            $stmt = $con->prepare("INSERT INTO notifications(user_id,notifications_type,notifications_title,notifications_desc,notification_photo) SELECT user_id,0,'Pengumuman',?,? FROM user WHERE user_isactive=1 AND user_id!=''");   
                
            $stmt->bind_param("ss",$desc,$photo_name);
                
            $stmt->execute();
            
            $stmt->close();

            /*
                Function location in : functions.php
            */
            notif($desc);
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