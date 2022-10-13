<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('common.five-star-icon')
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('all-assets\common\bootstrap-4.0\css\bootstrap.min.css') }}">
</head>
<body>
    <a href="/logout">
    <div class="h-100 w-100 d-flex flex-column justify-content-center align-items-center">
        @yield('content')
        {{-- <img src="{{ asset('all-assets/errors/403.gif') }}" alt="403" class="img-fluid"> --}}
        <div class="btn rounded-pill btn-success position-absolute" style="bottom: 10%;">Go Back</div>
    </div>
    </a>
    
</body>
</html>