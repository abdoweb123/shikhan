<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Example of Bootstrap 3 Warning Alert Message</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        .bs-example {
            margin: 20px;
        }
    </style>
</head>
<body>
<div class="bs-example">
    @if($status == true)
        <div class="alert alert-success">
            {{--<a href="#" class="close" data-dismiss="alert">&times;</a>--}}
            <strong>Success</strong> {{$msg}}
        </div>
    @endif

    @if($status == false)
        <div class="alert alert-danger">
            {{--<a href="#" class="close" data-dismiss="alert">&times;</a>--}}
            <strong>Error</strong> {{$msg}}
        </div>
    @endif
</div>
</body>
</html>
