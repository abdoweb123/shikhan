<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

use App\Models\pSupervisorData;
use App\Models\pSchoolData;
use App\Models\pSchoolStatistic;
use App\Models\pAdminStatistic;
use App\Models\pAdminStatisticsJobTitle;
use App\Models\Department;
use App\Models\DepartmentType;

class StatisticsService_not
{

  public function getAdminStatistics( $departmentId )
  {
      $data = pAdminStatistic::OfDepartment($departmentId)->first();
      return $data;
  }

  public function getpAdminStatisticsJobTitle( $departmentId )
  {
      $data = pAdminStatisticsJobTitle::OfDepartment($departmentId)->get();
      return $data;
  }

  public function getSupervisorStatistics( $schoolYear , $departmentId , $employeeId = null )
  {

      $types = [
        pSupervisorData::FIELD_VISIT,
        pSupervisorData::TRAINING_PROGRAM,
        pSupervisorData::WORKSHOP_IMPLEMENTATION,
        pSupervisorData::APPLIED_LESSON,
        pSupervisorData::HOUR_EDUCATIONAL,
        pSupervisorData::MONTHLY_ACHIEVEMENTS,
        pSupervisorData::INITIATIVES,
        pSupervisorData::AWARDS,
        pSupervisorData::DEVELOPMENT_PROGRAMS
      ];

      $data = [];

      foreach ($types as $type) {
        $sta = pSupervisorData::OfDataType($type)->OfSchoolYear($schoolYear)->whereHas('department' , function($query) use($departmentId) {
            return $query->OfParent($departmentId);
        });
        $sta->when($employeeId, function ($q) use($employeeId) {
            return $q->OfEmployee($employeeId);
        });
        $data[$type] = $sta->count();
      }

      return $data;

  }

  public function getSchoolStatistics( $schoolYear , $departmentId , $parent )
  {

      $types = [
        pSchoolData::SUPER_TEACHER ,
        pSchoolData::PROF_LEARN_COMM ,
        pSchoolData::QUALITY_COMMITTEE ,
        pSchoolData::INITIATIVES ,
        pSchoolData::PARTNERSHIPS ,
        pSchoolData::AWARDS ,
        pSchoolData::ACHIEVEMENTS ,
      ];

      $data = [];

      foreach ($types as $type) {
        $sta = pSchoolData::OfDataType($type)->OfSchoolYear($schoolYear);

        $sta->when($parent == 'self', function ($q) use($departmentId) {
            return $q->OfDepartment($departmentId);
        });

        $sta->when($parent == 'parent', function ($q) use($departmentId) {
            return $q->whereHas('department' , function($query) use($departmentId) {
                return $query->OfParent($departmentId);
            });
        });

        $data[$type] = $sta->count();
      }

      return $data;

  }

  public function getSchoolEmployeesStatistics( $departmentId , $parent )
  {

          $data = [];
          $sta = pSchoolStatistic::query();

          $sta->when($parent == 'self', function ($q) use($departmentId) {
              return $q->OfDepartment($departmentId);
          });

          $sta->when($parent == 'parent', function ($q) use($departmentId) {
              return $q->whereHas('department' , function($query) use($departmentId) {
                  return $query->OfParent($departmentId);
              });
          });


          $data = $sta->get();
          $dataStatistics = [
            'agents' => $data->sum('agents'),
            'teachers' => $data->sum('teachers'),
            'student_guids' => $data->sum('student_guids'),
            'care_guides' => $data->sum('care_guides'),
            'computer_teachers' => $data->sum('computer_teachers'),
            'source_agent' => $data->sum('source_agent'),
            'lab_teachers' => $data->sum('lab_teachers'),
            'mangements' => $data->sum('mangements'),
            'students' => $data->sum('students'),
            'employees' => $data->sum('employees'),
            'usefule_managment' => $data->sum('usefule_managment'),
            'usefule_learning' => $data->sum('usefule_learning'),
            'usefule_students' => $data->sum('usefule_students'),
            'usefule_forum' => $data->sum('usefule_forum'),
          ];
          // dd($dataStatistics);

          return $dataStatistics;

  }

  public function getSchoolPathStatistics( $departmentId )
  {

      return Department::OfType(DepartmentType::SCHOOL)->ofParent($departmentId)
          ->groupBy(['path_id','grade_id'])->select('path_id','grade_id', DB::raw('count(*) as total'))->get();

  }

}
