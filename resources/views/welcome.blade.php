<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        {{--

        @foreach(front_styles() as $style)<link rel="stylesheet" href="{{ $style }}"> 
        @endforeach

        @stack('styles')

        @foreach(front_scripts_top() as $script)<script src="{{ $script }}"></script> 
        @endforeach

        --}}
        
    </head>

    <body>
    
    </body>
    
</html>