<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class SiteTranslation extends Model
{
    protected $table = 'sites_translations';
    protected $fillable = ['site_id','locale','name','alias','slug','description','brief','header','meta_description','meta_keywords','image_details','trans_status'];
    public $timestamps = false;

    public function getImagePathAttribute()
    {
        return (substr($this->image_details, 0, 4) === 'http') ? $this->image_details : (\Storage::exists($this->image_details ?? '') ? url(\Storage::url($this->image_details ?? '')) : asset('assets/img/logo2.png') );    }

}
