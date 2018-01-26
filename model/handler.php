<?php

	//Function to get url after domain name
	function getCurrentUri()
	{
		$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
		if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
		$uri = '/' . trim($uri, '/');
		return $uri;
	}

	function extractUri() {
		$base_url = getCurrentUri();

		$result = array();
		$routes = array();
		$routes = explode('/', $base_url);
		foreach($routes as $route)
		{
			if(trim($route) != '')
				array_push($result, $route);
		}
		return $result;
	}
?>