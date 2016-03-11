<ul class="nav nav-pills nav-stacked">
	<li role="presentation" <?php echo $script_name == "index.php" ? 'class="active"' : ""  ?>>
		<a href="./">Home</a>
	</li>
	
	<?php if($_SESSION['session']->user->isAdmin): ?>
	<li role="presentation" <?php echo $script_name == "employees.php" ? 'class="active"' : ""  ?>>
		<a href="employees.php">Employees  <span class="badge"><?php echo getRowCount("employees"); ?> </span></a>
	</li>
	<?php endif; ?>
	
	<li role="presentation" <?php echo $script_name == "clients.php" ? 'class="active"' : ""  ?>>
		<?php
			/* check if master admin */
			if($_SESSION['session']->user->isMasterAdmin):
				$sql = 'SELECT count(*) as count FROM clients';
			else:
				$sql = 'SELECT count(*) as count FROM employee_access ea LEFT JOIN clients cl ON cl.id = ea.client_id WHERE ea.employee_id = "' . $_SESSION['session']->user->id . '" AND ea.client_id != "";';
			endif;
			$count = $db->fetch($sql);
		?>
		<a href="clients.php">Clients  <span class="badge"><?php echo $count->count; ?> </span></a> 
	</li>
	<li role="presentation">
		<a class="nav-container" data-toggle="collapse" href="#toolsNav">Tools<span class="caret-container"><span class="caret arrow"></span></span></a>

		<?php 

			$navactive = false;
			$toolarray = array(
				'tool_passgen.php',
				'tool_plugin.php',
				);
			if(in_array($script_name, $toolarray)){
				$navactive = true;
			}
		 ?>		


          <ul class="nav nav-pills nav-stacked collapse <?php echo $navactive ? 'in' : ""  ?>" id="toolsNav">
            <li <?php echo $script_name == "tool_plugin.php" ? 'class="active"' : ""  ?>><a href="tool_plugin.php">Wordpress Password Control Plugin</a></li>
            <li <?php echo $script_name == "tool_passgen.php" ? 'class="active"' : ""  ?>><a href="tool_passgen.php">Strong Password Generator</a></li>
            <li><a href="#">Serialize/Unserialize</a></li>
            <li class="nav-divider"></li>
          </ul>
	</li>
	<li role="presentation">
		<a href="#">Help Desk</a>
	</li>
	<li role="presentation">
		<a href="http://gitlab.office.561media.com" target="_blank">Gitlab</a>
	</li>
	<li role="presentation">
		<a href="http://wiki.561media.com" target="_blank">Wiki</a>
	</li>
</ul>
<style>
ul.nav-stacked ul.nav-stacked > li > a {
  padding-left: 30px;
}</style>
