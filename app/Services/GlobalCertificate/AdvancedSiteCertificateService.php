<?php

namespace App\Services\GlobalCertificate;
use App\SiteAdvancedCertificate;
use App\MemberGlobalCertificate;

class AdvancedSiteCertificateService
{

  private $user;
  private $sitesIds;

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function setSitesIds($sitesIds)
  {
      $this->sitesIds = $sitesIds;
      return $this;
  }

  public function getActiveUserAdvancedSiteCertificate()
  {
      return $this->user->member_global_certificates()->wherehas('global_certificate', function($q){
        return $q->advancedSiteCertificate();
      })->with('global_certificate')->active()->first();
  }

  public function isUserDeservesAdvancedSiteCertificate()
  {
    // successed sites between x - y
    // return $this->user->countSuccessedSites() >= advancedSiteCertificateCondition()[0] &&
    //   $this->user->countSuccessedSites() <= advancedSiteCertificateCondition()[1];

    return $this->user->isSuccessedInSites($this->sitesIds);


  }

  public function canPrintAdvancedSiteCertificate($userAdvancedSiteCertificate)
  {
      return $userAdvancedSiteCertificate->print_count == 0;
  }

}
