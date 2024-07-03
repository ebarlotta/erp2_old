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
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <table style="margin-bottom: 10px;" border="1">
                        <tbody>
                            <tr>
                                <td>Item a editar</td>
                                <td>Cantidad</td>
                            </tr>
                            <tr>
                                <td>
											  <select class="form-control"  wire:model="cmbitem" style="max-width : 250px;">
												<option value=" "> </option>
                                    @foreach ($items as $item)													 
													<option value="{{ $item->id}}">{{ $item->name}}</option>
												@endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="" class="form-control" wire:model="cantidad">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
						<span class="mt-3 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                  <button wire:click.prevent="GrabarItem({{ $cmbitem }},{{$cantidad}})" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-300 text-base leading-6 font-bold text-white-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
							Guardar
					  </button>
					</span>
                    <x-cerrar></x-cerrar>
                </div>
            </form>
        </div>
    </div>
</div>
