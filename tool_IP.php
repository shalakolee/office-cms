<?php include_once("includes/header.php"); ?>
<?php restrictedPage(); ?>


<?php 
/* get the rows from the ip address table */
$sql = "SELECT * FROM ip_address_assignment ORDER BY INET_ATON(ip_address)";
$rows = $db->query($sql);

//var_dump($rows);
 ?>

<style>
em {
	color: #c7254e;
    background-color: #f9f2f4;
}
</style>

<div class="panel panel-default" style="" id="ipaddresseslist">
<div class="panel-heading">IP Address Assignment</div>
	<div class="panel-body">
		This is a list of all the external IP Addresses assigned to this physical location, we are routing specific IP addresses to specific computers in the network and have a /23 block.  <em>50.235.54.0/23</em><br />
		This list allows you to see what is assigned.
		<br />
		<br />
 		<input class="search form-control" placeholder="Filter..." />


	</div>
	<table id="ipaddresses" class="table table-striped" style="">
	<thead>
		<tr>
			<th>External IP Address</th>
			<th>Assigned Computer</th>
			<th>External URL</th>
			<th>Internal URL</th>
		</tr>
	</thead>
	<tbody class="list">
		<?php foreach($rows as $row): ?>
			<tr>
				<td class="ip"><?php echo $row->ip_address ?></td>
				<td class="computer"><?php echo $row->assigned_to ?></td>
				<td class="exurl"><?php echo $row->external_url ?></td>
				<td class="inurl"><?php echo $row->internal_url ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>


<script>
$(document).ready(function(){
	var myList = new List('ipaddresseslist', {valueNames: [ 'ip', 'computer', 'exurl', 'inurl'], page:5000});

});
</script>


<?php include_once("includes/footer.php"); ?>


