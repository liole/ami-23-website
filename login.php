<?php
	include 'template.php';
	$msg = '';
	if (isset($_GET["logout"]))
	{
		$msg = '<div class="login_msg">Здійснено вихід</div>';
		setcookie("user", "", time()-60*60*24);  // delete
		setcookie("first_alert", "", time()-60*60*24);  // delete
		unset ($_COOKIE["user"]);
	}
	if (isset($_POST["login"]) && $_POST["login"] != '')
	{
		$sql = "SELECT login, password FROM users WHERE login = '".$_POST["login"]."' || email = '".$_POST["login"]."' || skype = '".$_POST["login"]."' || phone = '".$_POST["login"]."'";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		if ($row = $result->fetch_assoc())
		{
			if ($row["password"] == $_POST["password"])
			{
				setcookie("user", $row["login"], time()+60*60*24*30*3);  // 3 month
				header ('Location: index.php');
			} else $msg = '<div class="login_msg">Неправильний пароль</div>';
		} else $msg = '<div class="login_msg">Неправильне ім\'я користувача</div>';
	}
	printTop("Вхід");
?>
	<div class="login">
		<h1>ВХІД</h1>
		<?php echo $msg; ?>
		<form action="login.php" method="post">
			<input type="text" name="login" class="login_input" placeholder="Логін...">
			<input type="password" name="password" class="login_input" placeholder="Пароль...">
			<input type="submit" value="УВІЙТИ" class="login_submit">
		</form>
	</div>
<?php printBottom(); ?>