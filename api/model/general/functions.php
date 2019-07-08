<?php
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/api/model/generatejson.php';
    
    /*
        Function referred on : all
        Used for cheking user key is valid format
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function invalidKey(){
        $dataout = array(
                    "status" => "DENIED",
                    "message" => "Invalid key",
                    "result" => array()
                );
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    /*
        Function referred on : all
        Used for checking jwt token is valid format
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function invalidCredential(){
        $dataout = array(
                    "status" => "DENIED",
                    "message" => "Invalid credential",
                    "result" => array()
                );
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    /*
        Function referred on : all
        Used for checking jwt token is expired
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function tokenExpired(){
        $dataout = array(
                    "status" => "DENIED",
                    "message" => "Token expired",
                    "result" => array()
                );
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    /*
        Function referred on : all
        Used for checking jwt token is verified
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function credentialVerified($data){
        $dataout = array(
                    "status" => "OK",
                    "message" => "Credential is verified",
                    "result" => $data
                );
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    /*
        Function referred on : all
        Used for checking jwt token is verified
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function credentialVerifiedCalc($data,$total,$pagesize){
        $total = intval($total);
        if($total != 0){
            $total = ceil($total/intval($pagesize));
        }
        
        $dataout = array(
            			'status' => "OK",
            			'message' => "Valid credential",
            			'total_page' => $total,
            			'response' => $data
            	);
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    function runQuery_returnId($con){
        $last_id = $con->insert_id;
        
        return $last_id;
    }
    
    /*
        Function referred on : all
        Used for cheking valid email format
        Return data : true or false
    */
    function check_email_address($email) {
        // First, we check that there's one @ symbol, 
        // and that the lengths are right.
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters 
            // in one section or wrong number of @ symbols.
            return false;
        }
        
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if(!preg_match("/^(([A-Za-z0-9!#$%&'*+=?^_`{|}~-][A-Za-z0-9!#$%&↪'*+=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/",$local_array[$i])) {
              return false;
            }
        }
        
        // Check if domain is IP. If not, 
        // it should be valid domain name
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if(!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|↪([A-Za-z0-9]+))$/",$domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /*
        Function referred on : all
        Used for generating random string
        Return data : string
    */
    function generateRandomString(){
	    $length = 4;
	    if ($length % 2 == 1) {
	        $length++;
	    }
	    $bytes_length = $length / 2;
	    $bytes = openssl_random_pseudo_bytes($bytes_length, $cstrong);
	    $hex   = bin2hex($bytes);
	    return $hex;
    }
    
    /*
        Function referred on : all
        Used for generating random string
        Return data : string
    */
    function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
            
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);

        return $min + $rnd;
    }
    
    /*
        Function referred on : all
        Used for generating random string
        Return data : string
    */
    function getID($length) {
        $id = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $id .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
        }

        return $id;
    }
    
    /*
        Function referred on : all
        Used for calling the number of rows
        Return data: numeric
    */
    function count_rows($stmt){
        $rowsCount = $stmt->num_rows;
        
        $stmt->close();
        
        return $rowsCount;
    }
    
    /*
        Function referred on : all
        Used for checking the data in list
        Return data: true or false
    */
    function ListFindNoCase($list,$value,$delimiter=",")
    {
        $a = explode($delimiter,$list);
        for ($i=0;$i<count($a);$i++)
        {
            if(stristr($a[$i],$value))
            {
                return true;
            }
        }
        return false;
    }
    
    /*
        Function referred on : all
        Used for checking the user key
        Return data: row count data
    */
    function checkingAuthKey($con,$user_id,$key,$isadmin,$cache){
        $data = getMemcached("m_user_".$user_id."_".$key."_".$isadmin,$cache);
        if($data != ''){
            return 1;
        }else{
            if($isadmin == 0){
               $sql = "SELECT 
                            user_key
                        FROM 
                            `user`
                        WHERE
                            user_id = ?
                            AND
                            user_key = ?"; 
            }else{
               $sql = "SELECT 
                            user_key_admin
                        FROM 
                            `user`
                        WHERE
                            user_id = ?
                            AND
                            user_key_admin = ?"; 
            }
            
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $user_id,$key);
            
            $result = $stmt->execute(); //execute() tries to fetch a result set. Returns true on succes, false on failure.
            $stmt->store_result();
            
            $stmt->bind_result($col1);
            $stmt->fetch();
            
            $row_cnt = $stmt->num_rows;
            
            if($row_cnt > 0){
                setMemcached("m_user_".$user_id."_".$col1."_".$isadmin,$cache,1,3600);      
            }
            $stmt->close();
            
            return $row_cnt;
        }
    }
    
    /*
        Function referred on : all
        Used for cheking user key is valid format
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function userDone(){
        $dataout = array(
                    "status" => "NO",
                    "message" => "You have rated this item",
                    "result" => array()
                );
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    /*
        Function referred on : all
        Used for getting the data of calculation 
        Return data: rows
    */
    function calc_val($stmt){
        $stmt->execute();
        
        $row = $stmt->fetch();
        
        $stmt->close();
        
        return $row;
    }
    
    /*
        Function referred on : all
        Used for removing the all files from specific directory
    */
    function emptyFolder($path){
        //delete all file
        $files = glob($path.'/*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file))
                unlink($file); // delete file
        }
            
        unset($_SESSION['file']);
        unset($_SESSION['shop']);
        unset($_SESSION['card']);
        unset($_SESSION['selfie']);
    }
    
    /*
        Function referred on : all
        Used for removing the html tag in parameters
    */
    function param($param){
        $param = mb_convert_encoding($param, 'UTF-8', 'UTF-8');
        $param = htmlentities($param, ENT_QUOTES, 'UTF-8');
        
        return $param;
    }
    
    /*
        Function referred on : all
        Used for sending email
    */
    function sendEmail($sendfrom,$namefrom,$sendto,$nameto,$subject,$body){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Debugoutput = 'html';
        $mail->Host = "mail.ngulikin.com";
        $mail->Port = 25;
        $mail->SMTPAuth = true;
        $mail->Username = "ngulikin";
        $mail->Password = "FD76889Ddt!";
        $mail->setFrom($sendfrom, $namefrom);
        $mail->addAddress($sendto, $nameto);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = 'This is a plain-text message body';
                	
        return $mail->send();
    }
    
    /*
        Function referred on : all
        Used for getting month
    */
    function month($val){
        $month = "";
        switch(intval($val)){
            case 1:
                $month = "Januari";
            break;
            case 2:
                $month = "Februari";
            break;
            case 3:
                $month = "Maret";
            break;
            case 4:
                $month = "April";
            break;
            case 5:
                $month = "Mei";
            break;
            case 6:
                $month = "Juni";
            break;
            case 7:
                $month = "Juli";
            break;
            case 8:
                $month = "Agustus";
            break;
            case 9:
                $month = "September";
            break;
            case 10:
                $month = "Oktober";
            break;
            case 11:
                $month = "November";
            break;
            default:
                $month = "Desember";
        }
        
        return $month;
    }
?>