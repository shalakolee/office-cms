<?php 

class session{
	
	public $user = null;

	public function __construct(){
		$this->user = new user;
		$this->message = new messageReport;
		$this->logger = new Logger;
	}

}

 ?>