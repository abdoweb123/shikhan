<li role="presentation" style="float: right;list-style-type: none;">
@if ($page == 'home') <!-- /////////////// -->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @endif
      @endforeach

      <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
      @foreach($languages as $language)
        @if ($language->alias != request()->locale)
            <li><a href="{{ route('front.index' , [ 'locale' => $language->alias ] ) }}" style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;">{{ $language->title }}</a></li>
        @endif
      @endforeach
      </ul>

@elseif ($page == 'search') <!-- ////////////// //-->

@elseif ($page == 'contents') <!-- ////////////// ///-->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @endif
      @endforeach

      @if (empty($translations))
          <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
            @foreach($languages as $language)
              @if ($language->alias != request()->locale)
                  <a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;" href="{{ route( request()->route()->getName() , [ 'locale' => $language->alias , 'letter_name' => request()->letter_name ] ) }}">{{ $language->title }}</a>
              @endif
            @endforeach
          </ul>
      @else
          <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
            @foreach($translations as $translation)
                @if ($translation->language != request()->locale)
                  <li><a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"  href="{{ route('front.contents.index' , [ 'locale' => $translation->language, 'alias' => $translation->alias ] ) }}">
                    @foreach($languages as $language)
                      @if($language->alias == $translation->language) {{$language->title }} @endif
                    @endforeach
                  </a></li>
                @endif
            @endforeach
          </ul>
      @endif



@elseif ($page == 'lesson')   <!-- ////////////////// -->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @endif
      @endforeach

      <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
        @foreach($translations as $translation)
            @if ($translation->language != request()->locale)
              <li><a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"  href="{{ route('front.lessons.show' , [ 'locale' => $translation->language, 'alias' => $translation->alias ] ) }}">
                @foreach($languages as $language)
                  @if($language->alias == $translation->language) {{$language->title }} @endif
                @endforeach
              </a></li>
            @endif
        @endforeach
      </ul>


@elseif ($page == 'lecture')   <!-- /////////////////// -->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @endif
      @endforeach


      <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
        @foreach($translations as $translation)
            @if ($translation->language != request()->locale)
              <li><a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"  href="{{ route('front.lecture.show' , [ 'locale' => $translation->language, 'alias' => $translation->alias ] ) }}">
                @foreach($languages as $language)
                  @if($language->alias == $translation->language) {{$language->title }} @endif
                @endforeach
              </a></li>
            @endif
        @endforeach
      </ul>



@elseif ($page == 'student') <!-- /////////////////// -->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @endif
      @endforeach

      <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
        @foreach($languages as $language)
          @if ($language->alias != request()->locale)
            <a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"   href="{{ route( request()->route()->getName() , [ 'locale' => $language->alias , 'id' => request()->id ] ) }}">{{ $language->title }}</a>
          @endif
        @endforeach
      </ul>



@elseif ($page == 'teachers') <!-- ////////////////////// -->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @endif
      @endforeach

      <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
        @foreach($languages as $language)
          @if ($language->alias != request()->locale)
            <a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"   href="{{ route( request()->route()->getName() , [ 'locale' => $language->alias , 'id' => request()->id ] ) }}">{{ $language->title }}</a>
          @endif
        @endforeach
      </ul>



@elseif ($page == 'dic_contents') <!-- ///////////////*/// -->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @endif
      @endforeach

      @if (empty($translations))
          <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
            @foreach($languages as $language)
              @if ($language->alias != request()->locale)
                  <a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"   href="{{ route( request()->route()->getName() , [ 'locale' => $language->alias , 'letter_name' => request()->letter_name ] ) }}">{{ $language->title }}</a>
              @endif
            @endforeach
          </ul>
      @else
          <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
            @foreach($translations as $translation)
                @if ($translation->language != request()->locale)
                  <li><a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"  href="{{ route( request()->route()->getName() , [ 'locale' => $translation->language, 'alias' => $translation->alias , 'letter_name' => request()->letter_name ] ) }}">
                    @foreach($languages as $language)
                      @if($language->alias == $translation->language) {{$language->title }} @endif
                    @endforeach
                  </a></li>
                @endif
            @endforeach
          </ul>
      @endif



  @else <!-- ///////////////***// -->
      @foreach($languages as $language)
        @if ($language->alias == request()->locale)
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language" style="color: #5cc9df !important;"></i>{{ $language->title }}</a>
        @else
        <ul class="dropdown-menu lang_drb_che"  style="max-width: 50px;">
          <li><a style="font-size: 15px;padding: 9px 20%;width: 100% !important;text-align: center;"  href="{{ route( request()->route()->getName() , [ 'locale' => $language->alias ] ) }}">{{ $language->title }}</a></li>
        </ul>
        @endif
      @endforeach
  @endif





</li>
