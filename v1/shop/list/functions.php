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
                $icon = 'http://'.IMAGES_URL.'/'.$col4.'/shop/'.$col3;
            }else{
                $icon = "http://".INIT_URL."/img/icontext.png";
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
                        
            if($col3 == ''){
                $icon = "https://s4.bukalapak.com/img/409311077/s-194-194/TV_LED_Sharp_24__LC_24LE170i.jpg";
            }else{
                $icon = 'http://'.IMAGES_URL.'/'.$col4.'/shop/'.$col3;
            }
                        
            $data[] = array(
                            "shop_id" => $col1,
                            "shop_name" => $col2,
                            "shop_icon" =>  $icon,
                            "shop_difdate" => $col5
                        );
        }
        
        $stmt->close();
            
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerifiedCalc($data,$total,$pagesize);
    }
    
    /*
        Function referred on : product.php
        Used for returning array data
    */
    function product($stmt,$pagesize){
        
        $stmt->execute();
        
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5);
        
        $data = array();
        
        $total = 0;
        while ($stmt->fetch()) {
            $total = $col5;
            $data[] = array(
                      "product_id" => $col1,
                      "product_name" => $col2,
                      "product_image" => 'http://'.IMAGES_URL.'/'.$col4.'/product/'.$col3
                    );
        }
        
        $stmt->close();
            
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerifiedCalc($data,$total,$pagesize);
    }
    
    /*
        Function referred on : review.php
        Used for returning array data
    */
    function review($stmt,$pagesize){
        
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8);
        
        $data = array();
        
        $total = 0;
        while ($stmt->fetch()) {
            $total = $col8;
            if($col5 != "no-photo.jpg"){
                $icon = 'http://'.IMAGES_URL.'/'.$col4.'/'.$col5;
            }else{
                $icon = "http://".INIT_URL."/img/".$col5;
            }
            
            $data[] = array(
                      "shop_review_id" => $col1,
                      "user_id" => $col2,
                      "shop_review_comment" =>  $col3,
                      "user_photo" => $icon,
                      "fullname" => $col6,
                      "comment_date" => $col7
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerifiedCalc($data,$total,$pagesize);
    }
    
    /*
        Function referred on : discuss.php
        Used for returning array data
    */
    function discuss($stmt,$pagesize){
        
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8);
        
        $data = array();
        
        $total = 0;
        while ($stmt->fetch()) {
            $total = $col8;
            if($col5 != "no-photo.jpg"){
                $icon = 'http://'.IMAGES_URL.'/'.$col4.'/'.$col5;
            }else{
                $icon = "http://".INIT_URL."/img/".$col5;
            }
            
            $data[] = array(
                      "shop_discuss_id" => $col1,
                      "user_id" => $col2,
                      "shop_discuss_comment" =>  $col3,
                      "user_photo" => $icon,
                      "fullname" => $col6,
                      "comment_date" => $col7
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerifiedCalc($data,$total,$pagesize);
    }
?>