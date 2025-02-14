<?php

namespace App;

use App\Models\CourseTrack;
use App\Models\Lesson;
use App\Models\Lookup;
use App\Models\Test;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use App\libraries\DateHelper;

class course extends Model
{
    use Translatable,SoftDeletes;
    protected $table = 'courses';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'course_id';
    public $translatedAttributes = ['name','alias','subject','content','description','siteid','duration','video_duration','date_at','created_by','updated_by','header','meta_description','meta_keywords','short_code','image_details','trans_status'];
    public $translationModel = 'App\Translations\CourseTranslation';
    protected $fillable = ['title','the_same_id','languages','price','date','limit','site_id','category_id','post_ids','logo','status','exam_at','exam_approved','format','orientation','sort','max_trys','link','created_by','created_at','updated_by','updated_at','link_ended'
    ,'views_count','likes_count','link_arabiceasily'];

    const FOLDER_HTML = 'courses/html';
    const FOLDER_IMAGE =  'courses';


//    public function translation()
//    {
//        return $this->hasMany($this->translationModel);
//    }
//
//    public function sites()
//    {
//        return $this->belongsToMany('App\site', 'course_site', 'course_id', 'site_id')->withPivot(['term_id','main_site','certificate_template_name']);
//    }
//
    public function terms()
    {
        return $this->belongsToMany('App\Term', 'course_site', 'course_id', 'term_id')->withPivot(['site_id','main_site','certificate_template_name']);
    }

    public function getTirm($site_id = null)
    {
        if ($site_id){
          return $this->terms()->wherePivot('site_id', $site_id)->first();
        }

        // no site_id means we have the terms already loaded with cource and loaded with seleted site (like CourseController index)
        if (! $this->relationLoaded('terms')){
          return null;
        }

        if ($this->terms->isEmpty()){
          return null;
        }

        return $this->terms->first();


    }


    // public function getTirmRow()
    // {
    //     // when we load tirms with courses we get it where site_id so it loads only one record
    //     return $this->relationLoaded('terms') ? $this->terms->isNotEmpty() ? $this->terms->first() : null : null;
    // }

    public function subscribers()
    {
        return $this->belongsToMany('App\member','course_subscriptions','course_id','user_id');
    }

    public function lessons()
    {
        return $this->hasMany('App\Lesson');
    }

//    public function tests()
//    {
//        return $this->hasMany('App\course_test');
//    }

    public function tests()
    {
        // this test is for course or term or mybe diplom....
        return $this->morphMany(Test::class, 'testable');
    }



    public function questions_old()
    {
        return $this->hasMany('App\course_question');
    }

    // public function get_price()
    // {
    //     return $this->price > 0 ? $this->price .'L.E' : 'مجاني';
    // }

    public function getMaxTrys()
    {
        return $this->max_trys;
    }

    public function test_results()
    {
        return $this->hasMany('App\course_test_result');
    }

    public function final_results()
    {
        return $this->hasMany('App\MemberCoursesResult');
    }

    public function getImageDetailsPathAttribute()
    {
        return (substr($this->image_details, 0, 4) === 'http') ? $this->image_details : (\Storage::exists($this->image_details ?? 'nofile') ? url(\Storage::url($this->image_details ?? '')) : asset('assets/img/logo-imameen.png') );
    }

    public function getLogoPathAttribute()
    {
        return (substr($this->logo, 0, 4) === 'http') ? $this->logo : (\Storage::exists($this->logo ?? 'nofile') ? url(\Storage::url($this->logo ?? '')) : asset('assets/img/logo2.png') );
    }

    public static function default_logo()
    {
        $name = str_random(20);
        Storage::disk('storage')->copy('framework/backup/default/courses.png', 'app/public/courses/course-'.$name.'.png');
        return 'courses/course-'.$name.'.png';
    }

    public function getDateHijri($date)
    {
        $date = DateHelper::GregorianToHijri( strtotime($date) );
        return DateHelper::DateToShowHijri($date);
    }

    public function getExamAtDay()
    {
        if (!$this->attributes['exam_at'] ) {return '';}
        return __('dates.'.date('D', strtotime($this->attributes['exam_at'])));
    }

    public function getExamAtHijri()
    {
        if (!$this->attributes['exam_at'] ) {return '';}
        $date = DateHelper::GregorianToHijri( strtotime( $this->attributes['exam_at']));
        return DateHelper::DateToShowHijri($date);
    }

    public function isExamOpened()
    {
      $exam_at = date('Y-m-d H:i:s' ,strtotime($this->exam_at));
      return  $this->exam_at ? ($exam_at <= date('Y-m-d H:i:s')) : false;
    }

    public function getDateDay($date)
    {
        return __('dates.'.date('D', strtotime($date)));
    }

    public function scopeActive($query)
    {
       return $query->whereNULL('courses.deleted_at')
       ->where('courses.exam_at' ,'<=', date('Y-m-d') )
       ->where('courses.exam_at','!=', Null)
       ->whereNull('courses.deleted_at')
       ->where('courses.status',1)
       ;
    }


    public function translateFields( $fields = [])
    {
      return $this->translation()->where('locale', app()->getLocale())->select($fields)->first();
    }

    // public function courseVideosDuration()
    // {
    //   return $this->lessons->sum('video_duration');
    // }

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function course_track()
    {
        return $this->hasMany(CourseTrack::class);
    }

    public function trackLessons()
    {
        return $this->morphedByMany(Lesson::class, 'courseable', 'course_track')->withPivot('sort');
    }
    public function trackTests()
    {
        return $this->morphedByMany(Test::class, 'courseable', 'course_track')->withPivot('sort');
    }

    public function scopeWithLessonsCount($query)
    {
        return $query->withCount(['course_track', 'course_track as count_lessons' => function($q) { $q->lesson(); }]);
    }
    public function scopeWithTestsCount($query)
    {
        return $query->withCount(['course_track', 'course_track as count_tests' => function($q) { $q->test(); }]);
    }


    public function status()
    {
        return $this->belongsTo(Lookup::class, 'status_id', 'id');
    }


//    public function tests()
//    {
//        // this test is for course or term or mybe diplom....
//        return $this->morphMany(Test::class, 'testable');
//    }

//    public function lessons()
//    {
//        return $this->hasMany(Lesson::class);
//    }

    public function getTotalFee()
    {
        return $this->study_hours * $this->study_hour_fee;
    }

//    public function scopeActive($query)
//    {
//        return $query->where('status_id', Lookup::getActiveCourseStatus());
//    }

    // اكتف او جارى التجهيز
    public function scopeValid($query)
    {
        return $query->whereIn('status_id', [Lookup::getActiveCourseStatus(), Lookup::getPrepareCourseStatus()]);
    }


    public static function getList()
    {
        return course::all(['id','name']);
    }

    // الادمن يستطيع ان يضع الدرجة مباشرة للطالب
    public function canSetDegree()
    {
        return $this->can_set_degree == 1;
    }

    public function hasTrack()
    {
        if(count($this->trackLessons) == 0){
            return false;
        }
        return true;
    }

}
