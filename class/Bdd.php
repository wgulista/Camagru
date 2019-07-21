<?php 

	class Bdd
	{
		private $db = NULL;
		private $user = NULL;
		private $password = NULL;
		private $pdo = NULL;
		private $dsn = NULL;

		public function __construct($db_dsn, $dbname, $db_user, $db_password, $options)
		{
			$this->db		= $dbname;
			$this->user		= $db_user;
			$this->password = $db_password;
			$this->dsn		= $db_dsn;
			$this->options	= $options;
			try {
				$database = new PDO($this->dsn, $this->user, $this->password, $this->options);
				$database->exec("CREATE DATABASE IF NOT EXISTS camagru;");
				$database->query("USE camagru;");
				$database = NULL;
				$this->dsn = $this->dsn."dbname=".$this->db;
				$pdo = new PDO($this->dsn, $this->user, $this->password, $this->options);
			} catch (PDOException $e) {
				var_dump("DB ERROR : ". $e->getMessage());
			}
			$this->pdo = $pdo;
			$pdo = NULL;
		}

		public function create_table($table = array())
		{			
			echo "<span style='color:#FFA500; font-weight:semi-bold'>Creation table ...</span><br>";
			foreach ($table as $table_name => $t) {
				Bdd::query("DROP TABLE IF EXISTS ".$table_name.";", null);
				Bdd::query("CREATE TABLE IF NOT EXISTS ".$t.";", null);
			}
			echo "<span style='color:#00CD00; font-weight:bold'>Table created</span><br>";
		}

		public function query($sql, $data = array())
		{
			$res = $this->pdo->prepare($sql);
			if ($data != null) {
				foreach ($data as $k => $v) {
					$res->bindParam(":".$k, $v[0], $v[1]);
				}
				$res->execute();
			}
			else
				$res->execute();
			return $res;
		}

		public function getLast($name = null)
		{
			$id = $this->pdo->lastInsertId($name);
			$last = $this->query(
				"SELECT * FROM users where id=:id LIMIT 1", 
				array("id" => array($id, PDO::PARAM_INT))
			)->fetch();
			return $last;
		}
	}