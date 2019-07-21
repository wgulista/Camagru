<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<?php if (strstr(LAYOUT, "pages")) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo LAYOUT_PAGES."/css/style.css" ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo LAYOUT_PAGES."/css/media_queries.css" ?>">
	<?php } else { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo LAYOUT."/css/style.css" ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo LAYOUT."/css/media_queries.css" ?>">
	<?php } ?>
	<title>Camagru</title>
</head>
<body>
		<div id="container">
			<header class="main-header">
				<a id="logo" href="<?php echo ROOT ?>/index.php">Camagru</a>
				<nav class="main-nav">
					<ul>
						<?php if (strstr(LAYOUT, "pages")): ?>
						<li><a href="<?php echo ROOT ?>/index.php">Accueil</a></li>
						<li><a href="<?php echo ROOT ?>/gallery.php">Gallery</a></li>
						<?php else: ?>
						<li><a href="<?php echo ROOT ?>/pages/index.php">Accueil</a></li>
						<li><a href="<?php echo ROOT ?>/pages/gallery.php">Gallery</a></li>
						<?php endif; ?>
						<?php if (Auth::isLogged()): ?>
						<li><a href="<?php echo ROOT ?>/profil.php">Profile</a></li>
						<li><a onclick="logout()">Logout</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</header>

			<?php 
				Helper::error();
				Helper::success();
			?>

			<div id="main">
				<div id="content">