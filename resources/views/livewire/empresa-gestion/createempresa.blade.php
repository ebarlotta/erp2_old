<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center mt-24 pt-4 px-4 pb-20 text-center sm:block sm:p-0" style="background-color: beige; ">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle "></span>
        <div class="inline-block align-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-1 sm:align-top sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-wrap">
                        <div class="mb-4 mr-3">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="name">
                            @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Dirección</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="direccion">
                            @error('direccion') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4 mr-3">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Cuit</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="cuit">
                            @error('cuit') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">IB</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="ib">
                            @error('ib') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4 mr-3">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Establecimiento</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="establecimiento">
                            @error('establecimiento') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="telefono">
                            @error('telefono') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4 mr-3">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">actividad</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="actividad">
                            @error('actividad') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">actividad1</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="actividad1">
                            @error('actividad1') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">Imágen</label>
                            <input type="file" class="shadow appearance-none border rounded w-full py-2 pl-2 -mr-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" wire:model="imagen">
                            @error('imagen') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
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