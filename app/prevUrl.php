<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class prevUrl extends Model
{
    protected $connection = 'elmacademy_db_util';
    protected $table="prev_urls";

    protected $fillable = [
       'type','url','groupBy','table_id'
    ];


    public function site()
    {
        return  $this->belongsTo('App\site','table_id');
    }
    public function course()
    {
        return  $this->belongsTo('App\course','table_id');
    }

}
