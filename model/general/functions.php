<?php
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/generatejson.php';
    
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
                    "status" => "NO",
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
                    "status" => "NO",
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
                    "status" => "NO",
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
        Used for executing the query
    */
    function runQuery($stmt){
        $stmt->execute();
        
        $stmt->close();
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
        $stmt->execute();
        $stmt->store_result();
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
    function checkingAuthKey($con,$user_id,$key){
        $result = $con->query("SELECT 
                                    1
                                FROM 
                                    `user`
                                WHERE
                                    user_id = '".$user_id."'
                                    AND
                                    user_key = '".$key."'");
                                    
        $row_cnt = $result->num_rows;
        
        $result->close();
        
        return $row_cnt;
    }
    
    /*
        Function referred on : all
        Used for cheking user key is valid format
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function userDone($do){
        if($do == "favorite"){
            $dataout = array(
                    "status" => "NO",
                    "message" => "You have saved this item",
                    "result" => array()
                );
        }else{
            $dataout = array(
                    "status" => "NO",
                    "message" => "You have rated this item",
                    "result" => array()
                );
        }
        
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
        
        $stmt->bind_result($col1);
        
        $stmt->fetch();
        
        $count = $col1;
        
        $stmt->close();
        
        return $count;
    }
?>