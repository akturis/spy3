<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifEve extends Model
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
    protected $table = 'notification_eve';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'character_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'character_id',
                  'enabled',
                  'type',
                  'token'
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
     * Get the character for this model.
     *
     * @return App\Models\Character
     */
    public function character()
    {
        return $this->belongsTo('App\Models\Character','character_id');
    }



}
