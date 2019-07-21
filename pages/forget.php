<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";
	require_once "../layout/header.php";
?>
<article>
	<div class="block">
		<div class="form forget_password">
			<h2>Mot de passe oubli&eacute; ?</h2>
			<label>Saisissez un email ou identifiant</label>
			<div class="form-input">
				<div class="input"><input type="text" id="login_email" name="login_email" placeholder="Entrer votre identifiant ou email"></div>
			</div>
			<button class="btn" onclick="forget_password()">Envoyer</button>
		</div>
	</div>
</article>
<?php require_once "../layout/footer.php"; ?>