<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";

	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		http_response_code(403);
		die();
	}

	if (!empty($_POST) && !empty($_POST['login_email']))
	{
		$login_email = (isset($_POST['login_email']) ? htmlentities($_POST['login_email']) : NULL);
		$search_email = Helper::getDB()->query("SELECT id, password, email, login FROM users WHERE email=:email OR login=:login", array(
			'login' => array($login_email, PDO::PARAM_STR),
			'email' => array($login_email, PDO::PARAM_STR)
		))->fetch();
		if ($search_email)
		{
			$token = md5(sha1($search_email->email).md5($search_email->email) + time());
			Helper::getDB()->query("UPDATE users SET token=:token WHERE id=:id;", array(
				"id" => array($search_email->id, PDO::PARAM_INT),
				"token" =>  array($token, PDO::PARAM_STR)
			));
			$message = "
			<html>
				<head>
					<title>Camagru - Reinitialise votre mot de passe - ". $search_email->email ."</title>
				</head>
				<body>
					<table>
						<tr>
							<td>Bonjour ".$search_email->login."</td>
						</tr>
						<tr>
							<td>Pour pouvoir utiliser votre compte sur Camagru, veuillez valider le lien suivant</td>
						</tr>
						<tr>
							<td><a href='http://".$_SERVER['HTTP_HOST']."/".ROOT."/change_password.php?id=".$search_email->id."&token=".$token."'>Cliquez ici</a></td>
						</tr>
					</table>
				</body>
			</html>";

			mail($search_email->email, "Camagru - Reinitialise votre mot de passe", $message,
				"From: contact@camagru.com \n".
				"Reply-To:" .$search_email->email." \n".
				"Content-Type: text/html"
			);
			$_SESSION['success'] = "Mot de passe reinitialise";
		} else {
			$_SESSION['error']['login_email'] = "Cette email ou identifiant n'existe pas !";
		}
	} else {
		$_SESSION['error']['login_email'] = "Veuillez entrer un nom d'utilisateur ou un email valide !";
	}
?>