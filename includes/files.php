<?php
	include_once 'template.php';
	
	function includeFileByName ($name, $id, $embed = false)
	{	
		$output = '';
			$size = filesize ("files/".$name);
		if ($size/1024<1) {$size_s = $size.' b';}
		else if ($size/1048576<1) {$size_s = number_format($size/1024, 2).' Kb';}
		else {$size_s = number_format($size/1048576, 2).' Mb';};
		$format = strtoupper(pathinfo($name, PATHINFO_EXTENSION));
		$output .= '
			<div class="file_card'.(($embed)?' fileAttached':'').'" onClick="location = \'files/'.$name.'\';">
				'.(($embed)?'':'<div class="file_ID">ID '.$id.'</div>').'
				<img class="file_icon" src="images/icons/'.$format.'.ico">
				<div class="file_name">'.$name.'</div>
				<div class="file_atr">['.$format.'] <b>'.$size_s.'</b></div>
			</div>';
		return $output;
	}
	
?>