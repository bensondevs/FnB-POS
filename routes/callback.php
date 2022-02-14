<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Callback Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for handling callback action from any
| vendor or API that requires this application to accept data from the vendor.
| These routes has prefix of `/callbacks/` by default.
|
| Always keep in mind that these routes needs some kind of protection in the
| request class and or controller. Because this could be the gate for the hackers
| to take adventage of the application.
|
*/

/**
 * Socialite callback routes.
 */
Route::group(['prefix' => 'socialite'], function () {
	// Get callback data from the vendor of application
	Route::get('auth/{vendor}', [AuthController::class, 'socialiteCallback']);
});