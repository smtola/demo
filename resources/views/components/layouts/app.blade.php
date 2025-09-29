<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Book SMS') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @if(! config('livewire.inject_assets'))
        @livewireStyles
    @endif
</head>
<body class="bg-gray-50 text-gray-900">
    {{ $slot }}

    @if(! config('livewire.inject_assets'))
        @livewireScripts
    @endif
</body>
</html>


