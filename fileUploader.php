<?php
	include 'template.php';
	$newID = 0;
	if (isset($_POST["submit_file"]) && $_FILES["file"]["name"] != '')
	{
		if ($_FILES['file']['tmp_name'] != "") {
			$counter = 1 + file_get_contents ('files/counter');
			file_put_contents ('files/counter', $counter);
			$newfile = 'files/'.$counter.'_'.$_FILES["file"]["name"];
			move_uploaded_file($_FILES['file']['tmp_name'], $newfile);
			$sql = "INSERT INTO files (name) VALUES (
				'".$counter.'_'.$_FILES["file"]["name"]."')";
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			else {
				$newID = $db->insert_id;
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
	<?php if ($newID != 0) { ?>
	<script type="text/javascript">
		function isNumber(n) {
			return !isNaN(parseFloat(n)) && isFinite(n);
		}
		if (window.name = 'embed_upload')
		{
			var currValue = opener.document.getElementById ('attach_ID').value;
			var result = '';
			if (currValue == '') 
				result = <?php echo $newID; ?>;
			else {
				var arr = [];
				if (isNumber(currValue))
					arr = [parseInt(currValue)];
				else try { arr = JSON.parse(currValue); }
					catch(e) { arr = []; }
				var newArr = arr.concat([<?php echo $newID; ?>]);
				result = JSON.stringify (newArr);
			}
			opener.document.getElementById ('attach_ID').value = result;
		}
	</script>
	<?php } ?>
	<h3 style="text-align: center;"><?php echo ($newID == 0)?'Завантажити файл':'Файл успішно завантажено ID '.$newID;?></h3>
	<form action="fileUploader.php" method="post" enctype="multipart/form-data">
	до 8 Мб, назва файлу лише латиницею<br>
	<label for="file">Файл:</label> <input type="file" name="file" id="file"><br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="submit_file" type="submit" value="         ЗАВАНТАЖИТИ          ">
	</form>
	</body>
</html>