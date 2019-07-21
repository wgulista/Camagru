<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";
	if (!Auth::isLogged()) {
		header("Location: index.php");
		die();
	}
	require_once "../layout/header.php";

	$check_notif = Helper::getDB()->query(
		"SELECT notif FROM users WHERE id = :user_id", 
		array( 
			'user_id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT)
		)
	)->fetch();

	$_SESSION['Auth']['notif'] = $check_notif->notif;

	if (!empty($_POST))
    {
        $notif = $_POST['notif_recevoir'];
        Helper::getDB()->query(
            "UPDATE camagru.users SET users.notif = :notif WHERE users.id = :user_id",
            array(
                'user_id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT),
                'notif'   => array($notif, PDO::PARAM_INT)
            )
        );
        $_SESSION['success'] = "Les changements ont bien ete pris en compte !";
        header("Location: ".$_SERVER['REQUEST_URI']);
    }

?>
<article>
    <div class="block">
        <form class="profil" method="POST" action="<?= ROOT ?>/profil.php">
            <h1>Recevoir les notifications</h1>
            Status :
            OUI <input id="notif_recevoir" type="radio" value="1" name="notif_recevoir" <?php echo ($_SESSION['Auth']['notif'] == 1 ? 'checked="checked"' : ''); ?> />
            NON <input id="notif_ne_pas_recevoir" type="radio" value="0" name="notif_recevoir" <?php echo ($_SESSION['Auth']['notif'] == 0 ? 'checked="checked"' : ''); ?> />
            <br>
            <input class="btn" type="submit" value="Save Profile">
        </form>
    </div>

	<div class="block">
		<div class="profil">
			<br clear="both">
			<a href="change_password.php" class="btn">Modifier le mot de passe</a>
		</div>
	</div>
</article>

<?php require_once "../layout/footer.php"; ?>