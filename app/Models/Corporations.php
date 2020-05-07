<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corporations extends Model
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
    protected $table = 'corporations';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'corpID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'corpID',
                  'name',
                  'token',
                  'tracked',
                  'short_name'
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
     * Set the expires.
     *
     * @param  string  $value
     * @return void
     */
    public function setExpiresAttribute($value)
    {
        $this->attributes['expires'] = !empty($value) ? \DateTime::createFromFormat('[% date_format %]', $value) : null;
    }

    /**
     * Get expires in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getExpiresAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

}
