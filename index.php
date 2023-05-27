<?php

$dest 					= 'content/';
$webpQuality 			= 75;


$supportTypes 			= ['image/png', 'image/jpg', 'image/jpeg'];






$content = scandir($dest);
foreach ($content as $file) {
	$fullName = $dest . $file;
	$type = mime_content_type($fullName);

	if (in_array($type, $supportTypes)) {
		$webpFullName = $dest . pathinfo($file, PATHINFO_FILENAME) . '.webp';

		switch ($type) {
	        case 'image/jpeg':
	        case 'image/jpg':
	            $image = imagecreatefromjpeg($fullName);
	            $exif = exif_read_data($fullName);
	            if ($image && $exif && isset($exif['Orientation'])) {
	                $ort = $exif['Orientation'];

	                if ($ort == 6 || $ort == 5) {
	                    $image = imagerotate($image, 270, 0);
	                }
	                if ($ort == 3 || $ort == 4) {
	                    $image = imagerotate($image, 180, 0);
	                }
	                if ($ort == 8 || $ort == 7) {
	                    $image = imagerotate($image, 90, 0);
	                }

	                if ($ort == 5 || $ort == 4 || $ort == 7) {
	                    imageflip($image, IMG_FLIP_HORIZONTAL);
	                }
	            }
	            break;

	        default:
	            $image = imagecreatefrompng($fullName);
	            imagepalettetotruecolor($image);
	            imagealphablending($image, true);
	            imagesavealpha($image, true);
	    }

	    imagewebp($image, $webpFullName, $webpQuality);

	    imagedestroy($image);

	}
}