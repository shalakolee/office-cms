<?php
/*
coded by:

 ▄▄▄       █     █░▓█████   ██████  ▒█████   ███▄ ▄███▓▓█████  ██ ▄█▀ ▒█████  
▒████▄    ▓█░ █ ░█░▓█   ▀ ▒██    ▒ ▒██▒  ██▒▓██▒▀█▀ ██▒▓█   ▀  ██▄█▒ ▒██▒  ██▒
▒██  ▀█▄  ▒█░ █ ░█ ▒███   ░ ▓██▄   ▒██░  ██▒▓██    ▓██░▒███   ▓███▄░ ▒██░  ██▒
░██▄▄▄▄██ ░█░ █ ░█ ▒▓█  ▄   ▒   ██▒▒██   ██░▒██    ▒██ ▒▓█  ▄ ▓██ █▄ ▒██   ██░
 ▓█   ▓██▒░░██▒██▓ ░▒████▒▒██████▒▒░ ████▓▒░▒██▒   ░██▒░▒████▒▒██▒ █▄░ ████▓▒░
 ▒▒   ▓▒█░░ ▓░▒ ▒  ░░ ▒░ ░▒ ▒▓▒ ▒ ░░ ▒░▒░▒░ ░ ▒░   ░  ░░░ ▒░ ░▒ ▒▒ ▓▒░ ▒░▒░▒░ 
  ▒   ▒▒ ░  ▒ ░ ░   ░ ░  ░░ ░▒  ░ ░  ░ ▒ ▒░ ░  ░      ░ ░ ░  ░░ ░▒ ▒░  ░ ▒ ▒░ 
  ░   ▒     ░   ░     ░   ░  ░  ░  ░ ░ ░ ▒  ░      ░      ░   ░ ░░ ░ ░ ░ ░ ▒  
      ░  ░    ░       ░  ░      ░      ░ ░         ░      ░  ░░  ░       ░ ░ 
Logging Class
+----------------------------------+
+Version: 			0.0.1
+Author: 			Shalako Lee
+Latest Revision: 	02/24/2016
+Started: 			02/24/2016
+----------------------------------+
- this class is used to log any access to credentials and any system changes
-
*/
class Logger{

	//private $db;

	function __construct() {
		//$this->db = new db;
	}	
	public function create(){

	}
	public function read(){

	}
	public function logCredentialEdit($credential_id, $new_values){
		$db = new db();

		if($credential_id):

			$sql = 'SELECT * from credential WHERE id = ?';
			$original_values = $db->fetch($sql, $credential_id);

			$original_values 	= serialize($original_values);
			$new_values 		= serialize($new_values);

			//now lets log this to the database
			$sql = "INSERT INTO access_log (user_id, timestamp, credential_id, old_value, new_value, type) values (?,?,?,?,?,?)";
			$insert_id = $db->insert($sql, $_SESSION['session']->user->id, date('Y-m-d H:i:s'), $credential_id, $original_values, $new_values, "credential_edit");




			if($insert_id):
				return $insert_id;
			else:
				return false;
			endif;
		else:
			return false;
		endif;

	}
	public function logCredentialRequest($credential_id){
		$db = new db();
		if($credential_id):
			$sql = "INSERT INTO access_log (user_id,timestamp,credential_id,  type) values (?,?,?,?)";
			$insert_id = $db->insert($sql, $_SESSION['session']->user->id, date('Y-m-d H:i:s'), $credential_id, 'credential_view_request');
			if($insert_id):
				return $insert_id;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	}
	public function delete(){

	}


}