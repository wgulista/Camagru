<?php
	if (!defined("DS"))
		define("DS", DIRECTORY_SEPARATOR);
	// Donne le chemin de la racine
	if (!defined("ROOT"))
		define("ROOT", dirname($_SERVER['SCRIPT_NAME']));
	// Donne le chemin du dossier layout
	if (!defined("LAYOUT"))
		define("LAYOUT", ROOT."/layout");

	if (!defined("LAYOUT_PAGES"))
		define("LAYOUT_PAGES", dirname(ROOT)."/layout");

	if (!defined('REGEX_PASS'))
	    define("REGEX_PASS", "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[#$*&@])[\w#$*&@]{6,15}$/i");

	if (!defined('MAX_IMG_SIZE'))
	    define('MAX_IMG_SIZE', 5000000);

?>