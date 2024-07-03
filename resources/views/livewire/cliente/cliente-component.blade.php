<div>
    <x-titulo>Clientes</x-titulo>
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
                        @if(session('Clientes.Agregar'))
                            <x-crear>Nuevo Cliente</x-crear>
                            @if ($isModalOpen)
                                @include('livewire.cliente.createclientes')
                            @endif
                        @endif
                        <div style="display: block">
                            <label for="">Buscar por cuit</label><input class="shadow-md m-1" style="font-size: 18px; background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px;" wire:model="search" type="search" placeholder="Ingresa cuit">
                        </div>        
                        <div class="w-full">{{ $clientes->links() }}</div>
                    </div>
                    <table class="table-fixed table-striped w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2 hidden md:table-cell md:visible">Dirección</th>
                                <th class="px-4 py-2 hidden md:table-cell md:visible">Cuil</th>
                                <th class="px-4 py-2 hidden md:table-cell md:visible">Teléfono</th>
                                <th class="px-4 py-2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($clientes)
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        <td class="border px-4 py-2 text-left">{{ $cliente->name }}</td>
                                        <td class="border px-4 py-2 text-left hidden md:table-cell md:visible">{{ $cliente->direccion }}</td>
                                        <td class="border px-4 py-2 text-left hidden md:table-cell md:visible">{{ $cliente->cuil }}</td>
                                        <td class="border px-4 py-2 text-left hidden md:table-cell md:visible">{{ $cliente->telefono }}</td>
                                        <td class="border px-4 py-2">
                                            <div class="flex justify-center">
                                                @if(session('Clientes.Editar'))
                                                    <div class="sm:flex justify-center">
                                                        <!-- Editar  -->
                                                        <x-editar id="{{ $cliente->id }}"></x-editar>
                                                    </div>
                                                @endif
                                                @if(session('Clientes.Eliminar'))
                                                    <div class="sm:flex justify-center">
                                                        <!-- Eliminar -->
                                                        <x-eliminar id="{{ $cliente->id }}"></x-eliminar>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>       
                </div>
            </div>
        </div>
    </div>
</div>           
                    
{{-- 
@foreach ($datos as $cliente)
<div class="p-2 shadow-lg" style="background:linear-gradient(90deg, lightblue 20%, white 50%); width:93%; height:100px; display: flex; margin: 1.25rem; border-radius: 10px; height: 100%;">
    <div style="width:90%;">
        <div style="width:100%; display: flex">
            <p class="shadow-md m-1" style="font-size: 18px; background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px;">{{ $cliente->name }}</p>
            <p class="shadow-md m-1" style="background-color: rgb(226, 230, 230); border-radius: 10px; padding: 3px;">{{ $cliente->direccion }}</p>
        </div>
        <div style="width:100%; display: flex">
            <p class="shadow-md m-1" style="background-color: rgb(226, 230, 230);border-radius: 10px; padding: 3px;">{{ $cliente->telefono }}</p>
            <p class="shadow-md m-1" style="background-color: rgb(226, 230, 230);border-radius: 10px; padding: 3px;">{{ $cliente->email }}</p>
        </div>
    </div>
    <div style="width:10%;">
        <div class="block justify-center" style="width: 20%; margin: auto; justify-content: space-around;align-items: center;">
            <!-- Editar  -->
            <x-editar id="{{ $cliente->id }}"></x-editar>
            <!-- Eliminar -->
            <x-eliminar id="{{ $cliente->id }}"></x-eliminar>
        </div>
    </div>
</div>
@endforeach --}}
{{-- 
                    
</div> --}}
