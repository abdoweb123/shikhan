<?php

namespace App\Services;
// use App\Models\Term;
use DB;

class SettingsService
{

   // public function getMessageAfterRegestration($language = 'ar')
   // {
   //     // for mail and notification inner
   //     $messageAfterRegistration = \App\Setting::where('property','message_after_registration')->select('value')->first();
   //     return json_decode($messageAfterRegistration->value,true)[$language];
   // }

   // public function getCoursesCertificatesTemplates()
   // {
   //     return \App\Setting::where('flag','courses_templates')->select('id','property','value')->get();
   // }

   public function getCertificatesTemplates($params = [])
   {

       $templates = DB::Table('settings')->where('property', $params['template'])->first();
       if (! $templates){ return null; }



       $langTemplates = explode('//-//', $templates->value);
       if (empty($langTemplates)){ return null; }

       foreach ($langTemplates as $lang) {
           $template = explode('-//-', $lang);
           if ( $template[0] == $params['language'] ){
             return $template[1];
           }
       }

       return null;
   }

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




}
