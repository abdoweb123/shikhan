<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use DB;

class site extends Model
{
    use Translatable, SoftDeletes;
    public $table = "sites";
    public $useTranslationFallback = true;
    public $translationForeignKey = 'site_id';
    public $translatedAttributes = ['name','slug','description','header','meta_description','meta_keywords','brief','image_details','header_success','meta_description_success','meta_keywords_success'];
    public $translationModel = 'App\Translations\SiteTranslation';
    // protected $fillable = ['name','alias','sort','languages','logo','parent_id','status','created_by','updated_by','created_at','likes_count','views_count','updated_by','updated_at','new_flag','conditions','short_link','advanced_certificate_sort'];
    protected $fillable = ['title','parent_id','logo','new_flag','conditions','likes_count','views_count','short_link','certificate_template','sort','advanced_certificate_sort','status','deleted_at','created_by','updated_by','created_at','updated_at'];
    protected $casts = [
        'conditions' => 'array'
    ];
    const FOLDER_IMAGE = 'sites';

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function specialized_sites()
    {
        return $this->belongsToMany('App\SpecializedSite', 'site_specialized_site', 'site_id', 'specialized_site_id');
    }

    public function courses()
    {
        return $this->belongsToMany('App\course', 'course_site', 'site_id', 'course_id')->withPivot('main_site','short_link');
    }

    public function terms()
    {
        return $this->hasMany('App\Term');
    }

    public function site_subscription()
    {
        return $this->hasMany('App\SiteSubscription');
    }

    public function member_site_certificate()
    {
        return $this->belongsToMany('App\member', 'member_site_certificate', 'site_id', 'user_id')->withPivot('code');
    }

    public function extra_certificates()
    {
        return $this->hasMany('App\ExtraCertificate');
    }


    public function scopeMainSite($query)
    {
        return $query->where('main_site', 1);
    }

    public function scopeCurrentSite($query,$site_id)
    {
        return $query->where('sites.id', $site_id);
    }

    public function hasCondidtion1()
    {
        // user must finish at least one of the old sites
        return array_search(1, $this->conditions ?? []) !== false;
    }

    public function hasCondidtion2()
    {
        // user must finish debendent sites
        return array_search(2, $this->conditions ?? []) !== false;
    }

    public function hasCondidtion3()
    {
        // user can't print cirtfectae until finish at least one of the old sites and show message with this
        return array_search(3, $this->conditions ?? []) !== false;
    }

    public function hasCondidtion4()
    {
        // user can't print cirtfectae until finish debendent sites
        return array_search(4, $this->conditions ?? []) !== false;
    }

    public function getLogoPathAttribute()
    {
        return (substr($this->logo, 0, 4) === 'http') ? $this->logo : (\Storage::exists($this->logo ?? 'nofile') ? url(\Storage::url($this->logo)) : asset('assets/img/logo2.png') );
    }

    public function getImageDetailsPathAttribute()
    {
        return (substr($this->image_details, 0, 4) === 'http') ? $this->image_details : (\Storage::exists($this->image_details ?? 'nofile') ? url(\Storage::url($this->image_details ?? '')) : asset('assets/img/logo-imameen.png') );
    }

    public static function default_logo()
    {
        $name = str_random(20);
        Storage::disk('storage')->copy('framework/backup/default/sites.png', 'app/public/sites/site-'.$name.'.png');
        return 'sites/site-'.$name.'.png';
    }
    public function getstatus()
    {
      return $this->status==1 ? 'نشط':'غير نشط';
    }

    public function scopeValid($query)
    {
       return $query->where('status',1)->whereNULL('deleted_at');
    }

    public function scopeNew($query)
    {
       return $query->where('new_flag',1);
    }

    public function scopeOld($query)
    {
       return $query->where('new_flag',0);
    }

    public function scopeNewest($query)
    {
       return $query->where('new_flag',2);
    }

    public function getVideosDuration()
    {
        return DB::Table('sites')
        ->join('course_site','sites.id','course_site.site_id')
        ->join('courses_translations','course_site.course_id','courses_translations.course_id')
        ->where('course_site.site_id',$this->id)
        ->where('locale', app()->getLocale())
        ->select(DB::raw(" SEC_TO_TIME( SUM(time_to_sec(courses_translations.video_duration))) as videos_duration"))
        ->first()->videos_duration;
    }

    public function getSubscriptionsCount()
    {
        return $this->site_subscription()->count();
    }


    public function validCourses($get, $locale = null)
    {
        // عدد الدورات فى الدبلوم - فقط الغير ملغاة
        $valid = $this->courses()->whereNULL('courses.deleted_at')->when($locale, function($q) use($locale){
          return $q->whereRelation('translations', 'locale', '=', $locale);
        });
        if ($get == 'count'){ return $valid->count(); }
        return $valid->get();
    }

    public function examsStillClosed($get, $locale = null)
    {
        // الاختبارات التى لم تفتح حتى الان
        $closed = $this->courses()
          ->whereNULL('courses.deleted_at')
          ->where(function($q){
              $q->whereNull('exam_at')->orwhere('courses.exam_at', '>', date('Y-m-d H:i:s'))->orwhere('exam_approved',0);
          })
          ->when($locale, function($q) use($locale){
            return $q->whereRelation('translations', 'locale', '=', $locale);
          });

          if ($get == 'count'){ return $closed->count(); }
          return $closed->get();
    }

    public function isAllExamsOpened($locale = null)
    {
        // هل كل اختبارات الدبلوم تم فتحها
        return $this->examsStillClosed('count', $locale) > 0 ? false : true;
    }

    public function fullDegree()
    {
        return $this->validCourses('count') * 100;
    }

    public function termsCount()
    {
        return $this->terms->count();
    }

    public function scopeRoot($query)
    {
       return $query->where('parent_id', 0);
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id');
    }



}
