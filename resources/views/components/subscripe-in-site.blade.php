{{--
@if(Auth::guard('web')->user()->email_verified_at !== null )
@endif
--}}

@if (Auth::guard('web')->user())

    <script>

    $(".subscribe_in_site").click(function(event) {


        var ajaxRequest;
        var id=$(this).attr( "att-id" );
        var reloadpage = $(this).attr( "att-reloadpage" );
        // console.log(reloadpage);

           event.preventDefault();
           ajaxRequest= $.ajax({
                url: $(this).attr( "att-URL" ),
                type: "post",
                data: { "_token": "{{ csrf_token() }}" }
            });
            ajaxRequest.done(function (response, textStatus, jqXHR){
                 // console.log(id);

                 $("#div_already_sub_"+id).removeClass('hide_div');
                 $("#div_already_sub_"+id).addClass('show_div');
                 $("#div_cancel_sub_"+id).removeClass('hide_div');
                 $("#div_cancel_sub_"+id).addClass('show_div');

                 $("#div_sub_"+id).removeClass('show_div');
                 $("#div_sub_"+id).addClass('hide_div');

                 // reload page - in course show page we have to reload the page to make test button appears,
                 // we not need مشترك to displayed, if user subscribed then show test button so we have to reload page
                 if ( reloadpage !== undefined) {
                    location.reload();
                 }

            });
            ajaxRequest.fail(function (xhr, status, error){
                console.log(xhr.responseText);
            });


    });




    $(".unsubscribe_in_site").click(function(event) {

        var ajaxRequest;
        var id=$(this).attr( "att-id" );
        var reloadpage = $(this).attr( "att-reloadpage" );
        // console.log(reloadpage);

           event.preventDefault();
           ajaxRequest= $.ajax({
                url: $(this).attr( "att-URL" ),
                type: "get",
            });
            ajaxRequest.done(function (response, textStatus, jqXHR){
                 // console.log('success');

                 $("#div_already_sub_"+id).removeClass('show_div');
                 $("#div_already_sub_"+id).addClass('hide_div');
                 $("#div_sub_"+id).removeClass('hide_div');
                 $("#div_sub_"+id).addClass('show_div');
                 $("#div_cancel_sub_"+id).removeClass('show_div');
                 $("#div_cancel_sub_"+id).addClass('hide_div');

                 // reload page - in course show page we have to reload the page to make test button appears,
                 // we not need مشترك to displayed, if user subscribed then show test button so we have to reload page
                 if ( reloadpage !== undefined) {
                    location.reload();
                 }

            });
            ajaxRequest.fail(function (){
                console.log("fail");
            });


    });


    </script>

@endif
