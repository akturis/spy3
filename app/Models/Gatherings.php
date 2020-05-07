<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gatherings extends Model
{
  protected $fillable = ['name'];
  
  public $timestamps = false;
  public function corporation()
  {
    return $this->belongsTo(Corporation::class);
  }    
  public function user()
  {
    return $this->belongsTo(User::class);
  }    
}
