
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@if (Auth::guard('web')->user())
  @if(Auth::guard('web')->user()->email_verified_at == null )
    <script>
    $(".subscribe").click(function(event) {
        $("#result").removeClass('d-none');
        $("#result").addClass('text-center');
        $("#result").html('<h4 class="card-title text-center">{{ __("core.plese_Verify_email") }}</h4> <a class="link-from-here text-center" href="{{route("profile")}}">{{__("core.from_here")}}</a>');
        document.body.scrollTop = 10;
        document.documentElement.scrollTop = 10;
      });
    </script>
  @else
    <script>
        $(".subscribe").click(function(event) {
            var ajaxRequest;
            $("#result").addClass('d-none');
            var id=$(this).attr( "att-id" );
            var name=$(this).attr( "att-name" );

            event.preventDefault();
               ajaxRequest= $.ajax({
                    url: $(this).attr( "att-URL" ),
                    type: "get",
                });
                ajaxRequest.done(function (response, textStatus, jqXHR){
                     // Show successfully for submit message
                     console.log('success');
                     $("#subscribe_"+name).addClass('d-none');
                     $("#unsubscribe_"+name).removeClass('d-none');
                });

                /* On failure of request this function will be called  */
                ajaxRequest.fail(function (){
                    console.log("fail");
                    // Show error
                    $("#result").removeClass('d-none');
                    $("#result").html('There is error while submit');
                });
        });
        $(".unsubscribe").click(function(event) {
            var ajaxRequest;
            $("#result").addClass('d-none');
            var id=$(this).attr( "att-id" );
            /* Stop form from submitting normally */
            event.preventDefault();

            /* Get from elements values */

            /* Send the data using post and put the results in a div. */
            /* I am not aborting the previous request, because it's an
               asynchronous request, meaning once it's sent it's out
               there. But in case you want to abort it you can do it
               by abort(). jQuery Ajax methods return an XMLHttpRequest
               object, so you can just use abort(). */
               ajaxRequest= $.ajax({
                    url: $(this).attr( "att-URL" ),
                    type: "get",
                });

                /*  Request can be aborted by ajaxRequest.abort() */

                ajaxRequest.done(function (response, textStatus, jqXHR){
                     // Show successfully for submit message
                     $("#unsubscribe_"+id).addClass('d-none');
                     $("#subscribe_"+id).removeClass('d-none');
                });

                /* On failure of request this function will be called  */
                ajaxRequest.fail(function (){
                    console.log("fail");
                    // Show error
                    $("#result").removeClass('d-none');
                    $("#result").html('There is error while submit');
                });
        });
    </script>
  @endif
@endif
