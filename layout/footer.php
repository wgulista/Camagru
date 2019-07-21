			</div>
			<aside id="sidebar">
				<?php if (!Auth::isLogged()): ?>
				<div class="block">

					<h2>Connecter vous</h2>
					<p>Pour profiter des fonctionnalites du site !</p>
					<div class="connexion">
						<div class="form">
							<?php if (strstr(LAYOUT, "pages")): ?>
							<form action="<?= ROOT ?>/login.php" method="post"><div class="form-input">
							<?php else: ?>
							<form action="<?= ROOT ?>/pages/login.php" method="post"><div class="form-input">
							<?php endif; ?>
								<div class="input-name">Identifiant</div>
								<div class="input">
									<input type="text" name="login"></div>
								</div>
								<div class="form-input">
									<div class="input-name">Mot de passe</div>
									<div class="input"><input type="password" name="pass"></div>
								</div>
								<div class="submit"><button class="btn" style="float:right" type="submit">Se connecter</button></div>
							</form>
							<br clear="both">
							<div class="forget">
								<?php if (strstr(LAYOUT, "pages")): ?>
								<a class="link" href="<?= ROOT ?>/forget.php">Mot de passe oubli&eacute; ?</a>
								<?php else: ?>
								<a class="link" href="<?= ROOT ?>/pages/forget.php">Mot de passe oubli&eacute; ?</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<?php if (Auth::isLogged()): ?>
				<div class="block">
					<div id='snapshot' class="snapshot">
						<h2>Your Image</h2>
						<?php 
							$image_user = Helper::getDB()->query("SELECT id, title, file FROM pictures WHERE user_id=:user_id ORDER BY created DESC;", array(
								'user_id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT)
							));
							$image_user = $image_user->fetchAll();
							?>
						<?php foreach ($image_user as $img) : ?>
							<div class="pictures">
								<?php if (strstr(LAYOUT, "pages")): ?>
								<a href="<?= ROOT ?>/comments.php?id=<?= $img->id ?>">
								<?php else: ?>
								<a href="<?= ROOT ?>/pages/comments.php?id=<?= $img->id ?>">
								<?php endif; ?>
								<img class="pictures" width="100%" src="<?= LAYOUT_PAGES.$img->file ?>" alt="<?php echo $img->title ?>" />
								</a>
								<button id="delete_img" onclick="delete_img(<?= $img->id ?>)">x</button>
							</div>
					<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>
			</aside>
		</div>
		<div class="clr"></div>
		<footer>
			<p>Copyright &copy; 2019 CAMAGRU</p>
		</footer>
	</div>
	<?php if (strstr($_SERVER['SCRIPT_FILENAME'], "pages")): ?>
		<script type="text/javascript" src="<?php echo dirname(ROOT)."/layout/js/ajax.js"; ?>"></script>
		<script type="text/javascript" src="<?php echo dirname(ROOT)."/layout/js/app.js"; ?>"></script>
		<?php if (strstr($_SERVER['SCRIPT_FILENAME'], "pages/index.php")): ?>
			<script type="text/javascript" src="<?php echo dirname(ROOT)."/layout/js/cam.js"; ?>"></script>
			<script type="text/javascript" src="<?php echo dirname(ROOT)."/layout/js/cam_btn_press.js"; ?>"></script>
		<?php endif; ?>
	<?php else: ?>
		<script type="text/javascript" src="<?php echo ROOT."/layout/js/ajax.js"; ?>"></script>
		<script type="text/javascript" src="<?php echo ROOT."/layout/js/app.js"; ?>"></script>
	<?php endif; ?>
</body>
</html>