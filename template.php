<?php
	ob_start();
	date_default_timezone_set ("Europe/Kiev");

	if (!isset($_COOKIE["user"]) && stripos($_SERVER['PHP_SELF'], 'login.php') === false)
		header ('Location: login.php');
	include 'data.php';
	
	$db = new mysqli('localhost', 'ami_user', 'Pa$$word', 'ami'); 
	if($db->connect_errno > 0)
		die('Unable to connect to database [' . $db->connect_error . ']');
	
	/*** CURRENT USER INFO ***/
	if (isset($_COOKIE["user"]))
	{
		$sql_currentUser = "SELECT * FROM users WHERE login = '".$_COOKIE["user"]."'";
		if(!$result_currentUser = $db->query($sql_currentUser)) die('There was an error running the query [' . $db->error . ']');
		$currUser = $result_currentUser->fetch_assoc();
		$currUser["avatar"] = $_COOKIE["user"].'.jpg';
		if (!file_exists('images/avatars/'.$currUser["avatar"])) $currUser["avatar"] = 'noAv.png';
		$currBirthday = date("d.m.Y",strtotime($currUser["birthday"]));
		if (!strtotime($currUser["birthday"])) $currBirthday = '';
	}
	
	function printTop ($title)
	{
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $title; ?> - ПМІ-23</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="fonts.css" rel="stylesheet" type="text/css">
		<link href="style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="script.js"></script>
		<?php if ($title == 'Фотогалерея' || $title == 'Головна') { ?>
		<!-- FANCYBOX -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".fancybox").fancybox({padding:5,openEffect:'elastic',closeEffect:'elastic'});
			});
		</script>

		<?php } ?>
	</head>
	<body>
		<nav>
			<?php 
				if (isset($_COOKIE["user"]))
				{
					global $menu;
					foreach ($menu as $text => $url)
					{
						$class = 'menu'.(($title == $text)?' sel':'');
						echo "<a href='$url' class='$class'>$text</a>\n";
					}
					echo '<img src="images/lock.png" class="logout" onClick="location = \'login.php?logout\'">';
				}
			?>
		</nav>
		<aside>
			<img src="images/ami23.png" style="width: 100%">
			<img src="images/ami_m.png" style="width: 100%">
			<?php if (isset($_COOKIE["user"])) { ?>
			<h2>Завтра:</h2>
			<?php 
				global $db;
				
				$limit = 5;
				$timestamp = time();
				if (0+date('H')>=$limit) $timestamp = strtotime('tomorrow');
				$week_day = date('D', $timestamp);
				echo '<div class="tomorrow_schd">';
				if ($week_day == 'Sat' || $week_day == 'Sun')
					echo '<span style="color: #e95; font-size: 14pt; font-weight: bold;">Вихідний!</span>';
				else {
					$sql = "SELECT * FROM schedule WHERE day = '$week_day' ORDER BY num ASC, week ASC";
					if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
					while ($row = $result->fetch_assoc())
					{
						if (($row["week"]==date('W',$timestamp)%2) || $row["week"]=='2')
						{
							echo $row["num"].'. '.$row["title"].(($row["type"]=='lek')?' (лекц.)':'').'<br>';
						}
					}
				}
				echo '</div>';
				
				$sql = "SELECT surname, firstname FROM users WHERE birthday LIKE '".date("%-m-d",$timestamp)."'";
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				while ($row = $result->fetch_assoc())
					echo '<div class="tomorrow_birthday">'.$row["surname"].' '.$row["firstname"].'</div>';
			?>
			<?php } ?>
		</aside>
		<div class="main">
<?php
	}
	function printBottom ()
	{
?>
		</div>
		<?php
			global $currUser;
			if (isset($_COOKIE["user"]) && !isset($_COOKIE["first_alert"]) && $currUser["password"] == 'Pa$$word') { ?>
		<div class="first_alert">
			Будь ласка, заповніть свої дані та змініть пароль. Пізніше це можна буде зробити у розділі <br> "<b>Користувачі</b>" -&gt; "<b>Редагувати мої дані</b>".<br>
			<button onClick="location = 'users.php?edit';">Змінити зараз</button><button onClick="this.parentNode.style.display = 'none';">Змінити пізніше</button>
		</div>
		<?php 
				setcookie("first_alert", 'true', time()+60*60*24*30*3);
			}
		?>
		<footer>
			Copyright &copy; 2014
		</footer>
	</body>
</html>
<?php
		ob_flush();
		global $db;
		$db->close(); 
		
	}	
?>