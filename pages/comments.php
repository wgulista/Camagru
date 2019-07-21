<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";

	$id = (isset($_GET['id']) ? htmlentities($_GET['id']) : NULL);
	$id = intval($id);

	$image = Helper::getDB()->query("SELECT id, title, description, file FROM pictures WHERE id=:id", array(
		"id" => array($id, PDO::PARAM_INT)
	))->fetch();

	$comments = Helper::getDB()->query(
		"SELECT u.id as user, u.login, c.id as comment_id, c.like_count, c.comment, c.created, c.user_id, p.id as picture_id FROM comments c 
		LEFT JOIN users u 
		ON u.id=c.user_id
		LEFT JOIN pictures p 
		ON p.id=c.picture_id
		WHERE c.picture_id=:picture_id
		ORDER BY created DESC;", 
	array(
		"picture_id" => array($image->id, PDO::PARAM_INT)
	));

	$comment = $comments->fetchAll();

	if (empty($image)) {
		header("Location: ../index.php");
	}

	require_once "../layout/header.php";
?>
<article>
	<div class="block">
		<?php if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])): ?>
			<a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>">Retour à la page précédente</a>
		<?php endif ?>
		<div class="edit_img">
			<div>
				<h1><?php echo $image->title ?></h1>
				<img src="<?php echo LAYOUT_PAGES.$image->file ?>" alt="tmp">
				<p><?php echo $image->description ?></p>
				<br clear="both">
			</div>
			<br clear="both">
			<div id="comments">
				<div class="comments-title">Comments</div>
				<?php if (Auth::isLogged()): ?>
				<div class="comment form">
					<div id="form_comment" class="form-input">
						<input type="hidden" id="picture_id" name="picture_id" value="<?php echo $image->id ?>">
						<div class="input-name">Votre commentaire</div>
						<div class="textarea">
							<textarea id="comment" name="comment" required></textarea>
						</div>
						<div class="submit">
							<button class="btn btn-little" style="float:right" onclick="add_comment()">Envoyer</button>
						</div>
					</div>
					<br clear="both">
				</div>
				<?php endif; ?>
				<?php if (!empty($comment)) : ?>
					<?php foreach ($comment as $c) {

                        $like_by_current_user = Helper::getDB()->query("SELECT count(*) as total FROM likes WHERE ref_id = :ref_id AND ref='comments' AND user_id = :user_id", array(
                            'ref_id' => array($c->comment_id, PDO::PARAM_INT),
                            'user_id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT)
                        ))->fetch();

					    ?>
						<div class="user_comment">
							<?php if (Auth::isLogged() && ($c->user_id === $_SESSION['Auth']['id'])): ?>
								<a class="btn btn-red btn-little" id="<?php echo $c->comment_id."_".$c->picture_id ?>" href="#" onclick="delete_comment(<?php echo $c->comment_id ?>, <?php echo $c->picture_id ?>)">Supprimer</a>
							<?php endif; ?>
							<div class="author">
								<?php echo ucfirst($c->login) ?>
								<div class="date">
									<?php echo $c->created ?>
								</div>
							</div>
							<div class="comment">
								<p>
									<?php echo $c->comment ?>
									<?php if (!Auth::isLogged()): ?>
									<br><span id="like_count"><?= $c->like_count ?></span> Like
									<?php endif ?>
								</p>
							</div>

                            <?php if (!Auth::isLogged()): ?>
                                <span id="like_count"><?= $c->like_count ?></span> Like
                            <?php else: ?>
                                <?php if ($like_by_current_user->total == 0): ?>
                                    <div class="like" data-ref="comments" data-ref_id="<?= $c->comment_id ?>" data-user_id="<?= isset($_SESSION['Auth']['id']) ? $_SESSION['Auth']['id'] : ''; ?>">
                                        <button id="like"><span id="like_count"><?= $c->like_count ?></span> Like</button>
                                    </div>
                                <?php else: ?>
                                    <span id="like_count"><?= $c->like_count ?></span> Like
                                <?php endif; ?>
                            <?php endif ?>
							<br clear="both">
						</div>
					<?php } ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>

<?php require_once "../layout/footer.php"; ?>