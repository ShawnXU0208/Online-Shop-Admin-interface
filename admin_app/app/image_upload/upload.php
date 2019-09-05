<?php


	function convertImage($original, $type, $output = "temp_image.jpg", $quality = 100){

		//check the image type, jpg, png, bmp
		if(preg_match("/jpg|jpeg/i", $type)){
			$imageTemp = imagecreatefromjpeg($original);
		}
		else if(preg_match("/png/i", $type)){
			$imageTemp = imagecreatefrompng($original);
		}
		else{
			return false;
		}
		echo $imageTemp;
		imagejpeg($imageTemp, $output, $quality);
		imagedestroy($imageTemp);

		return true;
	}


	
	//get image type
	//$imageLocation = $_FILES["imageFile"]["name"];
	$imageName = $_FILES["imageFile"]["name"];
	$imageLocation = $_FILES['imageFile']['tmp_name'];
	$imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

	//move_uploaded_file($imageLocation, "temp_original.jpg");
	//echo $_FILES['imageFile']['tmp_name'];
	//check extension of the image uploaded
	$validExtensions = array("jpg", "jpeg", "png");
	if(in_array($imageType, $validExtensions)){
		//convert image and upload
		if(convertImage($imageLocation, $imageType)){
		    // set response code - 201 success
	        http_response_code(201);
			echo json_encode(array("message" => "image is uploaded successfully"));		
		}else{
			// set response code - 503 service unavailable
		    http_response_code(503);
		    echo json_encode(array("message" => "try again"));				
		}
	}else{
		// set response code - 503 service unavailable
	    http_response_code(503);
	    echo json_encode(array("message" => "image uploaded isn't valid"));	
	}
?>