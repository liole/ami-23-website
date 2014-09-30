<?php
	if (isset($_FILES['image']))
	{
		function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}
		$uploadfile = 'images/upload/';
		$uploadfile .= generateRandomString().'.jpg';
		if (file_exists($uploadfile))
		{
			echo "{ 'error' : 'File with the same name already exists.' }";
			exit;
		}
		move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
		include('images/SimpleImage.php'); 
		$image = new SimpleImage(); 
		$image->load($uploadfile);
		$maxSize = 700;
		if ($image->getWidth() > $maxSize || $image->getHeight() > $maxSize)
		{
			if ($image->getWidth() > $image->getHeight())
				$image->resizeToWidth($maxSize); 
			else
				$image->resizeToHeight($maxSize); 
			$image->save($uploadfile);
		}
		$output = array (
			"image" => array (
				"width" => $image->getWidth(),
				"height" => $image->getHeight()
			),
			"link" => $uploadfile
		);
		echo json_encode($output);
	}
?> 