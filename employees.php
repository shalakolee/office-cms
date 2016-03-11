<?php include_once("includes/header.php"); ?>
<?php restrictedPage(); ?>


<?php 
//get a list of all the users 
$sql = "SELECT * FROM employees WHERE id != '".$_SESSION['session']->user->id."'";
$users = $db->query($sql);

$sql = "SELECT id, name FROM clients" ;
$clients = $db->query($sql);

//using this number to give unique id to all the different shit on the page
$v = 99999; //high number here so we never actually hit it


?>


<div class="panel panel-default">
	<div class='panel-heading'>
		<div class='panel-title'>Employee's</div>
	</div>
	<div class="panel-body">
		You currently have admin access<br /><br />
		<div class="msgholder">
		</div>
		<button class="btn btn-primary" id="btnAddNew" href="#addnew">Add New Employee</button>
		<?php 
		//putting the add new employee shit into a new fancybox
		 ?>
		 <form id="frmAddNew" class="form">
		 <div id="addnew" style="display:none;margin-top:20px;">
		 	<div class="panel panel-default">
		 		<div class="panel-heading"><div class="panel-title">Add New Employee</div></div>
		 		<div class="panel-body">
		 			<div style="width:48%;float:left;">
		 				<div class="input-group">
			 				<span class="input-group-addon" id="first-name" >First Name</span>
			 				 <input type="text" class="form-control" placeholder="First Name" aria-describedby="first-name" name="fname" id="fname" required>
		 				</div>
		 				<div style="height:20px;"></div>
		 				<div class="input-group">
			 				<span class="input-group-addon" id="_phone"><i class="fa fa-phone"></i></span>
			 				 <input type="text" class="form-control" placeholder="000.000.000" aria-describedby="_phone" name="phone" id="phone">
		 				</div>
		 				<div style="height:20px;"></div>
		 				<div class="input-group">
			 				<span class="input-group-addon" id="_username">Username</span>
			 				 <input type="text" class="form-control" placeholder="" aria-describedby="_username" name="username" id="username" required>
		 					<span class="input-group-addon" ><i class='fa' id='fa_username'></i></span>

		 				</div>
		 				<div style="height:20px;"></div>
		 				<div class="input-group">
		 					<span class="input-group-addon">Access Level</span>
		 					<select class="form-control" name='user_access_level' placeholder="Please Select">
		 						<option value='0'> -- Select --</option>
		 						<option value="4">Employee</option>
		 						<?php if( $_SESSION['session']->user->isMasterAdmin): ?>
		 						<option value="5">Admin</option>
		 						<option value="6">Super Admin</option>
		 						<?php endif; ?>
		 					</select>
		 				</div>
		 			</div>
		 			<div style="width:48%;float:right;">
		 				<div class="input-group">
			 				<span class="input-group-addon" id="last-name">Last Name</span>
			 				 <input type="text" class="form-control" placeholder="Last Name" aria-describedby="last-name" name="lname" id="lname" required>
		 				</div>
		 				<div style="height:20px;"></div>
		 				<div class="input-group">
			 				<span class="input-group-addon" id="_email"><i class="fa fa-envelope"></i></span>
			 				 <input type="text" class="form-control" placeholder="@561media.com" aria-describedby="_email" name="email" id="email" required>
		 				</div>
		 				<div style="height:20px;"></div>
		 				<div class="input-group">
			 					<span class="input-group-addon" id="_password">Password</span>
			 				 <input type="text" class="form-control" placeholder="" aria-describedby="_password" name="password" id="password" required>
		 				      <span class="input-group-btn">
							      <button class="btn btn-default genpass btnGeneratePassword" type="button"href="#generatePassword" data-targetid="password">Generate</button>
						      </span>
		 				</div>
						<?php //fancybox for the generate password button ?>
						<div id="generatePassword" style="display:none;">
							<div class="panel panel-default">
								<div class="panel-heading">Generate Password</div>
								<div class="panel-body">
									<?php //generate the initial password here then make a new generate button ?>
										<div class="input-group">
						 				<span class="input-group-addon" id="_getpassword">
						 					<copy-button class='copy_password' title="Copy">
						 					<i class='fa fa-copy'></i>
						 					</copy-button> 
						 					<span class='copytxt' style="display:none;" >
						 					<?php //this is here so we have the copy button ?>
						 					<?php $ranPassword = generateRandomPassword(8); ?>
						 					<?php echo $ranPassword; ?>
						 					</span>
					 					</span>
						 				 <input type="text" class="form-control" placeholder="" aria-describedby="_getpassword" name="getpassword" id="getpassword" value="<?php echo $ranPassword; ?>">
									      <span class="input-group-btn">
										      <button class="btn btn-default" type="button" id="btnGenerate">Generate</button>
									      </span>
										</div>
										<div style="height:20px;"></div>
										<div>
											<div >
												<input type="checkbox" id="passwordSave" /><span style="font-size:12px;"> I have saved the password in a safe location.</span>
											</div>
											<div style="margin-top:20px;">
												<button class="btn btn-default" type="button" id="btnUse" style="float:right;" disabled>Use Password</button>
											</div>
											<div style="clear:both;"></div>
										</div>

										<div style="clear:both;"></div>
								</div>
							</div>
						</div>

		 			</div>
		 			<div style="clear:both;height:20px;"></div>
					<div class="panel panel-default">
						<div class="panel-heading">Access</div>
						<div class="panel-body">

						Please select the credentials that you would like this user to have access to.

						</div>
						<table class="table table-striped" style="width: 100%;">
							<thead>
								<tr>
									<td style="width: 28px;"></td>
									<td>Client</td>
									<td>Credential</td>
								</tr>
							</thead>
							<tbody class="credential_list">
								
							</tbody>
						</table>

					</div>
					<div style="width:100%">
						<div style="float:left">
							<div class="btn btn-default btnAddCred" >Add Credential</div>
						</div>
						<div style="float:right">
							<span class="btn btn-default" id="btnCancel">Cancel</span>
							<button class="btn btn-primary" id='btnSave' disabled>Save Employee</button>
						</div>
					</div>
		 			<div style="clear:both;height:20px;"></div>

		 		</div>
		 		</form>
		 	</div>
		 </div>


	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<td>Employee ID</td>
				<td>Name</td>
				<td>Username</td>
				<td>Phone Number</td>
				<td>Status</td>
				<td>Action</td>
		</thead>
		<tbody>
			<?php 
			if($users):
				foreach($users as $user):
				?>
				<tr>
					<td><?php echo $user->id ?></td>
					<td><?php echo ucwords($user->lname . ", " .$user->fname); ?></td>
					<td><?php echo $user->username ?></td>
					<td><?php echo $user->phone; ?></td>
					<td><?php echo ucwords($user->status); ?></td>
					<td><button class="btn btn-sm btn-default edit_button" data-target="tr<?php echo $user->id ?>">Edit</button></td>
				</tr>
				<tr style="display:none"></tr><?php //this is just here to keep table striping in order ?>
				<tr style="display:none" id="tr<?php echo $user->id  ?>" class="usereditrow">
					<td colspan="6">
						<div class="panel panel-default">
							<div class="panel-heading">Editing: <?php echo ucwords($user->lname . ", " .$user->fname); ?> </div>
							<div class="panel-body">
								<div style="clear:both;height:20px;"></div>
								<div style="text-align:right;">
									<form id="frmEditUser<?php echo $user->id ?>" class="form">
									<input type="hidden" value="<?php echo $user->id ?>" name="employee_id" />
						 			<div style="width:48%;float:left;">
							 				<div class="input-group">
								 				<span class="input-group-addon" id="first-name<?php echo $user->id  ?>" >First Name</span>
								 				 <input type="text" value="<?php echo ucwords($user->fname)  ?>" class="form-control" placeholder="First Name" aria-describedby="first-name<?php echo $user->id  ?>" name="fname" id="fname" required>
							 				</div>
							 				<div style="height:20px;"></div>
							 				<div class="input-group">
								 				<span class="input-group-addon" id="_phone"><i class="fa fa-phone"></i></span>
								 				 <input type="text" value="<?php echo $user->phone ?>" class="form-control" placeholder="000.000.000" aria-describedby="_phone" name="phone" id="phone">
							 				</div>
							 				<div style="height:20px;"></div>
							 				<div class="input-group">
								 				<span class="input-group-addon" id="_username">Username</span>
								 				 <input type="text" value="<?php echo $user->username ?>" class="form-control" placeholder="" aria-describedby="_username" name="username" id="username"  disabled>
							 					<span class="input-group-addon" ><i class='fa' id='fa_username'></i></span>

							 				</div>
							 				<div style="height:20px;"></div>
							 				<div class="input-group">
							 					<span class="input-group-addon">Access Level</span>
							 					<select class="form-control" name='user_access_level' placeholder="Please Select" <?php echo $user->access_level == 5 && !$_SESSION['session']->user->isMasterAdmin ? " disabled" : $user->access_level == 6  && !$_SESSION['session']->user->isMasterAdmin  ? " disabled" : "" ?>>
							 						<option value='0'> -- Select --</option>
							 						<option value="4" <?php echo $user->access_level == 4 ? "selected " : "" ?>>Employee</option>
							 						<option value="5" <?php echo $user->access_level == 5 ? "selected " : "" ?><?php echo $_SESSION['session']->user->isMasterAdmin ? "" : " disabled" ?>>Admin</option>
							 						<option value="6" <?php echo $user->access_level == 6 ? "selected " : "" ?><?php echo $_SESSION['session']->user->isMasterAdmin ? "" : " disabled" ?>>Super Admin</option>
							 					</select>
							 				</div>
							 			</div>
							 			<div style="width:48%;float:right;">
							 				<div class="input-group">
								 				<span class="input-group-addon" id="last-name">Last Name</span>
								 				 <input type="text"  value="<?php echo ucwords($user->lname)  ?>" class="form-control" placeholder="First Name" aria-describedby="last-name" name="lname" id="lname" required>
							 				</div>
							 				<div style="height:20px;"></div>
							 				<div class="input-group">
								 				<span class="input-group-addon" id="_email"><i class="fa fa-envelope"></i></span>
								 				 <input type="text" value="<?php echo $user->email ?>" class="form-control" placeholder="@561media.com" aria-describedby="_email" name="email" id="email" required>
							 				</div>
							 				<div style="height:20px;"></div>
							 				<div class="input-group">
								 					<span class="input-group-addon" id="_password">Password</span>
								 				 	<input type="text" class="form-control" placeholder="******" aria-describedby="_password" name="password" id="password<?php echo $user->id ?>" >
							 				      <span class="input-group-btn">
												      <button class="btn btn-default genpass btnGeneratePassword" type="button" href="#generatePassword" data-targetid="password<?php echo $user->id ?>">Generate</button>
											      </span>
							 				</div>
							 				<div style="height:20px;"></div>
							 				<div class="input-group">
							 					<span class="input-group-addon">Status</span>
							 					<select class="form-control" name='user_status' placeholder="Please Select" >
							 						<option value='0'> -- Select --</option>
							 						<option value='active' <?php echo $user->status == 'active' ? " selected" : "" ?>>Active</option>
							 						<option value='inactive' <?php echo $user->status == 'inactive' ? " selected" : "" ?>>Inactive</option>
							 					</select>
							 				</div>


							 			</div>
										<div style="clear:both;height:20px;"></div>	

										<div class="panel panel-default" style="text-align:left;">
											<div class="panel-heading">Access</div>
											<div class="panel-body">

											Please select the credentials that you would like this user to have access to.

											</div>
											<table class="table table-striped" style="width: 100%;">
												<thead>
													<tr>
														<td style="width: 28px;"></td>
														<td>Client</td>
														<td>Credential</td>
													</tr>
												</thead>
												<tbody class="credential_list">
													<?php //select credentials that the user already has access to and write them ?>
													<?php

														$sql = "SELECT ea.client_id, cl.name from employee_access ea LEFT JOIN clients cl on ea.client_id = cl.id where employee_id = ? and client_id > 0";
														$userClients = $db->query($sql, $user->id);

														foreach($userClients as $thisclient):
															?>
															<tr>
																<td>
																	<i class="fa fa-minus removecredential"></i>
																</td>
																<td>
																	<select data-placeholder="Choose a Client..." id="clientSelect_<?php echo $v; ?>" name="clients[<?php echo $v; ?>][client_id]" class="clientSelect" >
																		<option></option>
																		<?php foreach($clients as $client): ?>
																			<option value="<?php echo $client->id; ?>" <?php echo $thisclient->client_id == $client->id ? "selected" : ""; ?> ><?php echo ($client->name); ?></option>
																		<?php endforeach; ?>
																	</select>
																</td>
																<td>
																	<?php 
																	echo "<select data-placeholder='Choose Credentials...' id='credentialSelect_{$v}' name='clients[{$v}][credentials][]' multiple class='credentialsselect' style='display:none;float:right;' >";
																	 ?>
																	 <option></option>

																	 	 <?php 

																	 	 	$sql="select c.id, c.label, ct.type as type from credential c left join credential_types ct on c.type_id = ct.id  where c.client_id = ? "; 
																	 	 	$myCredentials = $db->query($sql, $thisclient->client_id);
																	 	 		
																	 	 	$sql = "SELECT credential_id from employee_access where employee_id = ? AND credential_id is not null";
																	 	 	$selectedCredentials = $db->query($sql, $user->id );
																	 	 	$selected = null; //must reset this or it loads all the credentials from each client
																	 	 	foreach($selectedCredentials as $selectedCredential):
																	 	 		$selected[] = $selectedCredential->credential_id;
																 	 		endforeach;

																			foreach($myCredentials as $credential):
																				echo '<option value="' . $credential->id . '" ' . (in_array($credential->id, $selected) ? "selected" : "" ) . ' >' . $credential->type. ' (' .$credential->label. ')' .  '</option>';
																			endforeach;													 	 		

																	 	 ?>
																																				
																	<?php echo "</select>"; ?>
																 	 		
																</td>
															</tr>
															<?php
															$v++;
														endforeach;
														?>
												</tbody>
											</table>
										</div>

									<div style="width:100%">
										<div style="float:left">
											<div class="btn btn-default btnAddCred" >Add Credential</div>
										</div>
										<div style="float:right">
											<div class="btn btn-default btn-canceledit">Cancel</div>
											<div class="btn btn-primary btn-saveuser">Save User</div>
										</div>
									</div>

									</form>




								</div>

							</div>
						</div>
					</td>
				</tr>
				<?php
				endforeach;
			else:
				echo "<tr><td colspan='5'>No Employees</td></tr>";
			endif;
			?>
		</tbody>
	</table>
