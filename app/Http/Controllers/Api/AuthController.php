<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Auth\{
    LoginRequest,
    RegisterRequest,
    ForgotRequest,
    ResetRequest,
};

use App\Repositories\AuthRepository;
use App\Services\SocialiteService;

class AuthController extends Controller
{
    /**
     * Auth repository class container
     * 
     * @var \App\Repositories\AuthRepository|null
     */
    private $auth;

    /**
     * Socialite service class container
     * 
     * @var \App\Services\SocialiteService|null
     */
    private $socialite;

    /**
     * Controller constructor method
     * 
     * @param  \App\Repositories\AuthRepository  $auth
     * @param  \App\Services\SocialiteService    $socialite
     * @return void
     */
    public function __construct(
        AuthRepository $auth, 
        SocialiteService $socialite
    ) {
        $this->auth = $auth;
        $this->socialite = $socialite;
    }

    /**
     * Attemp login for a user 
     * 
     * Accessible through: '/api/auth/login'
     * 
     * @param  \App\Http\Requests\Api\Auth\LoginRequest  $requets
     * @return \Illuminate\Support\Facades\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $user = $this->auth->login($credentials);

        return repository_api_response($this->auth, [
            'user' => $user,
            'token' => $user->token,
        ]);
    }

    /**
     * Register for a user
     * 
     * Accessible through: '/api/auth/login'
     * 
     * @param  \App\Http\Requests\Api\Auth\RegisterRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function register(RegisterRequest $request)
    {
        $registerData = $request->validated();
        $user = $this->auth->register($registerData);

        return repository_api_response($this->auth);
    }

    /**
     * Forgot password for a user
     * 
     * Accessible through: '/api/auth/forgor'
     * 
     * @param  \App\Http\Requests\Api\Auth\ForgotRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function forgot()
    {
        //
    }

    /**
     * Reset password for a user. This endpoint will ask
     * token from user to enable itself to reset user's password
     * through the repository.
     * 
     * Accessible through: '/api/auth/reset'
     * 
     * @param  \App\Http\Requests\Api\Auth\ResetRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function reset()
    {
        //
    }

    /**
     * Give user the URL to authenticate in provider's auth page
     * 
     * Accessible through: '/api/auth/socialite/{provider}/redirect'
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     * @return \Illuminate\Support\Facades\Response
     */
    public function socialiteRedirect(Request $request, string $provider = 'google')
    {
        $url = $this->socialite
            ->setProvider($provider)
            ->urlToAuthProvider();

        return response()->json(['url' => $url]);
    }

    /**
     * Attempt authenticate the user that uses socialite method
     * 
     * Accessible through: '/api/auth/socialite/{provider}/login'
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     * @return \Illuminate\Support\Facades\Response
     */
    public function socialiteLogin(Request $request, string $provider = 'google')
    {
        // Acquire token from the socialite login
        $token = $request->input('token');

        // Get provider user instance
        $providerUser = $this->socialite
            ->setProvider($provider)
            ->setToken($token)
            ->getProviderUser();

        // Acquire user record in database
        // and authenticate the user
        $user = $this->auth->socialiteLogin($providerUser, $provider);

        return response()->json([
            'user' => new UserResource($user),
            'status' => 'success',
            'message' => 'Successfully authenticate user using ' . $provider . ' authentication',
        ]);
    }
}