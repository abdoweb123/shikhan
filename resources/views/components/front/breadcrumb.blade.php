@isset($breadCrumb)
@if (! empty($breadCrumb))

      @if ( $breadCrumb::PAGE == 'contents' )
      <a  href="{{ route('front.contents.index' ) }}">{{ __('project.levels') }}</a> /
      @if($breadCrumb->parent)
        <a  href="{{ route('front.contents.index' , [ 'alias' => $breadCrumb->parent->activeTranslation->first()->alias ] ) }}">{{ $breadCrumb->parent->translation(app()->getLocale())->first()->title }}</a>/
      @endif
      <a  href="{{ route('front.contents.index' , [ 'alias' => $breadCrumb->activeTranslation->first()->alias ] ) }}">{{ $breadCrumb->translation(app()->getLocale())->first() != null ? $breadCrumb->translation(app()->getLocale())->first()->title : '' }}</a> /
      @endif
      @if ( $breadCrumb::PAGE == 'lesson' )
      <a  href="{{ route('front.lessons.show' , [ 'alias' => $breadCrumb->activeTranslation->first()->alias ] ) }}">{{ $breadCrumb->translation(app()->getLocale())->first() != null ?$breadCrumb->translation(app()->getLocale())->first()->title : '' }}</a>
      @endif
      @if ( $breadCrumb::PAGE == 'pages' )
      <a  href="{{ route( $breadCrumb->activeTranslation->first()->route ) }}">{{ $breadCrumb->activeTranslation->first()->title }}</a>
      @endif
    {{--<x-front.breadcrumb :breadCrumb="$breadCrumb->allParents" />--}}
@endif
@endisset
