<?php 
require_once("includes/loader.php"); // load the required classes and functions

// echo "<pre>";
// var_dump($_SESSION);
// echo "</pre>";



// going to put all the ajax calls here
require_once("includes/ajax_handler.php");




 ?>
<!DOCTYPE HTML>
<script src="js/jquery-1.12.0.min.js"></script>
<script src="js/chosen/chosen.jquery.min.js"></script>
<script src="js/bootstrap/js/bootstrap.min.js"></script>
<script src="js/fancybox/jquery.fancybox.js"></script>
<script src="js/list.js"></script>
<script src="js/copy.js"></script> 

<link rel="stylesheet" type="text/css" href="js/chosen/chosen.min.css">
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/fa/css/font-awesome.min.css">

<!-- include our stylesheet last for overrides -->
<link rel="stylesheet" type="text/css" href="css/front_end.css">

<div class='panel panel-default' style="width: 98%;margin:0 auto;margin-top:20px;margin-bottom:20px;">
	<div class='panel-heading'>
		<div class='heading-title'>
			<span style="float:left;">Welcome <?php echo $_SESSION['session']->user->firstname; ?></span>
			<div style="float:right;" class="dropdown">
				<i class="fa fa-cogs dropdown-toggle" id="settingsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer"><span class="caret"></span></i>
				  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="settingsDropdown">
				    <li><a id="logoutbtn" href="#">Logout</a><form action="" method="post" id="logoutform"><input name="logout" type="hidden" value="Log Out" /></form></li>
				    <!-- <li role="separator" class="divider"></li> -->
				  </ul>
				  <script>
				  	/* handle the logout */
				  	$("#logoutbtn").click(function(){
				  		$("#logoutform").submit();
				  	});
				  </script>
			</div>
			<span style="clear:both">&nbsp;</span>
		</div>	
	</div>
	<div class='panel-body'>

	    	<?php if($_SESSION['session']->message->reports): ?>
              	<?php echo $_SESSION['session']->message->reportMessage(); ?>
			<?php endif; ?>
		<div class="navigation" style="width:20%;float:left;">
		<?php include_once("includes/nav.php"); ?>
		</div>

		<div class="content" style="float:right;width:78%">