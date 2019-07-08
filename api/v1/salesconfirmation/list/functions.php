<?php
    function listPendingUser($stmt){
        $data = array();
        
        $stmt->execute();
        
        $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6);
    
        while ($stmt->fetch()) {
            if($col3 != 'ktp.png'){
                $photo_card = IMAGES_URL.'/'.urlencode(base64_encode($col2.'/seller/card/'.$col3));
            }else{
                $photo_card = "/img/".$col3;
            }
            if($col4 != 'selfie.png'){
                $photo_selfie = IMAGES_URL.'/'.urlencode(base64_encode($col2.'/seller/selfie/'.$col4));
            }else{
                $photo_selfie = "/img/".$col4;
            }
            if($col6 != ''){
                $icon = IMAGES_URL.'/'.urlencode(base64_encode($col2.'/shop/icon/'.$col3));
            }else{
                $icon = "/img/no-image.jpg";
            }
            $data[] = array(
                      "user_id" => $col1,
                      "username" => $col2,
                      "photo_card" => $photo_card,
                      "photo_selfie" => $photo_selfie,
                      "shop_name" => $col5,
                      "shop_icon" => $icon
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified($data);
    }
?>