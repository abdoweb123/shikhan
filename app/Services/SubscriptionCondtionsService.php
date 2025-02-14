<?php

namespace App\Services;
use App\Services\HelperService;
use DB;

class SubscriptionCondtionsService
{

    private $user;
    private $site;
    private $helperService;

    public function __construct( $params = [] )
    {
        $this->user = isset($params['user']) ? $params['user'] : null;
        $this->site = isset($params['site']) ? $params['site'] : null;
        $this->helperService = new HelperService();
    }


    public function userCanSubscribeInSite()
    {

        $data['isUserSubscribedInSite'] = $this->helperService->isUserSubscribedInSite($this->user, $this->site);

        $siteDependents = $this->getSiteDependents($this->site);

        if ($this->isSiteHasDependents($siteDependents)) {
            $data['isUserFinishedDependents'] = $this->isUserPassedDependents($siteDependents);
            $data['siteDependentsTitle'] = implode("-", $siteDependents->pluck('name')->toArray() );
        } else {
            $data['isUserFinishedAtLeastOneSite'] = $this->isUserPassedAtLeastOneSite();
        }

    }

    public function getSiteDependents()
    {
        // هناك دبلومات تعتمد على اخرى مثلا يجب قبل الاشتراك فى دبلوم اجازة القران ان يكون انهى دبلوم القران و علومه
        return DB::Table('site_dependent')
          ->join('sites_translations','site_dependent.depend_on_site_id','sites_translations.site_id')
          ->where('site_dependent.site_id',$this->site->id)
          ->where('sites_translations.locale', app()->getLocale())
          ->select('site_dependent.depend_on_site_id','sites_translations.name')
          ->get();
    }

    public function isSiteHasDependents($siteDependents)
    {
        return $siteDependents->isNotEmpty();
    }

    public function isUserPassedDependents($dependents)
    {
        foreach ($dependents as $site) {
            $userPassedInSite = $this->helperService->userPassedInSite($this->user, $site);
            if (! $userPassedInSite['status']) {
              return false;
            }
        }

        return true;
    }

    public function isUserPassedAtLeastOneSite()
    {
        $userPassedInSite = $this->helperService->userPassedInSite($this->user, $this->site);
        if (! $userPassedInSite['status']) {
          return false;
        }
        return true;
    }


}
