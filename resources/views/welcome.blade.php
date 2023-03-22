<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

        <title>Laravel</title>

        @vite('resources/css/app.css')
    </head>
    <body>
        <div id="app">
            <div class="container" style="display: flex">
                <router-view name="Home"></router-view>
                <router-view name="Show"></router-view>
                <router-view></router-view>
            </div>
        </div>
        @vite('resources/js/app.ts')
    </body>
</html>
