<?php include_once("includes/header.php"); ?>
<?php restrictedPage(); ?>


<?php 
/* get the rows from the ip address table */
//$sql = "SELECT * FROM ip_address_assignment ORDER BY INET_ATON(ip_address)";
//$rows = $db->query($sql);

//var_dump($rows);
 ?>

<style>
.tableblock{
	width:4%;
	margin:0px;
	padding:0px;
	border:1px solid black;
	display:inline-block;
	letter-spacing:0px;
	height:20px;

}
</style>

<div class="panel panel-default" style="" id="ipaddresseslist">
	<div class="panel-heading">Server Rack</div>
		<div class="panel-body">

			<div style="width:100%;letter-spacing:-6px;">
			<?php for($i=0;$i <= 24; $i++){ ?>
				<div class="tableblock"><?php echo $i ?></div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>


<script>
$(document).ready(function(){

});
</script>


<?php include_once("includes/footer.php"); ?>


