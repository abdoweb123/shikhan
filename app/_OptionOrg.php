<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Auth;
class OptionOrg extends Model
{
    public $table = "options";

    protected $fillable = [
        'type'	,'icon'	,'created_at',	'updated_at'
            ];



}
