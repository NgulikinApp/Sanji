<?php
    function favorite($product_id,$user_id,$count_rows){
        $data = array(
                      "product_id" => intval($product_id),
                      "user_id" => $user_id,
                      "product_count_favorite" => $count_rows
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>