<?php 
	
if(isset($_POST["input"])){
	require_once("includes/site_functions.php");	// load up the site functions

	$input = $_POST['input'];
	//echo "<pre>";
	if(is_serialized($input)):
		//unserialize the data and return it
		print_r(unserialize($input));
	else:
		//serialize the data and return it
		try{
			//$myArray = substr($input, 1);
			//$myArray = substr($input, );
			//$myArray = $;
			echo(serialize($input));
			echo "serilized";
		}catch(Exception $e){
			print_r($e->getMessage());			
		}

	endif;
	//echo "</pre>";

	die();
	exit();

}


 ?>
<?php include_once("includes/header.php"); ?>



<textarea name="input" id="input"></textarea>
<button class="btn btn-default" id="btnSwitch">Serialize/Unserialize</button>


<div ><pre id="result"style=width:300px;height:300px;></pre></div>


<script>
	$(document).ready(function(){
		$("#btnSwitch").click(function(){
			$.ajax({
				url:'tool_serialize.php',
				type:'POST',
				data: {input:$("#input").val()},
				success: function(response){
					console.log(response)
					$("#result").text(response);
				}
			});		
		});

	});

</script>


<?php include_once("includes/footer.php"); ?>


