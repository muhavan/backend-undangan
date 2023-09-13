<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Muhavan - PHP Framework">
    <meta name="description" content="Muhavan - PHP Framework">
    <meta name="theme-color" content="#ffffff">
    <meta name="color-scheme" content="light">
    <title>Muhavan - PHP Framework</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('kamu.png') }}">
</head>

<body class="font-sans m-0 bg-gradient-to-br from-indigo-300 to-purple-400">
    <div class="flex h-screen justify-center items-center">
        <div class="text-slate-600 text-center bg-white p-8 rounded-xl drop-shadow-2xl">
            <h1 class="inline font-medium text-4xl">Muhavan |</h1>
            <p class="inline font-medium text-2xl ml-2">{{ $data }}</p>
            <small class="block mt-4" id="information"></small>
        </div>
    </div>

    <script src="{{ asset('js/welcome.js') }}"></script>
</body>

</html>