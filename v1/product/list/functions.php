<?php
    /*
        Function referred on : category.php
        Used for returning array data
    */
    function listcategory($stmt,$cache){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3);
        
        $data = array();
    
        while ($stmt->fetch()) {
            $data[] = array(
                      "category_id" => intval($col1),
                      "category_name" => $col2,
                      "category_url" => 'http://init.ngulikin.com/img/category/'.$col3
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified($data);
    }
    
    /*
        Function referred on : feed.php
        Used for returning array data
    */
    function feed($stmt){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6);
        
        $data = array();
    
        while ($stmt->fetch()) {
            $data[] = array(
                      "product_id" => intval($col1),
                      "product_name" => $col3,
                      "product_image" => 'http://images.ngulikin.com/'.urlencode(base64_encode ($col2.'/product/'.$col4)),
                      "product_price" => number_format($col5, 0, '.', '.'),
                      "product_isfavorite" => $col6
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
        
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7);
        
        $data = array();
        
        while ($stmt->fetch()) {
                $total = $col7;
                if($col3 == ''){
                    $icon = "https://s4.bukalapak.com/img/409311077/s-194-194/TV_LED_Sharp_24__LC_24LE170i.jpg";
                }else{
                    $icon = 'http://images.ngulikin.com/'.urlencode(base64_encode ($col5.'/shop/'.$col3));
                }
                $data[] = array(
                                    "product_id" => $col1,
                                      "product_name" => $col2,
                                      "product_image" =>  $icon,
                                      "product_price" =>  $col4,
                                      "product_difdate" => $col6
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