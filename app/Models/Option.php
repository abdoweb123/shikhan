<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Option extends Model
{
    protected $table='options';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'option_id';
    public $translatedAttributes = ['locale_id','title'];
    public $translationModel = 'App\Models\OptionTranslation';
    protected $fillable = [
        'type','order','name','required'
    ];

    const FOLDER_HTML = 'options/html';
    const FOLDER_IMAGE =  'options';

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    function isVideo()
    {
      return $this->alias == 'video';
    }

    function isSound()
    {
      return $this->alias == 'sound';
    }

    function isPdfRead()
    {
      return $this->alias == 'pdf_read';
    }

    function isPdfDownload()
    {
      return $this->alias == 'pdf_download';
    }

    function isDocRead()
    {
      return $this->alias == 'doc_read';
    }

    function isDocDownload()
    {
      return $this->alias == 'doc_download';
    }

    function isSource()
    {
      return $this->alias == 'source';
    }

}
