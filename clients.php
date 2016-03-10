<?php include_once("includes/header.php"); ?>

		<?php 


		/**
		 * sql to get a list of all the clients that this user has some sort of access to
		 * var dump the $_SESSION to see 
		 */
		// foreach($_SESSION['session']->user->access['clients'] as $client):
		// 	$clientArray[] = $client['client_id'];
		// endforeach;


		/*
		 * if we are a super admin, show ALL the clients
		 */

		if($_SESSION['session']->user->isMasterAdmin):
			$sql = 'SELECT * FROM clients';
		else:
			$sql = 'SELECT cl.* FROM employee_access ea 
					LEFT JOIN clients cl ON cl.id = ea.client_id 
					WHERE ea.employee_id = "'. $_SESSION['session']->user->id . '" AND ea.client_id != "";';
		endif;


		$clients = $db->query($sql);



		$cred_edit_number = 0;
		$note_edit_number = 0;
		?>
		<div id="clients">
 		<input class="search form-control" placeholder="Search Clients" />
 		<table class="table table-striped" style="">
			<thead>
				<tr>
					<th style="line-height: 30px;">Client
						<?php if($_SESSION['session']->user->isAdmin == true): ?>
						<a class="new-btn btn btn-primary btn-sm" style="float: right;">Add New Client</a>
						<form class="add_client" style="display:none;">
							<h3>Add New Client</h3>
							<div style="width:48%;float:left;">
				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-name">Client Name</span>
					 				<input type="text" class="form-control" placeholder="Client Name" aria-describedby="client-name" name="name" id="name">
				 				</div>

			 					<div style="height:20px;"></div>

								<div class="input-group">
									<span class="input-group-addon" id="first-name" >First Name</span>
									<input type="text" class="form-control" placeholder="First Name" aria-describedby="first-name" name="fname" id="fname" required>
								</div>


			 					<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-address">Address</span>
					 				<input type="text" class="form-control" placeholder="Address" aria-describedby="client-address" name="address1" id="address1">
				 				</div>

			 					<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-city">City</span>
					 				<input type="text" class="form-control" placeholder="City" aria-describedby="client-city" name="city" id="city">
				 				</div>
				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-postalcode">Postalcode</span>
					 				<input type="text" class="form-control" placeholder="Postalcode" aria-describedby="client-postalcode" name="postalcode" id="postalcode">
				 				</div>

				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-phone1"><i class="fa fa-phone"></i></span>
					 				<input type="text" class="form-control" placeholder="Phone" aria-describedby="client-phone1" name="phone1" id="phone1">
				 				</div>

				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-url">Website URL</span>
					 				<input type="text" class="form-control" placeholder="Website URL" aria-describedby="client-url" name="url" id="url">
				 				</div>


				 			</div>

				 			<div style="width:48%;float:right;">
				 			<div style="height:54px;"></div>
				 				<div class="input-group">
					 				<span class="input-group-addon" id="last-name">Last Name</span>
					 				<input type="text" class="form-control" placeholder="Last Name" aria-describedby="last-name" name="lname" id="lname">
				 				</div>

				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-address2">Address 2</span>
					 				<input type="text" class="form-control" placeholder="Address 2" aria-describedby="client-address2" name="address2" id="address2">
				 				</div>

				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-state">State</span>
					 				<input type="text" class="form-control" placeholder="State" aria-describedby="client-state" name="state" id="state">
				 				</div>


				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-email"><i class="fa fa-envelope"></i></span>
					 				 <input type="text" class="form-control" placeholder="Email" aria-describedby="client-email" name="email" id="email">
				 				</div>
				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-phone2"><i class="fa fa-phone"></i></span>
					 				<input type="text" class="form-control" placeholder="Phone" aria-describedby="client-phone2" name="phone2" id="phone2">
				 				</div>

				 				<div style="height:20px;"></div>

				 				<div class="input-group">
					 				<span class="input-group-addon" id="client-devurl">Dev URL</span>
					 				<input type="text" class="form-control" placeholder="Dev URL" aria-describedby="client-devurl" name="devurl" id="devurl">
				 				</div>
				 				<div style="height:20px;"></div>

							</div>
							<div style="width: 100%; float: left;">
								<h3>Client Credential(s)</h3>
								<div style="height: 20px; border-top: 1px solid #ddd;"></div>

								<table class="table table-striped" style="width: 100%;">
									<thead>
										<tr>
											<td style="width: 28px;"></td>
											<td>Type</td>
											<td>Label</td>
											<td>Link</td>
											<td>Username</td>
											<td>Password</td>
											<td>Plugin GUID</td>
											<td>PIN</td>
											<td>Notes</td>
										</tr>
									</thead>
									<tbody class="add_client_credential">
										
									</tbody>
								</table>

								<div style="height: 20px;"></div>

								<a class="save_add_credential btn btn-primary" style="float: left;">Add Credential</a>
								<a class="save_add_client btn btn-primary" style="float:right;">Save Client</a>
								<a class="cancel-btn btn btn-default" style="float: right; margin-right: 6px;">Cancel</a>
							</div>

						</form>
						<?php endif;?>
					</th>
				</tr>
			</thead>
			<tbody class="list">
				<?php if(!$clients): ?>
					<tr><td>No Clients</td></tr>
				<?php endif; ?>

				<?php foreach($clients as $client): ?>
					<?php 
					//lets get a list of all the notes for this client
					$sql = 'SELECT n.*, e.fname as fname, e.lname as lname FROM notes n 
							left join employees e on n.employee_id = e.id 
							WHERE client_id = '.$client->id.' AND n.status=\'active\'';
					$clientNotes = $db->query($sql);
					?>
					<tr class = "clientTR">
						<td>
							<span class='name'><?= $client->name; ?> <i class="client_expand fa fa-caret-down" data-target="logins_<?= $client->id; ?>"></i></span>
							<div class="logins panel panel-default" id='logins_<?=$client->id;?>' style="display:none;margin-top:20px;">
							<?php //lets add the client information here ?>
							<div class="panel-heading">Client Information <?= $_SESSION['session']->user->isAdmin == true ? '<a class="edit_button" client="'.$client->id.'">Edit</a>' : ''; ?></div>
							<form action="post" id="form_<?= $client->id;?>" client="<?= $client->id; ?>" class='client_form'>
							<input type="hidden" name="client_id" value="<?= $client->id; ?>">
							<div class="panel-body">
								<div class="client-information">
									<div class="panel panel-default" style="width:48%;float:left;">
										<div class="panel-heading">Contact Information</div>
										<div class="panel-body">
											<div class="formRow">
												<div>
													<span class="name contact_fname-<?=$client->id;?>"><?=ucwords($client->contact_fname);?></span> 
												
													<span class="name contact_lname-<?=$client->id;?>"><?=ucwords($client->contact_lname);?></span>
												</div>
												
												<div><span class="contact_address_1-<?=$client->id;?>"><?=ucwords($client->contact_address_1);?></span></div>
												<div><span class="contact_address_2-<?=$client->id;?>"><?=ucwords($client->contact_address_2); ?></span></div>
												<div><span class="contact_city-<?=$client->id;?>"><?=ucwords($client->contact_city);?></span> <span class="contact_state-<?=$client->id;?>"><?=$client->contact_state;?></span> <span class="contact_postalcode-<?=$client->id;?>"><?=$client->contact_postalcode;?></span></div>
												<br />
												<div><?=$client->contact_phone_1 ? '<i class="fa fa-phone ficon"></i> ' : '' ?><span class="contact_phone_1-<?=$client->id;?>"><?=$client->contact_phone_1; ?></span></div>
												<div><?=$client->contact_phone_2 ? '<i class="fa fa-phone ficon"></i> ' : '' ?><span class="contact_phone_2-<?=$client->id;?>"><?=$client->contact_phone_2; ?></span></div>
												<div><?=$client->contact_email ? '<i class="fa fa-envelope ficon"></i> ': '' ?><span class="contact_email-<?=$client->id;?>"><a href="mailto:<?=$client->contact_email;?>"><?=$client->contact_email;?></a></span></div>

											</div>
										</div>
									</div>
									<style>
									.client-information .input-group{
										margin-bottom:20px;
									}
									</style>
									<div class="panel panel-default" style="width:48%;float:right;">
										<div class="panel-heading">Notes</div>
										<div class="panel-body">
											<div class="formRow">Website URL:</div><div><span class="url-<?= $client->id; ?>"><a href="<?= $client->url; ?>"><?= $client->url; ?></a></span></div>
											<div style="height:20px;"></div>
											<div class="formRow">Dev URL:</div><div><span class="devurl-<?= $client->id; ?>"><a href="<?= $client->devurl; ?>"><?= $client->devurl; ?></a></span></div>
											<div style="height:20px;"></div>
											<div class="formRow">
												Notes:<br />
												<?php 
												//make sure we are only putting 5 notes into the notes tab 
												//TODO: make this better;
												$notesCount = count($clientNotes);
													if($notesCount > 5 ):
														$notesCount = 5;
													endif;
													$i=0;
													
												 ?>
												<?php while($i < $notesCount): ?>
													<?php echo $clientNotes[$i]->note ?>
													<i class="fa fa-info-circle" rel="popover" title="<?php echo ucwords($clientNotes[$i]->fname ." " . $clientNotes[$i]->lname) ?>" data-content="<?php echo $clientNotes[$i]->date_modified ?>"></i>
													<?php $i++ //now output all the notes and hide them until we click all above ?>
												<?php endwhile; ?>
											</div>

										</div>
									</div>
									<div style="clear:both;"></div>
								</div>
							</div>
							<a class="btn btn-default saveEdit saveEdit-<?= $client->id; ?>" style="float:right; margin-right: 15px; display: none !important;" client="<?= $client->id; ?>">Save</a>
							</form> <!-- end form -->
							<table class="table table-striped">
								<thead>
									<tr>
										<td> </td>
										<td>Login Type</td>
										<td>Label</td>
										<td>Login Link</td>
										<td>Username</td>
										<td>Password</td>
										<td>Plugin GUID</td>
										<td>PIN</td>
										<td>Notes</td>
										<?php if($_SESSION['session']->user->isAdmin == true): ?>
										<td>Action</td>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody class="list" id="credential_list-<?=$client->id;?>">

								<?php // put all the stuff here, like the edit button etc ?>

								<?php 
									/* check if master admin */
									if($_SESSION['session']->user->isMasterAdmin):
										$sql = 'SELECT c.id, ct.type as type, c.label, c.url, c.username, c.password, c.pin, c.guid FROM credential c LEFT JOIN credential_types ct on ct.id = c.type_id where client_id = '. $client->id;
									else:
										$sql = 'SELECT c.id, ct.type as type, c.label, c.url, c.username, c.password, c.pin, c.guid FROM employee_access ea LEFT JOIN credential c ON c.id = ea.credential_id LEFT JOIN credential_types ct ON ct.id = c.type_id WHERE ea.employee_id = "' . $_SESSION['session']->user->id . '" AND c.client_id = "' . $client->id . '" AND status = "active";';
									endif;
									$credResults = $db->query($sql);

									foreach($credResults as $cred):
										$cred_edit_number++;
										//grab all the comments for this credential
										$sql = 'select n.*, e.fname as fname, e.lname as lname from notes n 
												left join employees e on n.employee_id = e.id 
												where n.credential_id = '. $cred->id ." and n.status='active'";
										$credNotes = $db->query($sql);
										//build out the table row
										?>

										<tr id="edit_cred_line-<?=$cred_edit_number;?>" client="<?=$client->id;?>" credential-id="<?=$cred->id;?>" og="true">
											<td class="del"> </td>
											<td class="type"><?php echo $cred->type ?></td>
											<td class="c_label"><?php echo $cred->label ?><input type="hidden" value="<?=$cred->label;?>"></td>
											<td class="url"><a href="<?php echo $cred->url ?>" target="_blank" rel="popover" title="Link:" data-content="<?php echo $cred->url ?>">Link</a><input type="hidden" value="<?=$cred->url;?>"></td>
											<td class="username">
												<copy-button class='copy_username'><i class='fa fa-copy'></i></copy-button> <span class='copytxt'><?php echo $cred->username ?></span><input type="hidden" value="<?=$cred->username;?>">
											</td>
											<td class="password">
												<div class="viewCredentialBtn btn btn-default" data-credid="<?php echo $cred->id; ?>">VIEW</div>
												<div class="credential" style="display:none;">
													<copy-button class='copy_password'><i class='fa fa-copy'></i></copy-button> <span class='copytxt'></span><input type="hidden" value="">
												</div>
											</td>
											<td class="guid"><?php echo $cred->guid ?><input type="hidden" value="<?=$cred->guid;?>"></td>
											<td class="pin"><?php echo $cred->pin ?><input type="hidden" value="<?=$cred->pin;?>"></td>
											<td class="notes">


												<i class="fa fa-comments cred_notes" href="#crednotes_<?php echo $cred->id ?>" style="cursor:pointer;"></i>


												<?php //putting a hidden div in here that we can load up with a floating box ?>
												<div id="crednotes_<?php echo $cred->id ?>" style="width: 500px; height: 500px; display:none;">
												<textarea id="edit_new_comment-<?=$cred_edit_number; ?>" placeholder="Note" class="form-control"></textarea>
												<div style="margin-top: 4px; text-align: right;">
													<a class="btn btn-primary btn-sm add_edit_note" target="<?=$cred_edit_number;?>" credential-id="<?=$cred->id;?>">Add New Note</a>
												</div>

													<?php foreach($credNotes as $credNote): ?>
														<?php $note_edit_number++; ?>
														<div class="note" id="note_<?=$credNote->note_id;?>" style="margin-bottom:20px;">
															<div class="note-author"><?php echo ucwords($credNote->fname . " " . $credNote->lname); ?></div>
															<div class="note-content" style="border:1px solid black;padding:10px; border-radius:5px;">
																<?php echo $credNote->note; ?>
																<?php if($credNote->employee_id == $_SESSION['session']->user->id || $_SESSION['session']->user->isAdmin == true): ?>
																<button type="button" class="delete_cred_note" note="<?=$credNote->note_id;?>" style="float: right;">&times;</button>
																<?php endif; ?>
															</div>
														</div>
													<?php endforeach; ?>
												</div>
											</td>
											<?php if($_SESSION['session']->user->isAdmin == true): ?>
											<td class="action">
												<a class="btn btn-primary btn-sm cred_edit" cred-line="<?=$cred_edit_number;?>" credential-id="<?=$cred->id;?>">Edit</a>
											</td>
											<?php endif; ?>
										</tr>
										<?php
									endforeach;
								?>
								</tbody>
								<?php if($_SESSION['session']->user->isAdmin == true): ?>
								<tr>
									<td colspan="10" style="text-align: right;">
										<a class="btn btn-primary add_edit_cred" client="<?=$client->id;?>">Add New Credential</a>
									</td>
								</tr>
								<?php endif; ?>
							</table>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>


