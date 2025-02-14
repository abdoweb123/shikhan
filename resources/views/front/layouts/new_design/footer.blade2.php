
  <!--Start Footer Area -->
      <footer class="footer-area " style=" padding-top: 0px !important;">
          
            <div class="footer-bottom-area m-0 p-0">
                <div class="row  " style="width: 100%;">
                    
                    <div class="col-9 col-md-9 col-12 mt-3">
                        
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
                        
                        <i class='bx bx-copyright'></i>2021  <a href="{{ url('/')}}" target="_blank"> اكاديمية البلدة الطيبة</a> | {{ __('core.footer.All rights reserved')}}</div>
                    <div class="col-3 col-md-3 col-12 single-footer-widget" >
                        <ul class="social-link">
                        <li><a href="https://www.facebook.com/BALLDATAYIBA/?ref=pages_you_manage" class="d-block" target="_blank"><i class='bx bxl-facebook'></i></a></li>
                        <li><a href="https://twitter.com/BALDATAYIBA" class="d-block" target="_blank"><i class='bx bxl-twitter'></i></a></li>
                        <!--<li><a href="#" class="d-block" target="_blank"><i class='bx bxl-instagram'></i></a></li>-->
                        <li><a href="https://www.youtube.com/channel/UCoddfkVHlnQjajAAcr8UJ9A" class="d-block" target="_blank"><i class='bx bxl-youtube'></i></a></li>
                        <li><a href="mailto:baldatayiba@gmail.com" class="d-block" target="_blank"><i class='bx bx-envelope'></i></a></li>
                    </ul>
                    </div>
                    
                </div>
            </div>
        </footer>
        <!-- End Footer Area -->
        
        <div class="go-top" style="background-color: #bc8d60;"><i class='bx bx-up-arrow-alt'></i></div>

