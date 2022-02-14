<?php

return [
	/*
    |--------------------------------------------------------------------------
    | Twilio SID
    |--------------------------------------------------------------------------
    |
    | This option controls the twilio SID that will be used for the API request
	| purpose. This represents the SID of twilio account used by the application.
    |
    */
    'sid' => env('TWILIO_SID', 'ANOTHER_TWILIO_SID'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Auth Token
    |--------------------------------------------------------------------------
    |
    | This option controls the twilio authentication token for the request.
    |
    */
    'auth_token' => env('TWILIO_AUTH_TOKEN', 'ANOTHER_AUTH_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Number
    |--------------------------------------------------------------------------
    |
    | This option controls the twilio sending numebr that will be seen by user.
    |
    */
    'number' => env('TWILIO_NUMBER', 'ANOTHER_NUMBER'),
];