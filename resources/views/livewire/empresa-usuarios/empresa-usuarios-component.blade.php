<div>

    <x-titulo>Relacionar Usuarios a Empresas</x-titulo>
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
                    @if ($seleccionado)
                        <div class="text-left">
                            <button wire:click="mostrarmodal()"
                                class="bg-green-300 hover:bg-green-400 text-white-900 font-bold py-2 px-4 rounded">
                                Relacionar nuevo Usuario
                            </button>
                        </div>
                    @endif
                </div>

                @if ($isModalOpen)
                    @include('livewire.empresa-usuarios.createempresausuarios')
                @endif
                @if ($isModalRoles)
                    @include('livewire.empresa-usuarios.adminempresausuarios-roles')
                @endif
                @if ($datos)
                    <div class="flex">
                        <div class="h-full" style="width: 40%">
                            Empresas
                            @foreach ($datos as $empresa)
                                <ul>
                                    <li class="border text-left @if ($seleccionado == $empresa->id) bg-red-100 @endif"
                                        wire:click="CargarUsuarios({{ $empresa->id }})">
                                        <div class="w-full p-3 hover:scale-110 transition-all duration-500">
                                            <div class="flex rounded overflow-hidden border">
                                                @if($empresa->imagen) 
                                                <img class="block rounded-md flex-none bg-cover"
                                                    src="{{ asset('/'. $empresa->imagen) }}"
                                                    style="width: 100px; height: 100px;">
                                                @else
                                                <img class="block rounded-md flex-none bg-cover"
                                                    src="{{ asset('images/sin_imagen.jpg') }}"
                                                    style="width: 100px; height: 100px;">
                                                @endif
                                                <div
                                                    class="bg-white w-full rounded-b pl-4 flex flex-col justify-between leading-normal">
                                                    <div class="text-black  pt-4 font-bold text-lg mb-2 leading-tight">
                                                        {{ $empresa->name }}</div>
                                                    <p class="text-grey-darker text-base">{{ $empresa->cuit }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                            <div class="w-full">{{ $datos->links() }}</div>
                        </div>
                        <div>
                            <div class="bg-transparent">Usuarios</div>
                            @if ($usuariosdelaempresa)
                                @foreach ($usuariosdelaempresa as $usuario)
                                    <ul>
                                        <li class="border px-4 py-2 text-left bg-red-100">
                                            <div class="w-full p-2 hover:scale-110 transition-all duration-500">
                                                <div class="flex rounded overflow-hidden border">
                                                    <img class="block flex-none bg-cover" src="https://picsum.photos/seed/picsum/80/80" style="width: 100px; height: 100px;">                                            
                                                    <div class="bg-white rounded-b pl-4 pt-4 flex flex-col justify-between leading-normal">
                                                        <div class="text-black font-bold text-lg mb-2 leading-tight">
                                                            {{ $usuario['name'] }}</div>
                                                        {{-- <p class="text-grey-darker text-base">Read more and more</p> --}}
                                                    </div>
                                                    {{-- @if ($usuario->activo) --}}
                                                    <div class="bg-white rounded-b p-4 flex flex-col justify-between leading-normal">
                                                        <div class="text-black font-bold text-xl mb-2 leading-tight">
                                                            <img class="block w-15 h-15 flex-none bg-cover" src="{{ asset('images/activo.jpg') }}" width="40" height="40">
                                                        </div>
                                                    </div>
                                                    {{-- @endif --}}
                                                    <div style="margin: auto;">
                                                        <button class="btn btn-info" wire:click="CambiarRol({{ $usuario['user_id'] }})">
                                                            Cambiar Rol
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @else
                    <h1>No hay datos</h1>
                @endif
            </div>
        </div>
    </div>
</div>
