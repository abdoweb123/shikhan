
<!--********************************    start   Gallry  ***************************************************-->
<button class="btn btn-info" id="button_Show_Hide" >Create gallery</button>
<div id="newpost" style="display: none;z-index: 1000;height: 162%;width: 129%;background-color: rgba(44, 46, 47, 0.55);margin-left: -264px;position: fixed;top: -141px;left: 0;bottom: 0;right: 0;">
    <div    id="gallery_modal" style="padding: 30px;width: 78%;position: fixed;top: 29px;background-color: rgb(255, 255, 255);box-shadow: rgb(85, 119, 119) 1px 1px 20px 0px;background-clip: padding-box;pointer-events: auto;flex-direction: column;border-radius: 10px;outline: 0px;left: 252px;height: 90%;overflow: auto;">
        <div id="gallery_title"style="margin-top: -31px;">
            <div class="title" style="margin-top: 19px;"><h3> Gallery </h3></div>
            <!--search form-->
            <form method="post" action="{{route('gallery.search_gallery')}}" data-parsley-validate class="form-horizontal form-label-left pull-right " style="margin-bottom: -9px;margin-right: 98px;margin-top: -37px;">


                <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input type="text" value="{{ Request::old('search') ?: '' }}" id="search" name="search" class="form-control col-md-7 col-xs-12" placeholder="" style="width: 237px;">
                        @if ($errors->has('search'))
                            <span class="help-block">{{ $errors->first('search') }}</span>
                        @endif

                    </div>
                    <div class="search_check_block">
                        <input type="checkbox" id="search_check" class="pull-right" name="search_check"  style="width: 19px;height: 19px;margin-left: 6px;float: left;margin-right: -19px;margin-top: 8px;">
                        <div class="pull-right" style="margin-right: -60px;"> <p style="padding-top: 7px;">IN All</p></div>
                    </div>
                    <div class="col-md-1 col-sm-4 col-xs-12 ">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                        <input name="_method" type="hidden" value="get">
                        <button id ="search_images" type="submit" class="btn btn-success" style="margin-left: 110px;margin-top: 2px;width: 38px;height: 30px;"><i class="fa fa-search" aria-hidden="true"></i> </button>
                    </div>


                </div>
            </form>

            <!--*********End search**************************-->


            <i class="fa fa-times close "  id="close" aria-hidden="true" style="position: absolute;right: 11px;top: 10px;"></i>
            <div class="ln_solid"></div>
        </div><!--end title-->
        <div class="msg" style="width: 402px;margin-left: 158px;margin-top: -82px;"></div>
        <div class="alert alert-danger print-error-msg" style="display: none;width: 433px;margin-left: 162px;"> <ul></ul></div>


        <div id="gallery_body row">
            <div class="col-md-8" style=" height:500px ; overflow: auto;">

                <!--display images-->

                <div class="preload" style="display: none;margin-left: 200px;" >
                    <div class="loader"></div>
                    <!--<i class="fa fa-spinner" aria-hidden="true"></i>-->
                    <img src="{{asset('admin/images/loader.gif')}}">
                </div>
                <!--      End preload-->

                <div id="image_block" class="row" >


                </div>
                <!--*********************start store_gallery_items***********************************************-->
                <form action="{{route('gallery.store_gallery_items')}}  " method="get" name="add" enctype="multipart/form-data" >


                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <input type="hidden" name="item_id" value="{{ $item_id }}" id="item_id">
                        <input type="hidden" name="item_type" value="{{ $item_type }}" id="item_id">
                        <input type="hidden" name="_token" value="{{ Session::token() }}" id="createG">
                        <button type="submit" id ="gallery_items_upload" class="btn btn-warning col-md-5 col-sm-6 col-xs-12" style="margin-top: 8px;margin-left: 149px;display: none;">Add Images</button>
                    </div>

                </form>
                <!--*********************end store_gallery_items***********************************************-->
                <!--*********************start delete images***********************************************-->
                <!--                        <form action="{{route('gallery.delete_images')}}  " method="get" name="add" enctype="multipart/form-data" >
                <div class="col-md-12 col-sm-6 col-xs-12">
                <input type="hidden" name="item_id" value="{{ $item_id }}" id="item_id">
                <input type="hidden" name="item_type" value="{{ $item_type }}" id="item_id">
                <input type="hidden" name="_token" value="{{ Session::token() }}" id="createG">
                <button type="submit" id ="delete_images" class="btn btn-warning col-md-5 col-sm-6 col-xs-12" style="margin-top: 8px;margin-left: 149px;display: none;">delete Images</button>
            </div>

        </form>-->
        <!--*********************end delete images***********************************************-->



    </div>

    <div class="col-md-4">

        <!--************************************start show images in gallery************-->
        <form action="{{route('gallery.show_gallery_Images')}} " method="get" enctype="multipart/form-data"style="margin-top: 20px">
            <div class="form-group{{ $errors->has('gallery_list') ? ' has-error' : '' }}">
                <label class=" col-md-12 col-sm-3 col-xs-12"style="margin-bottom:7px;" for="gallery_list">Gallery List
                </label>
                <div class="col-md-12 col-sm-6 col-xs-12">
                    <select id="gallery_list"  name="gallery_list"  class="form-control col-md-7 col-xs-12">

                    </select>
                    @if ($errors->has('gallery_list'))
                        <span class="help-block">{{ $errors->first('gallery_list') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-12 col-sm-6 col-xs-12">
                <input type="hidden" name="_token" value="{{ Session::token() }}" id="createG">
                <button id="show_images" class="btn btn-warning col-md-5 col-sm-6 col-xs-12" style="margin-top: 8px;margin-left: 149px;">show_Images</button>
            </div>
        </form>
        <!--************************************end show images in gallery************-->
        <!--*********************** startr upload_gallery*******************************-->
        <form action="{{route('gallery.upload_gallery')}} " method="post" name="upload" enctype="multipart/form-data" id="upload">
            <input type="hidden" value="{{$lang_id}}" name="lang_id" id="lang_id"/>
            <div class="form-group{{ $errors->has('Image[]') ? ' has-error' : '' }}">
                <label class=" col-md-12 col-sm-3 col-xs-12" style="margin-bottom:7px;margin-top: 7px;"for="Image[]">Upload Images
                </label>
                <div class="col-md-12 col-sm-6 col-xs-12">
                    <input type="file" value="{{ Request::old('Image[]') ?: '' }}" id="gallery_files" name="Image[]" class="form-control col-md-7 col-xs-12" accept="image/*" multiple >
                    @if ($errors->has('Image[]'))
                        <span class="help-block">{{ $errors->first('Image[]') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-12 col-sm-6 col-xs-12">
                <input type="hidden" name="_token" value="{{ Session::token() }}" id="createG">
                <button type="submit" id ="gallery_upload" class="btn btn-warning col-md-5 col-sm-6 col-xs-12" style="margin-top: 8px;margin-left: 149px;">Upload</button>
            </div>
        </form>
        <!--*********************** end upload_gallery*******************************-->
        <!--***********************sart create new gallery***************************-->
        <!--                <form action="{{route('gallery.store_gallery')}}" method="post" enctype="multipart/form-data"  name="create" id="create">

        <div class="form-group{{ $errors->has('gallery_name') ? ' has-error' : '' }}">
        <label class=" col-md-12 col-sm-3 col-xs-12"style="margin-bottom:7px;" for="gallery_name">Create New Gallery
    </label>
    <div class="col-md-12 col-sm-6 col-xs-12">
    <input type="text" value="{{ Request::old('gallery_name') ?: '' }}" id="gallery_name" name="gallery_name" class="form-control col-md-7 col-xs-12" accept="image/*" placeholder="gallery Name"  >
    @if ($errors->has('gallery_name'))
    <span class="help-block">{{ $errors->first('gallery_name') }}</span>
@endif
</div>
</div>

<div class="col-md-12 col-sm-6 col-xs-12" style="margin-top: 6px;">
    <input type="file" value="{{ Request::old('cover_img') ?: '' }}" id="cover_img" name="cover_img" accept="image/*" class="form-control col-md-12 col-xs-12"   >
    @if ($errors->has('cover_img'))
        <span class="help-block">{{ $errors->first('cover_img') }}</span>
    @endif
</div>

<div class="col-md-12 col-sm-6 col-xs-12" >
    <input type="hidden" name="_token" value="{{ Session::token() }}">
    <button id="create_gallery" name="create_gallery" class="btn btn-warning col-md-5 col-sm-6 col-xs-12" style="margin-top: 8px;margin-left: 149px;">create</button>
</div>

</form>-->
<!--*****************************end create new gallery********************-->


</div>
</div>

</div>
</div>


<style>
    /*for checkbox*/
    #check {
        width: 24px;
        height: 24px;
        position: relative;
        background: #fcfff4;
        border-radius: 7px;
        box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
        background-color: #6cb6da;
        margin-top: -6px;
        margin-left: 160px;
    }
    textarea {width:181px !important}
</style>
