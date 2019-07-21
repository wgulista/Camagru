<?php 
	require_once "database.php";
	require_once "../class/Bdd.php";
	require_once "../function/constant.php";

	$screenshot_folder = dirname(getcwd()).DS.'layout'.DS.'screenshot';

	if ($folder = opendir($screenshot_folder))
	{
		while (($file = readdir($folder)) !== false) {
			if ($file == '.' || $file == '..')
				continue;
			rmdir($screenshot_folder.DS.$file);
		}
	}
	$bdd = new Bdd($DB_DSN, $DB_NAME, $DB_USER, $DB_PASSWORD, $DB_OPTIONS);
	$bdd->create_table($DB_TABLE);

	if (!empty($_SESSION))
    {
        session_unset();
        session_destroy();
        session_reset();
    }
    sleep(2);
    header("Location: ../pages/logout.php");