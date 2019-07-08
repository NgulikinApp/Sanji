<?php
    function updateStatusQuestions($questions_id){
        $data = array(
                      "questions_id" => $questions_id
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>