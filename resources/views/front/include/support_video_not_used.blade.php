
<!-- Large modal -->

<button type="button"
  style="border: 1px solid #03a84e;background-color: white;border-radius: 50px;padding: 12px 55px;"
  class="btn" data-toggle="modal" data-target="#support_model_{{ $modalId }}">
<span>{{ $title }}</span>
<i class="fas fa-play-circle" aria-hidden="true" style="font-size: 30px;"></i>
</button>

<div class="modal fade bd-example-modal-lg" id="support_model_{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

           @php
              $supportItem =  new \stdClass();
              $supportItem->value = $modalContent;
           @endphp

           @if($type == 'video')
             @include('components.options.video',['item' => $supportItem])
           @endif
           @if($type == 'source')
             @include('components.options.source',['item' => $supportItem])
           @endif
           @if($type == 'pdf_download')
             @include('components.options.pdf_download',['item' => $supportItem])
           @endif
           @if($type == 'pdf_read')
             @include('components.options.pdf_read',['item' => $supportItem])
           @endif
           @if($type == 'doc_read')
             @include('components.options.doc_read',['item' => $supportItem])
           @endif
           @if($type == 'sound')
             @include('components.options.sound',['item' => $supportItem])
           @endif

    </div>
  </div>
</div>
