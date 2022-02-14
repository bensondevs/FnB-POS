<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Go-biz API Base URL
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API integration base URL.
    | This base URL will be used for the implementation for API request.
    |
    | This configuration can guess which ENV value will be used.
    |
    */
    'base_url' => which_env(
    	env('GOBIZ_APP_ENV', 'local') == 'local', 
    	'GOBIZ_API_SANDBOX_BASE_URL',
    	'GOBIZ_API_BASE_URL'
    ),

    /*
    |--------------------------------------------------------------------------
    | Go-biz API OAUTH URL
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API integration base URL.
    | This API URL will be used to authenticate the Go-biz request.
    |
    | This configuration can guess which ENV value will be used.
    |
    */
    'oauth_url' => which_env(
    	env('GOBIZ_APP_ENV', 'local') == 'local',
    	'GOBIZ_API_SANDBOX_OAUTH_URL',
    	'GOBIZ_API_OAUTH_URL'
    ),
];