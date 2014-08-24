<?php
	include 'template.php';
	$newID = 0;
	$newIDs = array();
	if (isset($_POST["submit_image"]) && $_FILES["image"]["name"] != '')
	{
		include('images/SimpleImage.php'); 
		function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}
		for($i=0; $i<count($_FILES['image']['name']); $i++) if ($_FILES['image']['tmp_name'][$i] != "") {
			$file_id = generateRandomString();
			$newfile = 'photos/'.$file_id.'.jpg';
			$thumbfile = 'photos/thumb/'.$file_id.'.jpg';
			$maxSize = 1000; $thumbSize = 200;
			move_uploaded_file($_FILES['image']['tmp_name'][$i], $newfile);
			$image = new SimpleImage(); 
			$image->load($newfile);
			if (!isset($_POST["fullsize"]))
			{
				if ($image->getWidth() > $maxSize || $image->getHeight() > $maxSize)
				{
					if ($image->getWidth() > $image->getHeight())
						$image->resizeToWidth($maxSize); 
					else $image->resizeToHeight($maxSize); 
				}
				$image->save($newfile);
			}
			$image->resizeToWidth($thumbSize);
			$image->save($thumbfile);
			$sql = "INSERT INTO photos (album, title, file_id) VALUES (
				".$_POST["album"].", '".$_POST["title"]."', '$file_id')";
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			else {
				$newID = $db->insert_id;
				$newIDs[] = $newID;
			}
		}
	}
?><!DOCTYPE HTML>
<html>
	<head>
		<title>Завантаження - ПМІ-23</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="fonts.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="script.js"></script>
	</head>
	<body style="background-color: #9f9">
	<script type="text/javascript">
		if (window.name = 'embed_upload')
		{
			var arr = JSON.parse(opener.document.getElementById ('attach_ID').value);
			newArr = arr.concat(<?php echo json_encode($newIDs); ?>);
			opener.document.getElementById ('attach_ID').value = JSON.stringify (newArr);
		}
	</script>
	<h3 style="text-align: center;"><?php echo ($newID == 0)?'Завантажити фото':'Фото успішно завантажено.';?></h3>
	<form action="uploader.php" method="post" enctype="multipart/form-data">
	<label for="image">Файл(и):</label> <input type="file" name="image[]" id="image" multiple><br>
	<label for="fillsize">Зберегти розмір (для фото тексту)</label><input type="checkbox" name="fullsize" id="fillsize"><br>
	<label for="title">Підпис: </label><input type="text" name="title" id="title"><br>
	<label for="album">Фотоальбом: </label><select name="album" id="album" value="0">
	<?php
		echo '<option value="0">Несортовано</option>';
		$sql = "SELECT * FROM albums WHERE id <> 0 ORDER BY id DESC";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		while ($row = $result->fetch_assoc())
			echo '<option value="'.$row["id"].'">'.$row["title"].'</option>';
	?>
	</select><br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="submit_image" type="submit" value="         ЗАВАНТАЖИТИ          ">
	</form>
	</body>
</html>