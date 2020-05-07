<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Roles extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name'
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
     * Get the roleHasPermission for this model.
     *
     * @return App\Models\RoleHasPermission
     */
    public function roleHasPermission()
    {
        return $this->hasOne('App\Models\RoleHasPermission','role_id','id');
    }

    /**
     * Get the userHasRoles for this model.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function userHasRoles()
    {
        return $this->hasMany('App\Models\UserHasRole','role_id','id');
    }


    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('id', function (Builder $builder) {
            $builder->where('id', '<>', 2)->where('id', '<>', 4);
        });
    }

}
