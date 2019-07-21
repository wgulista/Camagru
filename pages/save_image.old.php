<?php 
	require_once "../class/include.php";
	require_once "../function/constant.php";
	
	if (!Auth::isLogged()) {
		header("Location: ../index.php");
		die();
	}

	if (isset($_FILES['files']['size']) && $_FILES['files']['size'] == 0) {
		$_SESSION['error']['ext'] = "Veuillez selectionner une image";
		header("Location: index.php");
		die();
	}

	if (!empty($_FILES)) {
		$dest = dirname(getcwd().DS);
		$img_file = $dest.DS."layout".DS."screenshot".DS;
		$user_folder = $img_file.$_SESSION['Auth']['login'].DS;
		if (!is_dir($user_folder)) {
			mkdir($user_folder, 0755, true);
		}
		$img = $_FILES['files'];
		$img['name'] = strtolower($img['name']);
		$img_name = explode('.', $img['name']);
		array_pop($img_name);
		$title = implode('.', $img_name);
		$title = str_replace(" ", "_", $img_name[0]);
		$title = $title."_".rand(1, 100).'.'.time();
        if (preg_match('/(.+\.jpeg|.+\.jpg|.+\.png)$/i', $img['name']) && $img['size'] <= MAX_IMG_SIZE) {
			move_uploaded_file($img['tmp_name'], $user_folder.$title.".png");
			Helper::getDB()->query("INSERT INTO pictures SET user_id=:user_id, title=:title, description=:description, created=:created, file=:file, like_count=0;", array(
				'user_id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT),
				'title' => array($title, PDO::PARAM_STR),
				'description' => array("", PDO::PARAM_LOB),
				'file' => array("/screenshot/".$_SESSION['Auth']['login']."/".$title.".png", PDO::PARAM_STR),
				'created' => array(date("Y-m-d H:i:s"), PDO::PARAM_STR)
			));
			$_SESSION['success'] = "Votre image a bien ete enregistrée";
			header("Location: index.php");
			die();
		} else { 
			$_SESSION['error']['ext'] = "L'image que vous avez essayer d'uploader n'est pas valide !";
			header("Location: index.php");
			die();
		}
	}