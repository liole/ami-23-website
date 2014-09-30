<?php
	include_once __DIR__.'/../template.php';
	include_once __DIR__.'/userNames.php';
	
	function includePoll ($row, $embed = false)
	{
		global $users_data;
		$output = '';
		$answers = json_decode ($row["answers"]);
		$ans_data = json_decode ($row["data"]);
		$voted = false;
		foreach ($ans_data as $user_arr)
			foreach ($user_arr as $p_login)
				if ($p_login == $_COOKIE["user"]) {
					$voted = true;
					break;
				}
		$output .= '<div class="poll_card'.($embed?' pollAttached':'').(($voted)?' poll_answered':'').'">
		<div class="poll_question"><div class="poll_ID">ID '.$row["id"].'</div>'.$row["question"].'</div>';
		$max_ans = 0;
		$sum_ans = 0;
		foreach ($ans_data as $data)
		{
			$count_data = count($data);
			$sum_ans += $count_data;
			if ($count_data > $max_ans)
				$max_ans = $count_data;
		}
		foreach ($answers as $id => $value)
		{
			$count_ans = count($ans_data[$id]);
			$users_ans = '';
			$my_vote = '';
			foreach ($ans_data[$id] as $user_login)
			{
				$users_ans .= $users_data[$user_login].'; ';
				if ($user_login == $_COOKIE["user"]) $my_vote = ' answered';
			}
			$width = 0;
			$percent = 0;
			if ($sum_ans != 0) {
				$width = floor($count_ans*100/$max_ans);
				$percent = floor($count_ans*100/$sum_ans);
			}
				
			$output .= '
	<div class="poll_answer">
		<div class="answer_text'.$my_vote.'" '.(($voted)?'':'onClick="location=\'polls.php?poll='.$row["id"].'&answer='.$id.'\';"').'>'.$value.'</div>
		<div class="answer_num" onClick="alert(\''.$users_ans.'\')">'.$count_ans.'</div>
		<div class="answer_diagram"><div class="diagram_line" style="width: '.$width.'%;">'.$percent.'%</div></div>
	</div>';
		}
		$output .= '</div>';
		return $output;
	}
	
?>