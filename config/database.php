<?php
	$DB_DSN = "mysql:host=localhost;charset=UTF8;";
	$DB_NAME = 'camagru';
	$DB_USER = "root";
	$DB_PASSWORD = "";
	$DB_TABLE = array(
		'users'=> 'users (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
			login VARCHAR(255) NOT NULL,
			password VARCHAR(255) NOT NULL,
			email VARCHAR(255) NOT NULL,
			is_connected TINYINT(1) UNSIGNED NOT NULL,
			token VARCHAR(255) NULL,
			notif TINYINT(1) DEFAULT 1,
			created DATETIME
		)',
		'pictures' => 'pictures (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user_id INT UNSIGNED NOT NULL,
			title VARCHAR(255) NOT NULL,
			description LONGTEXT,
			file VARCHAR(255) NOT NULL,
			like_count INT UNSIGNED NOT NULL,
			created DATETIME
		)',
		'comments' => 'comments (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user_id INT UNSIGNED NOT NULL,
			picture_id INT UNSIGNED NOT NULL,
			comment VARCHAR(300) NOT NULL,
			like_count INT UNSIGNED NOT NULL,
			created DATETIME
		)',
		'likes' => 'likes (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user_id INT UNSIGNED NOT NULL,
			ref_id INT UNSIGNED NOT NULL,
			ref VARCHAR(60) NOT NULL,
			vote TINYINT(1) UNSIGNED NOT NULL,
			created DATETIME
		)'
	);
	$DB_OPTIONS = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
?>