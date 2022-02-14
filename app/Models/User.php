<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Contracts\Models\SocialAuthenticable;
use App\Enums\User\UserType as Type;

class User extends Authenticatable implements SocialAuthenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => Type::class,
    ];

    /**
     * Find user by social account vendor and token
     * 
     * @param  string  $vendor
     * @param  string  $token
     * @param  bool    $abortIfFail
     * @return self
     */
    public static function findBySocial(
        string $vendor, 
        string $token, 
        bool $abortIfFail = false
    ) {
        $query = self::where(strtolower($vendor) . '_id', $token);

        return $abortIfFail ? 
            $query->firstOrFail() :
            $query->first();
    }

    /**
     * Get socialite user relationship.
     * 
     * To call this as attribute will load the socialite user
     * instance from the database table relationship with `user`
     * and `socilitie_users`
     * 
     * @return \App\Models\SocialiteUser|null
     * 
     * To call this method directly will return eloquent relationship
     * instead.
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialiteUser()
    {
        return $this->hasOne(SociliteUser::class);
    }

    /**
     * Authenticate the user to the application.
     * This will produce the user's API token and the
     * session of the user
     * 
     * @return self
     */
    public function authenticate()
    {
        // Delete old tokens
        $this->tokens()->delete();

        // Create Token
        $token = $this->createToken(time())->plainTextToken;
        $this->attributes['api_token'] = $token;

        // Authenticate using laravel authenticator
        auth()->login($this);

        return $this;
    }
}
