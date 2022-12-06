<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>

        @vite('resources/css/app.css')
    </head>
    <body class="text-base font-normal leading-tight text-gray-900 antialiased">
        <main class="m-auto px-edge mt-edge prose max-w-screen-lg">
            @yield('content')
        </main>
    </body>
</html>
