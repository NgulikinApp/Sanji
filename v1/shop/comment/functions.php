<?php
    function comment($stmt,$id,$shop_id,$user_id,$comment){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4);
        
        $stmt->fetch();
        
        if($col2 == 'no-photo.jpg'){
            $photo = 'http://'.INIT_URL.'/img/'.$col2;
        }else{
            $photo = 'http://'.IMAGES_URL.'/'.$col1.'/'.$col2;
        }
        
        $data = array(
                    "id" => intval($id),
                    "shop_id" => intval($shop_id),
                    "user_id" => $user_id,
                    "fullname" => $col3,
                    "user_photo" => $photo,
                    "shop_comment" => $comment,
                    "comment_date" => $col4
                );
                        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>