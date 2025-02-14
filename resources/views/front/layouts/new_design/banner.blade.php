  <!-- Start Main Banner -->

@isset($results)
    <section class="home-slides owl-carousel owl-theme container">
        @foreach($result  as $item)
            @if($item->title == 'اخرى')
            @else
                <div class="main-banner item-bg3" style="background-image: url('{{ url($item->logo_path) }}');">
                    <a href="{{ route('courses.index',$item->alias) }}"> <div class="d-table">
                        <div class="d-table-cell">
                            <div class="container">
                                <div class="main-banner-content text-center">

                                  {{--  <div class="btn-box">
                                        <a href="{{ route('courses.index',$item->alias) }}" class="default-btn"><i class='bx bx-move-horizontal icon-arrow before'></i><span class="label">عرض المزيد </span><i class="bx bx-move-horizontal icon-arrow after"></i></a>

                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            @endif
        @endforeach
    </section>
@else
  <section class="home-slides owl-carousel owl-theme">
            <div class="main-banner item-bg1" style="background-image: url('{{asset('storage/sites/banner_01.webp')}}');">
              <a class="link-owl" href="{{ route('front.info.show' , [ 'alias' => 'عن-الأكاديمية'] ) }}">
                <div class="d-table">
                    <div class="d-table-cell">
                        <div class="container">
                            <!-- <div class="main-banner-content">
                                <span class="sub-title">متقدم</span>
                                <h1>دبلوم متقدم</h1>
                                <p> محتويات الدبلومة المختصر</p>

                                <div class="btn-box">
                                    <a href="courses-2-columns-style-1.html" class="default-btn"><i class='bx bx-move-horizontal icon-arrow before'></i><span class="label">عرض الدبلومة</span><i class="bx bx-move-horizontal icon-arrow after"></i></a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
              </a>
            </div>


            <div class="main-banner item-bg1" style="background-image: url('{{asset('storage/sites/banner_03.webp')}}');">
              <a class="link-owl" href="{{route('register') }}">
                <div class="d-table">
                    <div class="d-table-cell">
                        <div class="container">
                            <!-- <div class="main-banner-content">
                                <span class="sub-title">متقدم</span>
                                <h1>دبلوم متقدم</h1>
                                <p> محتويات الدبلومة المختصر</p>

                                <div class="btn-box">
                                    <a href="courses-2-columns-style-1.html" class="default-btn"><i class='bx bx-move-horizontal icon-arrow before'></i><span class="label">عرض الدبلومة</span><i class="bx bx-move-horizontal icon-arrow after"></i></a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
              </a>
            </div>


            <div class="main-banner item-bg1" style="background-image: url('{{asset('storage/sites/banner_04.webp')}}');">
              <a class="link-owl" href="{{route('teachers.index') }}">
                <div class="d-table">
                    <div class="d-table-cell">
                        <div class="container">
                            <!-- <div class="main-banner-content">
                                <span class="sub-title">متقدم</span>
                                <h1>دبلوم متقدم</h1>
                                <p> محتويات الدبلومة المختصر</p>

                                <div class="btn-box">
                                    <a href="courses-2-columns-style-1.html" class="default-btn"><i class='bx bx-move-horizontal icon-arrow before'></i><span class="label">عرض الدبلومة</span><i class="bx bx-move-horizontal icon-arrow after"></i></a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
              </a>
            </div>


            <div class="main-banner item-bg1" style="background-image: url('{{asset('storage/sites/banner_06.webp')}}');">
              <a class="link-owl" href="https://www.baldatayiba.com/ar/%D8%A7%D9%84%D8%AF%D8%A8%D9%84%D9%88%D9%85%D8%A7%D8%AA">
                {{-- route('front.info.show' , [ 'alias' => 'الدبلومات'] ) --}}
                <div class="d-table">
                    <div class="d-table-cell">
                        <div class="container">
                            <!-- <div class="main-banner-content">
                                <span class="sub-title">متقدم</span>
                                <h1>دبلوم متقدم</h1>
                                <p> محتويات الدبلومة المختصر</p>

                                <div class="btn-box">
                                    <a href="courses-2-columns-style-1.html" class="default-btn"><i class='bx bx-move-horizontal icon-arrow before'></i><span class="label">عرض الدبلومة</span><i class="bx bx-move-horizontal icon-arrow after"></i></a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
              </a>
            </div>


            <div class="main-banner item-bg1" style="background-image: url('{{asset('storage/sites/banner_07.webp')}}');">
              <a class="link-owl" href="{{ route('front.page.contact_us' ) }}">
                <div class="d-table">
                    <div class="d-table-cell">
                        <div class="container">
                            <!-- <div class="main-banner-content">
                                <span class="sub-title">متقدم</span>
                                <h1>دبلوم متقدم</h1>
                                <p> محتويات الدبلومة المختصر</p>

                                <div class="btn-box">
                                    <a href="courses-2-columns-style-1.html" class="default-btn"><i class='bx bx-move-horizontal icon-arrow before'></i><span class="label">عرض الدبلومة</span><i class="bx bx-move-horizontal icon-arrow after"></i></a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
              </a>
            </div>


        </section>
@endisset
        <!-- End Main Banner -->
