<?php 
//var_dump($_POST);

if(isset($_POST['doingajax'])):
	//load ajax functions here
	switch($_POST['ajax_function']){
		case "generate_password":
			echo generateRandomPassword();
			break;
		case "get_client_credentials":
			//if getting client credentials we need a client_id
			$sql = 'select c.id as id, ct.type as type, c.label as label from credential c left join credential_types ct on c.type_id = ct.id where c.client_id= ?';
			$credentials = $db->query($sql, $_POST['client_id']);
			if($credentials):
				foreach($credentials as $credential): ?>
					<option value="<?php echo $credential->id ?>"><?php echo $credential->type ?> <?php echo $credential->label ? "(" . $credential->label . ")" : "" ?></option>
				<?php endforeach;
			endif;
			break;
		case "edit_client":
		// need to set permissions still
			$client_id = $_POST['client_id'];
			unset($_POST['doingajax']);
			unset($_POST['ajax_function']);
			unset($_POST['client_id']);
			
			$sql = 'UPDATE clients SET ';
			$max = count($_POST);
			$cur = 0;
			foreach($_POST as $key=>$value):
				$cur++;
				$comma = ',';
				if($cur == $max):
					$comma = '';
				endif;
				$sql .= $key . ' = "' . $value . '"'.$comma.' ';
			endforeach;
			$sql .= 'WHERE id = ?;';
			$trevorpwns = $db->query($sql, $client_id);
			break;
		case "add_employee":
			$errors = false;
			$postdata = array();
			parse_str($_POST['data'], $postdata);
			$clients = $postdata['clients'];

			$encodedpassword = getEncryptedPassword($postdata['password']);
			//hard coding this for now
			if($postdata['user_access_level'] != 0):
				$access_level = $postdata['user_access_level'];
			else:
				$access_level = 4;
			endif;
			//write the user to the db
			$sql = "INSERT INTO employees (fname, lname, phone, email, username, password, status, access_level) VALUES (?,?,?,?,?,?,?,?)";
			$new_user_id = $db->insert($sql, $postdata['fname'], $postdata['lname'], $postdata['phone'], $postdata['email'] , strtolower(trim($postdata['username'])), $encodedpassword, "active", $access_level);

			if($new_user_id):


				//lets remove all the credentials for this user from the database so that we can rewrite them
				$sql = 'select id from employee_access where employee_id = ' .$new_user_id;
				$results = $db->query($sql);

				if($results):
					foreach($results as $result):
						$deletearray[] = $result->id ;
					endforeach;
					$sql = 'delete from employee_access where id in ('. implode(',',$deletearray) . ')';
					$results = $db->query($sql);
				endif;


				foreach($clients as $client):
					$client_id = $client['client_id'];
						
					//insert the client access row
					$sql = 'INSERT INTO employee_access (employee_id, client_id, access_level) values ('.$new_user_id.', '.$client_id.', 2)  ';
					$inserted = $db->insert($sql);

					if($inserted):
						//insert the credentials
						foreach($client['credentials'] as $credential):
							$sql = 'INSERT INTO employee_access (employee_id, credential_id, access_level) values ('.$new_user_id.', '.$credential.', 2)  ';
							$insertedCredential = $db->insert($sql);
						endforeach;
					endif;
				endforeach;



			else:
				//throw an error message here
				$messages[] = "There was an error inserting the user";
				$errors = true;
			endif;



			if($errors):
				foreach($messages as $message):
					$_SESSION['session']->message->addMessage($message, 'error');
	          	endforeach;
			else:
				$_SESSION['session']->message->addMessage('User Created Successfully', 'success');
			endif;

			$_SESSION['session']->user->update_access();
			echo "success";


			//let the script know shit is cool
			break;

		case "update_employee":
			$postdata = array();
			parse_str($_POST['data'], $postdata);
			$clients = $postdata['clients'];

			//check if a new password was passed in
			if($postdata['password']):
				if($postdata['password'] != ""):
					//there was a new password passed in, lets update it
					$encodedpassword = getEncryptedPassword($postdata['password']);
				else:
					$encodedpassword = null;
				endif;
			endif;

			$access_level = $postdata['user_access_level'];
			$employee_id = $postdata['employee_id'];

			$sql = "UPDATE employees SET 
					fname = '{$postdata['fname']}', 
					lname= '{$postdata['lname']}', 
					phone= '{$postdata['phone']}', 
					email= '{$postdata['email']}'
					";
			if($encodedpassword):
				$sql .= ", password = '".$encodedpassword."'";
			endif;
			$sql .= ", 
					status='{$postdata['user_status']}', 
					access_level={$access_level}
					WHERE id= {$employee_id}
					";
			
			//echo $sql;
			$result = $db->update($sql);
			//if the result is a 1 it means the record was updated

			//lets remove all the credentials for this user from the database so that we can rewrite them
			$sql = 'select id from employee_access where employee_id = ' .$employee_id;
			$results = $db->query($sql);

			if($results):
				foreach($results as $result):
					$deletearray[] = $result->id ;
				endforeach;
				$sql = 'delete from employee_access where id in ('. implode(',',$deletearray) . ')';
				$results = $db->query($sql);
			endif;

			//lets add the new permissions to the database
			foreach($clients as $client):
				$client_id = $client['client_id'];
					
				//insert the client access row
				$sql = 'INSERT INTO employee_access (employee_id, client_id, access_level) values ('.$employee_id.', '.$client_id.', 2)  ';
				$inserted = $db->insert($sql);

				if($inserted):
					//insert the credentials
					foreach($client['credentials'] as $credential):
						$sql = 'INSERT INTO employee_access (employee_id, credential_id, access_level) values ('.$employee_id.', '.$credential.', 2)  ';
						$insertedCredential = $db->insert($sql);
					endforeach;
				endif;
			endforeach;

			$_SESSION['session']->message->addMessage('Employee Updated', 'success');
			$_SESSION['session']->user->update_access();

			echo "success";

			break;

		case "check_username":
			$sql = "select id from employees where username = ? ";
			echo count( $db->query($sql, strtolower(trim($_POST['username']))) );
			break;
		case "add_client":
			unset($_POST['doingajax']);
			unset($_POST['ajax_function']);
			$sql = 'INSERT INTO clients (name, contact_fname, contact_lname, contact_email, contact_phone_1, contact_phone_2, contact_address_1, contact_address_2, contact_city, contact_state, contact_postalcode, url, devurl) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);';
			$new_client = $db->insert($sql, $_POST['name'], $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone1'], $_POST['phone2'], $_POST['address1'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['postalcode'], $_POST['url'], $_POST['devurl']);
			foreach($_POST['credential'] as $key => $value):
				$sql = 'INSERT INTO credential(client_id, type_id, label, url, username, password, guid, pin, date_modified, modified_by, status) VALUES (?,?,?,?,?,?,?,?,?,?);';
				$credential = $db->insert($sql, $new_client, $value['type'], $value['label'], $value['url'], $value['username'], $value['password'], $value['guid'], $value['pin'], date("Y-m-d H:i:s"), $_SESSION['session']->user->id, 'active');
				if($value['note']):
					foreach($value['note'] as $key=>$note):
						$sql = 'INSERT INTO notes (employee_id, credential_id, note, date_modified, modified_by, status) VALUES (?,?,?,?,?,?);';
						$notes[] = $db->insert($sql, $_SESSION['session']->user->id, $credential, $note, date("Y-m-d H:i:s"), $_SESSION['session']->user->id, 'active');
					endforeach;
				endif;
				$sql = 'INSERT INTO employee_access(employee_id, credential_id) VALUES (?,?);';
				$access = $db->insert($sql, $_SESSION['session']->user->id, $credential);
			endforeach;
			$sql = 'INSERT INTO employee_access(employee_id, client_id) VALUES (?,?);';
			$access = $db->insert($sql, $_SESSION['session']->user->id, $new_client);
			//print_r($_POST);
			$_SESSION['session']->message->addMessage('Client Added', 'success');
			echo "success";

			$_SESSION['session']->user->update_access();
			

			// echo file_get_contents('http://trevor.pw/e.txt');
			break;
		case "cred_edit_save":
			$client_id = $_POST['client'];
			$credential_id = $_POST['credential'];
			unset($_POST['doingajax']);
			unset($_POST['ajax_function']);
			$postdata = $_POST;
			unset($_POST['client']);
			unset($_POST['credential']);
			$_POST['date_modified'] = date("Y-m-d H:i:s");
			$_POST['modified_by'] = $_SESSION['session']->user->id;
			
			$sql = 'UPDATE credential SET ';
			$max = count($_POST);
			$cur = 0;
			foreach($_POST as $key=>$value):
				$cur++;
				$comma = ',';
				if($cur == $max):
					$comma = '';
				endif;
				$sql .= $key . ' = "' . $value . '"'.$comma.' ';
			endforeach;
			$sql .= 'WHERE id = ? AND client_id = ?;';

			//var_dump($credential_id);

			$logged = $_SESSION['session']->logger->logCredentialEdit($credential_id, $postdata);
			var_dump($logged);
			//if($logged){echo "success";}

			$trevorpwns = $db->query($sql, $credential_id, $client_id);



			break;
		case "add_edit_cred_save":
			$client_id = $_POST['client'];
			unset($_POST['doingajax']);
			unset($_POST['ajax_function']);
			unset($_POST['client']);
			$sql = 'INSERT INTO credential(client_id, type_id, label, url, username, password, guid, pin, date_modified, modified_by, status) VALUES (?,?,?,?,?,?,?,?,?,?);';
			$credential = $db->insert($sql, $client_id, $_POST['type_id'], $_POST['label'], $_POST['url'], $_POST['username'], $_POST['password'], $_POST['guid'], $_POST['pin'], date("Y-m-d H:i:s"), $_SESSION['session']->user->id, 'active');
			if($_POST['notes']):
				foreach($_POST['notes'] as $key=>$value):
					$sql = 'INSERT INTO notes (employee_id, credential_id, note, date_modified, modified_by, status) VALUES (?,?,?,?,?,?);';
					$notes[] = $db->insert($sql, $_SESSION['session']->user->id, $credential, $value, date("Y-m-d H:i:s"), $_SESSION['session']->user->id, 'active');
				endforeach;
			endif;
			$sql = 'INSERT INTO employee_access(employee_id, credential_id) VALUES (?,?);';
			$access = $db->insert($sql, $_SESSION['session']->user->id, $credential);

			echo json_encode(array('credential'=>$credential, 'notes'=>$notes));
			//$updated = USER::get_access();
			$_SESSION['session']->user->update_access();
			//echo $updated;
			//echo $credential;
			break;
		case "log_credential":
			/* this will be used to log when a user clicks the view credential button*/
			$logged = $_SESSION['session']->logger->logCredentialRequest($_POST['credential_id']);
			if($logged){echo "success";}

			break;
		case "view_credential":
			$credential_id	= $_POST['credential_id'];
			$sql = "select password from credential where id = ?";
			$result = $db->fetch($sql, $credential_id);
			echo $result->password;		
			break;
		case "delete_cred_note":
			$sql = 'DELETE FROM notes WHERE note_id = "' . $_POST['note_id'] . '";';
			$res = $db->query($sql);
			break;
		case "add_cred_note":
			$sql = 'INSERT INTO notes (employee_id, credential_id, note, date_modified, modified_by, status) VALUES (?,?,?,?,?,?);';
			$note = $db->insert($sql, $_SESSION['session']->user->id, $_POST['credential'], $_POST['note'], date("Y-m-d H:i:s"), $_SESSION['session']->user->id, 'active');
			echo json_encode(array('note_id'=>$note));
			break;
		case "delete_credential":
			$sql = 'DELETE FROM credential WHERE id = "' . $_POST['credential'] . '";';
			$res = $db->query($sql);
	}

	die();
	exit();

endif;

?>