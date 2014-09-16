<?php
	ob_start();
	date_default_timezone_set ("Europe/Kiev");

	if (!isset($_COOKIE["user"]) && stripos($_SERVER['PHP_SELF'], 'login.php') === false)
		header ('Location: login.php');
	include 'data.php';
	if(!isset($_COOKIE["theme"]))
	{
		setcookie("theme", "light", time()+60*60*24*30*3);  // 3 month
		$_COOKIE["theme"] = "light";
	}
	if (isset($_GET["theme"]))
	{
		setcookie("theme", $_GET["theme"], time()+60*60*24*30*3);  // 3 month
		$_COOKIE["theme"] = $_GET["theme"];
	}
	
	
	$db = new mysqli('localhost', 'root', '', 'ami'); 
	if($db->connect_errno > 0)
		die('Unable to connect to database [' . $db->connect_error . ']');
	$db->set_charset('UTF8');
	
	/*** CURRENT USER INFO ***/
	if (isset($_COOKIE["user"]))
	{
		$sql_currentUser = "SELECT * FROM users WHERE login = '".$_COOKIE["user"]."'";
		if(!$result_currentUser = $db->query($sql_currentUser)) die('There was an error running the query [' . $db->error . ']');
		$currUser = $result_currentUser->fetch_assoc();
		$currUser["avatar"] = $_COOKIE["user"].'.jpg';
		if (!file_exists('images/avatars/'.$currUser["avatar"])) $currUser["avatar"] = 'noAv.png';
		if ($currUser["birthday"] == '0000-00-00') $currBirthday = '';
		else $currBirthday = date("d.m.Y",strtotime($currUser["birthday"]));
	}
	
	function printTop ($title, $style = null)
	{
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $title; ?> - ПМІ-23</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="styles/fonts.css" rel="stylesheet" type="text/css">
		<link href="process_css.php?file=style.css&theme=<?php echo $_COOKIE["theme"]; ?>" rel="stylesheet" type="text/css" />
		<?php if ($style) echo '<link href="process_css.php?file='.$style.'&theme='.$_COOKIE["theme"].'" rel="stylesheet" type="text/css" />'; ?>
		<meta name="viewport" content="width=device-width" />
		<link href="styles/media-style.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<script src="jquery.js"></script>
		<?php if ($title == 'Фотогалерея' || $title == 'Новини') { ?>
		<!-- FANCYBOX -->
		<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".fancybox").fancybox({padding:5,openEffect:'elastic',closeEffect:'elastic',afterLoad: function() {this.inner.append("<a href='"+this.href+"' class=\"image_download\" download></a>");}});
			});
		</script>

		<?php } 
			if ($title == 'Чат') { ?>
			<script src='https://cdn.firebase.com/js/client/1.0.15/firebase.js'></script>
			<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>
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
			<div id="more_menu2" onClick='moreMenu2(this);'>&#x25BC;</div>
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
			if (isset($_COOKIE["user"]) && !isset($_COOKIE["first_alert"]) && password_verify('Pa$$word', $currUser["password"])) { ?>
		<div class="first_alert">
			Будь ласка, заповніть свої дані та змініть пароль. Пізніше це можна буде зробити у розділі <br> "<b>Користувачі</b>" -&gt; "<b>Редагувати мої дані</b>".<br>
			<button onClick="location = 'users.php?edit';">Змінити зараз</button><button onClick="this.parentNode.style.display = 'none';">Змінити пізніше</button>
		</div>
		<?php 
				setcookie("first_alert", 'true', time()+60*60*24*30*3);
			}
		?>
		<a href="#" class="scrollToTop"></a>
		<script type="text/javascript">
			//Check to see if the window is top if not then display button
			$(window).scroll(function(){
				if ($(this).scrollTop() > 100) {
					$('.scrollToTop').fadeIn();
				} else {
					$('.scrollToTop').fadeOut();
				}
			});
			
			//Click event to scroll to top
			$('.scrollToTop').click(function(){
				$('html, body').animate({scrollTop : 0},800);
				return false;
			});
		</script>
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