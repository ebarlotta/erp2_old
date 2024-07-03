<div>
    <x-titulo>Informes</x-titulo>
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
                    <div class="flex justify-around">
                    {{-- <x-crear>Nuevo Informe</x-crear> --}}
                        <button class="bg-green-300 hover:bg-green-400 text-white-900 font-bold py-2 px-4 rounded" wire:click="create({{ $user_id }})">Nuevo Informe</button>
                        @if ($isModalOpen)
                            @include('livewire.tablas.createtablas')
                        @endif
                        @if (session('AsignacionOk')) 
                            <div class="alert alert-success">{{ session('AsignacionOk') }}
                        @endif
                        <div class="w-full">{{ $datos->links() }}</div>
                    </div>
                    <div style="display: block">
                        <div class="flex">
                            <div class="block w-1/2"><p><h3>Usuarios</h3></p>
                                <table class="table-fixed table-striped w-full" style="background-color: lightblue;">
                                    {{-- <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2">Nombre de la Etiqueta</th>
                                            <th class="px-4 py-2">Valor</th>
                                            <th class="px-4 py-2">Opciones</th>
                                        </tr>
                                    </thead> --}}
                                    <tbody>
                                        @if ($users)
                                            @foreach ($users as $user)
                                                <tr wire:click="CargarInformesHabilitados({{ $user->user_id }})" style="border: 5px solid white;" >
                                                    {{-- <td class="border px-4 py-2 text-left">{{ $tag->name }}</td> --}}
                                                    <td class="border px-4 py-2 text-left w-25">
                                                        @if($user->avatar)
                                                            <p><img src="{{ $user->avatar }}" alt="" width="40px;" height="40px;" style="margin: 7px;"></p>
                                                        @else
                                                            <p><img src="images/sin_imagen.jpg" alt="" width="40px;" height="40px;" style="margin: 7px;"></p>    
                                                        @endif
                                                    </td>
                                                    <td class=" w-75">
                                                        {{ $user->name }}
                                                    </td>
                                                    {{-- <td class="border px-4 py-2">
                                                        <div class="flex justify-center">
                                                            <!-- Editar  -->
                                                            <x-editar id="{{ $tag->id }}"></x-editar>
                                                            <!-- Eliminar -->
                                                            <x-eliminar id="{{ $tag->id }}"></x-eliminar>
                                                        </div>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                {{-- @foreach ($users as $user)
                                    <div class="p-2 shadow-lg" style="background:linear-gradient(90deg, lightblue 20%, white 50%); width:93%; height:100px; display: flex; margin: 1.25rem; border-radius: 10px;" wire:click="CargarInformesHabilitados({{ $user->user_id }})">
                                        <div style="width:70%;">
                                            <div style="width:100%; display: flex">
                                                @if($user->avatar)
                                                    <p><img src="{{ $user->avatar }}" alt="" width="40px;" height="40px;" style="margin: 7px;"></p>
                                                @else
                                                    <p><img src="images/sin_imagen.jpg" alt="" width="40px;" height="40px;" style="margin: 7px;"></p>    
                                                @endif
                                                <p class="shadow-md m-1" style="font-size: 18px; background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px; height: 45px;">{{ $user->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach --}}
                            </div>

                            <div class="block w-1/2">
                                <p><h3>Informes Habilitados</h3></p>
                                <table class="table-fixed table-striped w-full ml-3" style="background-color: lightgreen;">
                                    <tbody>
                                        @if(count($tablas)>=1)
                                            @foreach ($tablas as $tabla)
                                                <tr wire:click="CargarInformesHabilitados({{ $user->user_id }})" style="height: 40px; border: 5px solid white;">
                                                    <td>
                                                        {{ $tabla['name'] }}
                                                    </td>
                                                </tr>
                                            @endforeach                
                
                                            {{-- @foreach ($tablas as $tabla)
                                                <div class="p-2 shadow-lg" style="background:linear-gradient(90deg, lightblue 20%, white 50%); width:93%; height:100px; display: flex; margin: 1.25rem; border-radius: 10px;">
                                                    <div style="width:30%;">
                                                        <div style="width:100%; display: flex">
                                                            <p class="shadow-md m-1" style="font-size: 18px; background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px;">{{ $tabla['name'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach --}}
                                        @else
                                            @if($tablas==[])
                                                <tr>
                                                    <td>
                                                        Seleccione un usuario            
                                                    </td>
                                                </tr>
                                            @else
                                            <tr>
                                                <td>
                                                    Ningún informe habilitado
                                                </td>
                                            </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
