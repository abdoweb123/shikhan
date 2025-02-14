<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberTermsResult extends Model
{

    public $table = "member_terms_results";
    protected $fillable = [
        'user_id', 'site_id', 'term_id', 'courses_count', 'tests_count', 'user_successed', 'test_no_test', 'test_degree', 'test_rate', 'test_id', 'test_locale', 'test_code', 'test_created_at'
    ];


    // public function site_translation()
    // {
    //     return $this->belongsTo('App\Translations\SiteTranslation', 'site_id', 'site_id')->where('locale', app()->getLocale());
    // }
    //
    // public function term_translation()
    // {
    //     return $this->belongsTo('App\Translations\TermTranslation', 'term_id', 'term_id')->where('locale', app()->getLocale());
    // }
    //
    // public function scopeSuccessed($query)
    // {
    //     return $query->where('test_degree', '>=',  pointOfSuccess());
    // }
    //
    // public function scopeNotSuccessed($query)
    // {
    //     return $query->where('test_degree', '<',  pointOfSuccess());
    // }

}
