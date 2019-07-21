<?php 
	require_once "../class/include.php";
	require_once "../function/constant.php";
	if (!Auth::isLogged()) {
		header("Location: index.php");
		die();
	}
	Helper::getDB()->query("UPDATE users SET is_connected = 0 WHERE id=:id;", array(
		'id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT)
	));
	unset($_SESSION['Auth']);
	session_destroy();
	header("Location: ../index.php");
?>