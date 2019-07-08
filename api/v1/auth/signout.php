<?php
    /*
        This API used in ngulikin.com/js/module-general.js
    */
    
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
	include $_SERVER['DOCUMENT_ROOT'].'/api/model/beanoflink.php';
    include 'functions.php';
    
    unset($_SESSION['user_admin']);
    signout();
?>