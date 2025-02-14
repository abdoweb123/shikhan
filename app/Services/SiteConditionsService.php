<?php

namespace App\Services;
use App\Services\GlobalService;

class SiteConditionsService
{
    private $globalService;

    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }

    // public function checkSiteConditions($site, $user)
    // {
    //     if($site->hasCondidtion3()){
    //     }
    // }

    // condition 1
    // // الطالب لا يشترك فى الدبلوم الا اذا انهى دبلوم على الاقل من دبلومات المرحلة الاولى
    // // field has ["1"]
    // if($data['site']->hasCondidtion1()){
    //     $oldSites = App\site::where(['status' => 1,'new_flag' => 0])->select('id')->get();
    //     foreach($oldSites as $oldSite){
    //       if ($data['userFinishedAtLeastOneSite'] == false){
    //           $data['userFinishedAtLeastOneSite'] = $this->globalService->userFinishedAtLeastOneSite(Auth::guard('web')->user(), $oldSite );
    //       }
    //     }
    // }


    // condition 3
    // الطالب لا يستخرج شهادة الا اذا انهى دبلوم على الاقل من دبلومات المرحلة الاولى
    public function userFinishedAtLeastOneOfOldSites($site, $user)
    {
        $userFinishedAtLeastOneSite = true;

            $oldSites = \App\site::where(['status' => 1,'new_flag' => 0])->select('id')->get();
            foreach($oldSites as $oldSite){
                $userFinishedAtLeastOneSite = $this->globalService->userFinishedAtLeastOneSite($user, $oldSite);
                if ($userFinishedAtLeastOneSite == true){
                    break;
                }
            }

        return $userFinishedAtLeastOneSite;

    }

    // condition 4
    // الطالب لا يسنخرج شهادة الا اذا انهى الدبلومات المعتمد عليه الدبلوم الحالى
    public function userFinishDependents($site, $user)
    {
        $return = ['userFinishDependents' => true, 'note' => ''];

        if($site->hasCondidtion4()){
            $sitesMustFinishToSubscribe = $this->globalService->getSitesMustFinishToSubscribeIn($site->id);
            if ( $sitesMustFinishToSubscribe->isNotEmpty() ) {
                  $userFinishDependents = $this->globalService->userFinishDependents($user, $sitesMustFinishToSubscribe );
                  $return['userFinishDependents'] = $userFinishDependents;
                  if(! $userFinishDependents){
                    $return['note'] = ' لابد من إنهاء  ' . implode("-", $sitesMustFinishToSubscribe->pluck('name')->toArray() ) . ' قبل استلام شهادة الدبلوم ';
                  }
            }
        }

        return $return;

    }




}
