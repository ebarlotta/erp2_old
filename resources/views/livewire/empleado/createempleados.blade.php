<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center mt-24 pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        style="background-color: beige; ">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle "></span>
        <div class="inline-block align-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-1 sm:align-top sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 shadow-md flex flex-wrap">
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                        <input type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Ingrese Nombre" wire:model="name">
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1"
                            class="block text-gray-700 text-sm font-bold mb-2">Domicilio</label>
                        <input type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Domicilio" wire:model="domicilio">
                        @error('direccion') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1"
                            class="block text-gray-700 text-sm font-bold mb-2">DNI</label>
                        <input type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Ingrese Cuil" wire:model="dni">
                        @error('dni') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1"
                            class="block text-gray-700 text-sm font-bold mb-2">Cuil</label>
                        <input type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Ingrese Cuil" wire:model="cuil">
                        @error('cuil') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Fecha
                            Nacimiento</label>
                        <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Fecha de Nacimiento" wire:model="nacimiento" value="{{ $nacimiento}}">
                        @error('nacimiento') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Fecha
                            de Ingreso</label>
                        <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Ingrese Fecha de Ingreso" wire:model="ingreso">
                        @error('ingreso') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Estado Civil</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model="estadocivil" >
                            <option value="">-</option>
                            <option value="Separado/a">Separado/a</option>
                            <option value="Soltero/a">Soltero/a</option>
                            <option value="Viudo/a">Viudo/a</option>
                            <option value="Otro/a">Otro/a</option>
                        </select>
                        @error('estadocivil') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Tipo Contratación</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model="tipocontratacion">
                            <option value="">-</option>
                            <option value="Por Contrato">Por Contrato</option>
                            <option value="A tiempo parcial">A tiempo parcial</option>
                            <option value="Otro/a">Otro/a</option>
                        </select>
                        @error('tipocontratacion') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Régimen</label>
                        <select wire:model="regimen" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-</option>
                            <option value="Jubilación docente">Jubilación docente</option>
                            <option value="General">General</option>
                            <option value="Jubilación ordinaria">Jubilación ordinaria</option>
                            <option value="Jubilación de trabajador/a minusválido/a">Jubilación de trabajador/a
                                minusválido/a</option>
                        </select>
                        @error('regimen') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1"
                            class="block text-gray-700 text-sm font-bold mb-2">Banco</label>
                        <input type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Ingrese Banco" wire:model="banco">
                        @error('banco') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Número
                            de Cuenta</label>
                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" placeholder="Ingrese Número de cuenta" wire:model="nrocuentabanco">
                        @error('nrocuentabanco') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1"
                            class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                        <input type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Ingrese Teléfono" wire:model="telefono">
                        @error('telefono') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Mensualizado</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="mensualizado" id="" wire:model="mensualizado">
                            <option value="1">Mensualizado</option>
                            <option value="0">No Mensualizado</option>
                        </select>
                        @error('mensualizado') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Jornalizado</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="jornalizado" id="" wire:model="jornalizado">
                            <option value="1">Jornalizado</option>
                            <option value="0">No Jornalizado</option>
                        </select>
                        @error('jornalizado') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Por Hora</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="hora" id="" wire:model="hora">
                            <option value="1">Por Hora</option>
                            <option value="0">No por hora</option>
                        </select>
                        @error('hora') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1"
                            class="block text-gray-700 text-sm font-bold mb-2">Unidad/</label>
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="unidad" id="" wire:model="unidad">
                                <option value="1">Por Unidad</option>
                                <option value="0">No por Unidad</option>
                            </select>
                        @error('unidad') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Activo</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="activo" id="" wire:model="activo">
                            <option value="1">Activo</option>
                            <option value="0">Pasivo</option>
                        </select>
                        @error('activo') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Fecha
                            de Baja</label>
                        <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="exampleFormControlInput1" placeholder="Fecha de Baja" wire:model="baja">
                        @error('baja') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4 w-5/12 mr-3">
                        <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Categoría Profesional</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="categoriaprofesional" id="" wire:model="categoriaprofesional">
                            {{$categoriaprofesional . 'hola'}}
                            @foreach ($categoriasprofesionales as $categoriaprofesionalx)
                                @if ($categoriaprofesionalx->id == $categoriaprofesional)
                                    <option value="{{ $categoriaprofesionalx->id }}" selected>{{ $categoriaprofesionalx->name }}</option>
                                @else
                                    <option value="{{ $categoriaprofesionalx->id }}">{{ $categoriaprofesionalx->name }}</option>
                                @endif    
                            @endforeach
                        </select>

                        
                        @error('categoriaprofesional') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <x-guardar></x-guardar>
                    <x-cerrar></x-cerrar>
                </div>
            </form>
        </div>
    </div>
</div>
