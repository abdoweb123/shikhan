<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class SpecializedSite extends Model
{
    use Translatable, SoftDeletes;
    public $table = "specialized_sites";
    public $useTranslationFallback = true;
    public $translationForeignKey = 'specialized_site_id';
    public $translatedAttributes = ['name','alias'];
    public $translationModel = 'App\Translations\SpecializedSiteTranslation';
    protected $fillable = ['title','status','certificate_template','sort'];

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function sites()
    {
        return $this->belongsToMany('App\site', 'site_specialized_site', 'specialized_site_id', 'site_id'); // ->withPivot('main_site','short_link');
    }

    public function scopeValid($query)
    {
       return $query->where('status',1)->whereNULL('deleted_at');
    }

}
