<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Astrotomic\Translatable\Translatable;

class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Translatable;

    protected $table = 'teachers';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'teacher_id';
    public $translatedAttributes = ['locale','title','alias','qualification','specialization','position','description','header','meta_description','meta_keywords','trans_image','trans_status','created_by'];
    public $translationModel = 'App\Models\TeacherTranslation';
    protected $fillable = ['name','sort','image','birthday','country_id','phone','status','email','password'];

    const FOLDER_HTML = 'teachers/html';
    const FOLDER_IMAGE =  'teachers';

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function scopeWithTranslationOf($query, $locale = null)
    // {
    //     if (! $locale) {
    //       return $query;
    //     }
    //
    //     return $query->with(['translations' => function($q) use($locale){
    //         $q->where('locale', $locale);
    //     }]);
    //
    // }


    public function scopeActive($query)
    {
       return $query->where('status', 1);
    }

    public function isActive()
    {
       return $this->status == 1;
    }
}
