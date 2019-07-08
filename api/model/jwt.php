<?php
    //--------------------------------------------------------------------------
	// Link to File
	//--------------------------------------------------------------------------
    include $_SERVER['DOCUMENT_ROOT'].'/api/vendor/autoload.php';
    
    spl_autoload_register(function ($classname) {
		$classname = str_replace('\\', '/', $classname);
	    require $_SERVER['DOCUMENT_ROOT'].'/api/vendor/classes/'.$classname.'.php';
	});
    
    define('SECRET_KEY','ngulikinToken');
    
    $secretKey = base64_decode(SECRET_KEY);
?>