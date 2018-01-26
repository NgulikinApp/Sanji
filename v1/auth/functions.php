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
                "message" => 'Account is not exist',
                "result" => $data
        );
        
        /*
            Function location in : /model/generatejson.php
        */        
        generateJSON($dataout);
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
                    "message" => 'Email or password is wrong',
                    "result" => $data
                );
        }else{
            $dataout = array(
                    "status" => 'NO',
                    "message" => 'Email or username is wrong',
                    "result" => $data
                );
        }
        
        /*
            Function location in : /model/generatejson.php
        */        
        generateJSON($dataout);
    }
    
    /*
        Function referred on : all
        Used for calling the json data that inactive account
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function incative_account(){
        $data = array();
        $dataout = array(
                        "status" => 'NO',
                        "message" => 'Account has not activated, check your email',
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
                - user_isactive (0/1)
                - password
                - socmed (googleplus,facebook,etc)
                - id_socmed
    */
    function account_verify($stmt){
        $stmt->execute();
        
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5);
        
        $stmt->fetch();
        
        $user_id = $col1;
        $user_isactive = $col2;
        $password = $col3;
        $socmed = $col4;
        $idsocmed = $col5;
        
        $stmt->close();
        
        return array($user_id,intval($user_isactive),$password,$socmed,$idsocmed);
    }
    
    /*
        Function referred on : signin.php
        Used for showing the data of signin account
        Return data:
                - username
                - fullname
                - university
                - major
                - source (web, android, ios, etc...)
                - socmed (ngulikin if the user login on manual, googleplus, facebook, etc...)
                - merchant
    */
    function get_data_signin($stmt){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8);
        
        $stmt->fetch();
        
        $data = array(
                        "user_id"=>$col1,
                        "username"=>$col2,
                        "fullname"=>$col3,
                        "email"=>$col4,
                        "nohp"=>$col5,
                        "dob"=>$col6,
                        "gender"=>$col7,
                        "key"=>$col8
                    );
        
        $dataout = array(
                        "status" => 'OK',
                        "message" => 'Signin successfully',
                        "result" => (object)$data
                    );
        
        $stmt->close();
        
        /*
            Function location in : /model/generatejson.php
        */
        return generateJSON($dataout);
    }
    
    /*
        Function referred on : /forgotpassword/sendingcode.php
        Used for getting the verified account
        Return data:
                - user_id
                - user_isactive (0/1)
    */
    function get_account_forgotpassword($stmt){
        $stmt->execute();
        
        $stmt->bind_result($col1,$col2,$col3,$col4);
        
        $stmt->fetch();
        
        $user_id = $col1;
        $user_isactive = $col2;
        $email = $col3;
        $fullname = $col4;
        
        $stmt->close();
        
        return array($user_id,intval($user_isactive),$email,$fullname);
    }
    
    /*
        Function referred on : /forgotpassword/checkingcode.php
        Used for checking the code on forgot password module
        Return data:
                - user_id
    */
    function code_verified($stmt){
        $stmt->execute();
        
        $stmt->bind_result($col1);
        
        $stmt->fetch();
        
        $user_id = $col1;
        
        $stmt->close();
        
        return array($user_id);
    }
    
    /*
        Function referred on : /forgotpassword/checkingcode.php
        Used for checking the code on forgot password module
        Return data:
                - status (YES/NO)
                - message
                - result
    */
    function wrong_code_verified(){
        $data = array();
        
        $dataout = array(
                "status" => 'NO',
                "message" => 'Invalid code',
                "result" => $data
            );
        
        return generateJSON($dataout);
    }
    
    
    /*
        Function referred on : signin.php
        Used for compiling user table
        Return data:
    */
    function returndata_signin($user_id,$con){
        $stmt = $con->prepare("SELECT
                                    user_id,
                                    username, 
                                    fullname,
                                    email,
                                    phone,
                                    dob,
                                    gender,
                                    user_key
                                FROM 
                                    user 
                                WHERE 
                                    user_id=?");
        $stmt->bind_param("s", $user_id);
                
        /*
            Function location in : functions.php
        */
        get_data_signin($stmt);
    }
?>