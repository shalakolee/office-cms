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
Class Loader
Used to init all the system classes for the front end
+----------------------------------+
+Version: 			0.0.1
+Author: 			Shalako Lee & Trevor Nolan
+Latest Revision: 	01/29/2016
+Started: 			01/29/2016
+----------------------------------+
-*/


/**
 * put the classes and functions that you want to load here:
 */


require_once("settings.php");		// load the settings file
require_once("/classes/db.php");	// load the db class
$db = new db();
require_once("/classes/messages.php");	// load the user class
require_once("/classes/logging.php");	// load the user class
require_once("/classes/session.php");	// load the session class
require_once("site_functions.php");	// load up the site functions
require_once("/classes/user_class.php");	// load the user class
require_once("/classes/credential.php");	// load the user class

/**
 * Loading the classes before starting the session
 */
session_start();


/**
 * get the current page value
 */
$script_name = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1 );
//echo $script_name;
/**
 * Check if there is a front end session, if there is not, lets create one
 */
if(!$_SESSION || !$_SESSION['session']){
	$_SESSION['session'] = new session;
}

/**
 * check if the user is logged in or not, if they are not then lets point them to the login screen
 */
if($script_name != "login.php" && $_SESSION['session']->user->loggedin != 1){
	header('Location: login.php');exit;
}
/**
 * logout function
 * usage: 
 */
if(isset($_POST["logout"]) ){
	$_SESSION['session']->user->reset();
	header('Location: login.php');exit;
}

// echo "<pre>";
// var_dump($_SESSION['session']);
// echo "</pre>";


?>