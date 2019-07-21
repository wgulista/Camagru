<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";
	require_once "../config/database.php";

	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		http_response_code(403);
		die();
	}

	if (empty($_POST)) {
		header("Location: ../index.php");
		die();
	}

	if (!empty($_POST))
	{
		if (empty($_POST['login']) || !preg_match('/^[a-zA-Z0-9]+$/', $_POST['login'])) {
			$_SESSION['error']['login'] = "Votre identifiant n'est pas correcte ou identifiant vide !";
		} else {
			$user = Helper::getDB()->query("SELECT id FROM users WHERE login=:username", array(
				'username' => array($_POST['login'], PDO::PARAM_STR)
			))->fetch();
			if ($user) {
				$_SESSION['error']['user_exist'] = "Cet identifiant est deja utilise";
			}
		}

		$regex = "/^([a-z0-9][-a-z0-9_\+\.]*[a-z0-9])@([a-z0-9][-a-z0-9\.]*[a-z0-9]\.(fr|com)|([0-9]{1,3}\.{3}[0-9]{1,3}))$/i";
		if (empty($_POST['email']) || !preg_match($regex, $_POST['email'])) {
			$_SESSION['error']['email'] = "Votre adresse email n'est pas valide";
		} else {
			$email = Helper::getDB()->query("SELECT id FROM users WHERE email=:email", array(
				'email' => array($_POST['email'], PDO::PARAM_STR)
			))->fetch();
			if ($email)
				$_SESSION['error']['email_exist'] = "Cet email est deja utilise";
		}

		if (!preg_match("/^[\w]{6,20}$/i", $_POST['login'], $matches))
        {
            $_SESSION['error']['login'] = "Votre identifiant doit contenir entre 6 et 20 caracteres !";
        }

		if (empty($_POST['pass']) || !preg_match(REGEX_PASS, $_POST['pass'], $matches) || ($_POST['pass'] !== $_POST['pass2'])) {
            $_SESSION['error']['pass'] = "Votre mot de passe doit contenir au moins une lettre minuscule, une lettre masjuscule, un chiffre, un caractere speciale (# $ * & @) et doit faire entre 6 et 15 caracteres !";
		}

		if (empty($_SESSION['error']))
		{
			$password = md5(sha1($_POST['pass']).md5($_POST['pass']));
			$token = md5(sha1($_POST['pass']).md5($_POST['pass']) + time());
			Helper::getDB()->query("INSERT INTO users SET login=:login, password=:password, email=:email, token=:token, is_connected=:is_connected, created=:created", array(
				'login'        => array($_POST['login'], PDO::PARAM_STR),
				'email'        => array($_POST['email'], PDO::PARAM_STR),
				'password'     => array($password, PDO::PARAM_STR),
				'token'        => array($token, PDO::PARAM_STR),
				'is_connected' => array(0, PDO::PARAM_INT),
				'created'      => array(date("Y-m-d H:i:s"), PDO::PARAM_STR)
			));
			$last_user = Helper::getDB()->getLast();
			$message = "
			<html>
				<head>
					<title>Camagru - Confirmation de votre compte - ". $last_user->email ."</title>
				</head>
				<body>
					<table>
						<tr>
							<td>Bonjour ".$last_user->login."</td>
						</tr>
						<tr>
							<td>Pour pouvoir utiliser votre compte sur Camagru, veuillez valider le lien suivant</td>
						</tr>
						<tr>
							<td><a href='http://".$_SERVER['HTTP_HOST']."/".ROOT."/validate.php?id=".$last_user->id."&token=".$last_user->token."'>Cliquez ici</a></td>
						</tr>
					</table>
				</body>
			</html>";

            mail($last_user->email, "Camagru - Confirmation de votre compte", $message,
                "From: contact@camagru.com \n".
                "Reply-To:" .$last_user->email." \n".
                "Content-Type: text/html"
            );
			$_SESSION['success'] = "Votre compte a bien ete cree, veuillez confirmer l'email de validation !";
			header("Location: ../index.php");
			die();
		} else {
			header("Location: ../index.php");
			die();
		}
	}
?>