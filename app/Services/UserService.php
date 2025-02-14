<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Auth;
use App\User;

class UserService
{

  private $user;
  private $course;
  private $date;
  private $sitesIds;

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function setCourse($course)
  {
      $this->course = $course;
      return $this;
  }

  public function setDate($date)
  {
      $this->date = $date;
      return $this;
  }

  public function setSitesIds($sitesIds)
  {
      $this->sitesIds = $sitesIds;
      return $this;
  }

  public function countUserTestsOfDate()
  {
    // عدد اختبارات الطالب فى يوم محدد
    return $this->user->courses_results()->whereDate('test_created_at', $this->date)->distinct('course_id')->count();
  }

  public function courseInCurrentTestsOfDate()
  {
    // هل الدورة موجود فى اختبارات هذا اليوم
    return $this->user->courses_results()->whereDate('test_created_at', $this->date)->where('course_id', $this->course->id )->exists();
  }

  public function userDidntExceedTestsCountOfDate()
  {
      return $this->countUserTestsOfDate() < maxTestsPerDay();
  }

  public function userCanOpenTestInThisDate()
  {
      if($this->userDidntExceedTestsCountOfDate()){
        return true;
      }

      if($this->courseInCurrentTestsOfDate()){
        return true;
      }

      return false; // user can't .......
  }

  public function getUserSuccessedSites()
  {
      return $this->user->sites_results()->successed()->when(! empty($this->sitesIds), function($q){
        return $q->wherein('site_id', $this->sitesIds);
      })->get(); // ->load('site')
  }





  public function validateDoublicateUserName( $data , $id = 0 )
  {
      $validate = User::where([ 'user_name' => $data ]);
      $validate->when( $id , function($q) use($id) {
          return $q->where('id', '!=', $id);
      });

      $validate = $validate->exists();
      if ( $validate ) {
        throw ValidationException::withMessages(['user_name' => __('messages.already_exists' , [ 'var' => __('words.user_name') ] ) ]);
      }
  }

  public function updateActiveStatus( $record , $status )
  {
      // because force update
      $record->is_active = $status;
      $record->save();

      return true;
  }







    // --------------------------------

    public function getUserById($id,$language = null)
    {

        $language = $language ?? app()->getLocale();

        $user = User::with(['client.client_info' => function ($query) use ($language) {
          $query->where('language',$language);
        }])->where('id',$id)->first();

        if ($user) {
          $user->unseen_notifications_count = Notification::where([ 'user_reciever_id' => $user->id , 'read_at' => null])->count();
          $user->likes_count = ULike::where( 'user_id' , $user->id )->count();
        }

        return $user;

    }

    public function storeBanner($fileUpload,$user_id)
    {

        $language = app()->getLocale();

        $extension = $fileUpload->getClientOriginalExtension();
        $name= $user_id . '_banner_'. uniqid() . '.' .$extension;
        $fileUpload->move('storage/app/'.User::FILE_FOLDER,$name);

        return User::FILE_FOLDER.'/'.$name;

    }

    public function storeImage($fileUpload,$user_id)
    {

        $language = app()->getLocale();

        $extension = $fileUpload->getClientOriginalExtension();
        $name= $user_id . '_image_'. uniqid() . '.' .$extension;
        $fileUpload->move('storage/app/'.User::FILE_FOLDER,$name);

        return User::FILE_FOLDER.'/'.$name;

    }

    public function validateStoreBanner($fileUpload)
    {

      if (!in_array( $fileUpload->getClientOriginalExtension() , ['jpeg','png','gif','jpg','svg'] )) {
            return 'error extintion';
        }

      if ( ($fileUpload->getSize() /1000) > 500 ) {
        return 'حجم الصورة لا يزيد عن 500';
      }

      return true;

    }

    public function createRandomPassword($user)
    {

      $randPassword = CoreHelper::generateRandomString(8);
      $user->update([ 'password'=>  Hash::make($randPassword) ]);

      return $randPassword;

    }

    // public function updateFcm($user,$request)
    // {
    //     return $user->update(['fcm_token' => $request->fcm_token , 'mobile_type' => $request->mobile_type]);
    // }

    // public function update($request,$user)
    // {
    //
    //     $user->name = $request['name'] ;
    //     if (isset($request['email'])) {$user->email = $request['email'];}
    //     if (isset($request['password'])) {$user->password = Hash::make($request['password']);}
    //     if (isset($request['phone'])) {$user->phone = $request['phone'];}
    //     if (isset($request['gender_id'])) {$user->gender_id = $request['gender_id'];}
    //     if (isset($request['lat'])) {$user->lat = $request['lat'];}
    //     if (isset($request['lng'])) {$user->lng = $request['lng'];}
    //     if (isset($request['access_user_id'])) {
    //       $user->access_user_id = $request['access_user_id'];
    //     } else {
    //       $user->access_user_id = $user->id;
    //     }
    //     $user->ip = UtilHelper::getUserIp();
    //     $user->save();
    //
    //     return $user;
    //
    // }

    // public function store($request)
    // {
    //
    //   $user = new User();
    //   $user->type_id = $request['type_id'];
    //   $user->role = $request['role'];
    //   $user->name = $request['name'];
    //   if (isset($request['email'])) {$user->email = $request['email'];}
    //   $user->phone = $request['phone']; // $request['phone'];
    //   if (isset($request['gender_id'])) {$user->gender_id = $request['gender_id'];}
    //   if (isset($request['image'])) {$user->image = $request['image'];}
    //   $user->password = Hash::make($request['password']) ;
    //   $user->is_verified = $request['is_verified'] ;
    //   $user->ip = UtilHelper::getUserIp();
    //   $user->save();
    //
    //   if (!$user) {
    //     return false;
    //   }
    //
    //   $user->access_user_id = $user->id;
    //   $user->save();
    //
    //   return $user;
    //
    // }

    // public function isUserValid($user)
    // {
    //
    //     if ($user->isActive(0)) {
    //       return [ 'error' => trans('auth.in_active') , 'code' => 401 ];
    //     }
    //
    //     if ($user->isActiveAdmin(0)) {
    //       return [ 'error' => trans('auth.in_active') , 'code' => 401 ];
    //     }
    //
    //     if ($user->isVerified(0)) {
    //       return [ 'error' => trans('auth.not_verified') , 'code' => 4011 ];
    //     }
    //
    //     return [];
    //
    // }


    // public function getCountActiveAdmins()
    // {
    //     // used when deactivate any user if this user is last one admin so don't deactivaet it
    //     return User::with('roles')->wherehas('roles' , function($query) {
    //       return $query->where('role_id',1);
    //     })->count();
    // }


}
