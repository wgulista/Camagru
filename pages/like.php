<?php
	require_once "../class/include.php";

	if (!Auth::isLogged()) {
		header("Location: ../index.php");
		die();
	}

	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		http_response_code(403);
		die();
	}

	if(!isset($_SESSION['Auth']['id'])){
	    http_response_code(403);
	    die('Vous devez être connecté pour voter');
	}

	$accepted_refs = ["pictures", "comments"];
	if(!in_array($_POST['ref'], $accepted_refs)){
	    http_response_code(403);
	    die();
	}

	$like = new Vote();
	if ($_POST["like"] == 1) {
		$success = $like->like($_POST['ref'], $_POST['ref_id'], $_POST['user_id']);
		$success = ($success) ? 'true' : 'false';
	}

	$count = Helper::getDB()->query("SELECT count(*) as total FROM likes WHERE ref = :ref and ref_id = :ref_id", array(
	    'ref'       => array($_POST['ref'], PDO::PARAM_STR),
        'ref_id'    => array($_POST['ref_id'], PDO::PARAM_INT)
    ))->fetch();

	$update = Helper::getDB()->query("UPDATE {$_POST['ref']} SET like_count = :count WHERE id = :id", array(
        'count'     => array($count->total, PDO::PARAM_INT),
        'id'        => array($_POST['ref_id'], PDO::PARAM_INT)
    ));

	$votes = Helper::getDB()->query("SELECT like_count FROM {$_POST['ref']} WHERE id = :id;", array(
		"id" => array($_POST['ref_id'], PDO::PARAM_INT)
	))->fetch();

	header('Content-Type: application/json');
	die(json_encode(array(
		"like_count" => $votes->like_count,
		"success"    => $success
	), JSON_PRETTY_PRINT));
