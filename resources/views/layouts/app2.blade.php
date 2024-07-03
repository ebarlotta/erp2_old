<! DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Tailwind -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    
    <!-- Tooltips -->
    <link rel="stylesheet" href="{{asset('css/tooltips.css')}}">




    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


    <!-- Styles -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    
    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="js/jquery.mask.js"></script>
</head>
<body class="font-sans antialiased">
    
    <x-jet-banner />

    <div class="bg-gray-100 h-full">
        {{-- @livewire('navigation-menu') --}}

        <!-- Page Heading min-h-screen -->
        {{-- @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif --}}

        <!-- Page Content -->
        <main>
            {{ $slot ?? '' }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
