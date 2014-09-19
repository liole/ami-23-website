<?php

	$db = new mysqli('localhost', 'root', '', 'ami'); 
	if($db->connect_errno > 0)
		die('Unable to connect to database [' . $db->connect_error . ']');
	$db->set_charset('UTF8');
	
?>