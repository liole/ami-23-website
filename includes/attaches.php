<?php
	include_once 'template.php';
	function attachPoll ($id)
	{
		global $db;
		$output = '';
		$users_data = array();
		$sql_users = "SELECT login, firstname, surname FROM users";
		if(!$result_users = $db->query($sql_users)) die('There was an error running the query [' . $db->error . ']');
		while ($row_users = $result_users->fetch_assoc())
			$users_data[$row_users["login"]] = $row_users["surname"].' '.$row_users["firstname"];
		$sql = "SELECT * FROM polls WHERE id=$id";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
			$answers = json_decode ($row["answers"]);
			$ans_data = json_decode ($row["data"]);
			$voted = false;
			foreach ($ans_data as $user_arr)
				foreach ($user_arr as $p_login)
					if ($p_login == $_COOKIE["user"]) {
						$voted = true;
						break;
					}
			$output .= '<div class="poll_card pollAttached'.(($voted)?' poll_answered':'').'">
			<div class="poll_question"><div class="poll_ID">ID '.$row["id"].'</div>'.$row["question"].'</div>';
			$max_ans = 0;
			$sum_ans = 0;
			foreach ($ans_data as $data)
			{
				$count_data = count($data);
				$sum_ans += $count_data;
				if ($count_data > $max_ans)
					$max_ans = $count_data;
			}
			foreach ($answers as $id => $value)
			{
				$count_ans = count($ans_data[$id]);
				$users_ans = '';
				$my_vote = '';
				foreach ($ans_data[$id] as $user_login)
				{
					$users_ans .= $users_data[$user_login].'; ';
					if ($user_login == $_COOKIE["user"]) $my_vote = ' answered';
				}
				$width = 0;
				$percent = 0;
				if ($sum_ans != 0) {
					$width = floor($count_ans*100/$max_ans);
					$percent = floor($count_ans*100/$sum_ans);
				}
					
				$output .= '
		<div class="poll_answer">
			<div class="answer_text'.$my_vote.'" '.(($voted)?'':'onClick="location=\'polls.php?poll='.$row["id"].'&answer='.$id.'\';"').'>'.$value.'</div>
			<div class="answer_num" onClick="alert(\''.$users_ans.'\')">'.$count_ans.'</div>
			<div class="answer_diagram"><div class="diagram_line" style="width: '.$width.'%;">'.$percent.'%</div></div>
		</div>';
			}
			$output .= '</div>';
		return $output;
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
		global $db;
		$output = '';
		$sql = "SELECT * FROM files WHERE id=$id";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		$output = includeFileByName ($row["name"], $row["id"], true);
		return $output;
	}
?>