<?php
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
	include 'connection.php';
    include 'memcached.php';
    include 'jwt.php';
    include 'getUrl.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/mail/PHPMailerAutoload.php';
    include $_SERVER['DOCUMENT_ROOT'].'/model/general/functions.php';
    
    define("INIT_URL", "init.ngulikin.com");
    define("BEAN_URL", "bean.ngulikin.com");
    define("IMAGES_URL", "images.ngulikin.com");
?>