<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EveNotifs extends Model
{
    
    use Notifiable;
    public $discord_channel;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'evenotifs';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'notification_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'notification_id',
                  'sender_id',
                  'sender_type',
                  'text',
                  'type',
                  'timestamp'
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
    
    public function routeNotificationForDiscord()
    {
        return $this->discord_channel;
    }

    public function setDiscordChannel($discord_channel) {
        $this->discord_channel = $discord_channel;
    }

    /**
     * Get the notification for this model.
     *
     * @return App\Models\Notification
     */
    public function notification()
    {
        return $this->belongsTo('App\Models\Notification','notification_id');
    }

    /**
     * Get the sender for this model.
     *
     * @return App\Models\Sender
     */
    public function sender()
    {
        return $this->belongsTo('App\Models\Sender','sender_id');
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
