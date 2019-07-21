<?php
	require_once "../class/include.php";
	require_once "../function/constant.php";
	if (!Auth::isLogged()) {
		header("Location: ../index.php");
		die();
	}
	require_once "../layout/header.php";
?>
<article>
	<?php if (Auth::isLogged()): ?>
	<div class="hello">
		<h2>Hi <?php echo ucfirst($_SESSION['Auth']['login']); ?></h2>
	</div>
	<?php endif; ?>
	<div class="choice">
		<a id="view_video" class="link"><span style="color:#000">Pas de photo ?</span> Utiliser la camera</a>
		<br clear="both">
		<a id="view_file" class="link"><span style="color:#000">Pas de camera ? </span>Uploader un fichier</a>
	</div>
	<div id="block_video" class="block">
		<div id="block_video_camera" class="video">
			<div class="camera"> 
				<img id="select_filter" src="" alt="selected_filter" style="width:20%;" />
				<video id="video_launch" width="320" height="240" autoplay muted></video> 
				<button id="shot" class="btn">Take snapshot</button> 
			</div> 
			<br clear="both"> 
		</div> 
		<div id="filter_video" class="filter_video"> 
			<div class="label">Background </div> <div id="background" class="background"> 
				<img id="cat_snap" src="<?php echo LAYOUT_PAGES."/img/cat_snap.png"; ?>" title="Cat Snap"> 
				<img id="cigarret" src="<?php echo LAYOUT_PAGES."/img/cigarret.png"; ?>" title="Cigarret">
				<img id="frontcap" src="<?php echo LAYOUT_PAGES."/img/frontcap.png"; ?>" title="Front Cap">
				<img id="girlhair" src="<?php echo LAYOUT_PAGES."/img/girlhair.png"; ?>" title="Girl Hair">
				<img id="sidecap" src="<?php echo LAYOUT_PAGES."/img/sidecap.png"; ?>" title="Side Cap"> 
			</div> 
			<br clear="both">
		</div>
		<div class="form_hidden">
			<canvas id="canvas" style="display:none"></canvas>
			<form id="filter_form" action="make_picture.php" method="post">
				<input type="hidden" name="img_background" id="img_background" value="">
				<input type="hidden" name="filter_img" id="filter_img">
				<input type="hidden" name="filter_size" value="20" id="filter_size">
				<input type="hidden" name="coord_x" id="filter_x_coord">
				<input type="hidden" name="coord_y" id="filter_y_coord">
			</form>
		</div>
	</div>
</article>
<script type="text/javascript">
	
	var view_file = document.querySelector("#view_file");

	if (view_file) 
	{
		view_file.onclick = function () {
			var block_video_camera = document.querySelector("#block_video");
			block_video_camera.innerHTML = "<div class='put_file'><form method='POST' action='save_image.php' enctype='multipart/form-data'><input id='input_image' class='input-file' onchange='readURL(this)' type='file' name='files' /><img id='preupload' src='#' alt='selected_filter' style='width:50%; margin:10px auto; display:block; ' /> <button id='shot' class='btn'>Uploader</button></div>";
		}
	}

	if (view_video) 
	{
		view_video.onclick = function () 
		{
			window.location = "index.php";
		}
	}

</script>

<?php require_once "../layout/footer.php"; ?>