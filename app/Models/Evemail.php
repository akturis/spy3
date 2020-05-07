<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Evemail extends Model
{
    
    use Notifiable;
    
    public $discord_channel = "493874368762478594";

    public function routeNotificationForDiscord()
    {
        return $this->discord_channel;
    }

    public function setDiscordChannel($discord_channel) {
        $this->discord_channel = $discord_channel;
    }
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'evemails';

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
                  'from',
                  'mail_id',
                  'recipient_id',
                  'recipient_type',
                  'subject'
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
     * Get the mail for this model.
     *
     * @return App\Models\Mail
     */
    public function mail()
    {
        return $this->belongsTo('App\Models\Mail','mail_id');
    }

    /**
     * Get the recipient for this model.
     *
     * @return App\Models\Recipient
     */
    public function recipient()
    {
        return $this->belongsTo('App\Models\Recipient','recipient_id');
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

}
