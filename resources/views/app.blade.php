<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-t">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Vue</title>

    @vite('resources/js/app.js')
    @routes
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
