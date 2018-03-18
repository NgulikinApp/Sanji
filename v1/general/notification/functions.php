<?php
    /*
        Function referred on : getNotif.php and searchNotif.php
        Used for returning array data
    */
    function notification($stmt){
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6);
        
        $data = array();
    
        while ($stmt->fetch()) {
            $data[] = array(
                      "notification_id" => $col1,
                      "user_id" => $col2,
                      "notification_desc" => $col3,
                      "notification_photo" => $col4,
                      "notification_createdate" => $col5,
                      "username" => $col6
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified($data);
    }
?>