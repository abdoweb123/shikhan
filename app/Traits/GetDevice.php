<?php

namespace App\Traits;

trait GetDevice
{
    public function isMobile()
  	{
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
          return true;
        }
        return false;
  	}
}
