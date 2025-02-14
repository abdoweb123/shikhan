<?php
namespace App\Http\Controllers\core;
use Illuminate\Http\Request as Request;
use App;
use Route;
use App\Library\HelperService;
use App\Setting;

use GeniusTS\HijriDate\Translations\Arabic;
use GeniusTS\HijriDate\Date;

class api_controller extends Controller
{
    protected $helperService;

    public function __construct(HelperService $helperService)
    {
        $this->helperService = $helperService;
    }
}
