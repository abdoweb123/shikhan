<?php

namespace App\Services\GlobalCertificate;
use App\GlobalCertificate;
use App\MemberGlobalCertificate;

use App\Services\GlobalCertificate\AdvancedSiteCertificateService;
use App\Services\UserService;
use App\Services\SiteService;
use App\Services\GlobalService;

class GlobalCertificateService
{

  private $advancedSiteCertificateService;
  private $userService;
  private $siteService;
  private $user;
  private $globalCertificate;

  public function __construct()
  {
      $this->advancedSiteCertificateService = new AdvancedSiteCertificateService();
      $this->userService = new UserService();
      $this->siteService = new SiteService();
      $this->globalService = new GlobalService();
  }

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function setGlobalCertificate($globalCertificate)
  {
      $this->globalCertificate = $globalCertificate;
      return $this;
  }









  // type 1 : advanced_certificate
  public function getUserAdvancedSiteCertificateDetails()
  {
      $return = new \stdClass();
      $return->globalCertificate = null;
      $return->successedSites = [];
      $return->countUserSuccessedSites = 0;
      $return->sumSitesVideosDuration = 0;
      $return->sumSitesValidCourses = 0;
      $return->sumSitesFullDegree = 0;
      $return->sumUserSitesFullDegree = 0;
      $return->userSitesFullRate = 0;
      $return->deservesAdvancedSiteCertificate = false;
      $return->canPrintAdvancedSiteCertificate = false;
      $return->hasAdvancedSiteCertificate = false;


      $this->advancedSiteCertificateService->setUser($this->user);


      // 1 if already exists just return it
      $userAdvancedSiteCertificate = $this->advancedSiteCertificateService->getActiveUserAdvancedSiteCertificate();
      if ($userAdvancedSiteCertificate){
          $return->globalCertificate = $userAdvancedSiteCertificate;

          $return->successedSites = $this->getUserSuccessedSites($userAdvancedSiteCertificate->global_certificate->sites_ids);
          // if($userAdvancedSiteCertificate->global_certificate){ // if this global certificate in specific sites(sites_ids) so get only these sites ids, if not get all sites that user successed in.
          //
          // } else {
          //     $return->successedSites = $this->getUserSuccessedSites();
          // }

          $sitesDetails = $this->loadUserSuccessedSitesDetails($return->successedSites);
          $return->successedSites = $this->loadUserEachSiteRate($return->successedSites);
          $return->countUserSuccessedSites = $return->successedSites->count(); // old: $this->user->countSuccessedSites(); // to get only count succesed sites of advanced site certificate not all successed sites
          $return->sumSitesVideosDuration = $this->sumVideosDurationOfSites($sitesDetails);

          $return->sumSitesValidCourses = $sitesDetails->sum('courses_count'); // $this->sumSitesValidCourses($sitesDetails);
          $return->sumSitesFullDegree = $return->sumSitesValidCourses * 100;
          $return->sumUserSitesFullDegree = $return->successedSites->sum('user_site_degree');
          $return->userSitesFullDegree = $return->sumUserSitesFullDegree / $return->sumSitesFullDegree;
          $return->userSitesFullDegree = round($return->userSitesFullDegree * 100, 2);
          $return->userSitesFullRate = $this->globalService->siteRateRanges($return->userSitesFullDegree);

          $return->deservesAdvancedSiteCertificate = true;
          $return->canPrintAdvancedSiteCertificate = $this->advancedSiteCertificateService->canPrintAdvancedSiteCertificate($userAdvancedSiteCertificate);
          $return->hasAdvancedSiteCertificate = $return->canPrintAdvancedSiteCertificate ? true : false;
          return $return;
      }




      // 2 if user success in this advanced_site_certifucate sites
      $globalCertificate = \App\GlobalCertificate::advancedSiteCertificate()->active()->select('sites_ids')->first();
      if (! $globalCertificate && ! empty($globalCertificate->sites_ids)){
          return $return;
      }
      if (! $this->advancedSiteCertificateService->setSitesIds($globalCertificate->sites_ids)->isUserDeservesAdvancedSiteCertificate()){
          return $return;
      }



      // 3 if deserves, create and return it
      $created = $this->createUserGlobalCertificate();
      if (! $created){
        return $return;
      }

      $return->globalCertificate = $created;
      $return->successedSites = $this->getUserSuccessedSites($globalCertificate->sites_ids); // $created->global_certificate->sites_ids
      $sitesDetails = $this->loadUserSuccessedSitesDetails($return->successedSites);
      $return->countUserSuccessedSites = $return->successedSites->count(); // old: $this->user->countSuccessedSites();
      $return->sumSitesVideosDuration = $this->sumVideosDurationOfSites($sitesDetails);

      $return->sumSitesValidCourses = $sitesDetails->sum('courses_count'); // $this->sumSitesValidCourses($sitesDetails);
      $return->sumSitesFullDegree = $return->sumSitesValidCourses * 100;
      $return->sumUserSitesFullDegree = $return->successedSites->sum('user_site_degree');
      $return->userSitesFullDegree = $return->sumUserSitesFullDegree / $return->sumSitesFullDegree;
      $return->userSitesFullDegree = round($return->userSitesFullDegree * 100, 2);
      $return->userSitesFullRate = $this->globalService->siteRateRanges($return->userSitesFullDegree);

      $return->deservesAdvancedSiteCertificate = true;
      $return->canPrintAdvancedSiteCertificate = true;
      $return->hasAdvancedSiteCertificate = $return->canPrintAdvancedSiteCertificate ? true : false;

      return $return;

  }









