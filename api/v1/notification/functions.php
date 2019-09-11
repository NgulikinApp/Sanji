<?php
    function notif($desc){
        $data = array(
                      "type" => 0,
                      "desc" => $desc
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>