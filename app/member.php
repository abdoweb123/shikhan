<?php

namespace App;

use App\Models\TestResult;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Notifications\resetGeneratePassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


use DB;

class member  extends Authenticatable //implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'name_lang','name_search','user_locale','email', 'password','gender','birthday','provider','qualification',
        'country_id','phone','avatar','created_by','updated_at','status','join_in','ip','country_name_out','country_name','teacher_id',
        'id_number','id_image','error_email','free_status','partner_id','partner_code','discount','pay_amount','pay_image','currency_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['AvatarPath','IdImagePath'];

    protected $guard_name = 'web';
    // protected $table = 'fadamedia_baldatayiba.members';
    protected $table = 'members';

    public $timestamps = true;

    const NOT_PAID = 1;
    const PAID_SUSPENDED = 2; // سيتم مراجعة الدفع
    const PAID = 3;
    const FREE = 4;



    public function courses()
    {
        return $this->belongsToMany('App\course','course_subscriptions','user_id','course_id')->withTimestamps();
    }

    public function sites()
    {
        return $this->belongsToMany('App\site','site_subscriptions','user_id','site_id')->withTimestamps();
    }

    public function prizesDatas()
    {
        return $this->hasMany('App\PrizeData','user_id');
    }

    public function prizes()
    {
       return $this->hasMany('App\PrizeUser','user_id');
    }

    public function lessons()
    {
        return $this->belongsToMany('App\LessonOld','completed_lessons','user_id','lesson_id');
    }

    public function tests()
    {
        return $this->hasMany('App\course_test','user_id');
    }

