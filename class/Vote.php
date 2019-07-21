<?php 

	class Vote
	{
		private $vote = null;
	
		private function exists($ref, $ref_id) 
		{
			$vote = Helper::getDB()->query("SELECT * FROM {$ref} WHERE id=:ref_id;", array(
				"ref_id" => array($ref_id, PDO::PARAM_INT)
			))->fetch();

			if ($vote == false) 
			{
				throw new Exception('Impossible de voter pour un enregistrement qui n\'existe pas');
			}
		}

		private function check_vote($ref, $ref_id, $user_id, $score) 
		{
			$this->exists($ref, $ref_id);

			$this->vote = Helper::getDB()->query("SELECT id, vote, user_id FROM likes WHERE ref=:ref AND ref_id=:ref_id AND user_id=:user_id;", array(
				"ref"     => array($ref, PDO::PARAM_STR),
				"ref_id"  => array($ref_id, PDO::PARAM_INT),
				"user_id" => array($user_id, PDO::PARAM_INT)
			))->fetch();

			if ($this->vote){
				Helper::getDB()->query("UPDATE likes SET vote=:vote, created=:created WHERE id=:id;", array(
					"id"      => array($this->vote->id, PDO::PARAM_INT),
					"vote"    => array($score, PDO::PARAM_INT),
					"created" => array(date('Y-m-d H:i:s'), PDO::PARAM_STR)
				));
				return (true);
			}

			return (Helper::getDB()->query("INSERT INTO likes SET vote=:vote, ref=:ref, ref_id=:ref_id, user_id=:user_id, created=:created;", array(
					"ref"     => array($ref, PDO::PARAM_STR),
					"ref_id"  => array($ref_id, PDO::PARAM_INT),
					"user_id" => array($user_id, PDO::PARAM_INT),
					"vote"    => array($score, PDO::PARAM_INT),
					"created" => array(date('Y-m-d H:i:s'), PDO::PARAM_STR)
			)));
		}

		public function like($ref, $ref_id, $user_id) 
		{
			if ($this->check_vote($ref, $ref_id, $user_id, 1))
			{
				Helper::getDB()->query("UPDATE {$ref} SET like_count = like_count + 1 WHERE id=:ref_id;", array(
					"ref_id" => array($ref_id, PDO::PARAM_INT)
				));
				return (true);
			}
			return (false);
		}

	}
