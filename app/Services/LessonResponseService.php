<?php

namespace App\Services;
use App\Models\LessonResponse;

class LessonResponseService
{

  public function getStudentLessonResponsesWithChilds($student, $lesson)
  {
      return $student->lesson_responses()->root()
        ->with(['lesson_response_files','allChilds'])
        ->where('lesson_id', $lesson->id)
        ->get();
  }





}
