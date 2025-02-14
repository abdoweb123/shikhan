<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraCertificate extends Model
{
    protected  $table="extra_certificates";
    protected $fillable = ['site_id', 'titles', 'alias', 'certificate_template','params','download_translation'];
    protected $casts = [
        'titles' => 'array',
        'certificate_template' => 'array',
        'params' => 'array'
    ];
    public  $timestamps= false;

    public function getTitle($locale = null)
    {
        $locale = $locale ?? app()->getlocale();
        return isset($this->titles[$locale]) ? $this->titles[$locale] : '';
    }

}
