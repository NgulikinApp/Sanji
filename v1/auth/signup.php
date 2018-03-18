<?php
    /*
        This API used in ngulikin.com/js/module-signup.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/beanoflink.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/general/get_auth.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/general/postraw.php';
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
    
    if($token == ''){
        /*
            Function location in : /model/general/functions.php
        */
        invalidCredential();
    }else{
        try{
            //secretKey variabel got from : /model/jwt.php
            $exp = JWT::decode($token, $secretKey, array('HS256'));
            
            $data = array();
            $arrayCheck = true;
            foreach ($request as $key => $value){
                if($value == "" && $key != 'manual' && $key != 'id_socmed'){
                    $data[$key] = "empty";
                    $arrayCheck = false;
                }else if($key == 'username'){
                    $stmt = $con->prepare("SELECT 1 FROM user WHERE username=?");
                    $stmt->bind_param("s", $value);
                    if(preg_match('/^[\w]+$/', $value) == false && strlen($value) <= 3){
                        $data[$key] = "invalid";
                        $arrayCheck = false;
                    /*
                        Function location in : /model/general/functions.php
                    */
                    }else if(count_rows($stmt) > 0){
                        $data[$key] = "exist";
                        $arrayCheck = false;
                    }else{
                        $data[$key] = "valid";
                    }
                }else if($key == 'email'){
                    $stmt = $con->prepare("SELECT 1 FROM user WHERE email=?");
                    $stmt->bind_param("s", $value);
                    
                    /*
                        Function location in : /model/general/functions.php
                    */
                    if(check_email_address($value) == false){
                        $data[$key] = "invalid";
                        $arrayCheck = false;
                    /*
                        Function location in : /model/general/functions.php
                    */
                    }else if(count_rows($stmt) > 0){
                        $data[$key] = "exist";
                        $arrayCheck = false;
                    }else{
                        $data[$key] = "valid";
                    }
                }else if($key == 'dob' && checkdate(explode("-", $value)[2], explode("-", $value)[1], explode("-", $value)[0]) == false){
                    $data[$key] = "invalid";
                    $arrayCheck = false;
                }else{
                    $data[$key] = "valid";
                }
            }
            
            if($arrayCheck){
                /*
                    Function location in : /v1/auth/function.php
                */
                $key = encrypt_hash('ngulik_'.$request['username'].date('Y-m-d H:i:s'));
                /*
                    Function location in : /model/general/functions.php
                */
                $user_id = getID(16);
                $ismanual = intval($request['manual']);
                
                $passwordSocmed = '';
                $user_isactive = 0;
                if($request['socmed'] == 'facebook'){
                    $user_isactive = 1;
                    $passwordSocmed = encrypt_hash('Facebook_Ngulikin');
                }else if($request['socmed'] == 'googleplus'){
                    $user_isactive = 1;
                    $passwordSocmed = encrypt_hash('GooglePlus_Ngulikin');
                }
                
                $stmt = $con->prepare("INSERT INTO 
                                                    user(
                                                        user_id,
                                                        username,
                                                        fullname,
                                                        password,
                                                        email,
                                                        phone,
                                                        dob,
                                                        gender,
                                                        user_key,
                                                        source,
                                                        socmed,
                                                        password_socmed,
                                                        user_isactive,
                                                        id_socmed
                                                    ) 
                                                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
               
                $stmt->bind_param("ssssssssssssss",$user_id,$request['username'],$request['name'],$request['password'],$request['email'],$request['nohp'],$request['dob'],$request['gender'],$key,$request['source'],$request['socmed'],$passwordSocmed,$user_isactive,$request['id_socmed']);
                /*
                    Function location in : /model/general/functions.php
                */
                runQuery($stmt);
                
                $param = base64_encode($user_id.'~'.$key);
                
                if($user_isactive == 0){
                    $mail = new PHPMailer;
                    $mail->isSMTP();
                	$mail->Debugoutput = 'html';
                	$mail->Host = "mail.ngulikin.com";
                	$mail->Port = 25;
                	$mail->SMTPAuth = true;
                	$mail->Username = "ngulikin";
                	$mail->Password = "A98dNzn33n";
                	$mail->setFrom("info@ngulikin.com", "Ngulikin");
                	$mail->addAddress($request['email'], $request['name']);
                	$mail->Subject = 'Ngulikin (Forgot Password)';
                	$mail->Body = "Klik tombol aktif dibawah ini, untuk mengaktifkan akun anda.<br><br><a href='http://".BEAN_URL."/v1/activeAccount?q=".$param."'><div style='background-color:#004E82;border-radius: 10px;width: 30px;font-weight: bold;padding:8px;color:#FFFFFF;'>Aktif</div></a>";
                	$mail->AltBody = 'This is a plain-text message body';
                	
                	//email sended successfully
                	$mail->send();
                }
                
                $path = dirname($_SERVER["DOCUMENT_ROOT"]).'/'.IMAGES_URL.'/'.$request['username'];
                if (!file_exists($path)) {
                    mkdir($path, 0700, true);
                }
                
                $result = array(
                                "user_id"=>$user_id,
                                "username"=>$request['username'],
                                "fullname"=>$request['name'],
                                "email"=>$request['email'],
                                "nohp"=>$request['nohp'],
                                "dob"=>$dob,
                                "gender"=>$request['gender'],
                                "key"=>$key
                            );
                
                $data = array(
        			'status' => "OK",
        			'message' => "Signup successfully",
        			'response' => $data,
        			'data'=>(object)$result
        		);
            }else{
        		$data = array(
        			'status' => "NO",
        			'message' => "Invalid format",
        			'response' => $data
        		);
            }
            
            /*
                Function location in : /model/generatejson.php
            */
        	generateJSON($data);
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