<ul class="navbar card p-3">
    <li class="w-100">
        <div class="btn-group dropleft">
            <button type="button" class="btn btn-secondary mt-0 px-4 w-100 " style="font-size:20px"> <?php echo app('translator')->get('title.lessons'); ?> </button>
            <button type="button" class="btn btn-secondary mt-0 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton">
                @foreach(Auth::guard('web')->user()->track->lessons()->where('active', 1)->orderBy('sequence','ASC')->get() as $row)
                    <a class="dropdown-item btn  {{ $loop->first ? 'mt-0' : '' }}" href="{{ route('lessons.show',$row['id']) }}"> {{ $row['name'] }} </a>
                @endforeach
            </div>
        </div>
    </li>
</ul>
