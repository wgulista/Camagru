<?php 

	class Auth
	{	
		static function isLogged()
		{
			if (isset($_SESSION['Auth']) && isset($_SESSION['Auth']['login']))
				return (true);
            return (false);
		}

		static function isNotif()
        {
            if (isset($_SESSION['Auth']) && isset($_SESSION['Auth']['login']) && isset($_SESSION['Auth']['notif']) && $_SESSION['Auth']['notif'] == 1)
                return (true);
            return (false);
        }
	}
