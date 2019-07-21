<?php
	require_once "../class/include.php";

	if (empty($_POST['login']) && empty($_POST['pass']) ||
		!empty($_POST['login']) && empty($_POST['pass']) ||
		empty($_POST['login']) && !empty($_POST['pass']))
	{
		header("Location: ../index.php");
		exit();
	}
	if (!empty($_POST) && !empty($_POST['login']) && !empty($_POST['pass']))
	{
		$login = (isset($_POST['login']) ? htmlentities($_POST['login']) : NULL);
		$password = (isset($_POST['pass']) ? htmlentities($_POST['login']) : NULL);
		$password = md5(sha1($_POST['pass']).md5($_POST['pass']));
		$user = Helper::getDB()->query("SELECT id, login, password, is_connected, notif FROM users WHERE login=:login AND password=:pass AND token IS NULL;", array(
			'login' => array($login, PDO::PARAM_STR),
			'pass'  => array($password, PDO::PARAM_STR)
		))->fetch();
		
		if ($user)
		{
			Helper::getDB()->query("UPDATE users SET is_connected = 1 WHERE id=:id;", array(
				'id' => array($user->id, PDO::PARAM_INT)
			));
			$user->is_connected = 1;
			$_SESSION['Auth'] = (array)$user;
			$_SESSION['success'] = "Connexion reussi !"; 
		}
		else
		{
			$user = Helper::getDB()->query("SELECT id FROM users WHERE login=:login AND password=:pass AND token IS NOT NULL;", array(
				'login' => array($login, PDO::PARAM_STR),
				'pass'  => array($password, PDO::PARAM_STR)
			))->fetch();

			if ($user)
			{
				$_SESSION['error']['connect'] = "Vous devez d'abord valider votre compte !";
			}
			else
			{
				$_SESSION['error']['connect'] = "Mot de passe ou login incorrect !";
			}
		}
		header("Location: ../index.php");
		exit();
	}
?> 
