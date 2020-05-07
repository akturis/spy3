<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alts extends Model
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
    protected $table = 'alts';

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
                  'main_id'
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
     * Get the main for this model.
     *
     * @return App\Models\Main
     */
    public function main()
    {
        return $this->belongsTo('App\Models\Characters','main_id');
    }

    public function Alt()
    {
        return $this->belongsTo('App\Models\Characters','id');
    }


}
