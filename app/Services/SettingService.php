<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\helpers\UtilHelper;
use App\Models\Setting;

class SettingService
{

    public function getMessageAfterRegestration($language = 'ar')
    {
        // for mail and notification inner
        $messageAfterRegistration = \App\Setting::where('property','message_after_registration')->select('value')->first();
        return json_decode($messageAfterRegistration->value,true)[$language];
    }

    public function getCoursesCertificatesTemplates()
    {
        return \App\Setting::where('flag','courses_templates')->select('id','property','value')->get();
    }

    public function getCertificatesTemplates($params = [])
    {

        $templates = DB::Table('settings')->where('property', $params['template'])->first();
        if (! $templates){ return null; }



        $langTemplates = explode('//-//', $templates->value);
        // if (auth()->id() == 5972){
        //   $langTemplates = explode('//-//', $templates->value_for_test);
        // }
        if (empty($langTemplates)){ return null; }



        foreach ($langTemplates as $lang) {
            $template = explode('-//-', $lang);
            if ( $template[0] == $params['language'] ){
              return $template[1];
            }
        }

        return null;
    }

    // return collection
    // public function getAll($language = null)
    // {
    //     $language = $language ?? app()->getLocale();
    //
    //     $settings = Setting::where('is_active',1)->orderby('sort')->get();
    //
    //     $settings->transform(function ($item, $key) use($language){
    //
    //       // for translation fileds
    //       if ($item->is_translated) {
    //         $allTrans = UtilHelper::decodeData($item->value,true);
    //         $newValue = '';
    //         if (isset($allTrans[$language])){
    //           $newValue = $allTrans[$language];
    //         }
    //         $item->value = $newValue;
    //
    //         if ($item->property == 'logo'){
    //           $item->value = asset('storage/app/public/'.$newValue);
    //         }
    //
    //         if ($item->property == 'banner'){
    //           $item->value = asset('storage/app/public/'.$newValue);
    //         }
    //       }
    //
    //       return $item;
    //     });
    //
    //     return $settings;
    //
    // }

    public function getSettingByProperty($property, $language = null)
    {
        $language = $language ?? app()->getLocale();

        $item = Setting::where('property',$property)->first();
        if (!$item) {
          return false;
        }

        if ($item->is_translated) {
          $allTrans = json_decode($item->value,true);
          $newValue = $allTrans[$language];

          $item->value = $newValue;

          if ($item->property == 'logo'){
            $item->value = asset('storage/app/'.$newValue);
          }
        }
        return $item;
    }

    public function getOriginalSettingByProperty($property)
    {
        $item = Setting::where('property',$property)->first();
        if (!$item) {
          return false;
        }

        if ($item->is_translated) {
          $item->value = json_decode($item->value,true);
        }

        return $item;
    }

    // public function getSettingByProperties($property = [] ,$language = null)
    // {
    //   $language = $language ?? app()->getLocale();
    //
    //   $items = Setting::wherein('property',$property)->get();
    //
    //   $all = [];
    //   foreach ($items as $property => $item) {
    //     if ($item->is_translated) {
    //       $allTrans = UtilHelper::decodeData($item->value,true);
    //       $newValue = $allTrans[$language];
    //       $item->value = $newValue;
    //       $all = $all + [ $item->property => $item->value];
    //     } else {
    //       $all = $all + [ $item->property => $item->value];
    //     }
    //   }
    //
    //
    //   return $all;
    // }
    //
    // public function update($settings,$request)
    // {
    //
    //
    //
    //   try {
    //       foreach ($settings as $setting) {
    //           $property = $setting->property;
    //
    //           if ( $setting->is_translated ) {
    //             // 1 array
    //             $data = UtilHelper::decodeData($setting->value,true);
    //             if (empty($data)) {
    //               $data = [ $request->language => $request->$property ];
    //             } else {
    //               $data = array_merge( $data ,[ $request->language => $request->$property ]);
    //             }
    //             $setting->update(['value' => UtilHelper::encodeData($data) ]);
    //           } else {
    //             // 2 just save value not array
    //             // dont save logo it will be saved after upload in controller
    //             if ($property == 'logo') {
    //               continue;
    //             }
    //             if ($property == 'back_ground') {
    //               continue;
    //             }
    //
    //
    //             $data = $request->$property;
    //             $setting->update(['value' => $data ]);
    //           }
    //       }
    //   } catch (\Exception $e) {
    //       return $e->getMessage();
    //   }
    //
    //   return true;
    //
    // }





}
