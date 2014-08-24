<?php
	include 'template.php';
	
	printTop("Файли");
?>
	Через фізичні обмеження безкоштовного сервера файли можна завантажувати тільки розміром <b>до 8 Мб</b>. Для більших файлів використовуйте напр. Google Drive<br>
	<div class="new_album" onClick="openFileUploader()" id="upload_btn">ЗАВАНТАЖИТИ ФАЙЛ</div><br>
	<?php
		include 'includes/files.php';
		$sql = "SELECT * FROM files ORDER BY id DESC";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		while ($row = $result->fetch_assoc())
		{
			echo includeFileByName ($row["name"], $row["id"]);
		}
	?>
	
<?php printBottom(); ?>