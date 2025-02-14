<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\OptionValue;

class OptionController extends Controller
{

    public function searchOptionValues(Request $request)
    {

        $items = OptionValue::where('option_id','=', $request->crit)->orderby('titleGeneral')->get();
        $data = array();
        foreach ($items as $item) {
            $data[] = array('value' => $item->titleGeneral, 'id' => $item->id);
        }

        if(count($data)) {
            return response()->json($data);
        } else {
          return ['value'=>'No Result Found','id'=>''];
        };

    }


}
