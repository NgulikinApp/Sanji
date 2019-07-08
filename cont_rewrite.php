<?php

	//-----------------------------------------------------------------------------------
	// Link to File 
	//-----------------------------------------------------------------------------------
	include('common/handler-uri.php');
	include('common/handler-output.php');
	include('common/globalCons-uri.php');
 
	//-----------------------------------------------------------------------------------
	// Now, $routes will contain all the routes. $routes[0] will correspond to first route. 
	//-----------------------------------------------------------------------------------
	$routes = extractUri();
	
	//-----------------------------------------------------------------------------------
	// Re-routing if Authorized
	//-----------------------------------------------------------------------------------
	
	switch (@$routes[0]) {
	    case 'banner':
	        include('web/body/banner/index.php');
	    break;
	    case 'blog':
	        include('web/body/blog/index.php');
	    break;
	    case 'confirm':
	        include('web/body/confirm/index.php');
	    break;
	    case 'dashboard':
	        include('web/body/dashboard/index.php');
	    break;
	    case 'notif':
	        include('web/body/notif/index.php');
	    break;
	    case 'services':
	        include('web/body/services/index.php');
	    break;
	    case 'v1':
	        switch (@$routes[1]) {
	            case 'as':
					switch (@$routes[2]) {
				        case 'us':
                			include('api/v1/assistanceservices/detail/updateStatusSeller.php');
                		break;
						default :
							include('api/v1/assistanceservices/list/pendingQuestions.php');
				    }
				break;
	            case 'generateToken':
					include('api/v1/general/system/generateToken.php');
				break;
				case 'graphstatistics':
					include('api/v1/dashboard/graphStatistics.php');
				break;
	            case 'sc':
					switch (@$routes[2]) {
				        case 'us':
                			include('api/v1/salesconfirmation/detail/updateStatusSeller.php');
                		break;
						default :
							include('api/v1/salesconfirmation/list/pendingSeller.php');
				    }
				break;
	            case 'signin':
					include('api/v1/auth/signin.php');
				break;
				case 'signout':
					include('api/v1/auth/signout.php');
				break;
				case 'sumstatistics':
					include('api/v1/dashboard/sumStatistics.php');
				break;
	         }
	    break;
	    default:
		    include('web/body/notfound/index.php');
	}		