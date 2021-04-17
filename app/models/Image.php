<?php 


namespace App\Models;

class Image {

	public static function is_image($target_file) {

		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		return $imageFileType === 'jpg' || $imageFileType ==='png';
	}
	
	public static function create_uniq_file_name($file_name) {

		$demo = explode('.', $file_name);
		return $uniq_file_name = md5(uniqid()) . '.' . array_pop($demo);
	}

	public static function set_path($storage_path, $file_name) {

		return $path = $storage_path . $file_name;
	}

	public static function upload($file, $path) {
	
		move_uploaded_file($file["tmp_name"], $path);
	}	
}