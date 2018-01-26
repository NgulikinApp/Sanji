<?php
    function favorite($shop_id,$user_id,$count_rows){
        $data = array(
                      "shop_id" => intval($shop_id),
                      "user_id" => $user_id,
                      "shop_count_favorite" => $count_rows
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>