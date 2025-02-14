<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table='options';
    protected $fillable = [
        'type','order','titleGeneral','required'
    ];

    public function option_info()
    {
        return $this->hasMany('App\OptionInfo', 'option_id');
    }

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
