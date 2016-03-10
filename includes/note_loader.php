<?php
require_once("settings.php");
require_once("../classes/db.php");	// load the db class
$db = new db();


if(isset($_GET['credential']) && isset($_GET['cred_edit_number'])):

echo '<i class="fa fa-comments cred_notes" href="#crednotes_' . $_GET['credential'] . '" style="cursor:pointer;"></i>';

echo '<div id="crednotes_' . $_GET['credential'] . '" style="width: 500px; height: 500px; display:none;">';

echo '<textarea id="edit_new_comment-' . $_GET['cred_edit_number'] . '" placeholder="Note" class="form-control"></textarea>';


echo '<div style="margin-top: 4px; text-align: right;">';


echo '<a class="btn btn-primary btn-sm add_edit_note" target="' . $_GET['cred_edit_number'] . '" credential-id="' . $_GET['credential'] . '">Add New Note</a>';

echo '</div>';
if( is_array($_GET['notes']) ):
	foreach($_GET['notes'] as $key=>$note):
		$sql = 'SELECT * FROM notes WHERE note_id = "' . $note . '" AND credential_id = "' . $_GET['credential'] .'";';
		$res = $db->fetch($sql);
		echo '<div class="note" style="margin-bottom:20px;" id="note_' . $res->note_id . '">';
		echo '<div class="note-author">';
		$esql = 'SELECT * FROM employees WHERE id = "' . $res->employee_id . '";';
		$eres = $db->fetch($esql);
		echo ucwords($eres->fname . ' ' . $eres->lname);
		echo '</div>';
		echo '<div class="note-content" style="border:1px solid black;padding:10px; border-radius:5px;">';
		echo $res->note;
		echo '<button type="button" class="delete_cred_note" note="' . $res->note_id . '" style="float: right;">&times;</button>';
		echo '</div>';
		echo '</div>';
	endforeach;
endif;
echo '</div>';

endif;
?>