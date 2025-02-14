<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Social extends Model
{
    use Translatable;
    public $table = "social";
    public $useTranslationFallback = true;
    public $translationForeignKey = 'social_id';
    public $translatedAttributes = ['social_id',	'locale',	'title',	'link'];
    public $translationModel = 'App\Translations\SocialTranslation';
    protected $fillable = ['titel_org',	'icon','created_at','updated_at'];
    protected $casts = [
        'languages' => 'array',
    ];
    const FOLDER_IMAGE = 'social';
    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }



}
