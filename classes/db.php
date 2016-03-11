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
DB Class 
+----------------------------------+
+Version: 			0.0.1
+Author: 			Shalako Lee & Trevor Nolan
+Latest Revision: 	01/29/2016
+Started: 			01/29/2016
+----------------------------------+
-*/


/**
 * This class is used to create a database object that can be used at will
 * usage:
 * $rows = $db->query('SELECT * FROM table1 WHERE a=? AND b=?', 16, 22);
 */

class db{

	public $db = null;

	public function __construct(){
		/**
		 * Pull the database connection from the settings file
		 */
		global $_SETTINGS;
		
		$this->db = new PDO('mysql:host='.$_SETTINGS['dbHost'].';dbname='.$_SETTINGS['dbName'].';charset=utf8', $_SETTINGS['dbUser'], $_SETTINGS['dbPass']);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}

	public static function Instance(){
		static $instance = null;
		if ($instance === null)
		{
			$instance = new db();
		}
		return $instance;
	}
	/**
	 * Perform the Query and return an object
	 * NOTE: could modify this function to return an array if wanted
	 */
	public function query($query){
		$args = func_get_args();
	  	array_shift($args);
	  	
	  	$statement = $this->db->prepare($query);        
	  	$statement->execute($args);
	    
	    return $statement->fetchAll(PDO::FETCH_OBJ);
	}
	public function fetch($query){
		$args = func_get_args();
	  	array_shift($args);
	  	
	  	$statement = $this->db->prepare($query);        
	  	$statement->execute($args);
	    
	    return $statement->fetch(PDO::FETCH_OBJ);

	}
	//return last insert id on successful insert
	public function insert($query){
		$args = func_get_args();
	  	array_shift($args);
	  	
	  	$statement = $this->db->prepare($query);        
	  	if( $statement->execute($args) ):
	  		return $this->db->lastInsertId();
	  	else:
	  		return false;
	  	endif;
  	}
  	// return a row-count on update
  	public function update($query){
		$args = func_get_args();
	  	array_shift($args);
	  	
	  	$statement = $this->db->prepare($query);        
	  	if( $statement->execute($args) ):
	  		return $statement->rowCount();
	  	else:
	  		return false;
	  	endif;


	}

}//end db class


?>