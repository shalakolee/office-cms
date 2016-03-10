<?php 
require_once("includes/loader.php"); // load the required classes and functions

global $_SETTINGS;
//$db = new db();

/**
 * check if the user is logged in, if they are lets redirect them to the home page
 */


/**
 * NOTE: have to check this first for any redirect to work
 */
if(isset($_POST['login'])){
	$thisuser = new user;
	if(!$thisuser->login($_REQUEST['login_username'], $_REQUEST['login_password'])):
		$_SESSION['session']->message->addMessage('Login failed. Please try again', 'danger');
	endif;
}


if ( $_SESSION['session']->user->loggedin == 1 ){
	header( 'Location: /office-cms' );exit;
}


//var_dump($_SESSION);
?>



<script src="js/jquery-1.12.0.min.js"></script>
<script src="js/chosen/chosen.jquery.min.js"></script>
<script src="js/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="js/chosen/chosen.min.css">
<link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/fa/css/font-awesome.min.css">

<!-- include our stylesheet last for overrides -->
<link rel="stylesheet" type="text/css" href="css/front_end.css">




<script type="text/javascript">
	$(document).ready(function(){
		$('#username').focus();
	});
</script>
<?php //var_dump($_SESSION['session']->message); ?>
   
<!--  LOGIN FORM -->
<form action="" class="login-form" method="post">

	<div style="width:300px;box-shadow:0 0 30px black;margin:auto;margin-top:75px;padding:5px;text-align:center;background-color:#000;padding-top:20px;padding-bottom:20px;" class="rounded10"  >
	    <img src="images/logo.png" style="max-width:300px;"  />
	</div>

	<div style="width: 300px; margin: auto;box-shadow: 0 0 30px black;margin-top:60px;" class="panel panel-default">
	    <div class="panel-heading">
	        Login
	    </div>
	    <div class="panel-body" style="text-align:left;" >
	    	<?php if($_SESSION['session']->message->reports): ?>
	              	<?php echo $_SESSION['session']->message->reportMessage(); ?>
			<?php endif; ?>

	        <div class="formRow">
	            <label>Username</label>
	            <input type="text" name="login_username" title="Username" id="username" value="<?php echo $_POST['login_username'] ?>" class="form-control"  />
	        </div>
	        <div class="formRow">
	            <label>Password</label>
				<input name="login_password" title="Password" id="password" type="password" value="" class="form-control" />
	        </div>
	        <div style="margin-top:20px;">
				<input name="login" type="submit" value="LOGIN" class="btn btn-default submit_form " />
	        </div>
	        <div style="clear:both;height:20px;"></div>
	        <div class="formRow" style="text-align:center;"  >
				<!-- <a class="forgotpass" href="admin" style="color:#808080">Admin Login</a> -->

	        </div>
	    </div>
	</div>
</form>
<!-- /LOGIN FORM -->
