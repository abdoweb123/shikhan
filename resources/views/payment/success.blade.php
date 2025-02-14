<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	@if(isset(request()->success)&&request()->success==="true")
        <title>نجاح الدفع</title>
	@else
	<title>فشل الدفع</title>
	@endif
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .code {
                border-right: 2px solid;
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="message" style="padding: 10px;">
             @if(isset(request()->success)&&request()->success==="true")
        <h4> تم الدفع بنجاح</h4>
	@else
	<h4>فشل الدفع</h4>
	@endif
	<br>
	<div>
	<a href="{{url('/')}}">الرئيسية</a>
	</div>
            </div>
        </div>
    </body>
</html>
