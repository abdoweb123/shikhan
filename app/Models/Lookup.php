<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    protected $table = 'lookups';
    protected $fillable = ['type','title','alias','is_default','sort','status_id'];
    protected $casts = [
        'title' => 'array',
    ];

    public function getTitle($locale = null)
    {
        $locale = $locale ?? app()->getlocale();
        return isset($this->title[$locale]) ? $this->title[$locale] : '';
    }


    // pay_status
    public static function getPayedPayStatus() { return 15000; }

    public static function getFreePayStatus() { return 15001; }


    //-- enrolled pay statses
    public static function getNotPayedEnrollPayStatus() { return 3004; }

    public static function getNotReviewdEnrollPayStatus() { return 3000; }

    public static function getReviewingEnrollPayStatus() { return 3001; }

    public static function getNotCorrectEnrollPayStatus() { return 3002; }

    public static function getCorrectEnrollPayStatus() { return 3003; }

    public static function getDefaultEnrollPayStatus() { return static::getNotPayedEnrollPayStatus(); }


    //-- enrolled term pay statses
    public static function getNotPayedEnrollTermPayStatus() { return 4004; }

    public static function getNotReviewdEnrollTermPayStatus() { return 4000; }

    public static function getReviewingEnrollTermPayStatus() { return 4001; }

    public static function getNotCorrectEnrollTermPayStatus() { return 4002; }

    public static function getCorrectEnrollTermPayStatus() { return 4003;    }

    public static function getDefaultEnrollTermPayStatus() { return static::getNotReviewdEnrollTermPayStatus(); }


    //-- enrolled term course pay statses
    public static function getNotPayedEnrollTermCoursePayStatus() { return 19000; }

    public static function getReviewingEnrollTermCoursePayStatus() { return 19001; }

    public static function getNotCorrectEnrollTermCoursePayStatus() { return 19002; }

    public static function getCorrectEnrollTermCoursePayStatus() { return 19003; }


    //-- enrolled appreoved statuses
    public static function getNotReviewdEnrollAppreovedStatus() { return 5000; }

    public static function getReviewingEnrollAppreovedStatus() { return 5001; }

    public static function getDataNotCompleteEnrollAppreovedStatus() { return 5002; }

    public static function getReviewdWithErrorEnrollAppreovedStatus() { return 5003; }

    public static function getRejectedEnrollAppreovedStatus() { return 5004; }

    public static function getAcceptedEnrollAppreovedStatus() { return 5005; }

    public static function getDefaultEnrollAppreovedStatus() { return static::getNotReviewdEnrollAppreovedStatus(); }


    //-- enrolled Term appreoved statuses
    public static function getNotReviewdEnrollTermAppreovedStatus() { return 6000; }

    public static function getReviewingEnrollTermAppreovedStatus() { return 6001; }

    public static function getChangeChoicesEnrollTermAppreovedStatus() { return 6002; }

    public static function getReviewdWithErrorEnrollTermAppreovedStatus() { return 6003; }

    public static function getRejectedEnrollTermAppreovedStatus() { return 6004; }

    public static function getAccepteddEnrollTermAppreovedStatus() { return 6005; }

    public static function getDefaultEnrollTermAppreovedStatus() { return static::getNotReviewdEnrollTermAppreovedStatus(); }


    //-- enrolled Study statuses
    public static function getInCompletedEnrollStudyStatus() { return 7000; }

    public static function getEnrolledEnrollStudyStatus() { return 7001; }

    public static function getStudyingEnrollStudyStatus() { return 7002; }

    public static function getStudyingAndFinishedTermEnrollStudyStatus() { return 7003; }

    public static function getFinishedEnrollStudyStatus() { return 7004; }

    public static function getDefaultEnrollStudyStatus() { return static::getEnrolledEnrollStudyStatus(); }


    //-- enrolled Current statuses
    public static function getCurrentEnrollCurrentStatus() { return 17000; }

    public static function getDelayedEnrollCurrentStatus() { return 17001; }

    public static function getFinishedEnrollCurrentStatus() { return 17002; }

    public static function getDefaultCurrentStatus() { return static::getCurrentEnrollCurrentStatus(); }


    //-- enrolled Term Study statuses
    public static function getEnrolledEnrollTermStudyStatus() { return 8000; }

    public static function getStudyingEnrollTermStudyStatus() { return 8001; }

    public static function getFinishedEnrollTermStudyStatus() { return 8002; }

    public static function getDefaultEnrollTermStudyStatus() { return static::getEnrolledEnrollTermStudyStatus(); }


    //-- enrolled Term Course Study statuses
    public static function getEnrolledEnrollTermCourseStudyStatus() { return 16000; }

    public static function getStudyingEnrollTermCourseStudyStatus() { return 16001; }

    public static function getFinishedEnrollTermCourseStudyStatus() { return 16002; }

    public static function getDefaultEnrollTermCourseStudyStatus() { return static::getEnrolledEnrollTermCourseStudyStatus(); }



    // how to study courses
    public static function getExactRealStudyStatus() { return 11000; }

    public static function getEqualRealStudyStatus() { return 11001; }

    public static function getDefaultRealStudyStatus() { return Static::getExactRealStudyStatus(); }


    // certificate_study_type
    public static function getCertificateCoursesStudyStatus() { return 21000; } // دراسة مواد

    public static function getCertificateResearchStudyStatus() { return 21001; } // دراسة بحثية

    // pay types (the same as fee types) and extra is studyAndterm togther
    // لان الطالب يقوم بالدفع على الموقع بايصال واحد اجمالى
    // مصاريف الدراسة ومصاريف التيرم التى هيا الساعات الدراسية للمواد
    public static function getEnrollFeeType() { return 13000; }

    public static function getStudyAndTermFeeType() { return 13002; }


    //-- Success statuses - for all
    public static function getNotDetrminedSuccessStatus() { return 12000; }

    public static function getSuccessSuccessStatus() { return 12001; }

    public static function getFailedSuccessStatus() { return 12002; }

    public static function getDefaultSuccessStatus() { return static::getNotDetrminedSuccessStatus(); }


    //-- for all default status
    public static function getDefaultStatus() { return 1; }

    // statuses used in all
    public static function getActiveStatus() { return 1; }


    // course statuses
    public static function getActiveCourseStatus() { return 18000; }

    public static function getPrepareCourseStatus() { return 18001; }

    public static function getSpecificQuestionsStatus() { return 9000; }

    public static function getRandomQuestionsStatus() { return 9001; }


    // lesson_study_type
    public static function getDataLessonStudyType() { return 22000; }

    public static function getResearchLessonStudyType() { return 22001; }


    public function scopeActive($query) { return $query->where('status_id', 1); }



    public function getHtmlTitle($status_id, $locale = null)
    {
        return @[
          static::getCurrentEnrollCurrentStatus() =>
            '<span style="background-color: #3fff8a;padding: 5px 16px;border-radius: 5px;color: #018434;font-size: 16px;font-weight: bold;display: block ruby;">'.$this->getTitle($locale).'</span>',
          static::getDelayedEnrollCurrentStatus() =>
            '<span style="background-color: #ffcf3f;padding: 5px 16px;border-radius: 5px;color: #97740c;font-size: 16px;font-weight: bold;display: block ruby;">'.$this->getTitle($locale).'</span>',
        ][$status_id];

    }


}
