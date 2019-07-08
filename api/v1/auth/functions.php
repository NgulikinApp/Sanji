<?php
    /*
        Function referred on : all
        Used for generating code sha25
        Return data:
                - string
    */
    function encrypt_hash($string){
	   $hasted = strtoupper(hash("sha256",$string));
	   return $hasted;
    }
    
    function returndata_signin_admin($stmt,$user_id,$key){
        $data = array(
                    "user_id" => $user_id,
                    "key" => $key
            );
        $_SESSION['user_admin'] = $data;
        
        session_regenerate_id();
        session_regenerate_id(true);
    
        $dataout = array(
                        "status" => 'OK',
                        "message" => 'Login berhasil'
                    );
        
        $stmt->close();
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    /*
        Function referred on : all
        Used for calling the json data that wrong in input email or password account
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function notexist_account(){
        $data = array();
        
        $dataout = array(
                "status" => 'NO',
                "message" => 'Akun belum terdaftar',
                "result" => $data
        );
        
        /*
            Function location in : /model/generatejson.php
        */        
        generateJSON($dataout);
    }
    
    /*
        Function referred on : signin.php
        Used for getting the verified account
        Return data:
                - user_id
                - password
                - user_admin (0/1)
    */
    function account_verify_admin($stmt){
        $stmt->execute();
        
        $stmt->bind_result($col1, $col2, $col3);
        
        $stmt->fetch();
        
        $user_id = $col1;
        $password = $col2;
        $user_admin = intval($col3);
        
        $stmt->close();
        
        return array($user_id,$password,$user_admin);
    }
    
    /*
        Function referred on : all
        Used for calling the json data that wrong in input email or password account
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function wrongpassoremail_account($isSignin){
        $data = array();
        
        if(intval($isSignin)){
            $dataout = array(
                    "status" => 'NO',
                    "message" => 'Email atau password salah',
                    "result" => $data
                );
        }else{
            $dataout = array(
                    "status" => 'NO',
                    "message" => 'Email atau username salah',
                    "result" => $data
                );
        }
        
        /*
            Function location in : /model/generatejson.php
        */        
        generateJSON($dataout);
    }
    
    /*
        Function referred on : signin_admin.php
        Used for calling the json data that user is not admin
        Return data:
                - status (NO)
                - message
    */
    function access_denied(){
        $dataout = array(
                        "status" => 'NO',
                        "message" => 'Akses ditolak'
                    );
        
        /*
            Function location in : /model/generatejson.php
        */        
        generateJSON($dataout);
    }
    
    /*
        Function referred on : signout
        Used for calling the json data signout
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function signout(){
        $data = array();
        
        $dataout = array(
                "status" => 'OK',
                "message" => 'Signout successfully',
                "result" => (object)$data
        );
        
        /*
            Function location in : /model/generatejson.php
        */        
        return generateJSON($dataout);
    }
?>