<?php
    /*
        Function referred on : all
        Used for getting parameter by post raw method
        Return data: data bean
    */   
    function postraw(){
        $handle = fopen("php://input","rb");
        $raw_post_data = '';
        while(!feof($handle)){
            $raw_post_data .= fread($handle,8192);
        }
        fclose($handle);
        
        $request = json_decode($raw_post_data,true);
        
        return $request;
    }
    
?>