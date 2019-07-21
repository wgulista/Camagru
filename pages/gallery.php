<?php
require_once "../class/include.php";
require_once "../function/constant.php";

$limit = 0;
$offset = 8;
$range = 2;
if (isset($_GET['pages'])) {
    if (is_numeric($_GET['pages'])) {
        $_GET['pages'] = abs(intval($_GET['pages']));
        $limit = (($_GET['pages']) * $offset);
    } else {
        header("Location: gallery.php");
    }
}

$picture = Helper::getDB()->query(
    "SELECT p.file, p.id, p.title, p.user_id, p.like_count, users.login FROM pictures p
        INNER JOIN users ON users.id = p.user_id
		ORDER BY p.created DESC
		LIMIT :limit, :offset",
    array(
        "limit" => array($limit, PDO::PARAM_INT),
        "offset" => array($offset, PDO::PARAM_INT),
    ));

$p = $picture->fetchAll();

//$count_image = count((array)$p); // on avait uncount basé juste sur la portion d'image qu'on chargeait
$count_image = Helper::getDB()->query(
        "SELECT COUNT(pictures.id) nb from pictures"
)->fetch();
$count_image = $count_image->nb;

$nbPages = (int)ceil($count_image / $offset);

require_once "../layout/header.php";
?>
    <article>
        <div class="block">
            <div class="gallery">
                <?php if (!empty($p)): ?>
                    <?php foreach ($p as $picture) : ?>

                        <?php

                        $like_by_current_user = Helper::getDB()->query("SELECT * FROM likes WHERE ref_id = :ref_id AND ref='pictures' AND user_id = :user_id", array(
                            'ref_id' => array($picture->id, PDO::PARAM_INT),
                            'user_id' => array($_SESSION['Auth']['id'], PDO::PARAM_INT)
                        ))->fetch();

                        ?>
                        <div class="image">
                            <a href="comments.php?id=<?php echo $picture->id; ?>">
                                <img src="<?php echo LAYOUT_PAGES.$picture->file; ?>" alt="<?php echo $picture->title; ?>">
                            </a>
                            <?php if (!Auth::isLogged()): ?>
                                <span id="like_count"><?= $picture->like_count ?></span> Like
                            <?php else: ?>
                                <?php if (!$like_by_current_user): ?>
                                    <div class="like" data-ref="pictures" data-ref_id="<?= $picture->id ?>" data-user_id="<?= isset($_SESSION['Auth']['id']) ? $_SESSION['Auth']['id'] : ''; ?>">
                                        <button id="like"><span id="like_count"><?= $picture->like_count ?></span> Like</button>
                                    </div>
                                <?php else: ?>
                                    <span id="like_count"><?= $picture->like_count ?></span> Like
                                <?php endif; ?>
                            <?php endif ?>
                            <br clear="both">
                            <span class="author"><?php echo $picture->login ?></span>
                        </div>
                    <?php endforeach; ?>

				<div class="pagination">
					<ul>
					Pages :
					<?php
					for ($i = 0; $i < $nbPages; $i++) {
						echo ('<li><a href="gallery.php?pages='.($i).'">'.($i+1).'</a></li>');
					}
					?>
					</ul>
				</div>

                <?php else: ?>
                    <h1>Pas encore d'images</h1>
                <?php endif; ?>
            </div>
        </div>
    </article>

<?php require_once "../layout/footer.php"; ?>