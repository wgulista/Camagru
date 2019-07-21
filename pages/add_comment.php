<?php
	require_once "../class/include.php";

	if (!Auth::isLogged()) {
		header("Location: index.php");
		die();
	}

	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		$_SESSION['error']['url'] = "Une erreur est survenue !";
		die();
	}

	if (empty($_POST)) {
		header("Location: ../index.php");
		die();
	}





	if (!empty($_POST) && !empty($_POST['picture_id']) && !empty($_POST['comment'])) {
		header("Content-Type: text/plain");

		$picture_id = isset($_POST['picture_id']) ? htmlentities($_POST['picture_id']) : NULL;
		$comment = isset($_POST['comment']) ? htmlentities($_POST['comment']) : NULL;

		Helper::getDB()->query("INSERT INTO comments SET user_id=:user_id, picture_id=:picture_id, comment=:comment, created=:created, like_count = 0;", array(
			"user_id" => array($_SESSION['Auth']['id'], PDO::PARAM_INT),
			"picture_id" => array($picture_id, PDO::PARAM_INT),
			"comment" => array($comment, PDO::PARAM_STR),
			"created" => array(date("Y-m-d H:i:s"), PDO::PARAM_STR )
		));

		$user_picture = Helper::getDB()->query(
			"SELECT p.user_id, u.email, u.login, p.title, u.notif FROM pictures p
			JOIN users u ON u.id = p.user_id
			WHERE p.id=:picture_id
		", array(
			"picture_id" => array($picture_id, PDO::PARAM_INT)
		))->fetch();

		$message = "
		<html>
			<head>
				<title>Camagru - Commentaire - ". $user_picture->title ."</title>
			</head>
			<body>
				<table>
					<tr>
						<td>Bonjour ".$user_picture->login."</td>
					</tr>
					<tr>
						<td>Vous avez recu un nouveau commentaire</td>
					</tr>
					<tr>
						<td>".$comment."</td>
					</tr>
				</table>
			</body>
		</html>";

		if ($user_picture->notif)
        {
            mail($user_picture->email, "Camagru - Commentaire - ".$user_picture->title, $message,
                "From: contact@camagru.com \n".
                "Reply-To:" .$user_picture->email." \n".
                "Content-Type: text/html"
            );
        }
		$_SESSION['success'] = "Votre commentaire a bien ete enregistre !";
	} else {
		$_SESSION['error']['login_email'] = "Le commentaire n'a pas pu etre envoye correctement !";
	}