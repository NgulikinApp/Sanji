<?php
    /*
        Function referred on : all
        Used for getting the data from basic authorization
        Return data:
                - email
                - password
    */
    function basic_auth(){
        $header = @$_SERVER['HTTP_AUTHORIZATION'];
        $header_arr = explode(' ', $header);
        $basic_token = base64_decode(@$header_arr[1]);
        $basic_token_arr = explode(':', $basic_token);
        $email = @$basic_token_arr[0];
        $password = @$basic_token_arr[1];
        
        return array($email,$password);
    }
    
    /*
        Function referred on : all
        Used for getting the data from bearer authorization
        Return data: jwt token
    */
    function bearer_auth(){
        $header = @$_SERVER['HTTP_AUTHORIZATION'];
        $header_arr = explode(' ', $header);
        $bearer_token = base64_decode(@$header_arr[1]);
        
        return $bearer_token;
    }
?>