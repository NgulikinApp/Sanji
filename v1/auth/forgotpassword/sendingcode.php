<?php
    /*
        This API used in ngulikin.com/js/module-forgotpassword.js
    */
    
    date_default_timezone_set('Etc/UTC');
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/beanoflink.php';
    include $_SERVER['DOCUMENT_ROOT'].'/v1/auth/functions.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/general/get_auth.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/general/postraw.php';
    
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
    
    if($token == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel got from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $stmt = $con->prepare("SELECT 
                                        user_id,
                                        user_isactive,
                                        email,
                                        fullname
                                    FROM 
                                            user 
                                    WHERE 
                                            (email=? OR username=?)");
               
            $stmt->bind_param("ss", $request['email'],$request['email']);
            
            /*
                Function location in : /model/auth/function.php
            */
            $verified = get_account_forgotpassword($stmt);
            
            if($verified[0] == ""){
                /*
                    Function location in : /model/auth/function.php
                */
                wrongpassoremail_account(0);
            }else if($verified[1] == 0){
                /*
                    Function location in : /model/auth/function.php
                */
                incative_account();
            }else{
                /*
                    Function location in : /model/general/functions.php
                */
                $code = generateRandomString();
                
                $stmt = $con->prepare("UPDATE 
                                            user
                                        SET
                                            code_forgotpassword = ?,
                                            time_code_forgotpassword = NOW()
                                        WHERE 
                                            user_id = ?");
                   
                $stmt->bind_param("ss", $code,$verified[0]);
                
                /*
                    Function location in : /model/general/function.php
                */
                runQuery($stmt);
                $mail = new PHPMailer;
                $mail->isSMTP();
            	$mail->Debugoutput = 'html';
            	$mail->Host = "mail.ngulikin.com";
            	$mail->Port = 25;
            	$mail->SMTPAuth = true;
            	$mail->Username = "ngulikin";
            	$mail->Password = "A98dNzn33n";
            	$mail->setFrom("info@ngulikin.com", "Ngulikin");
            	$mail->addAddress($verified[2], $verified[3]);
            	$mail->Subject = 'Ngulikin (Forgot Password)';
            	$mail->Body = "Peringatan, jangan memberi tahu code berikut untuk menjaga kerahasiaan privasi anda.<br><br>Kode anda adalah <b>".$code."</b>";
            	$mail->AltBody = 'This is a plain-text message body';
            	
            	//email sended successfully
            	if ($mail->send()) {
            	    $result = array(
            			'email' => $verified[2]
            		);
            		$data = array(
            			'status' => "OK",
            			'message' => "Email has been sent successfully",
            			'response' => $result
            		);
            	} else {
            		$result = (object)array();
            		$data = array(
            			'status' => "NO",
            			'message' => "Email has not been sent",
            			'response' => $result
            		);
            	}
            	
            	/*
                    Function location in : /model/generatejson.php
                */
            	generateJSON($data);
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