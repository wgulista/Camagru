<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";
	
	if (!Auth::isLogged()) {
		header("Location: index.php");
		die();
	}

	$dest = dirname(getcwd().DS);
	$img_file = $dest.DS."layout".DS."screenshot".DS;
	$user_folder = $img_file.$_SESSION['Auth']['login'].DS;
	$file = "http://".$_SERVER['HTTP_HOST'].LAYOUT_PAGES."/screenshot/".$_SESSION['Auth']['login']."/test.png";

	$file_check = $user_folder."test.png";
	if (!Helper::fileExists($file_check)) {
		$_SESSION['error']['img'] = "Aucune image trouve";
		header("Location: index.php");
		die();
	}

	if (isset($_POST) && !empty($_POST)) {
		if (!Helper::fileExists($_POST['file'])) {
			$_SESSION['error']['img'] = "Vous avez essayer de toucher au code source, ce n'est pas bien !";
			header("Location: edit_img.php");
		}
		$image = (isset($_POST['file']) ? htmlentities($_POST['file']) : NULL);
		$title = (isset($_POST['title']) ? htmlentities($_POST['title']) : NULL);
		$description = (isset($_POST['description']) ? htmlentities($_POST['description']) : NULL);
		$description = nl2br($description);

		if (empty($title)) {
			$_SESSION['error']['img'] = "Vous devez specifier un nom a votre image !";
			header("Location: edit_img.php");
			exit();
		}
		if (!empty($title)) {
			$title = str_replace(" ", "_", $title);
			$title = $title."_".rand(1, 100);
			$copy = $user_folder.$title.".png";
			if (!copy($image, $copy)) {
				$_SESSION['error']['img'] = "L'image n'a pas pu etre copier correctement";
				header("Location: edit_img.php");
				exit();
			}
			unlink($user_folder."test.png");
			Helper::getDB()->query("INSERT INTO pictures SET user_id=:user_id, title=:title, description=:description, created=:created, file=:file, like_count=0;", array(
				'user_id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT),
				'title' => array($_POST['title'], PDO::PARAM_STR),
				'description' => array($description, PDO::PARAM_LOB),
				'file' => array("/screenshot/".$_SESSION['Auth']['login']."/".$title.".png", PDO::PARAM_STR),
				'created' => array(date("Y-m-d H:i:s"), PDO::PARAM_STR)
			));
			header("Location: index.php");
			exit();
		}
	}

	require_once "../layout/header.php";

?>
<article>
	<div class="block">
		<div class="edit_img">
			<div>
				<h1>Cette image vous convient-elle ?</h1>
				<br clear="both">
				<img src="<?php echo $file ?>" alt="tmp">
				<br clear="both">
				<button id="edit_oui" class="btn btn-little" type="submit">Oui</button>
				<button id="edit_non" class="btn btn-little btn-red" type="submit">Non</button>
				<br clear="both">
			</div>
			<br clear="both">
			<div id="edit_ok">
			</div>
		</div>
	</div>
</article>

<script type="text/javascript">
	
	var edit_oui = document.querySelector("#edit_oui");
	var edit_non = document.querySelector("#edit_non");

	if (edit_oui) 
	{
		edit_oui.onclick = function () {
			var edit_ok = document.querySelector("#edit_ok");
			edit_ok.innerHTML = "<div class=\"form\"> <form action=\"edit_img.php\" method=\"post\"> <input type=\"hidden\" name=\"file\" value=\"<?php echo $file ?>\"> <div class=\"form-input\"> <div class=\"input-name\"> Titre </div> <div class=\"input\"> <input type=\"text\" name=\"title\"> </div> </div> <div class=\"form-input\"> <div class=\"input-name\"> Description </div> <div class=\"textarea\"> <textarea name=\"description\"></textarea> </div> </div> <div class=\"submit\"> <button class=\"btn\" style=\"float:right\" type=\"submit\">Enregistrer</button> </div> </form> <br clear=\"both\"> </div>"; 
		}
	}

	if (edit_non) 
	{
		edit_non.onclick = function () 
		{
			var edit_ok = document.querySelector("#edit_ok");
			edit_ok.innerHTML = "";
			window.location = "index.php";
		}
	}

</script>

<?php require_once "../layout/footer.php"; ?>