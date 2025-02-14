
<div class="row">
  @foreach($data as $user)
  <div style="padding: 5px;">
    <a  class="btn btn-primary"
      style="color: black;background-color: white;border-radius: 50px;border: 1px solid #c6c6c6;">
      {{ $user->name }}
      {{--
      <span class="badge badge-light" style="background-color: #79be1b;border-radius: 50px;">{{ $country->count_success }}</span>
      <span class="sr-only">unread messages</span>
      --}}
    </a>
  </div>
  @endforeach
</div>
