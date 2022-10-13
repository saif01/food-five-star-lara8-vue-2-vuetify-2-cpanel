<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('common.five-star-icon')
    <title>CP Five Star Login</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/auth/app.css') }}">
</head>
<body>
    <div id="app">
        <div v-if="preloader" class="loader">
            @include('common.5star-loader')
        </div>
        <index-component maintenance="{{$maintenance}}" ></index-component>
        @if($maintenance == 'safsdfdsyyyasfs') <p>Maintenance Mood ON</p> @endif
        @if(config('values.app_debug')) <p>You Are Running In Local</p> @endif
    </div>
    <script src="{{ mix('js/auth/app.js') }}"></script>
</body>

</html>
