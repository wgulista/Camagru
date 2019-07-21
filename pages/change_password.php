<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";

	if (Auth::isLogged()) {
		$user_find = Helper::getDB()->query("SELECT id, token FROM users WHERE id=:id", array(
			"id" => array($_SESSION['Auth']['id'], PDO::PARAM_INT)
		))->fetch();
	} else {
		$id = (isset($_GET['id']) ? htmlentities($_GET['id']) : NULL);
		settype($id, "integer");
		$token = (isset($_GET['token']) ? htmlentities($_GET['token']) : NULL);
		settype($token, "string");
		$user_find = Helper::getDB()->query("SELECT id, token FROM users WHERE id=:id AND token=:token;", array(
			"id" => array($id, PDO::PARAM_INT),
			"token" => array($token, PDO::PARAM_STR)
		))->fetch();
	}

	if (!empty($_POST) && !empty($_POST['pass']) && !empty($_POST['pass2'])) {
		$pass = (isset($_POST['pass']) ? htmlentities($_POST['pass']) : NULL);
		$pass2 = (isset($_POST['pass2']) ? htmlentities($_POST['pass2']) : NULL);

		if (!preg_match(REGEX_PASS, $pass, $matches))
        {
            $_SESSION['error']['pass_error'] = "Votre mots de passe doit contenir au moins une lettre minuscule, une lettre masjuscule, un chiffre, un caractere speciale (# $ * & @) et doit faire entre 6 et 15 caracteres !";
            header("Location: change_password.php");
            die();
        }

		if (Auth::isLogged()) {
			$user = Helper::getDB()->query("SELECT id FROM users WHERE id=:id", array(
				"id" => array($_POST['id'], PDO::PARAM_INT)
			))->fetch();
		} else {
			$user = Helper::getDB()->query("SELECT id, token FROM users WHERE id=:id AND token=:token", array(
				"id" => array($_POST['id'], PDO::PARAM_INT),
				"token" => array($_POST['token'], PDO::PARAM_STR)
			))->fetch();
		}

		if (!empty($user)) {
			if ($pass == $pass2) {
				$password =  md5(sha1($pass) . md5($pass));
				if (isset($user->token) AND $user->token != NULL) {
					$user = Helper::getDB()->query("UPDATE users SET password=:password, token=NULL WHERE id=:id AND token=:token", array(
						"id" => array($user->id, PDO::PARAM_INT),
						"token" => array($user->token, PDO::PARAM_STR),
						"password" => array($password, PDO::PARAM_STR)
					));
				} else {
					$user = Helper::getDB()->query("UPDATE users SET password=:password WHERE id=:id AND token IS NULL;", array(
						"id" => array($user->id, PDO::PARAM_INT),
						"password" => array($password, PDO::PARAM_STR)
					));
				}
				$_SESSION['success'] = "Mot de passe reinitialise !";
				header("Location: index.php");
				die();
			} else {
				$_SESSION['error']['pass'] = "Les mots de passe ne sont pas identiques !";
			}
		}
	}

	if (empty($user_find)) {
		header("Location: index.php");
		die();
	}

	require_once "../layout/header.php";
?>
<article>
	<div class="block">
		<div class="change_password">
			<h1>Changer votre mot de passe</h1>
			<div class="form">
				<form action="<?= ROOT ?>/change_password.php" method="POST">
					<input type="hidden" name="id" value="<?php echo (isset($user_find->id) ? $user_find->id : $_GET['id']); ?>">
					<?php if (!Auth::isLogged()) : ?>
					<input type="hidden" name="token" value="<?php echo (isset($user_find->token) ? $user_find->token : $_GET['token']); ?>">
					<?php endif; ?>
					<div class="form-input">
						<div class="input-name">
							Nouveau mot de passe
						</div>
						<div class="input">
							<input type="password" name="pass" required>
						</div>
					</div>
					<div class="form-input">
						<div class="input-name">
							Confirmer le nouveau mot de passe
						</div>
						<div class="input">
							<input type="password" name="pass2" required>
						</div>
					</div>
					<div class="submit">
						<button class="btn" style="float:right" type="submit">Modifier</button>
					</div>
				</form>
				<br clear="both">
			</div>
		</div>
	</div>
</article>

<?php require_once "../layout/footer.php"; ?>