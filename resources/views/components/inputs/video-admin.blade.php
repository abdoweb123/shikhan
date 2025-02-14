<?php $url = $file; $myvalue = trim($file); $src=""; ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-12 embed-responsive embed-responsive-16by9" style="width: 100%;height: 100%;">
@if(strpos($myvalue, 'ifram') !== false)
     <?php $splite_ifram = explode('src="',$myvalue);
          if($splite_ifram[1]) { $splite_src=explode('"',$splite_ifram[1]); if($splite_src[0]){$src=$splite_src[0];} }
     ?>
     <embed width="420" height="315" src="{{$src}}">
@elseif ((strpos($myvalue, 'youtube') !== false)&&((strpos($myvalue, 'embed/') !== false)&&(strpos($myvalue, 'ifram') == false)&&(strpos($myvalue, 'object') == false)))
    <embed width="420" height="315" src="{{$myvalue}}">
@elseif ((strpos($myvalue, 'youtube') !== false)&&((strpos($myvalue, 'embed/') == false)&&(strpos($myvalue, 'ifram') == false)&&(strpos($myvalue, 'object') == false)))
      <?php $raw_link = $file; $link_without_details = preg_replace('/&(.*)/','',$raw_link); $t = str_replace('watch?v=', 'embed/', $link_without_details); $src=$t; ?>
      <embed width="420" height="315" src="{{$src}}">
@elseif(strpos($myvalue, 'object') !== false)
      <?php $arr = explode('www.youtube.com/v/',$myvalue); $src='';
          if($arr[1])
          {
              $youtube_id = explode('?',$arr[1]);
              if($youtube_id[0])
              {$tube_id=$youtube_id[0]; $src=$tube_id; }
          }
      ?>
       <embed width="420" height="315" src="https://www.youtube.com/embed/{{$src}}">
@elseif(strpos($myvalue, 'drive.google.com') !== false)
<div style="width: 100%">
<iframe src="{{$myvalue}}" width="420" height="280"></iframe>
</div>
@else
<div style="width: 450px; height 300px;">
      <video id="charVideo" controls="controls"
      @if (isset($width)) style="width: {{$width}}; height: {{$height}};" @endif title="">
          <source src="{{ asset('storage/app/public/'.$file) }}" >

            <!-- <source id="mp4Video" type="video/mp4">
            <source id="ogvVideo" type="video/ogv">
            <source id="webmVideo" type="video/webm"> -->
      </video>
    </div>
@endif
</div>
