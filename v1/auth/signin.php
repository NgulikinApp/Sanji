<?php
    /*
        This API used in ngulikin.com/js/module-signin.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
	include $_SERVER['DOCUMENT_ROOT'].'/model/general/get_auth.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/general/postraw.php';
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
        Function location in : /model/general/get_auth.php
    */
    $auth = basic_auth();
    
    /*
        Function location in : /model/general/postraw.php
    */
    $request = postraw();
    
    if($request['token'] == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel got from : /model/jwt.php
            $exp = JWT::decode($request['token'], $secretKey, array('HS256'));
            
            $param = '';
            $password = '';
            if(intval($request['manual']) == 1){
                $password = "password";
            }else{
                $password = "password_socmed";
            }
            
            $stmt = $con->prepare("SELECT 
                                    user_id,
                                    user_isactive,
                                    ".$password.",
                                    socmed,
                                    id_socmed
                                FROM 
                                    user 
                                WHERE 
                                    (email=? OR username=?)");
               
            $stmt->bind_param("ss", $auth[0],$auth[0]);
                
            /*
                Function location in : functions.php
            */
            $verified = account_verify($stmt);
            
            if($verified[0] == ""){
                /*
                    Function location in : functions.php
                */
                notexist_account();
            
            /*
                Function location in : /model/general/functions.php
            */
            }else if(($auth[1] != $verified[2] && intval($request['manual']) == '1') || (ListFindNoCase($verified[2],$auth[1]) == false && intval($request['manual']) == '0')){
                if(intval($request['manual']) == 0 && ListFindNoCase($verified[3],$request['socmed']) == false){
                    $password = $verified[2].','.$auth['1'];
                    $socmed = $verified[3].','.$request['socmed'];
                    $idsocmed = $verified[4].','.$request['id_socmed'];
                    
                    $key = encrypt_hash('ngulik_'.$verified[0].date('Y-m-d H:i:s'));
                    
                    $stmt = $con->prepare("UPDATE 
                                                user
                                            set
                                                time_signin = NOW(),
                                                password_socmed = ?,
                                                socmed = ?,
                                                id_socmed = ?,
                                                user_key = ?
                                            WHERE 
                                                user_id=?");
                    $stmt->bind_param("sssss",$password,$socmed,$idsocmed,$verified[0],$key);
                    
                    /*
                        Function location in : /model/general/functions.php
                    */
                    runQuery($stmt);
                    
                    /*
                        Function location in : functions.php
                    */
                    returndata_signin($verified[0],$con);
                    
                    return;
                }
                /*
                    Function location in : functions.php
                */
                wrongpassoremail_account(1);
            }else if($verified[1] == 0){
                /*
                    Function location in : functions.php
                */
                incative_account();
            }else{
                $key = encrypt_hash('ngulik_'.$verified[0].date('Y-m-d H:i:s'));
                
                $stmt = $con->prepare("UPDATE 
                                            user
                                        set
                                            time_signin = NOW(),
                                            user_key = ?
                                        WHERE 
                                            user_id=?");
                $stmt->bind_param("ss", $key,$verified[0]);
                
                /*
                    Function location in : /model/general/functions.php
                */
                runQuery($stmt);
                
                /*
                    Function location in : functions.php
                */
                returndata_signin($verified[0],$con);
            }
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