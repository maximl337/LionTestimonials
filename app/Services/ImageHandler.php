<?php

namespace App\Services;

use Auth;
use App\Events\ImageUpload;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHandler {

	public function save(UploadedFile $file, $type='picture')
	{
		
		try {

			// define storage path
		    $destinationPath = public_path('images/users');

		    // make file name
		    $fileName = microtime(true) . '-' . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

		    // move file
		    $file->move($destinationPath, $fileName);

		    $file_path = env('APP_URL') . 'images/users/' . $fileName;

		    $data = [
		    	'file' => $destinationPath . '/' . $fileName,
		    	'file_path' => $file_path,
		    	'user' => Auth::user(),
		    	'type' => $type
		    ];

		    event(new ImageUpload($data));

			return $file_path;

		} catch(\Symfony\Component\HttpFoundation\File\Exception $e) {

			throw $e;

		} catch(\Exception $e) {

			throw $e;

		}
		
	}
}