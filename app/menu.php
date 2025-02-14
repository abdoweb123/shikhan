<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    protected  $table="menu";
     public function menu_details()
    {
        return $this->hasMany('App\menu_details','menu_id');
    }
}
