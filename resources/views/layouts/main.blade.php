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
        <div class="flex flex-col m-auto pt-edge px-edge w-full max-w-screen-lg min-h-screen">
            <main class="prose flex-grow max-w-screen-lg">
                @yield('content')
            </main>
            <footer class="py-2 flex justify-start space-x-2">
                <a
                    class="flex text-sm text-gray-700 hover:text-gray-900 hover:underline"
                    href="{{ route('home') }}"
                 >
                    <span>@lang('app.footer.home')</span>
                </a>
                <span class="self-stretch w-px bg-gray-500"></span>
                <a
                    class="flex text-sm text-gray-700 hover:text-gray-900 hover:underline"
                    href="https://github.com/noeldemartin/vocab"
                    target="_blank"
                 >
                    <span>@lang('app.footer.source')</span>
                </a>
            </footer>
        </div>
    </body>
</html>
