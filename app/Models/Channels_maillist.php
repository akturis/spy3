<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channels_maillist extends Model
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
    protected $table = 'channels_maillists';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'channel_id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'maillist'
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
     * Get the channel for this model.
     *
     * @return App\Models\Channel
     */
    public function channel()
    {
        return $this->belongsTo('App\Models\Channel','channel_id');
    }



}
