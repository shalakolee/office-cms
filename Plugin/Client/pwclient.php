<?php
$encryptionKey	= "Werr3Nf241qg40sQT9YEy4lV300wc65k"; //must match the plugin encryption key

if(isset($_POST['GUID'])):


	//var_dump(decryptData($_POST['password']));


	//set up database
	$_SETTINGS['dbHost']	= 'localhost';
	$_SETTINGS['dbName']	= 'z15_application';
	$_SETTINGS['dbUser']	= 'root';
	$_SETTINGS['dbPass']	= 'Dev2013guy##';
	$db = new PDO('mysql:host='.$_SETTINGS['dbHost'].';dbname='.$_SETTINGS['dbName'].';charset=utf8', $_SETTINGS['dbUser'], $_SETTINGS['dbPass']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	$sql = "SELECT id from credential where guid = ?";
	
	$record_id = fetch($sql, decryptData($_POST['GUID']));

	if($record_id):
		//we have a valid credential, lets update it now
		$sql = "UPDATE credential SET password = ?, date_modified = ?, modified_by = ? where id={$record_id->id}";
		$updated = update($sql, decryptData($_POST['password']),date('Y-m-d H:i:s') , 999);
	endif;

	//now we need to write this to the access log
	//TODO: write this to the access log
	exit();
endif;

function fetch($query){
	global $db;
	$args = func_get_args();
  	array_shift($args);
  	
  	$statement = $db->prepare($query);        
  	$statement->execute($args);
    
    return $statement->fetch(PDO::FETCH_OBJ);

}
function update($query){
	global $db;
	$args = func_get_args();
  	array_shift($args);
  	
  	$statement = $db->prepare($query);        
  	if( $statement->execute($args) ):
  		return $statement->rowCount();
  	else:
  		return false;
  	endif;
}
function decryptData($data){
	global $encryptionKey;
	$data = urldecode($data);	
    //$data = rtrim(mcrypt_decrypt(MCRYPT_BLOWFISH, $encryptionKey, $data, 'ecb'), "\0\4");
    return $data;
}

?>
OK