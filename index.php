<?php
	require_once "./class/include.php";
	require_once "./function/constant.php";
	if (Auth::isLogged()) {
		header("Location: ". ROOT. "/pages/index.php");
		die();
	}

    if(get_magic_quotes_gpc()) {
        $_POST = array_map('stripslashes', $_POST);
        $_GET = array_map('stripslashes', $_GET);
        $_COOKIE = array_map('stripslashes', $_COOKIE);
    }

	include "./layout/header.php"
?>
	<article class="presentation">
		<header class="art-header">
			<h1>Camagru</h1>
			<p>Inscrivez vous pour voir les photos, les lik&eacute;s et les comment&eacute;s !</p>
			<h4>
				<a id="view_subscribe" class="link"><span style="color:#000;">Vous n'avez pas de compte ?</span> Inscrivez-vous</a>
			</h4>
			<br class="clear">
		</header>
		<div id="information" class="information">
		</div>
	</article>

<?php include "./layout/footer.php" ?>