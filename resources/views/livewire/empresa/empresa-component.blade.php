<div>
    <div class="content-center flex">
        <div class="flex-wrap bg-white p-2 text-center rounded-lg shadow-lg w-full flex justify-center">
            @if ($empresas)
                @foreach ($empresas as $empresa)
                    <div class="bg-gray-200 p-2 text-center rounded-lg shadow-lg w-auto m-1 justify-center">
                        <div wire:click="configurarempresa({{ $empresa['id'] }})" class="flex justify-end">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="gray">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <p class="relative -bottom-1 left-0 mx-2"
                            wire:click="cargamodulos({{ $empresa['id'] ? $empresa['id'] : 0 }})">
                            {{ $empresa['name'] }}

                            <img class="rounded-md" src="{{ asset('' . $empresa['imagen']) }}"
                                style="margin: auto; margin-top: 10px; width: 150px; height: 150px;">
                            <!-- <img wire:click="cargamodulos({{ $empresa['id'] }})" class="rounded-md" src="{{ asset('/images2/' . $empresa['imagen']) }}" style="margin: auto; margin-top: 10px; width: 150px; height: 150px;"> -->
                    </div>
                @endforeach
            @else
                <div class="bg-gray-200 p-2 text-center rounded-lg shadow-lg w-auto m-1">
                    <p class="relative -bottom-11 left-0">
                        No hay empresas relacionadas con este usuario.
                    </p>
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <button style="background-color: indianred; width:100%;">
                                {{ __('Log Out') }}
                            </button>
                        </a>
                    </form>
                </div>
            @endif
            {{-- <div class="chart-container" style="position: relative;"> --}}
            {{-- <div style="width: 80%; margin: auto; height:300px; width:40%">
                <canvas id="compras"></canvas>
            </div>
            <div style="width: 80%; margin: auto; height:40vh; width:40%">
                <canvas id="ventas"></canvas>
            </div> --}}
            {{-- </div> --}}

        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <script>
            var ctx = document.getElementById('compras').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($compras['labels']),
                    datasets: [{
                            label: 'Data',
                            data: @json($compras['data']),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2
                        },
                        {
                            label: 'Data',
                            data: @json($ventas['data']),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: {
                                target: 'origin',
                                above: 'rgb(255, 0, 0)', // Area will be red above the origin
                                below: 'rgb(0, 0, 255)' // And blue below the origin
                            }
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctx = document.getElementById('ventas').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($ventas['labels']),
                    datasets: [{
                        label: 'Data',
                        data: @json($ventas['data']),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: {
                            target: 'origin',
                            above: 'rgb(255, 0, 0)', // Area will be red above the origin
                            below: 'rgb(0, 0, 255)' // And blue below the origin
                        }
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        filler: {
                            propagate: true
                        }
                    }
                }
            });
        </script>
    </div>
