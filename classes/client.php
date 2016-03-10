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
Client Class
+----------------------------------+
+Version: 			0.0.1
+Author: 			Shalako Lee
+Latest Revision: 	02/24/2016
+Started: 			02/24/2016
+----------------------------------+

-
*/
class Client{

	private $db = new db;
	
	public $client_id;
	



	function __construct() {
		$this->db = new db;
	}	
	public function create(){

	}
	public function read(){

	}
	public function update(){

	}
	public function delete(){

	}
	public function getClientById($client_id){
		$sql = 'SELECT * from client where id = ?';
		$result = $this->db->fetch($sql, $client_id);
		return $result;
	}


}