@if (auth()->check())
  @if (! auth()->user()->email_verified_at)
    <a href="{{ route('show_verification_email') }}">تفعيل البريد</>
  @endif
@endif
