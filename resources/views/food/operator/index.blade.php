<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('common.five-star-icon')
    <title>CP Five Star Operator</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/food/operator/app.css') }}">
</head>

<body>
    <div id="app">
        <div v-if="preloader" class="loader">
            @include('common.5star-loader')
        </div>
        <index-component authuser="{{ Auth::user() }}" permission="{{ $roles }}" language="{{ $language }}">
        </index-component>
    </div>
    <script src="{{ mix('js/food/operator/app.js') }}"></script>
</body>

</html>
