<div>
    <x-titulo>Gestionar las distintas empresas</x-titulo>
    <x-slot name="header">
        <div class="flex">
            <!-- //Comienza en submenu de encabezado -->

            <!-- Navigation Links -->
            @livewire('submenu')
        </div>

    </x-slot>

    <div class="content-center flex">
        <div class="bg-white p-2 text-center rounded-lg shadow-lg w-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    @if (session()->has('message'))
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                            role="alert">
                            <div class="flex">
                                <div>
                                    <p class="text-xm bg-lightgreen">{{ session('message') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="text-left">
                        <button wire:click="CrearEmpresa()"
                            class="bg-green-300 hover:bg-green-400 text-white-900 font-bold py-2 px-4 rounded">
                            Crear empresa
                        </button>
                    </div>
                </div>
                @if ($isModalOpen)
                    @include('livewire.empresa-gestion.createempresa')
                @endif
                @if ($datos)
                    <div>
                        @foreach ($datos as $empresa)
                            <ul>
                                <li class="border text-left @if ($seleccionado == $empresa->id) bg-red-100 @endif"
                                    wire:click="CargarDatosEmpresa({{ $empresa->id }})">
                                    <div class="w-full p-3 hover:scale-105 transition-all duration-500" style="hover:background-color=pink">
                                        <div class="flex rounded overflow-hidden border hover:bg-red-100 ">
                                            @if($empresa->imagen) 
														<img class="block rounded-md flex-none bg-cover"
															 src="{{ asset(''. $empresa->imagen) }}"
															 style="width: 100px; height: 100px;">
														@else
														<img class="block rounded-md flex-none bg-cover"
															 src="{{ asset('images/sin_imagen.jpg') }}"
															 style="width: 100px; height: 100px;">
														@endif
                                            <div class="bg-white rounded-b ml-4 pt-4 pl-4 flex flex-col justify-between leading-normal bg-transparent">
                                                <div class="text-black font-bold text-lg mb-2 leading-tight bg-transparent" style="hover:background-color: yellow;">
                                                    {{ $empresa->name }}</div>
                                                <p class="text-grey-darker text-base">Read more</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                        <div class="w-full">{{ $datos->links() }}</div>
                    </div>
                @else
                    <h1>No hay datos</h1>
                @endif
            </div>
        </div>
    </div>
</div>
