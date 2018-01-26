<?php
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/model/beanoflink.php';
    include 'functions.php';
    
    /*
        Function location in : /model/connection.php
    */
    $con = conn();
    
    $q = base64_decode ($_GET['q']);
    
    $qArray = explode('~',$q);
    $user_id = $qArray[0];
    $key = $qArray[1];
    
    $stmt = $con->prepare("UPDATE 
                                user
                            SET
                                user_isactive = 1
                            WHERE 
                                user_id=?
                                AND
                                user_key=?");
    $stmt->bind_param("ss", $user_id,$key);
    
    /*
        Function location in : /model/general/functions.php
    */
    runQuery($stmt);
    
    echo "Akun anda sudah aktif, anda bisa login sekarang";
    
    /*
        Function location in : /model/connection.php
    */
    conn_close($con);
?>