<ul class="navbar">
    @if(Route::currentRouteName() != 'lessons.show')
        <li> <a target="_blank" style="font-size:20px" href="{{ route('lessons.show',['lesson_id' => $row['id']]) }}" target="new" class=" btn py-3 px-4"> @lang('core.back') </a> </li>
    @endif
    @if(!empty($row['video_path']) && Route::currentRouteName() != 'lessons.video')
        <li> <a target="_blank" style="font-size:20px" href="{{ route('lessons.video',['lesson_id' => $row['id']]) }}" target="new" class=" btn py-3 px-4"> @lang('field.video') </a> </li>
    @endif
    @if(($row->track->type == 'total' || !is_null($row->parent_id)) && $row->summaries()->where('active',1)->count() && Route::currentRouteName() != 'lessons.summary')
        <li> <a target="_blank" style="font-size:20px" href="{{ route('lessons.summary',['lesson_id' => $row['id']]) }}" target="new" class=" btn py-3 px-4"> @lang('field.summary') </a> </li>
    @endif
    @if(($row->track->type == 'total' || !is_null($row->parent_id)) && !empty($row->dictionary) && Route::currentRouteName() != 'lessons.dictionary')
        <li> <a target="_blank" style="font-size:20px" href="{{ route('lessons.dictionary',['lesson_id' => $row['id']]) }}" target="new" class=" btn py-3 px-4"> @lang('field.dictionary') </a> </li>
    @endif
    @if(!empty($row->activity) && Route::currentRouteName() != 'lessons.activity')
        <li> <a target="_blank" style="font-size:20px" href="{{ route('lessons.activity',['lesson_id' => $row['id']]) }}" target="new" class=" btn py-3 px-4"> @lang('title.activity') </a> </li>
    @endif
</ul>
