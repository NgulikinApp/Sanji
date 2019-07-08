<?php
    function sumStatistics($sumUser,$lastSumUser,$sumUserSeller,$lastSumUserSeller,$sumProducts,$lastSumProducts,$sumTransactions,$lastSumTransactions){
        $colorSumUser = ($sumUser>=$lastSumUser)?"blue":"red";
        $colorSumUserSeller = ($sumUserSeller>=$lastSumUserSeller)?"blue":"red";
        $colorSumProducts = ($sumProducts>=$lastSumProducts)?"blue":"red";
        $colorSumTransactions = ($sumTransactions>=$lastSumTransactions)?"blue":"red";
        
        $data = array(
                      "sumUser" => $sumUser,
                      "colorSumUser" => $colorSumUser,
                      "sumUserSeller" => $sumUserSeller,
                      "colorSumUserSeller" => $colorSumUserSeller,
                      "sumProducts" => $sumProducts,
                      "colorSumProducts" => $colorSumProducts,
                      "sumTransactions" => $sumTransactions,
                      "colorSumTransactions" => $colorSumTransactions
                    );
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
    
    function graphStatistics($stmt){
        $data = array();
        
        $stmt->execute();
        
        $stmt->bind_result($col1, $col2);
    
        while ($stmt->fetch()) {
            $data[] = array(
                      "sumData" => $col1,
                      "date" => $col2
                    );
        }
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified($data);
    }
?>