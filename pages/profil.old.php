<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";
	if (!Auth::isLogged()) {
		header("Location: index.php");
		die();
	}
	require_once "../layout/header.php";
?>
<article>
    <div class="block">
        <div class="profil">
            <br clear="both">
            <h1>Recevoir les notifications</h1>
            <p>Status : <input id="check_notif" data-user="<?= $_SESSION['Auth']['id']; ?>"type="checkbox" name="notif" value="" <?php echo (Auth::isNotif()) ? "checked" : ""; ?> /></p>
        </div>
    </div>

	<div class="block">
		<div class="profil">
			<br clear="both">
			<a href="change_password.php" class="btn">Modifier le mot de passe</a>
		</div>
	</div>
</article>

<?php require_once "../layout/footer.php"; ?>