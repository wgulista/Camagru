<?php 
	require_once "../class/include.php";
	require_once "../function/constant.php";
	if (!Auth::isLogged())
		header("Location: ../index.php");

	if (empty($_POST)) {
		header("Location: ../index.php");
	}

	$dest = dirname(getcwd().DS);
	$screenshot_file = $dest.DS."layout".DS."screenshot".DS;
	$img_filter_folder = $dest.DS."layout".DS."img".DS;
	$tmp = $screenshot_file.$_SESSION['Auth']['login'].DS."tmp".DS;

	function resize_filter($filterPath, $imgPath, $percent) {
		global $tmp;

		$filter = imagecreatefrompng($filterPath);
		$photo = imagecreatefrompng($imgPath);
		$imgWidth = imagesx($photo);
		$filterWidth = imagesx($filter);
		$filterHeight = imagesy($filter);

		// Calcul des nouvelles dimensions
		$newWidth = ($imgWidth / 100) * $percent;
		$newHeight = ($newWidth / $filterWidth) * $filterHeight;

		// Chargement
		$thumb = imagecreatetruecolor($newWidth, $newHeight);
		$source = imagecreatefrompng($filterPath);

		// Redimensionnement
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $filterWidth, $filterHeight);
		
		// Application transparance
		imagecolortransparent($thumb, imagecolorat($thumb, 0, 0));

		// Enregistrement
		header('Content-Type: image/png');
		imagepng($thumb, $tmp.'tmp.png');
	}

	function image_merge($imgPath, $filterPath, $coord_x, $coord_y) {
		global $screenshot_file;

		$dest = imagecreatefrompng($imgPath);
		$src = imagecreatefrompng($filterPath);
		imagecolortransparent($src, imagecolorat($src, 0, 0));

		$src_x = imagesx($src);
		$src_y = imagesy($src);

		$dest_x = imagesx($dest);
		$dest_y = imagesy($dest);
		$scaleX = $dest_x / 100;
		$scaleY = $dest_y / 100;

		imagealphablending($dest, true);
        imagesavealpha($dest, true);
        imagecopymerge($dest, $src, (int)($coord_x * $scaleX), (int)($coord_y * $scaleY), 0, 0, (int)($src_x), (int)($src_y), 100);
	
		// Output and free from memory
		header('Content-Type: image/png');
		imagepng($dest, $screenshot_file.DS.$_SESSION['Auth']['login'].DS."test.png");

		imagedestroy($dest);
		imagedestroy($src);	
	}

	if (isset($_POST) && !empty($_POST))
	{
		$background = (isset($_POST['img_background']) ? htmlentities($_POST['img_background']) : NULL);
		$filter_img = (isset($_POST['filter_img']) ? htmlentities($_POST['filter_img']) : NULL);
		$filter_img = $img_filter_folder.$filter_img;
		$filter_size = (isset($_POST['filter_size']) ? htmlentities($_POST['filter_size']) : NULL);
		$coord_x = (isset($_POST['coord_x']) ? htmlentities($_POST['coord_x']) : NULL);
		$coord_y = (isset($_POST['coord_y']) ? htmlentities($_POST['coord_y']) : NULL);

		$background = preg_replace('/\s/', '+', $background);
		list($type, $data) = explode(";", $background);
		list(, $data) = explode(",", $data);
		$data = base64_decode($data);
		$target = $tmp."tmp.png";
		if (!is_dir($tmp))
			mkdir($tmp, 0755, true);
		file_put_contents($target, $data);

		resize_filter($filter_img, $background, $filter_size);
		image_merge($background, $target, $coord_x, $coord_y);

		unlink($target);
		rmdir($tmp);

		header("Location: edit_img.php");
	}
