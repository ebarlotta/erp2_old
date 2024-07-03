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
        <x-crear>Nuevo Informe</x-crear>
        {{-- <button class="bg-green-300 hover:bg-green-400 text-white-900 font-bold py-2 px-4 rounded" wire:click="create({{ $user_id }})">Nuevo Informe</button> --}}
        @if($isModalOpen)
            @include('livewire.tablas.createtablas')
        @endif
        @if (session('AsignacionOk')) 
            <div class="alert alert-success">{{ session('AsignacionOk') }}
        @endif
    </div>
    <div style="display: block">

        <div class="flex">

            <div class="block w-1/2">
                <p><h3>Informes Habilitados</h3></p>
                <table>
                    @if(count($ListadeTablas)>=1)
                        @foreach ($ListadeTablas as $tabla)
                        {{-- <div class="p-2 shadow-lg" style="background:linear-gradient(90deg, lightblue 20%, white 50%); width:93%; height:100px; display: flex; margin: 1.25rem; border-radius: 10px;"> --}}
                            <tr>
                                <td>
                                    <div >
                                        <div style="width:100%;">
                                            <div class="w-full d-flex">
                                                <p class="shadow-md m-1 w-full" style="font-size: 18px; background-color: lightblue; border-radius: 10px; padding: 3px; width: 200px; height: 50px;">{{ $tabla['name'] }}</p>

                                                <div class="flex justify-center">
                                                    <!-- Desde 640 en adelante -->
                                                    <button wire:click="edit('{{$tabla->name}}')" class="hidden lg:flex bg-blue-300 hover:bg-blue-400 text-black-900 font-bold py-2 px-4 mr-2 rounded" style="heigth:10px;height: 50px;margin-top: 5px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                        Activar
                                                    </button>
                                                    <!-- Menos 640 en adelante -->
                                                    <button wire:click="edit('{{$tabla->name}}')" class="lg:hidden bg-blue-300 hover:bg-blue-400 text-black-900 font-bold py-1 px-1 mt-1 rounded" style="heigth:10px;height: 50px;margin-top: 5px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                    </button>
                                                </div>


                                                <div class="flex justify-center">
                                                    <!-- Desde 640 en adelante -->
                                                    <button wire:click="delete({{$tabla->id}})" class="hidden lg:flex bg-red-300 hover:bg-red-400 text-black-900 font-bold py-2 px-4 rounded mt-1w" style="heigth:10px;height: 50px;margin-top: 5px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Desactivar
                                                    </button>
                                                    <!-- Menos 640 en adelante -->
                                                    <button wire:click="delete({{$tabla->id}})" class="lg:hidden flex bg-red-300 hover:bg-red-400 text-black-900 font-bold py-1 px-1 rounded mt-1" style="heigth:10px;height: 50px;margin-top: 5px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <!-- Editar  -->
                                                {{-- <x-editar id="{{ $tabla->id }}"></x-editar><br> --}}
                                                <!-- Eliminar -->
                                                {{-- <x-eliminar id="{{ $tabla->id }}"></x-eliminar> --}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            {{-- </div> --}}
                            </tr>
                        @endforeach
                    @else
                        @if(isset($tablas))
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
                </table>
            </div>
        </div>
    </div>
</div>
