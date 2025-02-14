


  <div class="container-fluid">
    <div class="row" style="margin: 10px;">

      <div class="col-lg-12">
        <div class="section-heading">
          <h6></h6>
          <h2>{{ $lesson->title }}</h2>

          <div>
              {{ $lesson->teacher?->title}}<br>
              {!! $lesson->getDescription() !!}<br>



              @foreach($lesson->options->sortBy('sort') as $item)
                  @if($item->option?->isVideo())
                    <x-options.video :item='$item' :mainTitle="$lesson->title" />
                  @endif
                  @if($item->option?->isSound())
                    @include('components.options.sound',['item' => $item, 'main_title' =>  __('words.lesson_sound') . ' ' . $lesson->title ])
                  @endif
                  @if($item->option?->isPdfDownload())
                     @include('components.options.pdf_download',['item' => $item, 'main_title' =>  __('words.lesson_pdf') . ' ' . $lesson->title ])
                  @endif
                  @if($item->option?->isPdfRead())
                    @include('components.options.pdf_read',['item' => $item])
                  @endif
                  @if($item->option?->isDocDownload())
                    @include('components.options.doc_download',['item' => $item, 'main_title' =>  __('words.lesson_pdf') . ' ' . $lesson->title ])
                  @endif
                  @if($item->option?->isDocRead())
                  <x-options.doc_read  :item='$item' :mainTitle="$lesson->title" />
                  @endif
              @endforeach

          </div>

        </div>
      </div>
    </div>
  </div>
