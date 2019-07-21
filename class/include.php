<?php
	if (session_status() !== PHP_SESSION_ACTIVE) 
	{
		session_start();
	}

	spl_autoload_register(function($class) 
	{
		require_once (ucfirst($class).".php");
	});
?>