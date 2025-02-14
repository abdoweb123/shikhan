@php $trays = maxTests(); @endphp

if (Auth::guard('web')->user()) {
  if ( $userGetXtraTray ) {
    $trays = 3;
  }
}



$isUserTestedCourse
