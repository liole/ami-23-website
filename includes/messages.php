<?php
	include_once 'template.php';
	include 'includes/attaches.php';
	include_once 'includes/userNames.php';
		
	function replace_links ($source)
	{
		$pattern = '/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i';
		$replacement = '<a href="$1" target="_blank">$1</a>';
		return preg_replace($pattern, $replacement, $source);
	}
	
	function printMessage($row)
	{
		global $users_data, $currUser, $db;
		$avatar = $row["user"].'.jpg';
		if (!file_exists('images/avatars/'.$avatar)) $avatar = 'noAv.png';
		$sql_discCount = "SELECT count(*) as discCount  FROM discuss WHERE msg_id = '".$row["id"]."'";
		if(!$result_discCount = $db->query($sql_discCount)) die('There was an error running the query [' . $db->error . ']');
		$row_count = $result_discCount->fetch_assoc();
		$discCount = $row_count["discCount"];
		echo '
		<div class="message">
			<div class="msgIcon"><img src="images/avatars/'.$avatar.'" alt="'.$users_data[$row["user"]].'" /></div>
			<div class="msgInfo">
				'.date("d.m.Y H:i",strtotime($row["date"])).' | '.$discCount.'<img src="images/discuss.png" alt="discuss" onClick="location = \'?discuss='.$row["id"].'\';"/>
			</div>
			<div class="msgName">'.$users_data[$row["user"]].(($currUser["login"] == $row["user"] /*|| $currUser["type"] == 'admin'*/)?'<img src="images/delete.png" class="panelBtn" title="Видалити" onClick="if (confirm(\'Ви дійсно хочете видалити цей елемент та всі дописи до нього?\')) location = \'?delete=messages&id='.$row["id"].'\';"><img src="images/edit.png" class="panelBtn" title="Редагувати" onClick="location = \'?edit=messages&id='.$row["id"].'\';">':'').'</div>
			<div class="msgText">'.nl2br(replace_links($row["text"])).'</div>';
			if ($row["attach"] == 'poll')
				echo attachPoll($row["attach_id"]);
			if ($row["attach"] == 'album')
				echo attachAlbum($row["attach_id"]);
			if ($row["attach"] == 'photo')
				echo attachPhotos($row["attach_id"]);
			if ($row["attach"] == 'file')
				echo attachFile($row["attach_id"]);
		echo '</div>';
		return $discCount;
	}
	function printDiscussMesage ($row_discuss)
	{
		global $users_data, $currUser;
		$avatar = $row_discuss["user"].'.jpg';
		if (!file_exists('images/avatars/'.$avatar)) $avatar = 'noAv.png';
		echo '
	<div class="message repl">
		<div class="msgIcon"><img src="images/avatars/'.$avatar.'" alt="'.$users_data[$row_discuss["user"]].'" /></div>
		<div class="msgInfo">
			'.date("d.m.Y H:i",strtotime($row_discuss["date"])).'
		</div>
		<div class="msgName">'.$users_data[$row_discuss["user"]].(($currUser["login"] == $row_discuss["user"] /*|| $currUser["type"] == 'admin'*/)?'<img src="images/delete.png" class="panelBtn" title="Видалити" onClick="if (confirm(\'Ви дійсно хочете видалити цей елемент?\')) location = \'?delete=discuss&id='.$row_discuss["id"].'\';"><img src="images/edit.png" class="panelBtn" title="Редагувати" onClick="location = \'?edit=discuss&id='.$row_discuss["id"].'\';">':'').'</div>
		<div class="msgText">'.nl2br(replace_links($row_discuss["text"])).'</div>
	</div>';
	}
?>