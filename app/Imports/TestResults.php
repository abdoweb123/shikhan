<?php


namespace App\Imports;

use App\member;
use App\site;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestResults implements ToModel, WithHeadingRow
{
    public function __construct(String $site_alias,int $course_id)
    {
        $this->site_alias = $site_alias;
        $this->course_id = $course_id;
        $site = site::where('alias',$site_alias)->firstOrFail();
        $this->course = $site->courses()->findOrFail($course_id);

    }

    public function model(array $row)
    {
        $user = member::where('email',$row['email']);
        if ($user->count())
        {
            $user = $user->first();
        }
        else
        {
            $user = member::create(
            [
                'email' => $row['email'],
                'name' => $row['name'],
                'phone' => $row['phone'] ?? '',
                'password' => bcrypt('123456'),
                'created_by' => \Auth::guard('admin')->user()->id,
                'avatar' => member::default_avatar(),
                'status' => 1,
            ]);
        }

        if ($this->course->test_results()->where([['user_id','=',$user->id],['locale','=',$row['locale']],['no_test','<=',3]])->count() == '0')
        {
            return $this->course->test_results()->create([
                'user_id' => $user->id,
                'degree' => $row['degree'],
                'rate' => $row['degree'] >= 95 && $row['degree'] <= 100 ? 4 : ($row['degree'] >= 86 && $row['degree'] <= 94 ? 3 : ($row['degree'] >= 80 && $row['degree'] <= 85 ? 2 : ($row['degree'] >= 70 && $row['degree'] <= 79 ? 1 : 0))),
                'locale' => $row['locale'],
                'created_by' => \Auth::guard('admin')->user()->id,
                'flag' => 0,
            ]);
        }
        return null;
    }
}
