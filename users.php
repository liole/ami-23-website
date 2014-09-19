<?php
	include 'template.php';
	
	$message = '';
	if (isset($_POST["surname"]))
	{
		$password = $currUser["password"];
		if ($_POST["password"] != '')
			$password = password_hash ($_POST["password"], PASSWORD_BCRYPT);
		$birthday = '0000-00-00';
		if ($_POST["birthday"] != '')
			$birthday = date ("Y-m-d", strtotime($_POST["birthday"]));
		if ($_POST["vk_id"] != '')
		{
			$request = 'http://api.vkontakte.ru/method/users.get?uids='.$_POST["vk_id"].'&fields=photo_50';
			$response = file_get_contents($request);
			$info = array_shift(json_decode($response)->response);
			$vk_avatar = $info->photo_50;
			$newfile = 'images/avatars/'.$_COOKIE["user"].'.jpg';
			if (file_exists($newfile)) unlink($newfile);
			copy($vk_avatar, $newfile);
		}
		if ($_FILES["avatar_file"]["name"] != '') {
			$newfile = 'images/avatars/'.$_COOKIE["user"].'.jpg';
			if (file_exists($newfile)) unlink($newfile);
			move_uploaded_file($_FILES['avatar_file']['tmp_name'], $newfile);
			include('images/SimpleImage.php'); 
			$image = new SimpleImage(); 
			$image->load($newfile);
			$image->resize(48,48);
			$image->save($newfile,IMAGETYPE_JPEG,98);
		}
		if (isset($_POST["default_avatar"]))
		{
			$newfile = 'images/avatars/'.$_COOKIE["user"].'.jpg';
			if (file_exists($newfile)) unlink($newfile);
		}
		$sql = "UPDATE users SET 
			password='".$password."',
			firstname='".str_replace("'", "''", $_POST["firstname"])."',
			surname='".str_replace("'", "''", $_POST["surname"])."',
			midname='".str_replace("'", "''", $_POST["midname"])."',
			phone='".$_POST["phone"]."',
			email='".$_POST["email"]."',
			skype='".$_POST["skype"]."',
			birthday='".$birthday."'
			WHERE login='".$_COOKIE["user"]."'";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		else $message = 'Дані успішно збережено';
	}
	printTop("Користувачі");
?>

	<?php
		if (!isset ($_GET["edit"]))
		{
			echo '<div class="edit_current_user" onClick="location=\'?edit\';">Редагувати мої дані</div> '.$message;
			$sql = "SELECT * FROM users";
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			$num = 0;
			while ($row = $result->fetch_assoc())
			{
				$num++;
				if ($row["birthday"] == '0000-00-00') $birthday = '';
				else $birthday = date("j ".$month[date("n", strtotime($row["birthday"]))]." Y", strtotime($row["birthday"]));
				$fullname = $row["surname"].' '.$row["firstname"].' '.$row["midname"];
				$avatar = $row["login"].'.jpg';
				if (!file_exists('images/avatars/'.$avatar)) $avatar = 'noAv.png';
				echo '
		<div class="user_card">
			<div class="user_avatar"><img src="images/avatars/'.$avatar.'"></div>
			<div class="user_info">
				<div class="user_name">'.$num.'. '.$fullname.'</div>
			</div>
		</div>';

			// <div class="user_birthday">'.$birthday.'</div>
			// <div class="user_phone">'.$row["phone"].'</div>
			// <div class="user_skype">'.$row["skype"].'</div>
			// <div class="user_email">'.$row["email"].'</div>

			}
		} else {
			?>
		<h2 style="text-align: center;"><img src="images/users-edit.png" alt="user edit icon" width="32" align="top"> Редагування моїх даних</h2>
		<form class="user_edit_form" action="users.php" method="post" enctype="multipart/form-data">
			<label for="surname">Прізвище<span style="color: red;">*</span>:</label><input type="text" value="<?php echo $currUser["surname"]; ?>" name="surname" id="surname" required /><br>
			<label for="firstname">Ім'я<span style="color: red;">*</span>:</label><input type="text" value="<?php echo $currUser["firstname"]; ?>" name="firstname" id="firstname" required /><br>
			<label for="midtname">По-батькові:</label><input type="text" value="<?php echo $currUser["midname"]; ?>" name="midname" id="midname"/><br>
			<label for="birthday">Дата народж.:</label><input type="text" title="Введіть коректну дату" pattern="^(0?[1-9]|[12][0-9]|3[01])[\/\.](0?[1-9]|1[012])[\/\.]\d{4}$" placeholder="ДД.ММ.РРРР" value="<?php echo $currBirthday; ?>" name="birthday" id="birthday"/><br>
			<label for="phone">Телефн:</label><input type="tel" value="<?php echo $currUser["phone"]; ?>" name="phone" id="phone"/><br>
			<label for="email">E-mail:</label><input type="email" value="<?php echo $currUser["email"]; ?>" name="email" id="email"/><br>
			<label for="skype">Skype:</label><input type="text" value="<?php echo $currUser["skype"]; ?>" name="skype" id="skype"/><br>
			<label for="password">Пароль:</label><input type="password" value="" name="password" id="password"/><span class="pass_info">Залиште ці поля порожніми, щоб не змінювати пароль.</span><br>
			<label for="conf_password">Ще пароль:</label><input type="password" value="" onChange="checkPass();" name="conf_password" id="conf_password"/><br>
			<fieldset class="edit_avatar">
				<legend>Аватар: для редагування заповніть одне з полів.</legend>
				<label for="avatar_file">Файл:</label><input type="file" value="" name="avatar_file" id="avatar_file"/><span class="avatar_info">Квадрат, або буде розтягнуто.</span><br>
				<label for="vk_id">Експорт vk: ID:</label><input type="text" value="" name="vk_id" id="vk_id"/><br>
				<label for="default_avatar">Стандартний:</label><input type="checkbox" value="true" name="default_avatar" id="default_avatar"/><br>
			</fieldset>
			<input type="submit" id="submit" class="edit_user_submit" value="ЗБЕРЕГТИ">
		</form>
			<?php
		}
		
		
		/*
		$data = json_decode (file_get_contents('contact_data.json'));
		foreach ($data as $key => $student)
		{
			$sql = "INSERT INTO users(login, firstname, surname, midname, phone, email, skype) VALUES (
			'13i3".(1+$key)."', '".$student->name."', '".$student->surname."', '".$student->father_name."', '".$student->phone."', '".$student->e_mail."', '".$student->skype."')";
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		}
		*/
	?>
<?php printBottom(); ?>

<script>

function showUserInfoDialog()

</script>

<!-- TODO: Create UserInfoDialog. It should appear when someone clicks on the user name and show additional information
		   about this user -->