<?php
    function updateStatusSeller($user_id){
        $data = array(
                      "user_id" => $user_id
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>