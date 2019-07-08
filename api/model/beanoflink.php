<?php
    /*
        Modify php.ini
    */
    ini_set( 'session.use_only_cookies', TRUE );				
    ini_set( 'session.use_trans_sid', FALSE );
    ini_set( 'session.cookie_httponly', TRUE );				
    ini_set( 'session.cookie_secure', TRUE );
    ini_set( 'session.cookie_domain', "admin.ngulikin.com" );
    
    session_start();
    date_default_timezone_set("Asia/Jakarta");
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
	include 'connection.php';
    include 'memcached.php';
    include 'jwt.php';
	include $_SERVER['DOCUMENT_ROOT'].'/api/model/mail/PHPMailerAutoload.php';
	include $_SERVER['DOCUMENT_ROOT'].'/api/model/general/functions.php';
    
    define("INIT_URL", "https://www.admin.ngulikin.com");
    define("IMAGES_URL", "https://www.images.ngulikin.com");
?>