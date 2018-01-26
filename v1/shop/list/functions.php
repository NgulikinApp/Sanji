<?php
    /*
        Function referred on : feed.php
        Used for showing callback data the feed of shoping in landing page
    */
    function feed($stmt){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4);
        
        $data = array();
    
        while ($stmt->fetch()) {
            if($col3 != ""){
                $icon = 'http://images.ngulikin.com/'.urlencode(base64_encode ($col4.'/shop/'.$col3));
            }else{
                $icon = "http://init.ngulikin.com/img/icontext.png";
            }
            
            $data[] = array(
                      "shop_id" => $col1,
                      "shop_name" => $col2,
                      "shop_icon" =>  $icon
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified($data);
    }
    
    /*
        Function referred on : favorite.php
        Used for returning array data
    */
    function favorite($stmt,$pagesize){
        
        $stmt->execute();
        
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6);
        
        $data = array();
        
        while ($stmt->fetch()) {
                    $total = $col6;
                        
                    if($row[2] == ''){
                        $icon = "https://s4.bukalapak.com/img/409311077/s-194-194/TV_LED_Sharp_24__LC_24LE170i.jpg";
                    }else{
                        $icon = 'http://images.ngulikin.com/'.urlencode(base64_encode ($col4.'/shop/'.$col3));
                    }
                        
                    $data[] = array(
                                    "shop_id" => $col1,
                                    "shop_name" => $col2,
                                    "shop_icon" =>  $icon,
                                    "shop_difdate" => $col5
                                );
        }
        
        $stmt->close();
            
        $dataout = array(
            			'status' => "OK",
            			'message' => "Valid credential",
            			'total' => ceil($total/intval($pagesize))+1,
            			'response' => $data
            	);
        
        /*
            Function location in : /model/generatejson.php
        */
        generateJSON($dataout);
    }
    
    /*
        Function referred on : favorite.php
        Used for returning array data
    */
    function product($stmt,$pagesize){
        
        $stmt->execute();
        
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5);
        
        $data = array();
    
        while ($stmt->fetch()) {
            $total = $col5;
            $data[] = array(
                      "product_id" => $col1,
                      "product_name" => $col2,
                      "product_image" => 'http://images.ngulikin.com/'.urlencode(base64_encode ($col4.'/product/'.$col3))
                    );
        }
        
        $stmt->close();
            
        $dataout = array(
            			'status' => "OK",
            			'message' => "Valid credential",
            			'total' => ceil($total/intval($pagesize))+1,
            			'response' => $data
            	);
        
        /*
            Function location in : /model/generatejson.php
        */
        generateJSON($dataout);
    }
?>