<?php
    include 'middleware.php';
    /*
        Koneksi database
        Host     : ngulikin.com
        Username : ngulikin_admin
        Password : ngulik1234
        Database : ngulikin_master
    */
    
    function conn(){
        $con = new mysqli("ngulikin.com", "ngulikin_admin", "ngulik1234", "ngulikin_master");
        return $con;
    }
    
    /*
        User for closing the connection into database
    */
    function conn_close($con){
        $con->close();
    }
?>