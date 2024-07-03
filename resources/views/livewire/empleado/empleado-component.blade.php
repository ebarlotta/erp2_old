<div>
    <x-titulo>Empleados</x-titulo>
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
                        @if(session('Empleados.Agregar'))
                            <x-crear>Nuevo Empleado</x-crear>
                            @if ($isModalOpen)
                                @include('livewire.empleado.createempleados')
                            @endif  
                            <div class="w-full">{{ $datos->links() }}</div>
                        @endif
                    </div>
                    <label for="">Buscar por nombre</label><input class="shadow-md m-1" style="font-size: 18px; background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px;" wire:model="search" type="search" placeholder="Ingresa nombre o cuit">
                    <select wire:model="listaactivos">
                        <option value="1" selected>Sólo Activos</option>
                        <option value="0">Todos</option>
                    </select>
                    <!-- <input type="checkbox" wire:model="listaactivos" value="1">Todos / Sólo Activos -->
                    <div style="display: block">
                        <table class="table-fixed table-striped w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2" style="width: 10%">Legajo</th>
                                    <th class="px-4 py-2">Nombre</th>
                                    <th class="px-4 py-2">DNI</th>
                                    <th class="px-4 py-2">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($datos)
                                    @foreach ($datos as $empleado)
                                        <tr>
                                            <td class="border px-4 py-2 text-left" style="width: 10%">{{ $empleado->legajo }}</td>
                                            <td class="border px-4 py-2 text-left">{{ $empleado->name }}</td>
                                            <td class="border px-4 py-2 text-left">{{ $empleado->dni }}</td>
                                            <td class="border px-4 py-2">
                                                <div class="flex justify-center">
                                                    @if(session('Empleados.Editar'))
                                                        <div class="sm:flex justify-center">
                                                            <!-- Editar  -->
                                                            <x-editar id="{{ $empleado->id }}"></x-editar>
                                                        </div>
                                                    @if(session('Empleados.Eliminar'))
                                                    @endif
                                                        <div class="sm:flex justify-center">
                                                            <!-- Eliminar -->
                                                            <x-eliminar id="{{ $empleado->id }}"></x-eliminar>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table> 


                        {{-- @foreach ($datos as $empleado)
                            @if($empleado->activo)
                                <div class="p-2 shadow-lg" style="background:linear-gradient(90deg, lightblue 20%, white 50%); width:93%; height:100px; display: flex; margin: 1.25rem; border-radius: 10px; height: 100%;">
                            @else
                                <div class="p-2 shadow-lg" style="background:linear-gradient(90deg, lightGray 20%, white 50%); width:93%; height:100px; display: flex; margin: 1.25rem; border-radius: 10px; height: 100%;">
                            @endif
                                <div style="width:90%;">
                                    <div style="width:100%; display: flex">
                                        <p class="shadow-md m-1" style="font-size: 18px; background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px;">{{ $empleado->legajo }}</p>
                                        <p class="shadow-md m-1" style="background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px;">{{ $empleado->name }}</p>
                                    </div>
                                    <div style="width:100%; display: flex">
                                        <p class="shadow-md m-1" style="background-color: rgb(226, 230, 230);border-radius: 10px; padding: 3px;">{{ $empleado->dni }}</p>
                                    </div>
                                </div>
                                <div style="width:10%;">
                                    <div class="block justify-center" style="width: 20%; margin: auto; justify-content: space-around;align-items: center;">
                                        <!-- Editar  -->
                                        <x-editar id="{{ $empleado->id }}"></x-editar>
                                        <!-- Eliminar -->
                                        <x-eliminar id="{{ $empleado->id }}"></x-eliminar>
                                    </div>
                                </div>
                            </div>
                        @endforeach --}}
                    </div>
                </div>
