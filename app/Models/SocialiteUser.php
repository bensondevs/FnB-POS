<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

use App\Observers\SocialiteUserObserver as Observer;

class SocialiteUser extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'socialite_users';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set timestamp each time model is saved
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use incrementing value or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'vendor',
        'token',
        'user_instance',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Create callable attribute of `user`
     * This callable attribute will load the user instance that
     * owns the current record.
     * 
     * To call this method directly will return eloquent
     * relationship.
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}