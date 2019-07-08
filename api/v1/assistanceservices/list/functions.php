<?php
    function listPendingQuestions($stmt){
        $data = array();
        
        $stmt->execute();
        
        $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6);
    
        while ($stmt->fetch()) {
            $data[] = array(
                      "questions_id" => $col1,
                      "fullname" => $col2,
                      "email" => $col3,
                      "desc" => $col4,
                      "attach_file" => $col5,
                      "createdate" => $col6
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified($data);
    }
?>