<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageInfo extends Model
{
    protected $table='page_info';
    protected $fillable = [
        'page_id','language','title','alias','description','meta_description','meta_keywords','header','template_id','template','image','video','ip','is_active'
    ];

    public function imagePath()
    {
        return asset('storage/'.$this->image);
//        return  $image = Storage::get($this->image);
    }

    public function htmlPath()
    {
        // return asset('storage/'.$this->description);
        return  (Storage::exists($this->description ?? '') ? url(Storage::url($this->description ?? '')) : '' );
    }

}
