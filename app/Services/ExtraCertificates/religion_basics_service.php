<?php

namespace App\Services\ExtraCertificates;

class religion_basics_service
{

  private $site;
  private $user;
  private $extraCertificate;

  public function __construct($params = [])
  {
      $this->site = @$params['site'] ? $params['site'] : null;
      $this->user = @$params['user'] ? $params['user'] : null;
      $this->extraCertificate = @$params['extraCertificate'] ? $params['extraCertificate'] : null;
  }

  public function deserve()
  {
      if (! $this->site){
        return false;
      }

      if (! $this->user){
        return false;
      }

      if ($this->user->isSuccessedInSites($this->site->childs()->pluck('id'))){
        return true;
      }

      return false;
  }


  public function getResult()
  {
    return [
        'deserve' => $this->deserve(),
      ];
  }


  public function getCertificateData()
  {

      $languages = ['en','sw'];
      $sites = \App\Translations\SiteTranslation::where('site_id', $this->site->id)
        ->wherein('locale',$languages)
        ->select('site_id','name','locale')->get();

      $sitesIds = $this->site->childs()->pluck('id');

      if (! count($sitesIds)){
        $sumDegreesOfSites = 0;
        $fullDegree = 0;
        $fullRate = 0;
      } else {
        $userSumCoursesCount = $this->user->sites_results()->sum('courses_count');
        $userSumSiteDegrees = $this->user->sites_results()->sum('user_site_degree');
        $fullDegree = $userSumSiteDegrees / ($userSumCoursesCount * 100);
        $fullDegree = (round($fullDegree * 100, 2));
        $fullRate = (new \App\Services\GlobalService())->siteRateRanges($fullDegree);
      }


      // generate code ------------------------------------
      $code = '';

      do {
          $code = (new \App\Services\GlobalService())->generateRandomString(9, ['upper' => true]);
          $codeExists = \App\MemberExtraCertificate::where('code',$code)->exists();
      } while ( $codeExists == true );

      \App\MemberExtraCertificate::firstOrCreate(
          [
            'extra_certificate_id' => $this->extraCertificate->id ,
            'user_id' => $this->user->id ,
          ],
          [
            'code' => $code
          ]
      );
      // -----------------------------------------------


      return [
        'sites' => $sites,
        'fullDegree' => $fullDegree,
        'fullRate' => $fullRate,
        'code' => $code
      ];

  }


}
