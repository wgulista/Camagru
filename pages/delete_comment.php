<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";

	if (!Auth::isLogged()) {
		header("Location: index.php");
		die();
	}

	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		$_SESSION['error']['url'] = "Une erreur est survenue !";
		header("Location: index.php");
		die();
	}

	if (empty($_POST)) {
		header("Location: ../index.php");
		die();
	}

	if (!empty($_POST['comment_id']) && !empty($_POST['picture_id'])) {
		$comment_id = (isset($_POST['comment_id']) ? htmlentities($_POST['comment_id']) : NULL);
		$picture_id = (isset($_POST['picture_id']) ? htmlentities($_POST['picture_id']) : NULL);
		$comment = Helper::getDB()->query(
			"SELECT id, user_id, picture_id 
			FROM comments 
			WHERE id=:id AND user_id=:user_id AND picture_id=:picture_id;", 
		array(
			"user_id" => array($_SESSION['Auth']['id'], PDO::PARAM_INT),
			"picture_id" => array($picture_id, PDO::PARAM_INT),
			"id" => array($comment_id, PDO::PARAM_INT)
		))->fetch();
	} else {
		$_SESSION['error']['del_comment'] = "Ce commentaire n'existe pas";
		header("Location: ../index.php");
		die();
	}

	if (count($comment) > 0) {
		Helper::getDB()->query("DELETE FROM likes WHERE ref_id=:ref_id AND user_id=:user_id AND ref='comments';", array(
			"user_id" => array($comment->user_id, PDO::PARAM_INT),
			"ref_id" => array($comment->id, PDO::PARAM_INT)
		));
		Helper::getDB()->query("DELETE FROM comments WHERE id=:id AND user_id=:user_id AND picture_id=:picture_id;", array(
			"user_id" => array($comment->user_id, PDO::PARAM_INT),
			"picture_id" => array($comment->picture_id, PDO::PARAM_INT),
			"id" => array($comment->id, PDO::PARAM_INT)
		));
		$_SESSION['success'] = "Votre commentaire a bien ete supprime !";
	} else {
		$_SESSION['error']['del_comment'] = "Ce commentaire ne vous appartient pas !";
	}

?>