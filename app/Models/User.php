<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator, Hash;


class User extends Authenticatable
{
    use Notifiable;

    use SoftDeletes;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'users';

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
    * Indicates if the model should be timestamped.
    *
    * @var bool
    */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'permission_id'
    ];

    protected $guarded = ['password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    

    /**
    * Get the zone record of the user
    **/
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone', 'zone_id');
    }

    /**
    * Get the room record of the user
    **/
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'room_id');
    }

}
