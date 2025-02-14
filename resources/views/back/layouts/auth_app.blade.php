<!doctype html>
<html lang="en">
<head>
    @include('back/includes.head')
    @yield('back_css')

</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="right_col" role="main">
                @yield('content')
            </div>
        </div>
    </div>
    {{-- @include('back/includes.footer') --}}
    @include('back/includes.script')

    @yield('js_pagelevel')
</body>
</html>
