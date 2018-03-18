<?php
    /*
        Function referred on : getData.php
        Used for returning the detail data shop
        Return data:
                - shop_id
                - user_id
                - username
                - shop_name
                - shop_icon
                - shop_description
                - shop_banner
                - university
                - user_photo
                - shop_isfavorite (0/1)
    */
    function detail($stmt){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10);
        
        $data = array();
    
        while ($stmt->fetch()) {
            if($col5 != ""){
                $icon = 'http://'.IMAGES_URL.'/'.$col3.'/shop/'.$col5;
            }else{
                $icon = "http://".INIT_URL."/img/icontext.png";
            }
            
            if($col9 != "no-photo.jpg"){
                $user_photo = 'http://'.IMAGES_URL.'/srv/'.$col3.'/'.$col9;
            }else{
                $user_photo = "http://".INIT_URL."/img/".$col9;
            }
            
            if($col7 != ""){
                $shop_banner = 'http://'.IMAGES_URL.'/'.$col3.'/'.$col7;
            }else{
                $shop_banner = $col7;
            }
            
            $data['shop_id'] = $col1;
            $data['user_id'] = $col2;
            $data['username'] = $col3;
            $data['shop_name'] = $col4;
            $data['shop_icon'] = $icon;
            $data['shop_description'] = $col6;
            $data['shop_banner'] = $shop_banner;
            $data['university'] = $col8;
            $data['user_photo'] = $user_photo;
            $data['shop_isfavorite'] = $col10;
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>