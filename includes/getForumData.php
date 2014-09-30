<?php
	header('Content-Type: application/json');
	include_once __DIR__.'/../template.php';
	
	if (isset($_GET["topics"]))
	{
		$topics = array();
		$sql = "SELECT * FROM forum_topics";
		if(!$result = $db->query($sql)) die('{ "error" : "' . $db->error . '" }');
		while ($row = $result->fetch_assoc())
		{
			$sql2 = "SELECT count(topic_id) as post_count FROM forum_messages WHERE topic_id = ".$row["id"];
			if(!$result2 = $db->query($sql2)) die('{ "error" : "' . $db->error . '" }');
			$post_count = $result2->fetch_assoc()["post_count"];
			$sql2 = "SELECT author, date FROM forum_messages WHERE topic_id = ".$row["id"]." ORDER BY date DESC LIMIT 0,1";
			if(!$result2 = $db->query($sql2)) die('{ "error" : "' . $db->error . '" }');
			$last_info = $result2->fetch_assoc();
			$topics[] = array (
				"id" => $row["id"],
				"title" => $row["title"], 
				"description" => $row["description"], 
				"posts" => $post_count, 
				"last" => array(
					"author" => $last_info["author"],
					"date" => date("d.m.Y H:i",strtotime($last_info["date"]))
				)
			);
		}
		echo json_encode ($topics);
	}
	if (isset($_GET["messages"]))
	{
		$messages = array();
		if (!isset($_GET["from"])) $_GET["from"] = 0;
		if (!isset($_GET["limit"])) $_GET["limit"] = 10;
		$sql = "SELECT * FROM forum_messages WHERE topic_id = ".$_GET["messages"]." ORDER BY date DESC LIMIT ".$_GET["from"].", ".$_GET["limit"];
		if(!$result = $db->query($sql)) die('{ "error" : "' . $db->error . '" }');
		while ($row = $result->fetch_assoc())
		{
			$messages[] = array (
				"author" => $row["author"],
				"date" => date("d.m.Y H:i",strtotime($row["date"])),
				"text" => $row["text"]
			);
		}
		echo json_encode ($messages);
	}
?>