<?php

namespace App\Services;
use App\course_question;
use App\Models\Question;

class QuestionService
{


  public function getQuestionWithAnswersByLanguage($question, $language = null)
  {

      $language = $language ?? getDefaultLanguage()->alies;

      // return $course->questions_old()->translatedIn($language)->with(['answers' => function($q) use($language) {
      //     $q->translatedIn($language);
      // }])->orderBy('sequence', 'ASC')->get();

      return $question->load('translation','answers.translation');

  }

    public function loadQuestionWithAnswersByLanguage($question, $language = null)
    {
        $language = $language ?? getDefaultLanguage()->alies;
        return $question->load('translation','answers.translation');
    }

    public function ignoreAnswers($questionType)
    {
        return $questionType == Question::TYPE_ESSAY;
    }

    public function getRandomeQuestionsOfTestsIds($testIds = [], $showCount, $language = null)
    {
        return Question::whereIn('test_id', $testIds)->active()->with(['answers' => function($q) { $q->active(); }])
            ->inRandomOrder()->limit($showCount)->orderBy('sequence')->get();
    }

    public function getQuestionsByIds($questionsIds = [])
    {
        return Question::whereIn('id', $questionsIds)->active()->with(['answers' => function($q) { $q->active(); }])->get();
    }


}
