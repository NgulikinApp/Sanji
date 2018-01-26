<?php
    function rate($product_id,$user_id,$count_val){
        $data = array(
                      "product_id" => intval($product_id),
                      "user_id" => $user_id,
                      "product_avg_rate" => $count_val
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>