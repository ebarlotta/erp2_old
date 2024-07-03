<div>
    <div class="sm:block md:hidden lg:hidden xl:hidden">
        <?php echo session('nombre_empresa').'<br>'; ?>
    </div>
    {{-- Modo Escritorio --}}
    <div class="hidden sm:hidden md:block lg:block xl:block">
        <?php echo session('nombre_empresa').'<br>'; ?>
        <div class="hidden sm:hidden md:block lg:block xl:block  mb-4 mr-2 text-left mt-6" style=" display: flex; flex-wrap: wrap; width: 100%; justify-content: center;">
            <div>
                <!doctype html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <title>Chart Sample</title>
                    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
                </head>
                <body class="h-screen bg-gray-100">
                
                <div class="container px-4 mx-auto">
                
                    <div class="p-6 m-20 bg-white rounded shadow">
                        {!! $chart->container() !!}
                    </div>
                
                </div>
                
                <script src="{{ $chart->cdn() }}"></script>
                
                {{ $chart->script() }}
                </body>
                </html>
                
            </div>

        </div>
    </div>
</div>
