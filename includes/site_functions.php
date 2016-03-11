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
Site Functions
This is used to handle all the front end global functions
+----------------------------------+
+Version: 			0.0.1
+Author: 			Shalako Lee & Trevor Nolan
+Latest Revision: 	01/29/2016
+Started: 			01/29/2016
+----------------------------------+
-*/

/**
 * Generate a random password (optional $length)
 */
 function generateRandomPassword($length = 12){
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%=?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

/**
 * Return an encrypted password hash we can use in the db rather than plaintext password
 */
 function getEncryptedPassword($password){
	return password_hash($password, PASSWORD_DEFAULT );
}

/**
 * get a state dropdown 
 * ex: echo get_state_dropdown($_REQUEST['state'], "name", "id", "class";)
 */

function get_state_dropdown($selected, $name='state', $id='state', $class='' ){
	$stateselect = "<select name='{$name}' id='{$id}' class='{$class}'>";
	$stateselect .= "<option value=''>Select a State</option>"
	. "<option value='AL' state='AL' ". ($selected == 'AL' ? 'selected' : '') .">Alabama</option>"
	. "<option value='AK' state='AK' ". ($selected == 'AK' ? 'selected' : '') .">Alaska</option>"
	. "<option value='AZ' state='AZ' ". ($selected == 'AZ' ? 'selected' : '') .">Arizona</option>"
	. "<option value='AR' state='AR' ". ($selected == 'AR' ? 'selected' : '') .">Arkansas</option>"
	. "<option value='CA' state='CA' ". ($selected == 'CA' ? 'selected' : '') .">California</option>"
	. "<option value='CO' state='CO' ". ($selected == 'CO' ? 'selected' : '') .">Colorado</option>"
	. "<option value='CT' state='CT' ". ($selected == 'CT' ? 'selected' : '') .">Connecticut</option>"
	. "<option value='DE' state='DE' ". ($selected == 'DE' ? 'selected' : '') .">Delaware</option>"
	. "<option value='DC' state='DC' ". ($selected == 'DC' ? 'selected' : '') .">District Of Columbia</option>"
	. "<option value='FL' state='FL' ". ($selected == 'FL' ? 'selected' : '') .">Florida</option>"
	. "<option value='GA' state='GA' ". ($selected == 'GA' ? 'selected' : '') .">Georgia</option>"
	. "<option value='HI' state='HI' ". ($selected == 'HI' ? 'selected' : '') .">Hawaii</option>"
	. "<option value='ID' state='ID' ". ($selected == 'ID' ? 'selected' : '') .">Idaho</option>"
	. "<option value='IL' state='IL' ". ($selected == 'IL' ? 'selected' : '') .">Illinois</option>"
	. "<option value='IN' state='IN' ". ($selected == 'IN' ? 'selected' : '') .">Indiana</option>"
	. "<option value='IA' state='IA' ". ($selected == 'IA' ? 'selected' : '') .">Iowa</option>"
	. "<option value='KS' state='KS' ". ($selected == 'KS' ? 'selected' : '') .">Kansas</option>"
	. "<option value='KY' state='KY' ". ($selected == 'KY' ? 'selected' : '') .">Kentucky</option>"
	. "<option value='LA' state='LA' ". ($selected == 'LA' ? 'selected' : '') .">Louisiana</option>"
	. "<option value='ME' state='ME' ". ($selected == 'ME' ? 'selected' : '') .">Maine</option>"
	. "<option value='MD' state='MD' ". ($selected == 'MD' ? 'selected' : '') .">Maryland</option>"
	. "<option value='MA' state='MA' ". ($selected == 'MA' ? 'selected' : '') .">Massachusetts</option>"
	. "<option value='MI' state='MI' ". ($selected == 'MI' ? 'selected' : '') .">Michigan</option>"
	. "<option value='MN' state='MN' ". ($selected == 'MN' ? 'selected' : '') .">Minnesota</option>"
	. "<option value='MS' state='MS' ". ($selected == 'MS' ? 'selected' : '') .">Mississippi</option>"
	. "<option value='MO' state='MO' ". ($selected == 'MO' ? 'selected' : '') .">Missouri</option>"
	. "<option value='MT' state='MT' ". ($selected == 'MT' ? 'selected' : '') .">Montana</option>"
	. "<option value='NE' state='NE' ". ($selected == 'NE' ? 'selected' : '') .">Nebraska</option>"
	. "<option value='NV' state='NV' ". ($selected == 'NV' ? 'selected' : '') .">Nevada</option>"
	. "<option value='NH' state='NH' ". ($selected == 'NH' ? 'selected' : '') .">New Hampshire</option>"
	. "<option value='NJ' state='NJ' ". ($selected == 'NJ' ? 'selected' : '') .">New Jersey</option>"
	. "<option value='NM' state='NM' ". ($selected == 'NM' ? 'selected' : '') .">New Mexico</option>"
	. "<option value='NY' state='NY' ". ($selected == 'NY' ? 'selected' : '') .">New York</option>"
	. "<option value='NC' state='NC' ". ($selected == 'NC' ? 'selected' : '') .">North Carolina</option>"
	. "<option value='ND' state='ND' ". ($selected == 'ND' ? 'selected' : '') .">North Dakota</option>"
	. "<option value='OH' state='OH' ". ($selected == 'OH' ? 'selected' : '') .">Ohio</option>"
	. "<option value='OK' state='OK' ". ($selected == 'OK' ? 'selected' : '') .">Oklahoma</option>"
	. "<option value='OR' state='OR' ". ($selected == 'OR' ? 'selected' : '') .">Oregon</option>"
	. "<option value='PA' state='PA' ". ($selected == 'PA' ? 'selected' : '') .">Pennsylvania</option>"
	. "<option value='RI' state='RI' ". ($selected == 'RI' ? 'selected' : '') .">Rhode Island</option>"
	. "<option value='SC' state='SC' ". ($selected == 'SC' ? 'selected' : '') .">South Carolina</option>"
	. "<option value='SD' state='SD' ". ($selected == 'SD' ? 'selected' : '') .">South Dakota</option>"
	. "<option value='TN' state='TN' ". ($selected == 'TN' ? 'selected' : '') .">Tennessee</option>"
	. "<option value='TX' state='TX' ". ($selected == 'TX' ? 'selected' : '') .">Texas</option>"
	. "<option value='UT' state='UT' ". ($selected == 'UT' ? 'selected' : '') .">Utah</option>"
	. "<option value='VT' state='VT' ". ($selected == 'VT' ? 'selected' : '') .">Vermont</option>"
	. "<option value='VA' state='VA' ". ($selected == 'VA' ? 'selected' : '') .">Virginia</option>"
	. "<option value='WA' state='WA' ". ($selected == 'WA' ? 'selected' : '') .">Washington</option>"
	. "<option value='WV' state='WV' ". ($selected == 'WV' ? 'selected' : '') .">West Virginia</option>"
	. "<option value='WI' state='WI' ". ($selected == 'WI' ? 'selected' : '') .">Wisconsin</option>"
	. "<option value='WY' state='WY' ". ($selected == 'WY' ? 'selected' : '') .">Wyoming</option>"
	. "</select>";
	return $stateselect;
}
function getRowCount($table){
	$db = new db();
	$sql = 'select count(*) as count from '. $table;
	$count = $db->fetch($sql);
	return $count->count;
}
function restrictedPage(){
	if(!$_SESSION['session']->user->isAdmin):
		$_SESSION['session']->message->addMessage('You Currently Don\'t have access to that page.', 'danger');
		header('Location: ./');exit;
	endif;
}
function generateGuid(){
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


?>