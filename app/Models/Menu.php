<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    const FOLDER = 'menus';
    const PAGE = 'menu';


    protected $fillable = [
        'title','parent_id','menuable_id','menuable_type','sort','is_active'
    ];

    public function parent()
    {
        return $this->hasOne('App\Models\Menu','id', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // morph
    public function userable()
    {
        return $this->morphTo();
    }

    public function isContent()
    {
        return $this->userable_type == 'App\Models\Content';
    }

    public function isPage()
    {
        return $this->userable_type == 'App\Models\Page';
    }


    public function imagePath()
    {
        if (!$this->image){
          return null;
        }
        return asset('storage/app/public/'.$this->image);
    }

    public function scopeRoot($query)
    {
        return $query->where('parent_id', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function isActive($value)
    {
        return $this->is_active == $value;
    }

}
