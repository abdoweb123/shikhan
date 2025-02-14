<?php

namespace App\Services;
use App\site;
use App\Translations\SiteTranslation;
use App\helpers\UtilHelper;


class SiteService
{

  private $sites = [];

  public function setSites($sites)
  {
      $this->sites = $sites;
      return $this;
  }

  public function sumVideosDurationOfSites()
  {
      $sum = strtotime('00:00:00');
      foreach ($this->sites as $site) {
        $sum = date("H:i:s",strtotime($sum)+strtotime($site->getVideosDuration()));
      }

      return $sum;
  }

  public function aliasAndLanguageExists($alias, $language, $currentId = null)
  {
      return SiteTranslation::where('slug', $alias)->where('locale', $language)->where('site_id', '!=', $currentId)->exists();
  }

  public function getSitesTree($data, $site_id = 0, $parent = 0, $not = 0)
  {
      return UtilHelper::buildTree( $data, $parent, 0 );
  }

  public function getSitesTreeRoot($data, $parent = 0, $not = 0)
  {
      $temp = [];
      return UtilHelper::buildTreeRoot($data, $not, $temp, $parent, 0);
  }




}