</div>
<script>
	$(document).ready(function(){
 		var i = 0;
 		var max_access = <?php echo count($clients) ?>;


		$(".credentialsselect").chosen({width:"300px"});

		$(".genpass").fancybox({maxWidth:350});

//		$(".clientSelect").each(function(){
// 			
// 			//get the target from the name of this select
// 			var target = $(this).attr("id").replace("clientSelect_", "#credentialSelect_");
//	
// 			if ( $(target).size() > 1) {
//	 			$(target).empty();
//			}
//
// 			/* here i have to disable the seletced value in all the other selects */
// 			var myselection = $(this).val();
//			$(".clientSelect").not(this).each(function(){
//				$("option", this).each(function(){
//					if( $(this).val() == myselection){
//						$(this).attr('disabled', 'disabled');
//					}
//				});
//
//			});
//		});


		$(".btnAddCred").each(function(){
			//hide the ones that are maxxed out already
			var rowcount = 0;
			$(this).closest(".form").find(".credential_list tr").each(function(){
				rowcount++;
			});
			if( rowcount == max_access){
				$(this).hide();
			}
		});



		$(".btnAddCred").click(function(){
			i++;

			var myString = ""+
				"<tr>" +
				"	<td>" +
				"		<i class='fa fa-minus removecredential'></i>" +
				"	</td>" +
				"	<td>" +
				"		<select data-placeholder='Choose a Client...'' id='clientSelect_"+i+"' name='clients["+i+"][client_id]' class='clientSelect' >" +
				"			<option></option>" +
				"			<?php foreach($clients as $client): ?>" +
				"				<option value='<?php echo $client->id; ?>'><?php echo $client->name; ?></option>" +
				"			<?php endforeach; ?>" +
				"		</select>" +
				"	</td>" +
				"	<td>" +
				"		<select data-placeholder='Choose Credentials...' id='credentialSelect_"+i+"' name='clients["+i+"][credentials][]' class='chosen' multiple style='display:none;float:right;'>" +
				"			<option></option>" +
				"		</select>" +
				"	</td>" +
				"</tr";

			$(myString).appendTo( $(this).closest('form').find(".credential_list") );

			//need this to not be so universal and be specific to each location this access is handled
			var disableArray = [];
			$(this).closest(".form").find(".clientSelect").each(function(){
				disableArray.push($(this).val());
			});
			$(this).closest(".form").find("#clientSelect_"+i+ " option").each(function(){
				if($.inArray($(this).val(), disableArray) != -1){
					$(this).attr('disabled', 'disabled');
				}else{
					$(this).removeAttr("disabled");
				}
			});

			$(".clientSelect").chosen({width:'250px;', no_results_text: "Oops, nothing found!"});
			
			var rowcount = 0;
			$(this).closest(".form").find(".credential_list tr").each(function(){
				rowcount++;
			});
			if( rowcount == max_access){
				$(this).hide();
			}

		});

		// client select dropdown changed
 		$("body").delegate(".clientSelect", "change",function(){

 			var target = $(this).attr("id").replace("clientSelect_", "#credentialSelect_");
	
 			if ( $(target).size() > 1) {
	 			$(target).empty();
			}

 			$.ajax({
 				url:'employees.php',
 				type: "POST",
 				data:{doingajax:"true", ajax_function: "get_client_credentials", client_id:$(this).val()},
 				success:function(response){
 					//replace the data
 					$(target).empty().append(response);
 					$(target).chosen({width:'300px', no_results_text: "Oops, nothing found!"}).trigger("chosen:updated");
 				}
 			});
 			/* here i have to disable the seletced value in all the other selects */
 			var myselection = $(this).val();
			$(".clientSelect").not(this).each(function(){
				$("option", this).each(function(){
					if( $(this).val() == myselection){
						$(this).attr('disabled', 'disabled');
					}
				});

			});
			$(".clientSelect").chosen("destroy").chosen({width:'250px', no_results_text: "Oops, nothing found!"});
 		});

		//remove the current client access row
		$("body").delegate(".removecredential","click", function(){
			//reenable the option that was selected


			var ClosestForm = $(this).closest(".form");
	
			$(ClosestForm).find(".btnAddCred").fadeIn() ;

			$(this).closest("tr").remove();
			
			var disableArray = [];
			$(ClosestForm).find(".clientSelect").each(function(){
				disableArray.push($(this).val());
			});
			$(ClosestForm).find(".clientSelect option").not(":selected").each(function(){
				if($.inArray($(this).val(), disableArray) != -1){
					$(this).attr('disabled', 'disabled');
				}else{
					$(this).removeAttr("disabled");
				}
			});

			$(ClosestForm).find(".clientSelect").each(function(){
				$(this).trigger("chosen:updated");
			});
			//alert("here");

		});


		$("#btnCancel").click(function(){
			$('#frmAddNew')[0].reset();
			$(this).closest(".panel-body").find(".credential_list").empty();
			$("#addnew").slideToggle();			
			$("#btnAddCred").fadeIn();
			$("#btnAddNew").removeAttr("disabled");

		});



		//save button for adding a new employee
 		$("#btnSave").click(function(e){
 			e.preventDefault();
 			/* lets actually add the employee now with their access*/
 			var validForm = $('#frmAddNew')[0].checkValidity();
 			//alert(validForm);
 			if(validForm){
	 			var formData = $('#frmAddNew').serialize();
	 			$.ajax({
	 				url:'employees.php',
	 				type: "POST",
	 				data:{doingajax:"true", ajax_function: "add_employee", data:formData},
	 				success:function(response){
	 					//replace the data
 						//$(".msgholder").append(response);
	 					//console.log(response);
 					 	if(response == "success"){
	 					 	 location.reload();
	 					}

	 				}
	 			});
 			}else{
 				alert("Data not valid!");
 			}
 		});

 		//update employee function
 		$(".btn-saveuser").click(function(e){
 			e.preventDefault();
 			/* lets actually add the employee now with their access*/

 			//get the current form
 			var myForm = $(this).closest("form");

 			var validForm = $(myForm)[0].checkValidity();
 			//alert(validForm);
 			if(validForm){
	 			var formData = $(myForm).serialize();
	 			$.ajax({
	 				url:'employees.php',
	 				type: "POST",
	 				data:{doingajax:"true", ajax_function: "update_employee", data:formData},
	 				success:function(response){
	 					//replace the data

 						// $(".msgholder").append(response);
	 					//console.log(response);
	 					// if(response.indexOf("alert-success") > -1){
	 					// 	//reload this page
	 					 	 if(response == "success"){
	 					 	 location.reload();
	 					}
	 				}
	 			});
 			}else{
 				alert("Data not valid!");
 			}
 		});

 		$("#btnAddNew").click(function(){
 			$(this).attr("disabled", "disabled");
 			$("#addnew").slideToggle();
 		});


		
 		$("#username").keyup(function(){
 			$.ajax({
 				url:'employees.php',
 				type: "POST",
 				data:{doingajax:"true", ajax_function: "check_username", username:$(this).val()},
 				success:function(response){
 					//replace the data
					console.log(response);
						if(response > 0){
							$("#fa_username").removeClass("fa-check").addClass("fa-close");
							$("#btnSave").attr("disabled","disabled");
						}else{
							$("#fa_username").removeClass("fa-close").addClass("fa-check");
							$("#btnSave").removeAttr("disabled");
						}
	 				}
 			});

 		});

		var passwordTarget = null;
		$(".btnGeneratePassword").click(function(){
			passwordTarget = "#" + $(this).data("targetid");
		});

		$("#btnGenerate").click(function(){
			$.ajax({
				url:'employees.php',
				type: "POST",
				data:{doingajax:"true", ajax_function: "generate_password"},
				success:function(response){
					//replace the data
					$("#getpassword").val(response);
					$('.copytxt').text(response);
					getCopyButtons();
				}
			});
		});
		//use this password
		$("#btnUse").click(function(){
			$(passwordTarget).val($("#getpassword").val());
			$.fancybox.close(true);
		});
		$('#passwordSave').change(function() {
		       if($(this).is(":checked")) {
		       		$("#btnUse").prop('disabled', false)
		       }else{
		       		$("#btnUse").prop('disabled', true)
		       }
		   });

		$(".btn-canceledit").click(function(){

			$(".edit_button").each(function(){
				$(this).removeAttr("disabled");
			});
			$(this).closest("tr").slideToggle();
			//also clear this form
			$(this).parent().find("form")[0].reset();
		});


		$(".edit_button").click(function(){
//			alert("here");
			//$(this).closest("tr").append("</td></tr>");
			var target = "#" + $(this).data("target");
			$(".edit_button").each(function(){
				$(this).attr("disabled", "disabled");
			});
			$(target).slideToggle();
		});


		getCopyButtons();
 		$(".clientSelect").chosen({width:'250px'});
	});
    function getCopyButtons(){
    	var i = 0;
    	$(".copy_password").each(function(){
    		var copytxt = "#" + $(this).parent('span').find('.copytxt').attr("id", "copyPassword"+i).attr("id");
    		$(this).attr("target-element", copytxt);
    		i++;
    	});
    }

</script>
<style>
#credentialSelect_chosen{
	float:right;
	width:100% !important;
}
#clientSelect_chosen{
	width:100% !important;
}
</style>

<?php include_once("includes/footer.php"); ?>