  public function getActiveAdvancedSiteCertificate()
  {
      return GlobalCertificate::advancedSiteCertificate()->active()->first();
  }

  private function getUserSuccessedSites($sitesIds = [])
  {
      $this->userService->setUser($this->user)->setSitesIds($sitesIds);
      return $this->userService->getUserSuccessedSites();
  }

  private function loadUserSuccessedSitesDetails($sitesResults)
  {
      $sitesResults->load(['site' => function ($query) {
          $query->select('id','advanced_certificate_sort');
      }]);

      $sites = $sitesResults->pluck('site');

      foreach ($sites as $site) {
        $site->courses_count = $site->validCourses('count');
        $site->full_degree = $site->courses_count * 100;
      }

      return $sites->sortBy('advanced_certificate_sort');
  }

  private function loadUserEachSiteRate($sitesResults)
  {

      foreach ($sitesResults as $sitesResult) {
          if (! $sitesResult->site){
              $sitesResult->user_full_rate = 0;
          } else {
              $userFullDegree = $sitesResult->site->full_degree ? $sitesResult->user_site_degree / $sitesResult->site->full_degree : 0;
              $sitesResult->user_full_degree = round($userFullDegree * 100, 2);
              $sitesResult->user_full_rate = $this->globalService->siteRateRanges($sitesResult->user_full_degree);
          }
      }

      return $sitesResults;
  }



  private function sumVideosDurationOfSites($sites)
  {
      return $this->siteService->setSites($sites)->sumVideosDurationOfSites();
  }

  // private function sumSitesValidCourses($sites)
  // {
  //     return $this->siteService->setSites($sites)->sumSitesValidCourses();
  // }





  public function createUserGlobalCertificate()
  {
      do {
          $code = \Illuminate\Support\Str::random(12);
          $codeExists = $this->checkCodeExists($code);
      } while ( $codeExists == true );

      return $this->user->member_global_certificates()->create([
          'global_certificate_id' => $this->globalCertificate->id,
          'code' => $code
      ]);
  }

  public function checkCodeExists($code)
  {
      return MemberGlobalCertificate::where('code', $code)->exists();
  }





}
