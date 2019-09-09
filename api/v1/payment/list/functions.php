<?php
    function uploadedProof($stmt){
        $data = array();
        
        $stmt->execute();
        
        $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7);
    
        while ($stmt->fetch()) {
            $photopayment = IMAGES_URL.'/'.urlencode(base64_encode($col7.'/product/'.$col5));
            $data[] = array(
                      "invoice_id" => $col1,
                      "fullname" => $col2,
                      "email" => $col3,
                      "invoice_no" => $col4,
                      "photopayment" => $photopayment,
                      "paiddate" => $col6
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified($data);
    }
?>