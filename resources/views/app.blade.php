<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-t">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Vue</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    @vite('resources/js/app.js')
    @routes
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
