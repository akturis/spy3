<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermissions extends Model
{
    
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
    protected $table = 'role_has_permissions';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'permission_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'role_id'
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
     * Get the Permission for this model.
     *
     * @return App\Models\Permission
     */
    public function Permission()
    {
        return $this->belongsTo('App\Models\Permission','permission_id','id');
    }

    /**
     * Get the Role for this model.
     *
     * @return App\Models\Role
     */
    public function Role()
    {
        return $this->belongsTo('App\Models\Role','role_id','id');
    }



}
