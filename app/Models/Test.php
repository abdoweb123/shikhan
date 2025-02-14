<?php

namespace App\Models;

use App\course;
use App\Models\Lesson;
use App\Teacher;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Storage;

class Test extends Model
{
    use Translatable;
    protected $table = 'tests';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'test_id';
    public $translatedAttributes = ['title','alias','trans_status'];
    public $translationModel = 'App\Models\TestTranslation';
    protected $fillable =
        ['name','testable_id','testable_type','teacher_id','show_count','duration', 'attempts_count',
            'test_type_id','get_questions','percentage','extra_try_fee','created_by_admin_id',
            'created_by_teacher_id','status'
        ];

    const FOLDER_HTML = 'tests/html';
    const FOLDER_IMAGE =  'tests';


    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'test_lesson', 'test_id', 'lesson_id');
    }

    public function courses()
    {
        return $this->morphToMany(Course::class, 'courseable', 'course_track');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function test_type()
    {
        return $this->belongsTo(Lookup::class, 'test_type_id', 'id');
    }

    public function test_results()
    {
        return $this->hasMany(TestResult::class);
    }

    public function seen()
    {
        // is test seen
        return $this->morphOne(StudentSeen::class, 'seenable');
    }

    public function testable()
    {
        // this test is for course or term or mybe diplom....
        return $this->morphTo();
    }

    public function scopeWithDetails($query)
    {
//      return $query->with(['lessons:id', 'teacher:id', 'testable:id', 'test_type:id,title']);
      return $query->with(['lessons:id', 'teacher:id', 'testable:id', ]);
    }

    public function scopeWithActiveFullDetails($query)
    {
        return $query->with(['lessons:id', 'teacher:id', 'testable:id', 'test_type:id,title']);
    }

    public function isSpecificQuestions()
    {
        return $this->get_questions == Lookup::getSpecificQuestionsStatus();
    }

    public function isRandomQuestions()
    {
        return $this->get_questions == Lookup::getRandomQuestionsStatus();
    }

}
