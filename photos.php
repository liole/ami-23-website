<?php
	include 'template.php';

	$newID = 0;
	if (isset($_GET["newID"])) $newID = $_GET["newID"];
	if (isset($_GET["new_album"]))
	{
		$sql = "INSERT INTO albums (title) VALUES (
			'".$_GET["new_album"]."')";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		else {
			$newID = $db->insert_id;
			header('Location: photos.php?newID='.$newID);
			exit;
		}
	}
	
	printTop("Фотогалерея");
?>
	<?php
		if (!isset($_GET["album"]))
		{
			if ($newID != 0) echo '<div class="poll_newID">Новий альбом створено. Його ID: <b>'.$newID.'</b></div>';
			echo '<div class="new_album" onClick="createAlbum()">СТВОРИТИ АЛЬБОМ</div><div class="new_album" onClick="openUploader()" id="upload_btn">ЗАВАНТАЖИТИ ФОТО</div><br>';
			echo '<div class="album_cover" onClick="location=\'?album=0\';"><img src="images/many_photos.jpg">Несортовано</div>';
			$sql = "SELECT * FROM albums WHERE id <> 0 ORDER BY id DESC";
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			while ($row = $result->fetch_assoc())
			{
				$sql_ph = "SELECT * FROM photos WHERE album = ".$row["id"]." ORDER BY id DESC LIMIT 0, 1";
				if(!$result_ph = $db->query($sql_ph)) die('There was an error running the query [' . $db->error . ']');
				echo '<div class="album_cover" onClick="location=\'?album='.$row["id"].'\';"><img src="';
				if ($row_ph = $result_ph->fetch_assoc())
					echo 'photos/thumb/'.$row_ph["file_id"].'.jpg';
				else echo 'images/default-no-image.png';
				echo '">'.$row["title"].'</div>';
			}
		} else {
			$sql_alb = "SELECT * FROM albums WHERE id=".$_GET["album"];
			if(!$result_alb = $db->query($sql_alb)) die('There was an error running the query [' . $db->error . ']');
			if ($row_alb = $result_alb->fetch_assoc())
			{
				echo '<div style="float:right;">ID: '.$row_alb["id"].'</div><h2>'.$row_alb["title"].'</h2>';
				$sql = "SELECT * FROM photos WHERE album = ".$_GET["album"]." ORDER BY id DESC";
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				while ($row = $result->fetch_assoc())
				{
					echo '<div class="album_cover"><a title="'.$row["title"].'" class="fancybox" href="photos/'.$row["file_id"].'.jpg" rel="album'.$row["album"].'"><img src="photos/thumb/'.$row["file_id"].'.jpg"></a></div>';
				}
			}
		}
	?>
<?php printBottom(); ?>