<?php include_once("includes/footer.php"); ?>

 <script>
 	$(document).ready(function(){
 		var i = 0; // new cred index
 		var n = 0; // new note index
 		var t = <?=$cred_edit_number;?>; // credential edit number to continue where we set off so no confusion on the dom
 		var u = <?=$note_edit_number;?>; // edit note number
 		var notesobj = {};
 		var name = '<?= $_SESSION['session']->user->firstname . ' ' . $_SESSION['session']->user->lastname ?>';
 		var array = ['contact_fname', 'contact_lname', 'contact_address_1', 'contact_address_2', 'contact_city', 'contact_state', 'contact_postalcode', 'contact_phone_1', 'contact_phone_2', 'contact_email', 'url', 'devurl'];
 		

 		// add new stuff
 		$('.save_add_credential').click(function(e){
 			i++;
 			e.preventDefault();
 			var cred =	'<tr class="credential_line-' + i + '">' +
 						'	<td style="text-align: center;"><i class="remove_cred_line fa fa-minus" target="credential_line-' + i + '"></i></td>' +
 						'	<td>' +
 						'		<select class="cred_type_select" name="credential['+ i +'][type]">' +
 						<?php
 							$sql = 'SELECT id, type FROM credential_types ORDER BY type ASC;';
 							$creds = $db->query($sql);
 							foreach($creds as $cred):
 								echo "'			<option value=\"" . $cred->id . "\">" . $cred->type . "</option>' +";
 							endforeach;
 						?>
 						'		</select>' +
 						'	</td>' +
 						'	<td>' +
 						'		<input type="text" class="form-control" placeholder="Label" name="credential[' + i + '][label]">' +
 						'	</td>' +
 						'	<td>' +
 						'		<input type="text" class="form-control" placeholder="Link" name="credential[' + i + '][link]">' +
 						'	</td>' +
 						'	<td>' +
 						'		<input type="text" class="form-control" placeholder="Username" name="credential[' + i + '][username]">' +
 						'	</td>' +
 						'	<td>' +
 						'		<input type="text" class="form-control" placeholder="Password" name="credential[' + i + '][password]">' +
 						'	</td>' +
						'	<td>' +
 						'		<input type="text" class="form-control" placeholder="GUID" name="credential[' + i + '][guid]">' +
 						'	</td>' +
 						'	<td>' +
 						'		<input type="text" class="form-control" placeholder="PIN" name="credential[' + i + '][pin]">' +
 						'	</td>' +
 						'	<td style="text-align: center;">' +
 						'		<i class="fa fa-comments add_cred_notes" href="#comment_holder-' + i + '"></i>' +
 						'		<div id="comment_holder-' + i + '" style="display: none; width: 500px; height: 500px;">' +
 						'			<textarea id="comment-' + i + '" placeholder="Note" class="form-control"></textarea>' +
 						'			<div style="margin-top: 4px; text-align: right;">' +
 						'				<a class="btn btn-primary btn-sm add_new_comment" target="' + i + '">Add New Note</a>' +
 						'			</div>' +
 						'			<div id="comments-' + i + '"></div>' +
 						'		</div>' +
 						'	</td>' +
 						'</tr>';
 			$('.add_client_credential').append(cred);
 			$('.cred_type_select').chosen({no_results_text: "Oops, nothing found!"});

 		});

 		$('body').delegate('.add_new_comment', 'click', function(e){
 			var note = $('#comment-' + $(this).attr('target')).val();
 			if(note){
	 			n++;
	 			e.preventDefault();
	 			var cred = $(this).attr('target');
	 			$('#comments-' + $(this).attr('target')).append('<div class="note-' + n + '"><div class="note-author" style="margin-top: 10px;">' + name + '</div><div class="note-content" style="border:1px solid black;padding:10px; border-radius:5px;" id="visual_comment-' + n + '">' + note + '<button type="button" class="delete_note" style="float: right;" target="' + n + '">&times;</button></div><input type="hidden" name="credential[' + cred + '][note][' + n + ']" id="hidden_comment-' + n + '" value="' + note + '"></div>');
	 			$('#comment-' + $(this).attr('target')).val('');
	 		}
 		});

 		$('body').delegate('.remove_cred_line', 'click', function(e){
 			e.preventDefault();
 			$('.' + $(this).attr('target')).remove();
 		});
 		$('body').delegate('.delete_note', 'click', function(e){
 			e.preventDefault();
 			$('.note-' + $(this).attr('target')).remove();
 		});

		$(".add_cred_notes").fancybox({maxWidth:500, maxHeight: 700});

 		$('.save_add_client').click(function(e){
			e.preventDefault();
			var data = $('.add_client').serializeObject();
			data['doingajax'] = 'true';
			data['ajax_function'] = 'add_client';
			$.ajax({
				url:'clients.php',
				type: "POST",
				data: data,
				success:function(response){
					console.log(response);
					$('.edit_button').click();
				 	if(response == "success"){
	 					 location.reload();
	 				}
				}
			});
		});

 		$('.cancel-btn').click(function(e){
 			e.preventDefault();
 			$('.add_client').slideToggle();
 			$('.new-btn').show();
 			$('.add_client')[0].reset();
			for (a = 0; a <= i; a++) { 
				$('.credential_line-'+a).remove();
			}
 			i = 0;

 		});
 		$('.new-btn').click(function(e){
 			e.preventDefault();
 			$('.add_client').slideToggle();
 			$(this).hide();
 		});

		// edit crap

		// NEW CREDENTIAL NOTES
		$(".add_new_cred_notes").fancybox({maxWidth:500, maxHeight: 700});

 		$('body').delegate('.add_new_cred_comment', 'click', function(e){
 			var note = $('#comment-' + $(this).attr('target')).val();
 			if(note){
	 			n++;
	 			e.preventDefault();
	 			var cred = $(this).attr('target');
	 			$('#comments-' + $(this).attr('target')).append('<div class="note-' + n + '"><div class="note-author" style="margin-top: 10px;">' + name + '</div><div class="note-content" style="border:1px solid black;padding:10px; border-radius:5px;" id="visual_comment-' + n + '">' + note + '<button type="button" class="delete_new_cred_note" style="float: right;" target="' + n + '">&times;</button></div><input type="hidden" name="credential[' + cred + '][note][' + n + ']" id="hidden_comment-' + n + '" value="' + note + '"></div>');
	 			$('#comment-' + $(this).attr('target')).val('');
	 		}
 		});
 		$('body').delegate('.delete_new_cred_note', 'click', function(e){
 			e.preventDefault();
 			$('.note-' + $(this).attr('target')).remove();
 		});
		// DELETE CREDENTIAL LINES BUTTON
		$('body').delegate('.remove_edit_cred', 'click', function(e){
			e.preventDefault();
			cred_line = $(this).attr('cred-line');
			var og = $('#edit_cred_line-' + cred_line).attr('og');
			var credential = $(this).attr('credential-id');
			var data = {};
			data['doingajax'] = 'true';
			data['ajax_function'] = 'delete_credential';
			data['credential'] = credential;
			if(og == 'true') {
				var what = confirm('Are You Sure?');
				if(what == true){
					// add some ajax to delete the already stored cred
					$.ajax({
						url:'clients.php',
						type:'POST',
						data: data,
						success: function(){
							$('#edit_cred_line-' + cred_line).remove();
						}
					});
				}
			} else {
				$('#edit_cred_line-' + cred_line).remove();
			}

		});


		$('body').delegate('.delete_cred_note', 'click', function(e){
			var note = $(this).attr('note');
			var data = {};
			var what = confirm('Are You Sure?');
			data['doingajax'] = 'true';
			data['ajax_function'] = 'delete_cred_note';
			data['note_id'] = note;
			if(what == true){
				$.ajax({
					url:'clients.php',
					type:'POST',
					data: data,
					success: function(){
						$('#note_' + note).remove();
					}
				});
			}
		});

		// SAVE NEW CREDENTIAL TO CLIENT WOOT
		$('body').delegate('.add_edit_cred_save', 'click', function(e){
			e.preventDefault();
			var client = $(this).attr('client');
			var cred_line = $(this).attr('cred-line');
			var data = {};
			var notes = [];
			data['client'] = client;
			data['type_id'] = $('#edit_cred_line-' + cred_line + ' td.type select').val();
			var typetxt = $('#edit_cred_line-' + cred_line + ' td.type select option:selected').text();
			data['label'] = $('#edit_cred_line-' + cred_line + ' td.c_label input.form-control').val();
			data['url'] = $('#edit_cred_line-' + cred_line + ' td.url input.form-control').val();
			data['username'] = $('#edit_cred_line-' + cred_line + ' td.username input.form-control').val();
			data['password'] = $('#edit_cred_line-' + cred_line + ' td.password input.form-control').val();
			data['guid'] = $('#edit_cred_line-' + cred_line + ' td.guid input.form-control').val();
			data['pin'] = $('#edit_cred_line-' + cred_line + ' td.pin input.form-control').val();
			data['doingajax'] = 'true';
			data['ajax_function'] = 'add_edit_cred_save';
			$.each($('#edit_cred_line-' + cred_line + ' td.notes #comment_holder-' + cred_line + ' #comments-' + cred_line + ' input[type="hidden"]'), function(e){
				notes.push($(this).val());
			});
			data['notes'] = notes;
			var savenotes = $('#edit_cred_line-' + cred_line + ' td.notes #comment_holder-' + cred_line + ' #comments-' + cred_line).html();
			// console.log(data);
			$.ajax({
				url:'clients.php',
				type: "POST",
				data: data,
				success:function(response){
					var obj = JSON.parse(response);
					var r_cred = obj['credential'];
					var r_notes = obj['notes'];
					var s_notes = '';
					var nc = 0;
					$.each(r_notes, function(p,q){
						nc++;
						s_notes += '&notes[]=' + q;
					});
					t = t + nc;

					$('#edit_cred_line-' + cred_line + ' td.del').html('');
					$('#edit_cred_line-' + cred_line + ' td.type').html(typetxt);
					$('#edit_cred_line-' + cred_line + ' td.c_label').html(data['label'] + '<input type="hidden" value="' + data['label'] + '">');
					$('#edit_cred_line-' + cred_line + ' td.url').html('<a href="' + data['url'] + '" target="_blank" rel="popover" title="Link:" data-content="' + data['url'] + '">Link</a><input type="hidden" value="' + data['url'] + '">');
					$('#edit_cred_line-' + cred_line + ' td.username').html('<copy-button class="copy_username"><i class="fa fa-copy"></i></copy-button> <span class="copytxt">' + data['username'] + '</span><input type="hidden" value="' + data['username'] + '">');
					// this needs to be changed
					$('#edit_cred_line-' + cred_line + ' td.password').html('<copy-button class="copy_password"><i class="fa fa-copy"></i></copy-button> <span class="copytxt">' + data['password'] + '</span><input type="hidden" value="' + data['password'] + '">');
					$('#edit_cred_line-' + cred_line + ' td.guid').html(data['guid'] + '<input type="hidden" value="' + data['guid'] + '">');
					$('#edit_cred_line-' + cred_line + ' td.pin').html(data['pin'] + '<input type="hidden" value="' + data['pin'] + '">');
					$('#edit_cred_line-' + cred_line + ' td.notes').load('includes/note_loader.php?credential=' + r_cred + s_notes + '&cred_edit_number=' + t);
					$('#edit_cred_line-' + cred_line + ' td.action').html('<a class="btn btn-primary btn-sm cred_edit" cred-line="' + cred_line + '" credential-id="' + response + '">Edit</a>');
					$('#edit_cred_line-' + cred_line).attr('credential-id', response).attr('og', 'true');
					$("[rel=popover]").popover({'trigger':'hover', 'html':true});
					getCopyButtons();
				}
			});

		});

		// SAVE EDIT CREDENTIAL
		$('body').delegate('.cred_edit_save', 'click', function(e){
			e.preventDefault();
			var cred_line = $(this).attr('cred-line');
			var client = $('#edit_cred_line-' + cred_line).attr('client');
			var credential = $('#edit_cred_line-' + cred_line).attr('credential-id');
			var data = {};
			data['client'] = client;
			data['credential'] = credential;
			data['label'] = $('#edit_cred_line-' + cred_line + ' td.c_label input.form-control').val();
			data['url'] = $('#edit_cred_line-' + cred_line + ' td.url input.form-control').val();
			data['username'] = $('#edit_cred_line-' + cred_line + ' td.username input.form-control').val();
			data['password'] = $('#edit_cred_line-' + cred_line + ' td.password input.form-control').val();
			data['guid'] = $('#edit_cred_line-' + cred_line + ' td.guid input.form-control').val();
			data['pin'] = $('#edit_cred_line-' + cred_line + ' td.pin input.form-control').val();
			data['doingajax'] = 'true';
			data['ajax_function'] = 'cred_edit_save';
			$.ajax({
				url:'clients.php',
				type: "POST",
				data: data,
				success:function(response){
					console.log(response);
					$('#edit_cred_line-' + cred_line + ' td.c_label input[type="hidden"]').val(data['label']);
					$('#edit_cred_line-' + cred_line + ' td.url input[type="hidden"]').val(data['url']);
					$('#edit_cred_line-' + cred_line + ' td.username input[type="hidden"]').val(data['username']);
					$('#edit_cred_line-' + cred_line + ' td.password input[type="hidden"]').val(data['password']);
					$('#edit_cred_line-' + cred_line + ' td.guid input[type="hidden"]').val(data['guid']);
					$('#edit_cred_line-' + cred_line + ' td.pin input[type="hidden"]').val(data['pin']);
					$('#edit_cred_line-' + cred_line + ' .cred_edit_cancel').click();
				}
			});

		});

		//view credential click
		$("body").delegate(".viewCredentialBtn", 'click', function(e){
			var cred_id = $(this).data("credid");
			var data = {};
			var clicked = $(this);

			data['doingajax'] = 'true';
			data['ajax_function'] = 'log_credential';
			data['credential_id'] = cred_id;
			$.ajax({
				url:'clients.php',
				type: "POST",
				data: data,
				success:function(response){
					//now the credential is logged, lets pull and display the password
					if(response == "success"){
						$.ajax({
							url:'clients.php',
							type:"POST",
							data: {doingajax:"true", ajax_function: "view_credential", credential_id: cred_id},
							success:function(cred_response){
								//console.log(cred_response);
								$(clicked).hide();
								$(clicked).parent().find(".credential").find(".copytxt").text(cred_response).next().val(cred_response);
								$(clicked).parent().find(".credential").fadeIn("fast", function(){
										getCopyButtons;
										$(this).fadeIn().delay(18000).fadeOut("fast", function(){
											$(this).find(".copytxt").text("").next().val('').parent().fadeOut("fast", function(){
													$(clicked).show();
												});
										});
								});
								
							}
						});
					}

					//response.here
					// console.log(response);
					// if(response == "success"){
					// 	//let the user see the password
					// 	$(clicked).hide();
					// 	$(clicked).parent().find(".credential").fadeIn("fast", function(){
					// 		getCopyButtons();
					// 	});
					// }else{
					// 	// there was an error writing the access to the database, do nothing
					// }
				}
			});
		});



		// EDIT BUTTON CREDENTIAL
		$('body').delegate('.cred_edit', 'click', function(e){
			e.preventDefault();
			var credential = $(this).attr('credential-id');
			var cred_line_number = $(this).attr('cred-line');
			var cred_line = '#edit_cred_line-' + cred_line_number;

			var label = $(cred_line + ' td.c_label input[type="hidden"]').val();
			var link = $(cred_line + ' td.url input[type="hidden"]').val();
			var username = $(cred_line + ' td.username input[type="hidden"]').val();
			var password = $(cred_line + ' td.password input[type="hidden"]').val();
			var guid = $(cred_line + ' td.guid input[type="hidden"]').val();
			var pin = $(cred_line + ' td.pin input[type="hidden"]').val();
			var notes = $(cred_line + ' td.notes').html();
			notesobj[cred_line_number] = notes;

			$(cred_line + ' td.del').html('<i class="remove_edit_cred fa fa-minus" cred-line="' + cred_line_number + '" credential-id="' + credential + '"></i>');
			$(cred_line + ' td.c_label').html('<input type="text" class="form-control" placeholder="Label" name="credential[' + credential + '][label]" value="' + label + '"><input type="hidden" value="' + label + '">');
			$(cred_line + ' td.url').html('<input type="text" class="form-control" placeholder="Link" name="credential[' + credential + '][link]" value="' + link + '"><input type="hidden" value="' + link + '">');
			$(cred_line + ' td.username').html('<input type="text" class="form-control" placeholder="Username" name="credential[' + credential + '][username]" value="' + username + '"><input type="hidden" value="' + username + '">');
			$(cred_line + ' td.password').html('<input type="password" class="form-control" placeholder="Password" name="credential[' + credential + '][password]" value="' + password + '"><input type="hidden" value="' + password + '">');
			$(cred_line + ' td.guid').html('<input type="text" class="form-control" placeholder="GUID" name="credential[' + credential + '][guid]" value="' + guid + '"><input type="hidden" value="' + guid + '">');
			$(cred_line + ' td.pin').html('<input type="text" class="form-control" placeholder="PIN" name="credential[' + credential + '][pin]" value="' + pin + '"><input type="hidden" value="' + pin + '">');
			$(cred_line + ' td.notes').html('<a class="btn btn-default btn-sm cred_edit_cancel" cred-line="' + cred_line_number + '" credential-id="' + credential + '">Cancel</a>');
			$(cred_line + ' td.action').html('<a class="btn btn-primary btn-sm cred_edit_save" cred-line="' + cred_line_number + '">Save</a>');
		});


		// CANCEL BUTTON EDIT CREDENTIAL
		$('body').delegate('.cred_edit_cancel', 'click', function(e){
			e.preventDefault();
			var credential = $(this).attr('credential-id');
			var cred_line_number = $(this).attr('cred-line');
			var cred_line = '#edit_cred_line-' + cred_line_number;

			var label = $(cred_line + ' td.c_label input[type="hidden"]').val();
			var link = $(cred_line + ' td.url input[type="hidden"]').val();
			var username = $(cred_line + ' td.username input[type="hidden"]').val();
			var password = $(cred_line + ' td.password input[type="hidden"]').val();
			var guid = $(cred_line + ' td.guid input[type="hidden"]').val();
			var pin = $(cred_line + ' td.pin input[type="hidden"]').val();
			var notes = $(cred_line + ' td.notes input[type="hidden"]').val();


			$(cred_line + ' td.del').html('');
			$(cred_line + ' td.c_label').html(label + '<input type="hidden" value="' + label + '">');
			$(cred_line + ' td.url').html('<a href="' + link + '" target="_blank" rel="popover" title="Link:" data-content="' + link + '">Link</a><input type="hidden" value="' + link + '">');
			$(cred_line + ' td.username').html('<copy-button class="copy_username"><i class="fa fa-copy"></i></copy-button> <span class="copytxt">' + username + '</span><input type="hidden" value="' + username + '">');
			$(cred_line + ' td.password').html('<div class="viewCredentialBtn btn btn-default" data-credid="' + credential + '">VIEW</div><div class="credential" style="display:none;"><copy-button class="copy_password"><i class="fa fa-copy"></i></copy-button> <span class="copytxt">' + password + '</span><input type="hidden" value="' + password + '"></div>');
			$(cred_line + ' td.guid').html(guid + '<input type="hidden" value="' + guid + '">');
			$(cred_line + ' td.pin').html(pin + '<input type="hidden" value="' + pin + '">');

			$(cred_line + ' td.notes').html(notesobj[cred_line_number]);
			$(cred_line + ' td.action').html('<a class="btn btn-primary btn-sm cred_edit" cred-line="' + cred_line_number + '" credential-id="' + credential + '">Edit</a>');

			$("[rel=popover]").popover({'trigger':'hover', 'html':true});
			getCopyButtons();
		});

		$('body').delegate('.add_edit_note', 'click', function(e){
			u++;
 			var note = $('#edit_new_comment-' + $(this).attr('target')).val();
 			var target = $(this).attr('target');
 			var credential = $(this).attr('credential-id');
 			var data = {};
 			data['ajax_function'] = 'add_cred_note';
 			data['doingajax'] = 'true';
 			data['note'] = note;
 			data['credential'] = credential;
 			if(note){
 				$.ajax({
 					url:'clients.php',
 					type:'POST',
 					data: data,
 					success: function(response){
 						console.log(response)
						var obj = JSON.parse(response);
						var note_id = obj['note_id'];
 						$('#crednotes_' + target).append('<div class="note" id="note_' + note_id + '" style="margin-bottom:20px;"><div class="note-author">' + name + '</div><div class="note-content" style="border: 1px solid black;padding:10px; border-radius:5px;">' + note + '<button type="button" class="delete_cred_note" note="' + note_id + '" style="float:right;">&times;</button></div></div>');
 						$('#edit_new_comment-' + target).val('');
 					}
 				});
	 		}
		});

		// ADD NEW CREDENTIAL TO CLIENT BUTTON
		$('.add_edit_cred').click(function(e){
			t++;
			e.preventDefault();
			var client = $(this).attr('client');
 			var cred =	'<tr id="edit_cred_line-' + t + '" client="' + client + '" og="false">' +
 						'	<td class="del" style="text-align: center;"><i class="remove_edit_cred fa fa-minus" cred-line="' + t + '"></i></td>' +
 						'	<td class="type">' +
 						'		<select class="cred_type_select" name="credential['+ t +'][type]">' +
 						<?php
 							$sql = 'SELECT id, type FROM credential_types ORDER BY type ASC;';
 							$creds = $db->query($sql);
 							foreach($creds as $cred):
 								echo "'			<option value=\"" . $cred->id . "\">" . $cred->type . "</option>' +";
 							endforeach;
 						?>
 						'		</select>' +
 						'	</td>' +
 						'	<td class="c_label">' +
 						'		<input type="text" class="form-control" placeholder="Label" name="credential[' + t + '][label]">' +
 						'	</td>' +
 						'	<td class="url">' +
 						'		<input type="text" class="form-control" placeholder="Link" name="credential[' + t + '][link]">' +
 						'	</td>' +
 						'	<td class="username">' +
 						'		<input type="text" class="form-control" placeholder="Username" name="credential[' + t + '][username]">' +
 						'	</td>' +
 						'	<td class="password">' +
 						'		<input type="text" class="form-control" placeholder="Password" name="credential[' + t + '][password]">' +
 						'	</td>' +
 						'	<td class="guid">' +
 						'		<input type="text" class="form-control" placeholder="GUID" name="credential[' + t + '][guid]">' +
 						'	</td>' +
 						'	<td class="pin">' +
 						'		<input type="text" class="form-control" placeholder="PIN" name="credential[' + t + '][pin]">' +
 						'	</td>' + 						
 						'	<td class="notes">' +
 						'		<i class="fa fa-comments add_new_cred_notes" href="#comment_holder-' + t + '"></i>' +
 						'		<div id="comment_holder-' + t + '" style="display: none; width: 500px; height: 500px;">' +
 						'			<textarea id="comment-' + t + '" placeholder="Note" class="form-control"></textarea>' +
 						'			<div style="margin-top: 4px; text-align: right;">' +
 						'				<a class="btn btn-primary btn-sm add_new_cred_comment" target="' + t + '">Add New Note</a>' +
 						'			</div>' +
 						'			<div id="comments-' + t + '"></div>' +
 						'		</div>' +
 						'	</td>' +
 						'	<td class="action">' +
 						'		<a class="btn btn-primary btn-sm add_edit_cred_save" client="' + client + '" cred-line="' + t + '">Add</a>' +
 						'	</td>' +
 						'</tr>';
 			$('#credential_list-' + $(this).attr('client')).append(cred);
 			$('.cred_type_select').chosen({no_results_text: "Oops, nothing found!"});
		});


 		$(".chosen").chosen();
		$("[rel=popover]").popover({'trigger':'hover', 'html':true});
		$(".cred_notes").fancybox({maxWidth:500, maxHeight: 700});
 		/* list.js filtering*/
		var clientList = new List('clients', {valueNames: [ 'name']});


		/* handle the caret click*/
		$(".name").click(function(){

			//$(this).parent().removeClass("active");

			var ce = $(this).find(".client_expand");

			$('.logins').not($(ce).find(".logins")).each(function(){
				$(this).parent().parent().removeClass("active");
				$(this).slideUp();
				$(this).parent("td").find(".client_expand").addClass("fa-caret-down").removeClass("fa-caret-up");
			});

			$("#" + $(ce).data("target")).stop().slideToggle("", function(){
				var visible = $(this).is(":visible") ;
				if(visible){
					$(this).parent().parent().addClass("active");
					$(this).parent("td").find(".client_expand").removeClass("fa-caret-down").addClass("fa-caret-up");
				}
			});
		

		});
		getCopyButtons();

		$('.edit_button').click(function() {
			var d = $(this).text();
			var client_id = $(this).attr('client');
			var save = '.saveEdit-' + client_id;
			var place = ['First Name', 'Last Name', 'Address 1', 'Address 2', 'City', 'State', 'Postal Code', 'Phone #1', 'Phone #2', 'E-mail', 'URL', 'Dev URL'];
			if(d == 'Edit') {
				$(save).show();
				$(this).html('Cancel');
				$('.ficon').hide();
				var fields = [];
				var values = [];
				$.each(array, function(e, p){
					var l = '.' + p + '-' + client_id;
					var k = $(l).text();
					fields.push(l);
					values.push(k);
				});
				$.each(fields, function(e, p){
					var v = values[e];
					if(array[e] == 'contact_phone_1' || array[e] == 'contact_phone_2'){
						$(p).html('<div class="input-group"><span class="input-group-addon"><i class="fa fa-phone"></i></span><input type="text" value="' +  v +'" name="' + array[e] + '" placeholder="'+place[e]+'" class="form-control"></div><input type="hidden" value="' + v +'" id="' + array[e] + '-' + client_id + '">')
					} else if (array[e] == 'contact_email') {
						$(p).html('<div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span><input type="text" value="' +  v +'" name="' + array[e] + '" placeholder="'+place[e]+'" class="form-control"></div><input type="hidden" value="' + v +'" id="' + array[e] + '-' + client_id + '">')
					} else {
						$(p).html('<div class="input-group"><span class="input-group-addon">' + place[e] + '</span><input type="text" value="' +  v +'" name="' + array[e] + '" placeholder="'+place[e]+'" class="form-control"></div><input type="hidden" value="' + v +'" id="' + array[e] + '-' + client_id + '">');
					}
				});
			} else {
				$(save).hide();
				$(this).html('Edit');
				$('.ficon').show();
				$.each(array, function(e, p){
					var f = '.' + p + '-' + client_id;
					var l = '#' + p + '-' + client_id;
					if(p == 'url' || p == 'devurl'){
						$(f).html('<a href="' + $(l).val() + '">' + $(l).val() + '</a>');
					} else if(p == 'contact_email') {
						$(f).html('<a href="mailto:' + $(l).val() + '">' + $(l).val() + '</a>');
					} else {
						$(f).html($(l).val());
					}
				});

			}
		});
		$('.saveEdit').click(function(e){
			e.preventDefault();
			var a = '#form_' + $(this).attr('client');
			$(a).submit();
		});
		$('.client_form').submit(function(e){
			e.preventDefault();
			var client_id =  $(this).attr('client');
			//console.log(data);
			var data = $(this).serializeObject();
			data['doingajax'] = 'true';
			data['ajax_function'] = 'edit_client';
			$.ajax({
				url:'clients.php',
				type: "POST",
				data: data,
				success:function(response){
					$.each(data, function(k, p){

						console.log($(n).val());
					});
					$('.edit_button').click();
				}
			});
		});
		$.fn.serializeObject = function(){
			var o = {};
			var a = this.serializeArray();
			$.each(a, function() {
				if (o[this.name] !== undefined) {
					if (!o[this.name].push) {
					    o[this.name] = [o[this.name]];
					}
					o[this.name].push(this.value || '');
				} else {
					o[this.name] = this.value || '';
				}
			});
			return o;
		};
 	});
		function validateEmail(email) {
		    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		    return re.test(email);
		}
 	    function getCopyButtons(){
    	var i = 0;
    	var e = 0;
    	$(".copy_username").each(function(){
    		var copytxt = "#" + $(this).parent('td').find('.copytxt').attr("id", "copyUsername"+i).attr("id");
    		$(this).attr("target-element", copytxt);
    		i++;
    	});
    	$(".copy_password").each(function(){
    		var copytxt = "#" + $(this).parent('div').find('.copytxt').attr("id", "copyPassword"+e).attr("id");
    		$(this).attr("target-element", copytxt);
    		e++;
    	});

    }
 </script>