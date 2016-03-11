<?php include_once("includes/header.php"); ?>
<?php 
	

 ?>
  	<div class="panel panel-default" style="">
  	<div class="panel-heading">Wordpress Password Control Plugin</div>
   		<div class="panel-body">
	  		<div class="ajaxloading" style="position:absolute;left:0px;top:0px;width:100%;height:100%;background-color: rgba(255,255,255,0.7);z-index:9999;border-radius:7px;display:none;">
	  		    <div style="width:200px;height:200px;background-color:#000;border-radius:100%;text-align:center;line-height:200px;margin:0 auto;position:absolute;top:50%;margin-top:-100px;left:50%;margin-left:-100px">
	  		        <img src="images/LOADING.gif" />
	  		    </div>
	  		</div>
	  		<h4>Info:</h4>
	  		<p>This plugin should be installed on all client wordpress sites, it will keep their site secure while keeping this cms updated with the latest password.</p>
	  		<br />
	  		<h4>Installation:</h4>
	  		<p>
	  			<ol type="1">
	  				<li>Download the latest version of the plugin <a href="plugin/client/561-password-control.zip">here</a>.</li>
	  				<li>Install and Activate the Plugin.</li>
	  				<li>Log out of WordPress</li>
	  				<li>Log in with the following credentials
	  					<ul>
	  						<li>Username: 561Media</li>
	  						<li>Password: dev2016##</li>
	  					</ul>
	  				</li>
	  				<li>Add and save the credential to the correct client (make sure you generate a GUID, you will need it for the next step), please use the "plugin generated" credential type. <em><strong>*NOTE:it is very important that the guid is in the CMS before it's in the plugin!</strong></em></li>
	  				<li>Go to the plugin settings for 561 Wordpress Password Control from the plugins page.</li>
	  				<li>Paste the GUID that you generated into the GUID field, and save the settings (the next time you log in it will start the password process).</li>
	  			</ol>
	  		</p>
	  		<br />
	  		<h4>Moving from a development environment to a live environment:</h4>
	  		<p>
	  			When you are migrating a site from development to production, this step is very important so that the passwords are not lost in migration.<br />
	  			<ol type="1">
	  				<li><strong><em>BEFORE LOGGING IN TO THE LIVE SITE</em></strong>, you will need to follow steps 5-7 from above to get a new GUID</li>
	  				<li><strong><em>BEFORE LOGGING IN TO THE LIVE SITE</em></strong>, run the search and replace script, and replace the development GUID with the production GUID</li>
	  			</ol>
	  			<span style="color: #c7254e;background-color:#f9f2f4"><strong><em>*NOTE:</em></strong> failure to do this will make the live site overwrite the dev credential and you will be locked out of both development and production, and will piss off the other developers that have to fix your fuckup.</span>
	  		</p>
  		</div>
	</div>

<?php include_once("includes/footer.php"); ?>


