<?php 
	
	class Helper
	{
		static private $db;

		static function getDB()
		{
			if (!self::$db)
			{
				self::$db = new Bdd("mysql:host=localhost;charset=UTF8;", 'camagru', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));
			}
			return self::$db;
		}

		static function fileExists($path){
			return (@fopen($path,"r")==true);
		}

		static function error()
		{
			if (isset($_SESSION['error']) &&!empty($_SESSION['error']))
			{
				echo "<div id='message' class='alert'>";
				echo "<ul>";
				foreach ($_SESSION['error'] as $error) {
					echo "<li>" . $error . "</li>";
				}
				echo "</ul>";
				echo "</div>";
				unset($_SESSION['error']);
			}
		}

		static function success()
		{
			if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
				echo "<div id='message' class='success'>";
				echo "<ul>";
				echo "<li>" .  $_SESSION['success'] . "</li>";
				echo "</ul>";
				echo "</div>";
				unset($_SESSION['success']);
			}
		}
	}

?>