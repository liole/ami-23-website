<?php
	include_once 'template.php';
		
	$users_data = array();
	$ext_users_data = array();
	$sql_users = "SELECT login, firstname, surname FROM users";
	if(!$result_users = $db->query($sql_users)) die('There was an error running the query [' . $db->error . ']');
	while ($row_users = $result_users->fetch_assoc())
	{
		$users_data[$row_users["login"]] = $row_users["firstname"].' '.$row_users["surname"];
		$ext_users_data[$row_users["login"]]["name"] = $users_data[$row_users["login"]];
		$avatar = $row_users["login"].'.jpg';
		if (!file_exists('images/avatars/'.$avatar)) $avatar = 'noAv.png';
		$ext_users_data[$row_users["login"]]["avatar"] = $avatar;
	}
	
?>