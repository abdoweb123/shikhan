<!-- 9- video-->
<style>
@media screen and (min-width: 0px) and (max-width: 990px) {
  #video_iframe { height: 200px;width: 280px; }
}

@media screen and (min-width: 991px) and (max-width: 2048px) {
  #video_iframe { height: 420px;width: 640px; }
}
</style>

<span></span>
<?php
    $url = $item->value;
    $myvalue = trim($url);
    $src="";
?>


<div class="embed-responsiveembed-responsive-16by9" style="text-align: center;">
      @if(strpos($myvalue, 'ifram') !== false)
          <?php
            $splite_ifram = explode('src="',$myvalue);

            if($splite_ifram[1]){
              $splite_src=explode('"',$splite_ifram[1]);
              if($splite_src[0]){
                $src=$splite_src[0];
              }
            }
          ?>
          <!--1-->
          <iframe id="video_iframe" class="embed-responsive-item" src="{{$src}}"></iframe>
      @elseif ((strpos($myvalue, 'youtube') !== false)&&((strpos($myvalue, 'embed/') !== false)&&(strpos($myvalue, 'ifram') == false)&&(strpos($myvalue, 'object') == false)))
          <!--2-->
           <iframe id="video_iframe" style="height: 420px;width: 640px;" class="embed-responsive-item" src="{{$myvalue}}"></iframe>
      @elseif ((strpos($myvalue, 'youtube') !== false)&&((strpos($myvalue, 'embed/') == false)&&(strpos($myvalue, 'ifram') == false)&&(strpos($myvalue, 'object') == false)))
          <?php
          $raw_link = $item->value;
          $link_without_details = preg_replace('/&(.*)/','',$raw_link);
          $t = str_replace('watch?v=', 'embed/', $link_without_details);
           $src=$t;
          ?>
          <!--3-->
          <iframe id="video_iframe" style="height: 420px;width: 640px;" class="embed-responsive-item" src="{{$src}}"></iframe>
          @elseif(strpos($myvalue, 'video') !== false)
            {!!$myvalue!!}
          @elseif(strpos($myvalue, 'object') !== false)
          <?php
          $arr = explode('www.youtube.com/v/',$myvalue);
          $src='';
          if($arr[1]){
          $youtube_id = explode('?',$arr[1]);
              if($youtube_id[0])
              {$tube_id=$youtube_id[0];
               $src=$tube_id;
              }
            }
           ?>
         <!--4-->
        <iframe id="video_iframe" style="height: 420px;width: 640px;" class="embed-responsive-item" src="https://www.youtube.com/embed/{{$src}}"></iframe>
      @else
        {{--
        <!-- <iframe id="" style="height: 420px;width: 640px;" class="embed-responsive-item" src="{{ Storage::url($url) }}"></iframe> -->
        --}}
        <!-- in i frame can't dispale auto play , but with video tag it's dispaled by default -->
        <video width="640" height="420" controls>
            <source src="{{ Storage::url($url) }}">
        </video>


      @endif

</div>

{{--
    @php
       if ($myvalue){
         $video_id = substr($myvalue, (strripos($myvalue,"/")+1) ) ;
       }
       if($src) {
         $video_id = substr($src, (strripos($src,"/")+1) ) ;
       }

       $url = 'https://www.googleapis.com/youtube/v3/videos?part=statistics&id='.$video_id.'&key=AIzaSyCwqEq1jgUqn1X3Mg1Ovib7ZF4kjylGmys';
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

       $response = curl_exec ($ch);
       $err = curl_error($ch);  //if you need
       curl_close ($ch);
       $vedio_data = json_decode($response,true);
    @endphp

    <div class="row" style="padding-top: 20px;">
        <div class="col-3" style="text-align: center;">
          <i class="fa fa-eye" style="font-size: 27px;"></i><br>
          {{ count($vedio_data['items']) ? $vedio_data['items'][0]['statistics']['viewCount']  : ''}}
        </div>
        <div class="col-3" style="text-align: center;">
          <i class="fa fa-thumbs-up" style="font-size: 27px;"></i><br>
          {{ count($vedio_data['items']) ? $vedio_data['items'][0]['statistics']['likeCount']  : ''}}
        </div>
        <div class="col-3" style="text-align: center;">
          <i class="fa fa-heart" style="font-size: 27px;"></i><br>
          {{ count($vedio_data['items']) ? $vedio_data['items'][0]['statistics']['favoriteCount']  : ''}}
        </div>
        <div class="col-3" style="text-align: center;">
          <i class="fa fa-comment-dots" style="font-size: 27px;"></i><br>
          {{ count($vedio_data['items']) ? $vedio_data['items'][0]['statistics']['commentCount']  : ''}}
        </div>
    </div>
    --}}
