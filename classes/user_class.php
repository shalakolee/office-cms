<?php
/*
coded by:
¦¦¦¦¦¦¦¦_   _¦     _¦¦¦¦¦¦_     _¦¦¦¦¦¦_   _¦          _¦¦¦¦¦¦¦¦ 
¦¦¦   ¯¦¦¦ ¦¦¦    ¦¦¦    ¦¦¦   ¦¦¦    ¦¦¦ ¦¦¦         ¦¦¦    ¦¦¦ 
¦¦¦    ¦¦¦ ¦¦¦¦   ¦¦¦    ¦¯    ¦¦¦    ¦¯  ¦¦¦         ¦¦¦    ¦¦¦ 
¦¦¦    ¦¦¦ ¦¦¦¦  _¦¦¦         _¦¦¦        ¦¦¦        _¦¦¦____¦¦¯ 
¦¦¦    ¦¦¦ ¦¦¦¦ ¯¯¦¦¦ ¦¦¦¦_  ¯¯¦¦¦ ¦¦¦¦_  ¦¦¦       ¯¯¦¦¦¯¯¯¯¯   
¦¦¦    ¦¦¦ ¦¦¦    ¦¦¦    ¦¦¦   ¦¦¦    ¦¦¦ ¦¦¦       ¯¦¦¦¦¦¦¦¦¦¦¦ 
¦¦¦   _¦¦¦ ¦¦¦    ¦¦¦    ¦¦¦   ¦¦¦    ¦¦¦ ¦¦¦¦    _   ¦¦¦    ¦¦¦ 
¦¦¦¦¦¦¦¦¯  ¦¯     ¦¦¦¦¦¦¦¦¯    ¦¦¦¦¦¦¦¦¯  ¦¦¦¦¦__¦¦   ¦¦¦    ¦¦¦ 
and:
 ▄▄▄       █     █░▓█████   ██████  ▒█████   ███▄ ▄███▓▓█████  ██ ▄█▀ ▒█████  
▒████▄    ▓█░ █ ░█░▓█   ▀ ▒██    ▒ ▒██▒  ██▒▓██▒▀█▀ ██▒▓█   ▀  ██▄█▒ ▒██▒  ██▒
▒██  ▀█▄  ▒█░ █ ░█ ▒███   ░ ▓██▄   ▒██░  ██▒▓██    ▓██░▒███   ▓███▄░ ▒██░  ██▒
░██▄▄▄▄██ ░█░ █ ░█ ▒▓█  ▄   ▒   ██▒▒██   ██░▒██    ▒██ ▒▓█  ▄ ▓██ █▄ ▒██   ██░
 ▓█   ▓██▒░░██▒██▓ ░▒████▒▒██████▒▒░ ████▓▒░▒██▒   ░██▒░▒████▒▒██▒ █▄░ ████▓▒░
 ▒▒   ▓▒█░░ ▓░▒ ▒  ░░ ▒░ ░▒ ▒▓▒ ▒ ░░ ▒░▒░▒░ ░ ▒░   ░  ░░░ ▒░ ░▒ ▒▒ ▓▒░ ▒░▒░▒░ 
  ▒   ▒▒ ░  ▒ ░ ░   ░ ░  ░░ ░▒  ░ ░  ░ ▒ ▒░ ░  ░      ░ ░ ░  ░░ ░▒ ▒░  ░ ▒ ▒░ 
  ░   ▒     ░   ░     ░   ░  ░  ░  ░ ░ ░ ▒  ░      ░      ░   ░ ░░ ░ ░ ░ ░ ▒  
      ░  ░    ░       ░  ░      ░      ░ ░         ░      ░  ░░  ░       ░ ░ 
User Class
+----------------------------------+
+Version: 			0.0.2
+Author: 			Shalako Lee & Trevor Nolan
+Latest Revision: 	11/23/2015
+Started: 			11/19/2015
+----------------------------------+

-
*/

class User {	

	public $isAdmin = false;
	public $isMasterAdmin = false;


	function __construct() {
		//just constructing this will reset all its variabless
		$isAdmin		= false;
		$isMasterAdmin 		= false;

	}	

	public function reset(){
		foreach ($this as $key => $value) {
            unset($this->$key);
        }
	}

	public function logout(){
		$this->reset();
		$_SESSION['session']->user = $this;
		return true;
	}

	public function login($user,$pass) {
		$db = new db();
		$sql = 'SELECT * from employees where username = ? and status="active"';		
		$results = $db->query($sql, $user );
		if(count($results) == 1 && password_verify($pass, $results[0]->password) ):
			//user is valid, lets log them in
			$this->id = $results[0]->id;
			$this->firstname = ucwords($results[0]->fname);
			$this->lastname = ucwords($results[0]->lname);
			$this->loggedin = 1;
			//set user admin
			$this->isAdmin = $this->isUserAdmin($this->id);
			//set user master admin
			$this->isMasterAdmin = $this->isUserMasterAdmin($this->id);
			//get initial access levels
			$this->access = $this->get_access();

			/**
			 * NOTE: add the rest of the user class here so that its fast and we arent calling it all the time
			 */

			$_SESSION['session']->user = $this;
			return true;
		else:
			//clear out the user
			$this->reset();
			$_SESSION['session']->user = $this;
			return false;
		endif;
	}

	/*
	 * check if the user is an admin
	 */
	public function isUserAdmin($user_id){
		$db = new db();
		$sql = 'select access_level from employees where id = ?';
		$adminuser = $db->fetch($sql, $user_id);
		if($adminuser):
			if( $adminuser->access_level == 5 || $adminuser->access_level == 6 ):
				return true;
			endif;
		endif;
		return false;
	}

	/*
	 * check if the user is a master admin
	 */
	public function isUserMasterAdmin($user_id){
		$db = new db();
		$sql = 'select access_level from employees where id = ?';
		$adminuser = $db->fetch($sql, $user_id);
		if($user_id):
			if( $adminuser->access_level == 6 ):
				return true;
			endif;
		endif;
		return false;
	}
	/*
	 * seperating this function so that if there is a change, we can update the class and the session
	 * USAGE: $_SESSION['session']->user->get_access(); // the session is the main instance of the user class
	 */
	public function get_access(){
		$db = new db();

		$access = array();

		if($this->isMasterAdmin):
			/*
			 * the user is a master admin so lets gather all the clients and the credentials
			 */
			$access = array();

			$sql1 = 'SELECT * FROM clients';
			$clients = $db->query($sql1);
			$x = 0;
			foreach($clients as $client):
				$access['clients'][$x]['client_id'] = $client->id;
				$sql = 'SELECT * from credential where client_id = ' . $client->id;
				$credentials = $db->query($sql);
				$y = 0; 
				if($credentials):
					foreach($credentials as $credential):
						$access['clients'][$x]['credentials'][$y]['credential'] = array("credential_id"=>$credential->id,"access_level"=>2);
						$y++;
					endforeach;
				endif;
				$x++;
			endforeach;
		else:
			/**
			 * not a master admin lets get the credentials and the clients the user has access to
			 */
			$sql1 = 'SELECT data from employee_access ea WHERE employee_id = ?';
			$credentials = $db->fetch($sql1, $this->id);

			if($credentials):
				$access = unserialize($credentials->data); 
			endif;
		endif;
		
		return $access;

	}

	/*
	 * any time that we change access or add anything, we need to refresh the access levels
	 * *USAGE: $_SESSION['session']->user->update_access();
	 */
	public function update_access(){
		$this->access = $this->get_access();
	}

}


?>