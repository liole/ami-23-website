<?php
	include 'template.php';

	$newID = 0;
	if (isset($_GET["newID"])) $newID = $_GET["newID"];
	if (isset($_POST["create_poll"]))
	{
		$emptys = array();
		foreach ($_POST["answer"] as $value)
			$emptys[] = array();
		
		$sql = "INSERT INTO polls (question,answers,data) VALUES (
			'".$_POST["question"]."', '".json_encode($_POST["answer"])."', '".json_encode($emptys)."')";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		else {
			$newID = $db->insert_id;
			header('Location: polls.php?newID='.$newID);
			exit;
		}
	}
	
	if (isset($_GET["answer"]))
	{
		$sql = "SELECT * FROM polls WHERE id=".$_GET["poll"];
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		$data = json_decode ($row["data"]);
		$voted = false;
		foreach ($data as $user_arr)
			foreach ($user_arr as $p_login)
				if ($p_login == $_COOKIE["user"]) {
					$voted = true;
					break;
				}
		if (!$voted)
		{
			$data[$_GET["answer"]][] = $_COOKIE["user"];
			$sql = "UPDATE polls SET data='".json_encode($data)."' WHERE id=".$_GET["poll"];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit;
		}
	}
	printTop("Опитування");
?>
	<?php
		
		include 'includes/polls.php';
		
		if (!isset($_GET["create"]))
		{
			echo '<div class="new_poll_btn" onClick="location = \'?create\'">СТВОРИТИ ОПИТУВАННЯ</div><br> Голосувати в кожному опитування можна лише раз. Щоб дізнатися хто проголосував за якусь відповідь нитисніть на кількість після тексту відповіді. При наведення на опитування справа зверху відоюражається його ID.<br><br>';
			if ($newID != 0) echo '<div class="poll_newID">Нове опитування створене. Його ID: <b>'.$newID.'</b></div>';
			
			$sql = "SELECT * FROM polls ORDER BY id DESC";
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			while ($row = $result->fetch_assoc())
			{
				echo includePoll ($row);
			}
		} else {
			?>
		<h2>Створити опитування</h2>	
		<form action="polls.php" method="post">
			<input type="text" class="create_poll_title" name="question" placeholder="Ваше запитання"><br>
			&nbsp;&nbsp;&nbsp;Відповіді:<br><input type="text" name="answer[]" class="create_poll_ans" placeholder="Введіть текст відововіді...">
			<div class="create_poll_add" onClick="addAns(this)">+</div>
			<input type="submit" value="СТВОРИТИ" class="create_poll_sbm" name="create_poll">
		</form>
			<?php
		}
	?>
<?php printBottom(); ?>