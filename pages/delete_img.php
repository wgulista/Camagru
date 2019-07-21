<?php 
	require_once "../class/include.php";
	require_once "../function/constant.php";

	if (!Auth::isLogged()) {
		header("Location: index.php");
		die();
	}

	if (empty($_POST)) {
		header("Location: ../index.php");
	}

	$id = (!empty($_POST['id']) ? htmlentities($_POST['id']) : NULL);

	$comments = Helper::getDB()->query("SELECT * FROM comments WHERE picture_id=:picture_id;", array(
		"picture_id" => array($id, PDO::PARAM_INT)
	));

	do {
		if (count($c) > 0) {
			Helper::getDB()->query("DELETE FROM likes WHERE ref_id=:ref_id AND user_id=:user_id AND ref='comments';", array(
				"user_id" => array($c->user_id, PDO::PARAM_INT),
				"ref_id" => array($c->id, PDO::PARAM_INT)
			));
			Helper::getDB()->query("DELETE FROM comments WHERE picture_id=:picture_id;", array(
				"picture_id" => array($c->picture_id, PDO::PARAM_INT)
			));
		}
	} while ($c = $comments->fetch());

	$image = Helper::getDB()->query("SELECT * FROM pictures WHERE id=:picture_id;", array(
		"picture_id" => array($id, PDO::PARAM_INT)
	))->fetch();

	if (count($image) > 0) {
		$dest = dirname(getcwd().DS);
		$img = str_replace("/", DS, $image->file);
		unlink($dest.DS."layout".$img);
		Helper::getDB()->query("DELETE FROM likes WHERE ref_id=:picture_id AND user_id=:user_id AND ref='pictures';", array(
			"picture_id" => array($id, PDO::PARAM_INT),
			"user_id" => array($_SESSION['Auth']['id'], PDO::PARAM_INT)
		));
		Helper::getDB()->query("DELETE FROM pictures WHERE id=:picture_id;", array(
			"picture_id" => array($id, PDO::PARAM_INT)
		));
	}

?>