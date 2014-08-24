<?php
	include_once 'template.php';
	function attachPoll ($id)
	{
		include_once 'includes/polls.php';
		global $db;
		$sql = "SELECT * FROM polls WHERE id=$id";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		return includePoll ($row, true);
	}
	function attachAlbum ($id)
	{
		global $db;
		$output = '';
		$max_first_line = 3;
		$sql = "SELECT count(id) as count_album FROM photos WHERE album = $id";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc(); $count = $row["count_album"];
		$first_line = $max_first_line;
		if ($count < $max_first_line) $first_line = $count;
		$leaved_images = $count-$max_first_line;
		if ($leaved_images < $max_first_line+1) $leaved_images = $max_first_line+1;
		$width = floor (568.0/($leaved_images)-6);
		$maxHeight = floor($width*3/4);
		$sql = "SELECT * FROM photos WHERE album = $id ORDER BY id DESC";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$output .= '<div style="text-align: right;">';
		for ($i = 0; $i < $first_line; ++$i)
		{
			$row = $result->fetch_assoc();
			$output .= '<div class="included_photo"><a title="'.$row["title"].'" class="fancybox" href="photos/'.$row["file_id"].'.jpg" rel="album'.$row["album"].'"><img src="photos/thumb/'.$row["file_id"].'.jpg"></a></div>';
		}
		while ($row = $result->fetch_assoc())
		{
			$output .= '<div class="included_photo" style="width: '.$width.'px; max-height: '.$maxHeight.'px"><a title="'.$row["title"].'" class="fancybox" href="photos/'.$row["file_id"].'.jpg" rel="album'.$row["album"].'"><img src="photos/thumb/'.$row["file_id"].'.jpg"></a></div>';
		}
		$output .= '</div>';
		return $output;
	}
	function attachPhotos ($ids)
	{
		global $db;
		$output = '';
		$max_first_line = 3;
		$arrIDs = json_decode ($ids);
		$count = count($arrIDs);
		$first_line = $max_first_line;
		if ($count < $max_first_line) $first_line = $count;
		$leaved_images = $count-$max_first_line;
		if ($leaved_images < $max_first_line+1) $leaved_images = $max_first_line+1;
		$width = floor (568.0/($leaved_images)-6);
		$maxHeight = floor($width*3/4);
		$output .= '<div style="text-align: right;">';
		for ($i = 0; $i < $first_line; ++$i)
		{
			$sql = "SELECT * FROM photos WHERE id = ".$arrIDs[$i];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			$row = $result->fetch_assoc();
			$output .= '<div class="included_photo"><a title="'.$row["title"].'" class="fancybox" href="photos/'.$row["file_id"].'.jpg" rel="album'.$row["album"].'"><img src="photos/thumb/'.$row["file_id"].'.jpg"></a></div>';
		}
		for ($i = $first_line; $i < $count; ++$i)
		{
			$sql = "SELECT * FROM photos WHERE id = ".$arrIDs[$i];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			$row = $result->fetch_assoc();
			$output .= '<div class="included_photo" style="width: '.$width.'px; max-height: '.$maxHeight.'px"><a title="'.$row["title"].'" class="fancybox" href="photos/'.$row["file_id"].'.jpg" rel="album'.$row["album"].'"><img src="photos/thumb/'.$row["file_id"].'.jpg"></a></div>';
		}
		$output .= '</div>';
		return $output;
	}
	function attachFile ($id)
	{
		include_once 'includes/files.php';
		global $db;
		$output = '';
		$sql = "SELECT * FROM files WHERE id=$id";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		$output = includeFileByName ($row["name"], $row["id"], true);
		return $output;
	}
?>