<?php
    function updateStatusPayment($invoice_id){
        $data = array(
                      "invoice_id" => $invoice_id
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>