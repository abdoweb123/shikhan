<?php

namespace App\Services\ExtraCertificates;

class religion_basics_details_service
{

  private $site;
  private $user;
  private $type;

  public function __construct($params = [])
  {
      $this->site = @$params['site'] ? $params['site'] : null;
      $this->user = @$params['user'] ? $params['user'] : null;
      $this->type = @$params['type'] ? $params['type'] : null;
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
        $userSumCoursesCount = $this->user->sites_results()->wherein('site_id', $sitesIds)->sum('courses_count');
        $userSumSiteDegrees = $this->user->sites_results()->wherein('site_id', $sitesIds)->sum('user_site_degree');
        $fullDegree = $userSumSiteDegrees / ($userSumCoursesCount * 100);
        $fullDegree = (round($fullDegree * 100, 2));
        $fullRate = (new \App\Services\GlobalService())->siteRateRanges($fullDegree);
      }


      $childSites = $this->user->sites_results()->wherein('site_id', $sitesIds)->with(['site' => function($q) {
        return $q->with(['translation' => function($q) { return $q->wherein('locale',['sw','en'])->select('site_id','name','locale'); }]);
      }])->get();




      foreach ($childSites as $childSite) {
        $childSite->user_full_rate = $childSite->user_site_degree / ($childSite->courses_count * 100);
        $childSite->user_full_rate = round($childSite->user_full_rate * 100, 2);
        $childSite->user_full_rate = (new \App\Services\GlobalService())->siteRateRanges($childSite->user_full_rate);
        $childSite->user_site_degree = $childSite->user_site_degree / $childSite->courses_count;        
      }
      $html = $this->convertChildSitesToHtml($childSites);


      return [
        'sites' => $sites,
        'fullDegree' => $fullDegree,
        'fullRate' => $fullRate,
        'html' => $html
      ];

  }


  private function convertChildSitesToHtml($childSites)
  {

      $rows = '';

      if($this->type == 'jpg'){
        foreach ($childSites as $key => $childSite) {
            $rows = $rows .
              '<tr>'.
                '<td style="padding: 4px; width: 40%;font-size: 14px;line-height: 15px;">'. $childSite->site?->translation?->where('locale', 'sw')->first()?->name . '</td>'.
                '<td style="padding: 4px; width: 7%;font-size: 12px;line-height: 15px;">'. __('trans.rate.'.$childSite->user_full_rate,[], app()->getLocale()) . '</td>'.
                  '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. optional($childSite)->courses_count . '</td>'.
                  '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. $childSite->user_site_degree . '</td>'.
                '<td style="padding: 4px; width: 7%;font-size: 12px;line-height: 15px;">'.  __('trans.rate.'.$childSite->user_full_rate,[], 'en') . '</td>'.
                '<td style="padding: 4px; width: 40%;font-size: 13px;line-height: 15px;">'.$childSite->site?->translation?->where('locale', 'en')->first()?->name . '</td>'.
              '</tr>';
        }
      }



      if($this->type == 'pdf'){
        foreach ($childSites as $key => $childSite) {
            $rows = $rows .
              '<tr>'.
                '<td style="padding: 4px; width: 38%;font-size: 12px;">'. $childSite->site?->translation?->where('locale', 'sw')->first()?->name . '</td>'.
                '<td style="padding: 4px; width: 6%;font-size: 12px;">'. __('trans.rate.'.$childSite->user_full_rate,[], app()->getLocale()) . '</td>'.
                  '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. optional($childSite)->courses_count . '</td>'.
                  '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. $childSite->user_site_degree . '</td>'.
                '<td style="padding: 4px; width: 6%;font-size: 12px;">'.  __('trans.rate.'.$childSite->user_full_rate,[], 'en') . '</td>'.
                '<td style="padding: 4px; width: 38%;font-size: 12px;">'. $childSite->site?->translation?->where('locale', 'en')->first()?->name . '</td>'.
              '</tr>';
        }
      }

      return '<table class="table table-bordered table-striped">
        <thead>
          <tr style="font-weight: bold;">
            <th scope="col">Diploma</th>
            <th scope="col">Draja</th>
            <th scope="col">Kozi - courses</th>
            <th scope="col">Draja - Degree</th>
            <th scope="col">Rate</th>
            <th scope="col">Diploma</th>
          </tr>
        </thead>
        <tbody>'. $rows . '</tbody></table>';

  }

}
