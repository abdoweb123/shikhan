<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrizeData extends Model
{
  protected $table="prize_data";
    protected $fillable = [
       'user_id','emails','whatsapp','telegram','description','links','link_share','photo_share','note'
    ];





}
