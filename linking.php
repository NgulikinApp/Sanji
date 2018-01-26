<?php

	//-----------------------------------------------------------------------------------
	// Link to File 
	//-----------------------------------------------------------------------------------
	include('model/handler.php');
 
	//-----------------------------------------------------------------------------------
	// Now, $routes will contain all the routes. $routes[0] will correspond to first route. 
	//-----------------------------------------------------------------------------------
	$routes = extractUri();
	
	//-----------------------------------------------------------------------------------
	// Re-routing if Authorized
	//-----------------------------------------------------------------------------------
	switch ($routes[0]) {
		case 'v1':
			switch ($routes[1]) {
			    case 'activeAccount':
					include('v1/auth/active_account.php');
				break;
				case 'asking':
					include('v1/general/asking/sendMail.php');
				break;
			    case 'forgotPassword':
				    switch ($routes[2]) {
				        case 'askingCode':
							include('v1/auth/forgotpassword/sendingcode.php');
						break;
						case 'checkingCode':
							include('v1/auth/forgotpassword/checkingcode.php');
						break;
						case 'changingPass':
							include('v1/auth/forgotpassword/changingpass.php');
						break;
				    }
				break;
				case 'generateToken':
					include('v1/general/system/generateToken.php');
				break;
				case 'product':
					switch ($routes[2]) {
						case 'category':
							include('v1/product/list/category.php');
						break;
						case 'favorite':
							include('v1/product/favorite/favoriteItem.php');
						break;
						case 'feed':
							include('v1/product/list/feed.php');
						break;
						case 'rate':
							include('v1/product/rate/rateItem.php');
						break;
						default :
							include('v1/product/detail/getData.php');
						break;
					}
				break;
				case 's':
					include('v1/general/search/searchItem.php');
				break;
				case 'shop':
				    switch ($routes[2]) {
				        case 'favorite':
							include('v1/shop/favorite/favoriteItem.php');
						break;
				        case 'feed':
							include('v1/shop/list/feed.php');
						break;
						default :
							include('v1/shop/detail/getData.php');
						break;
				    }
				break;
				case 'signin':
					include('v1/auth/signin.php');
				break;
				case 'signup':
					include('v1/auth/signup.php');
				break;
			}
		break;
	}		