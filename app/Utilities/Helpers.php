<?php

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Webpatser\Uuid\Uuid;

/**
 * Generate UUID
 * 
 * @return  string
 */
if (! function_exists('generate_uuid')) {
    function generate_uuid()
    {
        return Uuid::generate()->string;
    }
}

/**
 * Alias for "generate_uuid"
 * 
 * @return  string
 */
if (! function_exists('generateUuid')) {
    function generateUuid()
    {
        return generate_uuid();
    }
}

/**
 * Search value in collection
 * 
 * @param Illuminate\Support\Collection  $collection
 * @param mixed  $search
 * @return bool
 */
if (! function_exists('searchInCollection')) {
    function searchInCollection(Collection $collection, $search)
    {
        return ($collection->filter(function ($item) use ($search) {
            $attributes = array_keys($item);
            foreach ($attributes as $attribute)
                if (isset($item[$attribute]) && (! is_array($item[$attribute])))
                    if (stripos($item[$attribute], $search) !== false)
                        return true;

            return false;
        }))->toArray();
    }
}

function urlToUsername(string $urlString)
{
    $urlString = str_replace('http://', '', $urlString);
    $urlString = str_replace('https://', '', $urlString);
    $urlString = str_replace('www.', '', $urlString);

    $clearParams = explode('/', $urlString);
    
    $mainDomain = $clearParams[0];
    $breakMainDomain = explode('.', $mainDomain);
    $domainName = $breakMainDomain[0];
    $domainExtension = $breakMainDomain[1];

    return $domainName . $domainExtension;
}

function get_pure_class($class)
{
    $class = get_class($class);
    $explode = explode('\\', $class);
    return $explode[count($explode) - 1];
}

function get_lower_class($class)
{
    $lowerClassname = get_pure_class($class);
    $lowerClassname = strtolower($lowerClassname);
    return $lowerClassname;
}

function get_plural_lower_class($class)
{
    return str_to_plural(get_lower_class($class));
}

function numbertofloat($number)
{
    return sprintf('%.2f', $number);
}

function db(string $table = '')
{
    return ($table) ? DB::table($table) : new DB;
}

function clean_filename(string $filename)
{
    // Replace > with space
    $filename = str_replace('/', ' ', $filename);

    // Replace > with space
    $filename = str_replace('>', ' ', $filename);

    // Replace | with space
    $filename = str_replace('|', ' ', $filename);

    // Replace : with space
    $filename = str_replace(':', ' ', $filename);

    // Replace & with space
    $filename = str_replace('&', ' ', $filename);

    // Replace ? with space
    $filename = str_replace(' ', '_', $filename);
    
    // Replace spaces with _
    $filename = str_replace(' ', '_', $filename);

    return $filename;
}

if (! function_exists('random_hex_color')) {
    function random_hex_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}

function random_phone($length = 12)
{
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= random_int(0, 9);
    }

    return $result;
}

function gate()
{
    return new Gate();
}

function jsonResponse(array $response)
{
    return response()->json($response);
}

function viewResponse($viewName, $response, $repositoryObject)
{
    $view = view($viewName, $response);
    $view = $repositoryObject ?
        $view->with(
            $repositoryObject->status, 
            $repositoryObject->message
        ) : $view;

    return $view;
}

function apiResponse($repositoryObject, $responseData = null)
{
    $response = [];

    if (is_array($responseData)) {
        $attribute = array_keys($responseData)[0];
        $response[$attribute] = $responseData[$attribute];
    } else if ($responseData !== null) {
        $response['data'] = $responseData;
    }
    
    if ($status = $repositoryObject->status) {
        $response['status'] = (count($repositoryObject->statuses) > 1) ? 
            $repositoryObject->statuses :
            $status;
    }

    if ($message = $repositoryObject->message) {
        $response['message'] = (count($repositoryObject->messages) > 1) ?
            $repositoryObject->messages : 
            $message;
    }

    if ($queryError = $repositoryObject->queryError) {
        $response['query_error'] = (count($repositoryObject->queryErrors) > 1) ?
            $repositoryObject->queryErrors :
            $queryError;
    }

    return response()->json($response, $repositoryObject->httpStatus);
}

function uppercaseArray(array $array)
{
    return array_map('strtoupper', $array);
}

function uploadBase64File($base64File, $path = 'uploads/documents', $fileName = '')
{
    if(! File::exists($path))
        File::makeDirectory($path, $mode = 0755, true, true);

    $base64String = substr($base64File, strpos($base64File, ",") + 1);
    $fileData = base64_decode($base64String);
    $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];

    // Prepare image path
    $path = (substr($path, -1) == '/') ?
        $path : 
        $path . '/';
    $fileName = random_string(5) . ($fileName ? $fileName : Carbon::now()->format('YmdHis')) . '.' . $extension;
    $filePath = $path . $fileName;
    $putImage = Storage::put($filePath, $fileData);

    return $putImage ? $filePath : false;
}

function uploadBase64Image($base64Image, $imagePath = 'uploads/test', $imageName = '')
{
    if(! File::exists($imagePath))
        File::makeDirectory($imagePath, $mode = 0755, true, true);

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

function deleteFile($filePath)
{
    return Storage::delete($filePath);
}


function currency_format($amount, string $currencyCode = 'EUR', string $locale = 'nl_NL.UTF-8')
{
    $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
    return $formatter->formatCurrency($amount, $currencyCode);
}

function currentLink()
{
    return url()->current();
}

function requestMethod()
{
    return request()->method();
}

function isRoute($routeName)
{
    return Route::currentRouteName() == $routeName;
}

function isRouteStartsWith($start, $routeName = '')
{
    // if route is not defined make it current route
    $routeName = $routeName ? $routeName : Route::currentRouteName();

    return substr($routeName, 0, strlen($start)) == $start;
}

function convertBase64ToUploadedFile($base64String)
{
    $fileData = base64_decode($base64String);
    
    $mimeType = explode(':', substr($base64String, 0, strpos($base64String, ';')))[1];

    $tmpFilePath = sys_get_temp_dir() . '/' . random_string(20);
    file_put_contents($tmpFilePath, $fileData);

    $tmpFile = new SymfonyFile($tmpFilePath);
    $file = new UploadedFile(
        $tmpFile->getPathname(),
        random_string(10),
        $mimeType,
        0,
        false
    );

    return $file;
}