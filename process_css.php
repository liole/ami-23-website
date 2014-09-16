<?php
	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return implode(",", $rgb);
	}

	header ('Content-type: text/css');
	$data = json_decode (file_get_contents('styles/variables.json'), true);
	$file = file_get_contents ('styles/'.$_GET["file"]);
	foreach ($data[$_GET["theme"]] as $name => $value)
	{
		$file = str_replace ('%rgb$'.$name, hex2rgb($value), $file);
		$file = str_replace ('$'.$name, $value, $file);
	}
	echo $file;
?>