//    public function test_results()
//    {
//        return $this->hasMany('App\course_test_result','user_id');
//    }

    public function test_results()
    {
        return $this->hasMany(TestResult::class,'student_id');
    }

    public function course_test_results()
    {
        return $this->hasMany(course_test_result::class,'user_id');
    }

    public function term_results()
    {
        return $this->hasMany('App\TermTestResult','user_id');
    }


    public function course_tests_visual()
    {
        return $this->hasMany('App\CourseTestVisual','user_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function member_global_certificates()
    {
        return $this->hasMany('App\MemberGlobalCertificate','user_id');
    }

    public function extra_trays()
    {
        return $this->hasMany('App\MemberExtraTray','user_id');
    }

    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }

    public function setCountryIdAttribute($value)
    {
        if(! $value){
          $countryService = new \App\Services\CountryService();
          $this->attributes['country_id'] = $countryService->getDefaultCountry(['id'])->id;
        } else {
          $this->attributes['country_id'] = $value;
        }
    }


    public function getNationality($language = null)
    {
        $country = $this->country()->first();
        if (! $country){
          return '';
        }

        $language = $language ?? app()->getlocale();

        $name = json_decode($country->name,true );
        return isset($name[$language]) ? $name[$language] : '';

    }

    public function getAvatarPathAttribute()
    {
        if($this->avatar){
          if( str_starts_with($this->avatar, "https") ){
            return $this->avatar;
          }
        }

        return $this->avatar ? \Storage::exists($this->avatar) ? \Storage::url($this->avatar) : asset('assets/img/default/empty.png') : null;
    }

//    public function getAvatarPath64Attribute()
//    {
//        return $this->avatarPath ? base64_encode(file_get_contents($this->avatarPath)) : null;
//    }

    public function getAvatarPath64Attribute()
    {
        if (!empty($this->avatarPath) && file_exists($this->avatarPath)) {

            $fileContents = file_get_contents($this->avatarPath);
            if ($fileContents !== false) {
                return base64_encode($fileContents);
            } else {
                // Unable to read file contents
                return null;
            }

        } else {
            // File does not exist or is not specified
            return null;
        }
    }

    public static function default_avatar()
    {
        $name = \Str::random(20);
        Storage::disk('storage')->copy('framework/backup/default/members.jpg', 'app/public/members/member-'.$name.'.jpg');
        return 'members/member-'.$name.'.jpg';
    }

    public function getIdImagePathAttribute()
    {
        return $this->id_image ? \Storage::exists($this->id_image) ? \Storage::url($this->id_image) : asset('assets/img/default/empty.png') : '';
    }

//    public function getIdImagePath64Attribute()
//    {
//        return $this->IdImagePath ? base64_encode(file_get_contents($this->IdImagePath)) : null;
//    }

    public function getIdImagePath64Attribute()
    {
        if (!empty($this->IdImagePath) && file_exists($this->IdImagePath)) {

            $fileContents = file_get_contents($this->IdImagePath);
            if ($fileContents !== false) {
                return base64_encode($fileContents);
            } else {
                // Unable to read file contents
                return null;
            }

        } else {
            // File does not exist or is not specified
            return null;
        }
    }


    public function registered_from_extrnal()
    {
        return $this->hasMany('App\PrizeUserOutside','user_id');
    }

    public function courses_results()
    {
        return $this->hasMany('App\MemberCoursesResult','user_id');
    }

    public function sites_results()
    {
        return $this->hasMany('App\MemberSitesResult','user_id');
    }

    public function isUserSubscribedInSite($site_id)
    {
        return $this->sites()->where('sites.id', $site_id)->exists();
    }


    // will reblecd with testsCount under it
    // public function countTestsOfSite($site_id)
    // {
    //     return DB::Table('course_tests_results')
    //     ->join('course_site','course_tests_results.course_id','course_site.course_id')
    //     ->where('user_id',$this->id)->where('course_site.site_id',$site_id)
    //     ->select('course_tests_results.id')
    //     ->groupBy('course_site.site_id')->groupBy('course_site.course_id')
    //     ->get()->count();
    // }

    public function testsCount(int $site_id = null, string $locale = null)
    {
        // عدد اختبارات الطالب عامة او فى دبلوم
        return $this->courses_results()
            ->when($site_id, function($q) use($site_id){
                $q->where('site_id', $site_id);
            })
            ->when($locale, function($q) use($locale){
                $q->where('locale', $locale);
            })->count();
    }

    public function courseTestsCount(int $course_id = null, string $locale = null)
    {
        // عدد اختبارات الطالب فى دورة
        return $this->test_results()
            ->where('course_id', $course_id)
            ->when($locale, function($q) use($locale){
                $q->where('locale', $locale);
            })->count();
    }

    public function siteDegree($site_id, $locale = null)
    {
        // درجة الدبلوم - مجموع درجات الدورات لهذا الدبلوم
        return $this->courses_results()
          ->where('site_id', $site_id)
          ->when($locale, function($q) use($locale){
              return $q->where('locale', $locale);
          })->sum('test_degree');
    }

    public function isSuccessedInSite($site_id, $locale)
    {
        // يجب ان تكون درجة كل دورة فى الدبلوم اكبر من 50
        // اذا وجدت دورة واحدة فى الدبلوم اقل من 50 يعتبر راسب
        // لا يمكن استخدم جدول member_sites_result
        // لان هنا ناتى بالنتيجة التى على اساسها يتم تسجيل نجاح الطالب او لأ
        return $this->courses_results()->where('site_id', $site_id)->where('test_degree', '<' , pointOfSuccess())
          ->when($locale, function($q) use($locale){
              return $q->where('locale', $locale);
          })
          ->exists() ? false : true;
    }

    public function isAlreadySuccessedInSite($site_id)
    {
        return $this->sites_results()->successed()->where('site_id', $site_id)->exists();
    }

    public function isSuccessedInSites($sitesIds=[])
    {
        // هل الطالب نجح فى هذه الدبلومات
        if (! count($sitesIds)){
          return false;
        }
        return $this->sites_results()->successed()->wherein('site_id', $sitesIds)->count() == count($sitesIds);
    }

    public function countSuccessedSites()
    {
        return $this->sites_results()->successed()->count();
    }

    public function sumDegreesOfSites($sitesIds=[])
    {
        // مجموع درجات الطالب فى دبلومات معينة
        return $this->sites_results()->when(! empty($sitesIds) , function($query) use($sitesIds){
            return $query->wherein('site_id', $sitesIds);
        })->sum('user_site_degree');
    }

    public function maxCreatedAtOfSite($site_id, $locale = null)
    {
        return DB::Table('course_tests_results')
          ->join('course_site','course_tests_results.course_id','course_site.course_id')
          ->where('user_id',$this->id)->where('course_site.site_id',$site_id)->where('course_tests_results.locale',$locale)
          ->max('course_tests_results.created_at');
    }


    public function countTestsOfRange($range, $site_id=null, $locale = null)
    {
        // عدد الدورات فى رنج درجات
        return $this->courses_results()->where('test_degree', '>=' , $range[0])->where('test_degree', '<=' , $range[1])
          ->when( $site_id , function ($q) use($site_id) {
              return $q->where('site_id', $site_id);
          })
          ->when( $locale , function ($q) use($locale) {
              return $q->where('locale', $locale);
          })
          ->distinct('course_id')->count();
    }



    public function getPayImagePathAttribute()
    {
        return $this->pay_image ? \Storage::exists($this->pay_image) ? \Storage::url($this->pay_image) : asset('assets/img/default/empty.png') : null;
    }

    // free - pay status
    public function scopeNotPaid($query)
    {
       return $query->where('free_status', static::NOT_PAID);
    }

    public function scopeFree($query)
    {
       return $query->where('free_status', static::FREE);
    }

    public function scopePaid($query)
    {
       return $query->where('free_status', static::PAID);
    }

    public function scopeSuspended($query)
    {
       return $query->where('free_status', static::PAID_SUSPENDED);
    }

    public function scopeHasPartner($query)
    {
       return $query->where('partner_id', '!=', 0);
    }

    public function scopeNoPartner($query)
    {
       return $query->where('partner_id', 0);
    }



    public function isNotPaid()
    {
       return $this->free_status == static::NOT_PAID;
    }

    public function isFree()
    {
       return $this->free_status == static::FREE;
    }

    public function isPaid()
    {
       return $this->free_status == static::PAID;
    }

    public function isSuspended()
    {
       return $this->free_status == static::PAID_SUSPENDED;
    }

    public function freeStatusTitle()
    {
       return __('trans.free_status_'.$this->free_status);
    }

    public function member_extra_certificates()
    {
        return $this->hasMany('App\MemberExtraCertificate','user_id');
    }

}
