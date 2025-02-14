<?php

namespace App\Services;
use App\Models\Skill;
use App\Models\Content;
use App\Models\ContentInfo;
use DB;
use App\helpers\UtilHelper;
class SkillService
{

  public function getActiveSkills()
  {
    return Skill::Active()->get();
  }


   public function getSkillsForTrainings()
  {

        $skills= self::getActiveSkills();
      $SkillsForTrainings=[];
     foreach( $skills as  $kay => $skill){

         $skills= DB::table('skills')->join('lesson_skill','lesson_skill.skill_id','skills.id')
                ->join('lessons','lessons.id','lesson_skill.lesson_id')
                ->where('lessons.lesson_type_id',4)->where('skills.id',$skill->id)->select('skills.id','skills.title','lessons.id as lesson_id');
        $SkillsForTrainings[$skill->id]['Q']=$skills->get()->count();

        $skills_user= $skills->join('student_degrees','student_degrees.lesson_id','lessons.id')
        ->where('student_degrees.student_id',auth()->user()->userable->id)
        ->where('student_degrees.total_score','>','lessons.success_score'.-1)
        ->select('student_degrees.total_score','lessons.success_score')->count();
         $SkillsForTrainings[$skill->id]['A']=$skills_user;
        }


        return $SkillsForTrainings;
  }
   public function getSkillsForContents()
  {

        // $contents=  self::getContentsTreeOfCourse(1,null);
        $Allskills= Skill::get();
            $data = Content::Details('ar')->ofCourse(1)->orderBy('sort')->get();
            $parents=Content::Active()->where('parent_id',0)->get();
        //   $SkillsForTrainings=[];
          $SkillsForContents=[];

          foreach( $parents as  $kay_p => $parent){
            //   $data = UtilHelper::buildTreeRoot( $data, $exceptId, $temp , 0, 0 ) ;
          $parent_id= $parent->id;
              $temp=[];
            $childs = UtilHelper::buildTreeRoot( $data, null, $temp , $parent_id, 0 ) ;
            // return $skills;

                 foreach( $Allskills as  $kay_s => $oneskill){

                        $skill_id=$oneskill->id;
                        $SkillsForContents[$parent_id][$skill_id]['Q']=0;
                        $SkillsForContents[$parent_id][$skill_id]['A']=0;

                        foreach( $childs as  $kay_ch => $child){
                         $skills= DB::table('skills')->join('lesson_skill','lesson_skill.skill_id','skills.id')
                            ->join('lessons','lessons.id','lesson_skill.lesson_id')
                            ->where('lessons.lesson_type_id',4)->where('lessons.content_id',$child->id)->where('skills.id',$skill_id)->select('skills.id','skills.title','lessons.content_id','lessons.id as lesson_id');
                            if($skills->count() >= 1){
                                $SkillsForContents[$parent_id][$skill_id]['Q']=$SkillsForContents[$parent_id][$skill_id]['Q']+$skills->count();
                                $skills_user= $skills->join('student_degrees','student_degrees.lesson_id','lessons.id')
                                    ->where('student_degrees.student_id',auth()->user()->userable->id)
                                    ->where('student_degrees.total_score','>','lessons.success_score'.-1)
                                    ->select('student_degrees.total_score','lessons.success_score')->count();
                            $SkillsForContents[$parent_id][$skill_id]['A']=$SkillsForContents[$parent_id][$skill_id]['A']+$skills_user;
                            }


                    }
                 }
          }






        return $SkillsForContents;
  }



   public function getScoreSkills()
  {


    $getScoreSkills= DB::table('lessons')
        ->join('student_degrees','student_degrees.lesson_id','lessons.id')
        ->where('lessons.lesson_type_id',4)
        ->where('student_degrees.student_id',auth()->user()->userable->id)
        ->select(DB::raw("SUM(student_degrees.total_score) as student_total_score"),DB::raw("SUM(lessons.total_score) as lessons_total_score"))->get();


        return $getScoreSkills;
  }


}
