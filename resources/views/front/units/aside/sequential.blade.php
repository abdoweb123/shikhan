<ul class="navbar card p-3">
    <li class="w-100"> <a href="{{ route('lessons.index') }}" style="white-space: normal!important;" class=" btn m-0 p-1 w-100"> @lang('title.lessons') </a> </li>
</ul>
@if(Route::currentRouteName() != 'lessons.index')
    @php
    $parent = Auth::guard('web')->user()->track->lessons()->find(is_null(@$row['parent_id']) ? Route::input('lesson_id') : $row->parent_id);
    @endphp
    @if (!empty($parent))
        <ul class="navbar card p-3">
            @foreach($parent->children()->where('active',1)->orderBy('sequence','ASC')->get() as $child)
                <li class="w-100">
                    <a  style="white-space: normal!important;" class="{{ ($row['parent_id'] == Route::input('lesson_id') && $loop->first) || $child['id'] == Route::input('lesson_id') ? 'active' : '' }} btn p-1 w-100 {{ $loop->first ? 'mt-0' : '' }}" href="{{ route('lessons.show',$child['id']) }}"> {{ $child['name'] }} </a>
                </li>
            @endforeach
        </ul>
        @php
        $list = [] ;
        foreach(['show' => 'show','video_path' => 'video','summaries' => 'summary','dictionary' => 'dictionary','activity' => 'activity'] as $k => $v)
        {
            if(($k == 'show' ? Route::input('lesson_id') == $parent['id'] : ($k == 'summaries' ? $parent->summaries()->where('active',1)->count() > 0 : !empty($parent[$k]))) && (Route::currentRouteName() != 'lessons.'.$v || Route::input('lesson_id') != $parent['id']))
            {
                $list[$k] = $v ;
            }
        }
        @endphp
        @if ($list)
            <ul class="navbar card p-3">
                @foreach ($list as $k => $v)
                    <li class="w-100"> <a target="_blank" style="white-space: normal!important;" class="btn p-1 w-100 {{ $loop->first ? 'mt-0' : '' }}" href="{{ route('lessons.'.$v,['lesson_id' => $parent['id']]) }}" target="new" class=" btn py-3 px-4"> @lang($v == 'show' ? 'core.back' : 'field.'.$v) </a> </li>
                @endforeach
            </ul>
        @endif
    @endif
@endif
