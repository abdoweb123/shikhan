@if($type==2)
    @php $depth = $depth + 1 ; @endphp
    @isset($childs)
      @if (! empty($childs))
        @foreach ($childs as $am => $item)
        <div class="col-lg-3 col-6 "  style="direction: rtl;">
            <span class="badge badge-pill badge-primary Badg-No" >{{$am+1}}</span>
            <div class="single-product-box mb-30">
                <div class="product-image">
                    <a href="{{ route('front.contents.index' , [ 'alias' => $item->activeTranslation->first()->alias ] ) }}">
                        <img src="{{ $item->activeTranslation->first()->getImagePath() }}" alt="image">
                        <!--<img src="{{ $item->activeTranslation->first()->getImagePath() }}" alt="image">-->
                    </a>
    
                </div>
    
                <div class="product-content">
                    <h3><a href="{{ route('front.contents.index' , [ 'alias' => $item->activeTranslation->first()->alias ] ) }}">{{ $item->activeTranslation->first()->title }}</a></h3>
                    @if(app()->getlocale() != 'ar')
                		@if($item->translation->first())
                            <span  style="text-align: center;">
                                <div class="trans"style="left: auto !important;margin: 5px;" >
                                    <span class="fa fa-language get_trans" type_act="show"  att_div="child{{$item->translation->first()->id}}"></span>
                                </div>
                             
                              <div >
                                <span class="hiden" id="child{{$item->translation->first()->id}}">{{ $item->translation->first()->title }}</span>
                              
                              </div>
                            </span>
                    	@endif
                    	
                	@else
                	
                	    @if($item->translation('en')->first())
                           <span style="text-align: center;" >
                                <div class="trans" >
                                    <span class="fa fa-language get_trans" type_act="show"style="left: auto !important;margin: 5px;" att_div="child{{$item->translation->first()->id}}"></span>
                                </div>
                             
                              <div  class="hiden" id="child{{$item->translation->first()->id}}">
                                <span>{{ $item->translation('en')->first()->title }}</span>
                              
                              </div>
                            </span>
                    	@endif
                	
                    @endif
    
                </div>
            </div>
        </div>
        {{--<x-front.content-childs :childs="$item->allChilds" :depth="$depth"/>--}}
          
          
        @endforeach
      @endif
    @endisset
@else


    <!--@php $depth = $depth + 1 ; @endphp-->
    @isset($childs)
      @if (! empty($childs))
      
            @foreach ($childs as $am => $item)
             
            {{--
    
                <div class="col-lg-12">
                    <div class="courses-details-desc1">
                        <div class="courses-accordion1">
                            <ul class="accordion1">      
                                <li class="accordion-item1">
                                    <a class="accordion-title" href="javascript:void(0)">
                                        <i class="bx bx-chevron-down"></i>
                                         {{ $item->activeTranslation->first()->title }}
                                    </a>
    
                                    <div class="accordion-content1" style="display: none;">
                                        <ul class="courses-lessons1">
                                            
                                           	<x-front.content-childs :childs="$item->allChilds" depth="1"/> 
                                        </ul>
                                    </div>
                                </li> 
                            </ul>
                        </div>
                    </div>
                </div>
                        
                                    
           <div class="col-lg-3 col-6 "  style="direction: rtl;">
                <span class="badge badge-pill badge-primary Badg-No" >{{$item->translation->first()->id+1}}</span>
                <div class="single-product-box mb-30">
                    <div class="product-image">
                        <a href="{{ route('front.contents.index' , [ 'alias' => $item->activeTranslation->first()->alias ] ) }}">
                            <img src="{{ $item->activeTranslation->first()->getImagePath() }}" alt="image">
                            <!--<img src="{{ $item->activeTranslation->first()->getImagePath() }}" alt="image">-->
                        </a>
        
                    </div>
        
                    <div class="product-content">
                        <h3><a href="{{ route('front.contents.index' , [ 'alias' => $item->activeTranslation->first()->alias ] ) }}">{{ $item->activeTranslation->first()->title }}</a></h3>
        
                    </div>
                </div>
            </div>
            <x-front.content-childs :childs="$item->allChilds" :depth="$depth"/>--}}
          
                                       
            <li class="row single-lessons p-1">
                <div class=" col-4  lessons-info p-0 m-0">
                   <a href="{{ route('front.contents.index' , [ 'alias' => $item->activeTranslation->first()->alias ] ) }}"> <img src="{{ $item->activeTranslation->first()->getImagePath() }}" alt="image" width="45px"></a>
                </div>
                <div class=" col-8  d-md-flex d-lg-flex align-items-center">
                    <span class="number badge badge-pill badge-primary ">  {{$am+1 >= 10 ? $am+1  : '0'.$am+1 }}.</span>
                    <a href="{{ route('front.contents.index' , [ 'alias' => $item->activeTranslation->first()->alias ] ) }}"style=" font-size: 18px;">{{ $item->activeTranslation->first()->title }}</a>
                
                    @if(app()->getlocale() != 'ar')
                    		@if($item->translation->first())
                                <span  style="text-align: center;">
                                    <div class="trans"style="left: auto !important;margin: 5px;" >
                                        <span class="fa fa-language get_trans" type_act="show"  att_div="child{{$item->translation->first()->id}}"></span>
                                    </div>
                                 
                                  <div >
                                    <span class="hiden" id="child{{$item->translation->first()->id}}">{{ $item->translation->first()->title }}</span>
                                  
                                  </div>
                                </span>
                        	@endif
                        	
                    	@else
                    	
                    	    @if($item->translation('en')->first())
                               <span style="text-align: center;" >
                                    <div class="trans" >
                                        <span class="fa fa-language get_trans" type_act="show"style="left: auto !important;margin: 5px;" att_div="child{{$item->translation->first()->id}}"></span>
                                    </div>
                                 
                                  <div  class="hiden" id="child{{$item->translation->first()->id}}">
                                    <span>{{ $item->translation('en')->first()->title }}</span>
                                  
                                  </div>
                                </span>
                        	@endif
                    	
                        @endif
                
                </div>
            
                
                                                                
            </li>
    
          
          
          
        @endforeach
      @endif
    @endisset
@endif
  
  