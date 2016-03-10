<?php 
	//generate a random password
function gen_random_password($length) {
   	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%=?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}


// if there is an ajax request, this will handle it
if(isset($_POST['action']) && $_POST['action']=='generatePassword'):
	//see if we are generating one from the input box
	$passwords = [];
	if(isset($_POST['password']) && $_POST['password'] != ''):
		$passwords[0]['password'] 	= str_replace(" ", "_", $_POST['password']);
		$passwords[0]['hash']		= password_hash($passwords[0]['password'], PASSWORD_BCRYPT );
	else:
		//no password field , just return 15
		for($i = 0; $i <= 15; $i++):
			$passwords[$i]['password'] 	= gen_random_password(12);
			$passwords[$i]['hash']		= password_hash($passwords[$i]['password'], PASSWORD_BCRYPT );
		endfor;
	endif;

	echo json_encode($passwords);
	die();
	exit();

endif;
//post to self url
$url = "tool_passgen.php";

 ?>
<?php include_once("includes/header.php"); ?>
  	<div class="panel panel-default" style="">
  	<div class="panel-heading">Strong/Wordpress Password Generator</div>
   		<div class="panel-body">
	  		<div class="ajaxloading" style="position:absolute;left:0px;top:0px;width:100%;height:100%;background-color: rgba(255,255,255,0.7);z-index:9999;border-radius:7px;display:none;">
	  		    <div style="width:200px;height:200px;background-color:#000;border-radius:100%;text-align:center;line-height:200px;margin:0 auto;position:absolute;top:50%;margin-top:-100px;left:50%;margin-left:-100px">
	  		        <img src="images/LOADING.gif" />
	  		    </div>
	  		</div>
			<input class="form-control" placeholder="Input a Password" id="passwordinput" type="text" maxlength="20" />

  		</div>
		<table id="passwords" class="table table-striped" style="">
			<thead>
				<tr>
					<th>Password</th>
					<th>Encrypted Password</th>
				</tr>
			</thead>
			<tbody class="list">

			</tbody>
		</table>
	</div>
    <script>
		$(document).ready(function(){
	      	$('.ajaxloading').fadeIn();

	        $.ajax({
	          type: "POST",
	          url: '<?php echo $url ?>',
	          dataType : 'json',
	          cache: false,
	          data: {action: 'generatePassword'},
	          success: function(records){
	          	$('#passwords tbody').html(makeTable(records));
		      	$('.ajaxloading').fadeOut();
		      	getCopyButtons();
	          }
	        }).fail(function(error) {
			   console.log(error);
		      	$('.ajaxloading').fadeOut();
			});
			$('#passwordinput').keyup(function(){
				var mypassword = $("#passwordinput").val();
		    	// alert(mypassword);
		        $.ajax({
		          type: "POST",
		          url: '<?php echo $url ?>',
		          dataType : 'json',
		          cache: false,
		          data: {action: 'generatePassword', password: mypassword },
		          success: function(records){
		          	$('#passwords tbody').html(makeTable(records));
			      	$('.ajaxloading').fadeOut();
      		      	getCopyButtons();

		          }
		      }).fail(function(error){console.log(error);});
			});
		});
		function getCopyButtons(){
			var i = 0;
			var e = 0;
			$(".copy_passwd").each(function(){
				var copytxt = "#" + $(this).parent('td').find('.copytxt').attr("id", "copyPassword"+i).attr("id");
				$(this).attr("target-element", copytxt);
				i++;
			});
			$(".copy_hash").each(function(){
				var copytxt = "#" + $(this).parent('td').find('.copytxt').attr("id", "copyHash"+e).attr("id");
				$(this).attr("target-element", copytxt);
				e++;
			});
		}
		function makeTable(data){
			var tbl_body = "";
			$.each(data, function(id, object) {
				var tbl_row = "";
				tbl_row += "<td><copy-button class='copy_passwd'><i class='fa fa-copy'></i></copy-button> <span class='copytxt'>"+object.password+"</span></td>";
				tbl_row += "<td><copy-button class='copy_hash'><i class='fa fa-copy'></i></copy-button> <span class='copytxt'>"+object.hash+"</span></td>";
				tbl_body += "<tr>"+tbl_row+"</tr>";                 
			});
			return tbl_body;
		}

    </script> 
<?php include_once("includes/footer.php"); ?>


