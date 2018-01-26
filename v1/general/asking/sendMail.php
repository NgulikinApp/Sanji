<?php
    /*
        This API used in ngulikin.com/js/module-general.js
    */

    date_default_timezone_set('Etc/UTC');
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/beanoflink.php';
    
    /*
        Get parameter with from ajax form
        list parameter:
        1. name
        2. email
        3. question
    */
    
    $user_name	= $_POST['name'];
	$user_email = $_POST['email'];
	$question	= $_POST['question'];
	
	//checking all parameter that should not be empty
	if($user_name == "" || $user_email == "" || $question == ""){
	    $result = (object)array();
		$data = array(
			'status' => "NO",
			'message' => "Nama, email, dan pertanyaan harus diisi",
			'response' => $result
		);
	    
	    /*
            Function location in : /model/generatejson.php
        */
	    return generateJSON($data);
	
	//checking email format is valid
	}else if(check_email_address($user_email) == false){
	    $result = (object)array();
		$data = array(
			'status' => "NO",
			'message' => "Format email tidak benar",
			'response' => $result
		);
		
		/*
            Function location in : /model/generatejson.php
        */
	    return generateJSON($data);
	}
	
	$target_dir = "temp/";
	$target_file = "";
	if(!empty($_FILES["file"])){
	    $target_file = $target_dir . basename($_FILES["file"]["name"]);
	    
        //upload file into 'temp' directory
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
	}
    
    $mail = new PHPMailer;
    $mail->isSMTP();
	$mail->Debugoutput = 'html';
	$mail->Host = "mail.ngulikin.com";
	$mail->Port = 25;
	$mail->SMTPAuth = true;
	$mail->Username = "ngulikin";
	$mail->Password = "A98dNzn33n";
	$mail->setFrom($user_email, $user_name);
	$mail->addAddress('hello.ngulik@gmail.com', 'Admin');
	$mail->Subject = 'Tanya Ngulikin';
	$mail->Body = $question;
	$mail->AltBody = 'This is a plain-text message body';
	if($target_file != ""){
		$mail->addAttachment($target_file);
	}
	
	//email sended successfully
	if ($mail->send()) {
	    $result = array(
			'name' => $user_name,
			'email' => $user_email,
			'question' => $question,
			'attachment' => $target_file
		);
		$data = array(
			'status' => "OK",
			'message' => "Email has been sent successfully",
			'response' => $result
		);
	
	//email failed to sent
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
	if($target_file != "")unlink($target_file);
?>