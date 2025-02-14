<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
  public function cacheHas($key)
  {
    return Cache::has($key);
  }

  public function cacheGet($key)
  {
    return Cache::get($key , null);
  }

  public function cachePut($key, $data, $minutes)
  {
    Cache::put($key, $data, $minutes);
  }

  public function cacheForever($key, $data)
  {
      return Cache::rememberForever($key, function() use($data) {
        return $data;
      });
  }

  public function cacheForget($key)
  {
      if (is_array($key)){
        foreach ($key as $cacheItem) {
          Cache::forget($cacheItem);
        }
      }
      Cache::forget($key);
  }

  public function cacheFlush()
  {
    Cache::flush();
  }


  // --------------------------
  public function siteCaches()
  {
    return ['sites'];
  }

  public function courseCaches()
  {
    return [];
  }

  public function lessonCaches()
  {
    return [];
  }

}
