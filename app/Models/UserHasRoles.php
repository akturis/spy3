<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserHasRoles extends Model
{
    public $incrementing = false;    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_has_roles';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = ['role_id','user_id'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'role_id','user_id'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * Get the Role for this model.
     *
     * @return App\Models\Roles
     */
    public function Role()
    {
        return $this->belongsTo('App\Models\Roles','role_id','id');
    }

    /**
     * Get the User for this model.
     *
     * @return App\User
     */
    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }


}
