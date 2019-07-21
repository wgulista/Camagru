<?php
	require_once "../class/include.php";
	
	if (empty($_GET['id']) && empty($_GET['token']) ||
		!empty($_GET['id']) && empty($_GET['token']) ||
		empty($_GET['id']) && !empty($_GET['token']))
	{
		header("Location: ../index.php");
		exit();
	}

	if (!empty($_GET) && !empty($_GET['id']) && !empty($_GET['token']))
	{
		$id_user = (isset($_GET['id']) ? htmlentities($_GET['id']) : NULL);
		$token = (isset($_GET['token']) ? htmlentities($_GET['token']) : NULL);
		$user = Helper::getDB()->query("SELECT id FROM users WHERE id=:id AND token=:token;", array(
			'id' => array($id_user, PDO::PARAM_INT),
			'token'  => array($token, PDO::PARAM_STR)
		))->fetch();

		if ($user)
		{

			Helper::getDB()->query("UPDATE users SET token = NULL WHERE id=:id;", array(
				'id' => array($user->id, PDO::PARAM_INT)
			));
			$_SESSION['success'] = "Votre compte a ete active avec succes !";

		}
		else
		{
			$_SESSION['error']['validatation_failed'] = "Une erreur est survenue lors de la validation de votre compte !";
		}

		header("Location: ../index.php");
		exit();
	}
?> 
