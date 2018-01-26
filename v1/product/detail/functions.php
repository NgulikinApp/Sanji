<?php
    /*
        Function referred on : getData.php
        Used for showing callback the deatil data product
    */
    function detail($stmt){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16);
        
        $data = array();
    
        while ($stmt->fetch()) {
            $imageArray = explode(",",$col5);
            $countImage = sizeof($imageArray);
            
            if($col10 != ""){
                $icon = 'http://images.ngulikin.com/'.urlencode(base64_encode ($col3.'/product/'.$col10));
            }else{
                $icon = "http://init.ngulikin.com/img/icontext.png";
            }
            
            $product_images = array();
            for($i=0;$i<$countImage;$i++){
                $image = 'http://images.ngulikin.com/'.urlencode(base64_encode ($col3.'/product/'.$imageArray[$i]));
                array_push($product_images,$image);
            }
            $data['product_id'] = $col1;
            $data['user_id'] = $col2;
            $data['username'] = $col3;
            $data['product_name'] = $col4;
            $data['product_price'] = $col6;
            $data['product_description'] = $col7;
            $data['product_rate'] = $col8;
            $data['product_stock'] = $col9;
            $data['shop_icon'] = $icon;
            $data['shop_name'] = $col11;
            $data['product_count_favorite'] = $col12;
            $data['product_average_rate'] = $col13;
            $data['product_israte'] = $col14;
            $data['product_isfavorite'] = $col15;
            $data['product_image'] = $product_images;
            $data['product_rate_value'] = $col16;
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>