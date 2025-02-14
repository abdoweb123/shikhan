<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Partner extends Model
{
    use Translatable;

    public $useTranslationFallback = true;
    public $translationForeignKey = 'partner_id';
    public $translatedAttributes = ['title', 'alias', 'description', 'header', 'meta_description', 'meta_keywords','image','trans_status'];
    public $translationModel = 'App\Translations\PartnerTranslation';
    protected $fillable = [
       'id','name','country_id','logo','status','sort','updated_at','created_at'
    ];

    const FOLDER = 'partners';
    const FOLDER_IMAGE = 'partners/images';
    const FOLDER_HTML = 'partners/html';

    protected $table="partners";



    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function country()
    {
        return $this->belongsTo('App\Country','country_id');
    }

    public function scopeActive($query)
    {
       return $query->where('status', 1 );
    }

   public function getLogoPathAttribute()
    {
        $image= (substr($this->logo, 0, 4) === 'http') ? $this->logo : (\Storage::exists($this->logo ?? 'nofile') ? url(\Storage::url($this->logo ?? '')) : url(\Storage::url($this->logo ?? ''))  );
        return $image==null ? asset('assets/img/default/user.png') : $image;
    }



    public function codes()
    {
        return $this->hasMany('App\PartnerCode');
    }

}
