<?php
	include 'template.php';

	if (isset($_GET["new"]))
	{
		$sql = '';
		if ($_GET["new"] == "message")
		{
			/*
				file -> json | number
				photos -> json
				poll, album -> number
			*/
			if ($_POST["attach_ID"] == '' ||
				($_POST["attach_type"] == 'file' && (!is_numeric($_POST["attach_ID"]) && json_decode($_POST["attach_ID"]) == null)) ||
				($_POST["attach_type"] == 'photos' && json_decode($_POST["attach_ID"]) == null) ||
				(($_POST["attach_type"] == 'poll' || $_POST["attach_type"] == 'album') && !is_numeric($_POST["attach_ID"]))
				)
				$_POST["attach_type"] = 'none';
			//TODO: check if object exists.
			//NOW: same visual resul, but not-valid data stored in db
			$sql = "INSERT INTO messages (user,text,date,attach,attach_id) VALUES (
				'".$_COOKIE["user"]."', 
				'".str_replace("'", "''", $_POST["text"])."', 
				'".date("Y-m-d H:i:s")."', 
				'".$_POST["attach_type"]."', 
				'".$_POST["attach_ID"]."')";
		}
		if ($_GET["new"] == "reply")
		{
			$sql = "INSERT INTO discuss (msg_id,user,text,date) VALUES (
				".$_POST["msg_id"].", 
				'".$_COOKIE["user"]."', 
				'".str_replace("'", "''", $_POST["text"])."', 
				'".date("Y-m-d H:i:s")."')";
		}
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		else {
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit;
		}
	}
	if (isset($_GET["delete"]))
	{
		$sql = "SELECT user FROM ".$_GET["delete"]." WHERE id = ".$_GET["id"];
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		if ($row["user"] == $currUser["login"] || $currUser["type"] == 'admin')
		{
			$sql = "DELETE FROM ".$_GET["delete"]." WHERE id = ".$_GET["id"];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			if ($_GET["delete"] == 'messages') { //delete all discucss messages for this post
				$sql = "DELETE FROM discuss WHERE msg_id = ".$_GET["id"];
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			}
		}
		if ($_GET["delete"] == 'messages' && strpos($_SERVER['HTTP_REFERER'],'discuss') !== false) //not return to deleted page
			header('Location: index.php');
		else header('Location: '.$_SERVER['HTTP_REFERER']); //anyway return to previous page
		exit;
	}
	if (isset($_GET["save"]))
	{
		$sql = "SELECT * FROM ".$_GET["save"]." WHERE id = ".$_GET["id"];
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		if ($row["user"] == $currUser["login"] || $currUser["type"] == 'admin')
		{
			$sql = "UPDATE ".$_GET["save"]." SET 
				text = '".str_replace("'", "''", $_POST["text"])."'";
			if ($_GET["save"] == "messages") {
				$sql .= ", attach = '".$_POST["attach_type"]."', 
				attach_id = '".$_POST["attach_ID"]."'";
			}
			$sql .= " WHERE id = ".$_GET["id"];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		}
		$discussID = ($_GET["save"]=='messages')?$_GET["id"]:$row["msg_id"];
		header('Location: ?discuss='.$discussID); 
		exit;
	}
	
	printTop("Головна");
