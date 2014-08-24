<?php
	include_once 'template.php';
	$sql = "SELECT date FROM messages ORDER BY date DESC LIMIT 0,1";
	if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	$row = $result->fetch_assoc();
	$lastMsgTime = strtotime($row["date"]);
	$sql = "SELECT date FROM discuss ORDER BY date DESC LIMIT 0,1";
	if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	$row = $result->fetch_assoc();
	if (strtotime($row["date"]) > $lastMsgTime)
	$lastMsgTime = strtotime($row["date"]);
	if (!isset($_GET["clear"]))
		echo $lastMsgTime;
?>