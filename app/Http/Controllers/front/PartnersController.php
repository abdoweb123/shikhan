<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
// use App,Auth;
use App\Partner;
use App\Models\Page;
use App\Services\PartnerService;



class PartnersController extends Controller
{

    public function __construct(private PartnerService $partnerService)
    {

    }

    public function index(Request $request)
    {

      $data = $this->data($request,'partners');

      $page = Page::with(['activeTranslation'])->where('inner_name' , 'partners')->firstorfail();

      $this->seoInfo('page_inf', 'partners');
      $data['info'] = $page;

      $data['partners'] = $this->partnerService->getExactsortAndShuffle();

      return view('front.content.partners.index', $data );

    }


}