?>
	<?php
		include 'includes/messages.php';
		
		if (!isset($_GET["edit"]))
		{
			if (!isset($_GET["discuss"]))
			{
				?>
				<form action="?new=message" method="post" class="message">	
					<div class="msgIcon"><img src="images/avatars/<?php echo $currUser["avatar"]; ?>" alt="Я" /></div>
					<div class="attach_btn" onClick="showAttachPanel()">&nbsp;</div>
					<textarea name="text" onKeyUp="textAreaAdjust(this)" onFocus="window.textareaActive = true;" onBlur="window.textareaActive = false;" placeholder="Текст повідомлення..." class="msgFormText"></textarea>
					<input type="image" src="images/mail.png" class="msgFormSbm">
					<div id="attach_panel">
						Прикріплення: <select name="attach_type" value='none' OnChange="changeAttach(this)">
							<option value="none">Нічого</option>
							<option value="poll">Опитування</option>
							<option value="photo">Фото без альбому</option>
							<option value="album">Фотоальбом</option>
							<option value="file">Файл</option>
						</select>
						ID: <input type="text" name="attach_ID" id="attach_ID" style="width: 100px;"><span id="attach_comment"></span>
					</div>
				</form>
				<?php
				$mainDicsCount = 2; //numbers of replies to display on main page
				$per_page = 10;
				$pageRadius = 3;
				
				if (!isset($_GET["page"])) $page = 1;
				else $page = $_GET["page"];
				$sql = "SELECT count(id) as page_count FROM messages";
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				$row = $result->fetch_assoc(); $page_count = ceil($row["page_count"]/$per_page);
				
				$sql = "SELECT * FROM messages ORDER BY date DESC LIMIT ".($page-1)*$per_page.", ".$per_page;
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				while ($row = $result->fetch_assoc())
				{
					$discCount = printMessage($row);
					$sql_discuss = "SELECT * FROM discuss WHERE msg_id = ".$row["id"]." ORDER BY date DESC LIMIT 0, ".$mainDicsCount;
					if(!$result_discuss = $db->query($sql_discuss)) die('There was an error running the query [' . $db->error . ']');
					while ($row_discuss = $result_discuss->fetch_assoc())
					{
						printDiscussMesage($row_discuss);
					}
					if ($discCount != "0") echo '<div class="fade">&nbsp;</div>';
				}
				/* PAGES LIST */
				if ($page-$pageRadius < 1) $pageRadius = $pageRadius+($pageRadius-$page+1);
				if ($page+$pageRadius > $page_count) $pageRadius = $pageRadius+($pageRadius+$page-$page_count);
				echo '<div class="page_container">';
				if ($page != 1) echo '<a class="page_link" href="?page='.($page-1).'" class="page_link"><span class="page_arrow"><</span></a>';
				for ($i=1; $i<=$page_count; $i++)
				{
					if ($i == 2 && $i < ($page-$pageRadius)) echo '&nbsp; ... &nbsp;';
					if ($i == $page_count-1 && $i > ($page+$pageRadius)) echo '&nbsp; ... &nbsp;';
					if (($i >= ($page-$pageRadius) && $i <= ($page+$pageRadius)) || ($i == 1 || $i == $page_count))
						if ($i != $page) echo '<a href="?page='.$i.'" class="page_link"><span class="page_num">'.$i.'</span></a>';
						else echo '<span class="page_curr">'.$i.'</span>';
				}
				if ($page != $page_count) echo '<a href="?page='.($page+1).'" class="page_link"><span class="page_arrow">></span></a>';
				echo '</div>';
				/* END PAGES LIST */
			} 
			else 
			{
				$sql = "SELECT * FROM messages WHERE id = ".$_GET["discuss"];
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				$row = $result->fetch_assoc();
				$discCount = printMessage ($row);
			
				?>
			<form action="?new=reply" method="post" class="message repl">	
				<div class="msgIcon"><img src="images/avatars/<?php echo $currUser["avatar"]; ?>" alt="Я" /></div>
				<textarea name="text" onKeyUp="textAreaAdjust(this)" onFocus="window.textareaActive = true;" onBlur="window.textareaActive = false;" placeholder="Текст повідомлення..." class="msgFormText"></textarea>
				<input type="hidden" name="msg_id" value="<?php echo $_GET["discuss"]; ?>">
				<input type="image" src="images/mail.png" class="msgFormSbm">
			</form>
				<?php
				
				$sql = "SELECT * FROM discuss WHERE msg_id = ".$_GET["discuss"]." ORDER BY date DESC";
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				while ($row = $result->fetch_assoc())
				{
					printDiscussMesage ($row);
				}
			}
		} else {
			$sql = "SELECT * FROM ".$_GET["edit"]." WHERE id = ".$_GET["id"];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			$row = $result->fetch_assoc();
			?>
			<form action="<?php echo '?save='.$_GET["edit"].'&id='.$_GET["id"]; ?>" method="post" class="message">	
				<div class="msgIcon"><img src="images/avatars/<?php echo $currUser["avatar"]; ?>" alt="Я" /></div>
				<textarea name="text" onKeyUp="textAreaAdjust(this)" onFocus="textAreaAdjust(this)" placeholder="Текст повідомлення..." class="msgFormText"><?php echo $row["text"]; ?></textarea>
				<input type="image" src="images/mail.png" class="msgFormSbm">
				<?php if ($_GET["edit"] == 'messages') { ?>
				<div id="attach_panel" style="display: block;">
					Прикріплення: <select name="attach_type" id="attach_type" value='<?php echo $row["attach"]; ?>' OnChange="changeAttach(this)">
						<option value="none" <?php echo ($row["attach"]=='none')?'selected':''; ?>>Нічого</option>
						<option value="poll" <?php echo ($row["attach"]=='file')?'selected':''; ?>>Опитування</option>
						<option value="photo" <?php echo ($row["attach"]=='photo')?'selected':''; ?>>Фото без альбому</option>
						<option value="album" <?php echo ($row["attach"]=='album')?'selected':''; ?>>Фотоальбом</option>
						<option value="file" <?php echo ($row["attach"]=='poll')?'selected':''; ?>>Файл</option>
					</select>
					ID: <input type="text" name="attach_ID" id="attach_ID" style="width: 100px;" value="<?php echo $row["attach_id"]; ?>"><span id="attach_comment"></span><script type="text/javascript">changeAttach(document.getElementById('attach_type'));</script>
				</div>
				<?php } ?>
			</form>
			<?php
		}
	?>
<!-- NEEDS REWRITE -->
<script type="text/javascript">
	window.lastMsgTime = <?php include 'lastMsgTime.php'; ?>; 
	window.isActive = <?php echo ((isset($_GET["wasActive"]))?$_GET["wasActive"]:'true'); ?>;
	<?php if (!isset($_GET["discuss"])) echo "setInterval(function(){checkNewMsg()}, 10000);"; ?>
	<?php if (isset($_GET["newMsg"])) {
		echo "var notify = new Audio('images/notify.wav'); notify.play();\n";
		echo "window.history.pushState('', 'New message', '/');";
		if ($_GET["wasActive"] == 'false') {
			echo "changeIcon('new.ico');
			document.title = 'Нові повідомлення - ПМІ-23';";
		}
	}
	?>
</script>
<?php printBottom(); ?>