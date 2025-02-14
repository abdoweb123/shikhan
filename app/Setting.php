<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Setting extends Model
{
    use Translatable;
    public $table = "settings";
    public $useTranslationFallback = true;
    public $translationForeignKey = 'site_id';
    public $translatedAttributes = ['setting_id',	'locale',	'title',	'link',	'icon'];
    public $translationModel = 'App\Translations\SettingTranslation';
    protected $fillable = ['titel_org','created_at','updated_at'];
    protected $casts = [
        'languages' => 'array',
    ];
    const FOLDER_IMAGE = 'settings';
    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }



}
