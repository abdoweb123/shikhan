<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Traits\CacheTrait;

class CountryService
{
    use CacheTrait;

    public function getAll()
    {
        return $this->cacheForever('countries', DB::table('countries')->get());
    }

    public function filter($params = [])
    {
        $language = isset($params['language']) ? $params['language'] : app()->getlocale();
        $fields = isset($params['fields']) ? $params['fields'] : ['id','name','phonecode'];

        $cacheKey = 'countries_'.$language.implode("_", $fields);

        if ($this->cacheHas($cacheKey)){
          return $this->cacheGet($cacheKey);
        }

        $countries = DB::table('countries')->select($fields)->get();
        foreach ($countries as $key => $country) {
          $name = json_decode($country->name,true );
          $country->name = isset($name[$language]) ? $name[$language] : $name['en'];
        }

        return $this->cacheForever($cacheKey, $countries);

    }


    public function getAllowedCountries($params = [])
    {
        $language = isset($params['language']) ? $params['language'] : app()->getlocale();
        $fields = isset($params['fields']) ? $params['fields'] : ['id','name','phonecode'];

        $cacheKey = 'countries_allowed_'.$language.implode("_", $fields);

        if ($this->cacheHas($cacheKey)){
          return $this->cacheGet($cacheKey);
        }

        $countries = DB::table('countries')->where('allowed', 1)->select($fields)->get();
        foreach ($countries as $key => $country) {
          $name = json_decode($country->name,true );
          $country->name = isset($name[$language]) ? $name[$language] : $name['en'];
        }

        return $this->cacheForever($cacheKey, $countries);

    }


    public function getDefaultCountry($fileds = [])
    {
        return DB::table('countries')->select($fileds ?? ['name'])->where('is_default', 1)->first();
    }

}
