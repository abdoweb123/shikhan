Tawk  <!--Start Footer Area -->
    <style type="text/css">


      @media screen and (min-width: 0px) and (max-width: 800px) {
        .icon_whatsapp {
            position: fixed;
            bottom: 2%;
            left: 88%;
            width: max-content;
        }
      }
      @media screen and (min-width: 801px) and (max-width: 1900px) {
        .icon_whatsapp {
            position: fixed;
            bottom: 2%;
            left: 95%;
            width: max-content;
        }
      }

    .icon_whatsapp img {
        width: 100%;
    }
    </style>
    @php $whatsNumber = '+201507413993'; @endphp
    @php $whatsMessage = 'السلام عليكم ورحمة الله وبركاته '; @endphp
    <a href="https://api.whatsapp.com/send?phone={{$whatsNumber}}&text={{$whatsMessage}}" data-action="share/whatsapp/share" class="whatsapp-share icon_whatsapp" target="_blank">
        <img src="/images/icons/whats_icon.jpg" style="border-radius: 18px;">
        <!-- <div style="padding: 10px;font-size: 26px;text-decoration: underline !important;">راسلنا</div> -->
    </a>

    {{--
    @if(auth()->user())
      @if(auth()->id() == 5651)
      <a href="https://api.whatsapp.com/send?phone=+201142972722,+201100347311&text={{$whatsMessage}}" data-action="share/whatsapp/share" class="whatsapp-share icon_whatsapp" target="_blank">
          <img src="/images/icons/whats_icon.jpg" style="border-radius: 18px;">
      </a>
      @endif
    @endif
    --}}


      <footer class="footer-area " style=" padding-top: 0px !important;">
            <div class="footer-bottom-area m-0 p-0">
                <div class="row" style="width: 100%;">
                    <div class="col-4 col-md-4 col-12 mt-3">
                        <ul>
                            @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
                                <li>
                                    <img src="{{ asset('assets/img/icons/fada-qnwat.png') }}" alt="فضاء ميديا للبرمجيات" title="فضاء ميديا للبرمجيات" class='img-fluid'>
                                    <span style="font-size: 12px;font-weight: bold;"><a class="nav-link" href="https://www.spacechanels.com/">فضاء القنوات</a></span>
                                </li>
                            @else

                                <li>
                                    <img src="{{ asset('assets/img/icons/fada-qnwat.png') }}" alt="spacechanels" title="spacechanels" class='img-fluid'>
                                    <span style="font-size: 12px;font-weight: bold;"><a class="nav-link" href="https://www.spacechanels.com/">spacechanels</a></span>
                                </li>
                            @endif
                        </ul>

                        <i class='bx bx-copyright'></i>2021  <a href="{{ url('/')}}" target="_blank"> اكاديمية البلدة الطيبة</a> - {{ __('core.footer.All rights reserved')}}</div>


                    <div class="col-3 col-md-3 col-12 single-footer-widget" >
                        <ul class="social-link">
                          @isset($social)
                              @foreach($social as $item_social)
                                <li><a href="{{$item_social->link}}" class="d-block" target="_blank">{!! $item_social->icon !!}</a></li>
                              @endforeach
                          @endisset
                          {{--
                           <!-- <li><a href="https://www.tiktok.com/@baldatayiba" class="d-block" target="_blank"><i class="fab fa-tiktok"></i></a></li>
                           <li><a href="https://www.instagram.com/baldatayiba" class="d-block" target="_blank"><i class='bx bxl-instagram'></i></a></li>
                            <li><a href="https://www.facebook.com/BALLDATAYIBA/?ref=pages_you_manage" class="d-block" target="_blank"><i class='bx bxl-facebook'></i></a></li>
                            <li><a href="https://twitter.com/BALDATAYIBA" class="d-block" target="_blank"><i class='bx bxl-twitter'></i></a></li> -->
                            <!--<li><a href="#" class="d-block" target="_blank"><i class='bx bxl-instagram'></i></a></li>-->
                            <!-- <li><a href="https://www.youtube.com/channel/UCoddfkVHlnQjajAAcr8UJ9A" class="d-block" target="_blank"><i class='bx bxl-youtube'></i></a></li>
                            <li><a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=baldatayiba@gmail.com" target="_blank" class="d-block" target="_blank"><i class='bx bx-envelope'></i></a></li>
                            <li><a href='https://wa.me/+201094962423' class="d-block" target='_blank'><i class= "fa fa-whatsapp"></i></a></li>
                            <li><a href='https://t.me/BALDATAYIBA' class="d-block" target='_blank'><i class= "fa fa-telegram"></i></a></li> -->
                            --}}
                        </ul>
                    </div>

                    @if(! Auth::guard('web')->check())
                    {{--
                    <div class="top-header-btn col-2 col-md-2 col-12 mt-3 p-0" style="text-align: center;">
                        <a href="{{ route('login') }}" class="default-btn" style="background-color: #884d17;color: white;border-radius: 8px;"><i class='bx bx-log-in icon-arrow before'></i><span class="label">{{ __('words.login') }}</span><i class="bx bx-log-in icon-arrow after"></i></a>
                    </div>
                    --}}
                    {{--
                    <div class="top-header-btn col-2 col-md-2 col-12 mt-3 p-0" style="text-align: center;">
                        <a href="{{ route('register') }}" class="default-btn" style="background-color: #884d17;color: white;border-radius: 8px;"><i class="bx bx-log-in-circle icon-arrow before"></i><span class="label">{{ __('core.register') }}</span><i class="bx bx-log-in-circle icon-arrow  after"></i></a>
                    </div>
                    --}}
                    @endif

                </div>
            </div>
        </footer>
        <!-- End Footer Area -->

        <div class="go-top" style="background-color: #bc8d60;"><i class='bx bx-up-arrow-alt'></i></div>



            <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/63f9dcd24247f20fefe29217/1gq40d5pr';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
            
            
            
