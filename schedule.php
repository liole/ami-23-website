<?php
	include 'template.php';
	
	printTop("Розклад");
?>
	<div class="download_schd_pdf" onClick="location ='files/1_schedule_2-2014.pdf';">розклад.pdf</div>
	<h3 style="text-align: center; width: 450px;">Розклад занять на І семестр<br> 2014-2015 н.р.</h3>
	<?php
		$limit = 5; // to 5 AM use previous day date
		$timestamp = time();
			if (0+date('H')>=$limit) $timestamp = strtotime('tomorrow');
		$dateW = date('W',$timestamp);	
		foreach ($wdays as $id => $title)
		{
			echo '<h2 style="text-align: center;">'.$title.'</h2>';
			$sql = "SELECT * FROM schedule WHERE day = '$id' ORDER BY num ASC, week ASC";
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			while ($row = $result->fetch_assoc())
			{
				if ($row["week"] == '2' || $row["week"] == '0')
				{
					echo '
				<div class="subj">
					<div class="subj_time">'.$stime[$row["num"]].'</div>
					<div class="subj_num">'.$row["num"].'</div>';
				}
				$name1 = explode (' ', $row["teacher1"]);
				$in_name1 = $row["teacher1"];
				if (isset($name1[1]))
					$in_name1 = $name1[0].' '.substr($name1[1],0,2).'. '.substr($name1[2],0,2).'.';
				echo '
				<div class="subj_title'.(($row["week"]=='1')?' marg':'').(($row["week"]!=($dateW%2)&&$row["week"]!='2')?' not_active':'').'">'.$row["title"].'</div>
				<div class="subj_aud '.$row["type"].'">'.$row["aud1"].(($row["aud2"]!='0')?'<br>'.$row["aud2"]:'').'</div>
				<div class="subj_teachers">
					<span class="subj_teacher" onClick="alert(\''.$row["teacher1"].'\');">'.$in_name1.'</span>';
				if ($row["teacher2"] != '0')
				{
					$name2 = explode (' ', $row["teacher2"]);
					$in_name2 = $row["teacher2"];
					if (isset($name2[1]))
						$in_name2 = $name2[0].' '.substr($name2[1],0,2).'. '.substr($name2[2],0,2).'.';
					echo '<br><span class="subj_teacher" onClick="alert(\''.$row["teacher2"].'\');">'.$in_name2.'</span>';
				}
				echo '</div>';
				if ($row["week"] == '2' || $row["week"] == '1') echo '</div>';
				else echo '<hr>';
			}
		}
	?>
	
	<br><div class="subj_aud lek">ауд</div> - лекції <div class="subj_aud pr">ауд</div> - практичні; натисніть на викладача для повного імені<div class="subj_title marg not_active" onBlur="alert('now')">предмет</div> - цього тижня предмету нема.
<?php printBottom(); ?>