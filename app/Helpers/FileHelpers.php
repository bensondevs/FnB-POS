<?php

use Illuminate\Support\Facades\Storage;
use App\Repositories\StorageFileRepository;

/**
 * Check if variable is containing base64 type of string
 * 
 * @param  string  $string
 * @return  bool
 */
if (! function_exists('is_base64_string')) {
	function is_base64_string(string $string)
	{
		$temporary = explode(';base64,', $string);
	    if (isset($temporary[1])) {
	        $string = $temporary[1];
	    }

	    return base64_encode(base64_decode($string, true)) === $string;
	}
}

/**
 * Get base64 string extension
 * 
 * @param  string  $string
 * @return  string
 */
if (! function_exists('base64_extension')) {
	function base64_extension(string $string)
	{
		if (! is_base64_string($string)) return false;

		$temporary = explode(';base64,', $string);
	    $fileType = $temporary[0];
	    $dataType = explode('data:', $fileType);

	    if (! isset($dataType[1])) return;

	    $dataType = $dataType[1];
    	$temporaryDataType = explode('/', $dataType);

    	if (! isset($temporaryDataType[1])) return;

	    return $temporaryDataType[1];
	}
}

/**
 * Upload file brought by laravel request into
 * specififed directory
 * 
 * @param  mixed  $fileRequest
 * @param  string  $directory
 * @return  mixed
 */
if (! function_exists('upload_file')) {
	function upload_file($fileRequest, string $directory)
	{
		if (! is_last_character($directory, '/')) {
			$directory .= '/';
		}

		$storageFile = new StorageFileRepository;
		if (is_base64_string($fileRequest)) {
			$filePath = uploadBase64File($fileRequest, $directory);
        	return $storageFile->record($filePath);
		}

		$path = $directory . now()->format('YmdHis') . random_string(5);
	    $filename = str_replace(' ', '_', $fileRequest->getClientOriginalName());
	    $path .= urlencode(clean_filename($filename));
	    $fileContent = file_get_contents($fileRequest->getRealPath());

	    return $storageFile->upload($fileContent, $path);
	}
}

/**
 * Alias for "upload_file"
 * 
 * @param  mixed  $fileRequest
 * @param  string  $directory
 * @return  mixed
 */
if (! function_exists('uploadFile')) {
	function uploadFile($fileRequest, string $directory)
	{
		return upload_file($fileRequest, $directory);
	}
}

/**
 * Upload base64 file
 * 
 * @param  string  $base64File
 * @param  string  $path
 * @param  string  $filename
 * @return bool
 */
if (! function_exists('upload_base64_file')) {
	function upload_base64_file(
		string $base64File, 
		string $path = 'uploads/documents',
		string $filename = ''
	) {
		if (! File::exists($path)) {
	        File::makeDirectory($path, $mode = 0755, true, true);
		}

	    $base64String = substr($base64File, strpos($base64File, ",") + 1);
	    $fileData = base64_decode($base64String);
	    $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];

	    if (! $filename) $filename = now()->format('YmdHis');

	    // Prepare image path
	    $path = (substr($path, -1) == '/') ?
	        $path : 
	        $path . '/';
	    $filename = random_string(5) . $filename . '.' . $extension;
	    $filePath = $path . $filename;

	    return Storage::put($filePath, $fileData) ? $filePath : false;
	}
}

/**
 * Alias for "upload_base64_file"
 * 
 * @param  string  $base64File
 * @param  string  $path
 * @param  string  $filename
 * @return bool
 */
if (! function_exists('uploadBase64File')) {
	function uploadBase64File(
		string $base64File, 
		string $path = 'uploads/documents',
		string $filename = ''
	) {
		return upload_base64_file($base64File, $path, $filename);
	}
}

/**
 * Upload base64 image
 * 
 * @param  string  $base64Image
 * @param  string  $path
 * @param  string  $filename
 * @return bool
 */
if (! function_exists('upload_base64_image')) {
	function upload_base64_image(
		string $base64Image,
		string $path = 'uploads/test',
		string $imageName = ''
	) {
		if(! File::exists($imagePath)) {
	        File::makeDirectory($imagePath, $mode = 0755, true, true);
		}

	    $base64String = substr($base64Image, strpos($base64Image, ",") + 1);
	    $imageData = base64_decode($base64String);
	    $extension = explode('/', explode(':', substr($base64Image, 0, strpos($base64Image, ';')))[1])[1];

	    // Prepare image path
	    $imagePath = (substr($imagePath, -1) == '/') ?
	        $imagePath : 
	        $imagePath . '/';
	    $fileName = random_string(5) . ($imageName ? $imageName : Carbon::now()->format('YmdHis')) . '.' . $extension;
	    $filePath = $imagePath . $fileName;

	    $putImage = Storage::put($filePath, $imageData);

	    return $putImage ? $filePath : false;
	}
}

/**
 * Alias for "upload_base64_image"
 * 
 * @param  string  $base64Image
 * @param  string  $path
 * @param  string  $filename
 * @return bool
 */
if (! function_exists('uploadBase64Image')) {
	function uploadBase64Image(
		string $base64Image,
		string $path = 'uploads/test',
		string $imageName = ''
	) {
		return upload_base64_image(
			$base64Image, 
			$path, 
			$imageName
		);
	}
}

/**
 * Delete file from the diretcory
 * 
 * @param  string  $path
 * @return bool
 */
if (! function_exists('delete_file')) {
	function delete_file(string $path)
	{
	    return Storage::delete($path);
	}
}

/**
 * Alias for "delete_file"
 * 
 * @param  string  $path
 * @return bool
 */
if (! function_exists('deleteFile')) {
	function deleteFile(string $path)
	{
		delete_file($path);
	}
}