<!DOCTYPE html>
<html lang="ES">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Activos fijos</title>
        <link rel="stylesheet" href="{{asset('css/spinner.css')}}">
        <link rel="stylesheet" href="{{asset('css/acordeon.css')}}">
        <link rel="stylesheet" href="{{asset('css/modal.css')}}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <body class="bg-gray-100">
        <main class="max-w-7xl m-auto pt-7 pb-5 px-2 sm:px-0">
            {{$slot}}
        </main>
    </body>
</html>