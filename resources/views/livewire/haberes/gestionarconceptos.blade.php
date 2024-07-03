<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center mt-24 pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        style="background-color: beige; ">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle "></span>
        <div class="inline-block align-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-1 sm:align-top sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline" style="max-width: 70%;">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <table class="table table-responsive table-hover" border="1">
                        <tbody bordercolor="#FFFFFF" style="font-family : Verdana; font-size : 12px; font-weight : 300;" bgcolor="#EFF3F7">
                            <tr>Conceptos</tr>
                            <tr>
                                <td style="display: flex">
                                    <div id="DivConceptos">
                                        Items<br>
                                        <select class="form-control" wire:model="cmbitem" wire:change="CargarItemAModificar">
                                            <option value="" selected>-</option>
                                            @foreach($items as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    
                                        <select class="pt-2 mt-2" wire:model="chkTodos" wire:change="setTodos()">
                                            <option value="Activos">Activos</option>
                                            <option value="Todos" selected>Todos</option>
                                        </select>

                                        <!-- <input type="radio" name="Todos" class="btn" wire:model="chkTodos"   wire:click="setTodos('Todos')"  value="{{$chkTodos}}">  Todos
                                        <input type="radio" name="Todos" class="btn" wire:model="chkActivos" wire:click="setTodos('Activos')"value="{{!$chkTodos}}">Sólo Activos -->
                                    </div>

                                    <!-- <input type="radio" checked="true" name="T" id="T" value="Todos"
                                        onclick="xajax.call('RenovarConcepto', {method: 'get', parameters:[T.value]}); return false;">Todos
                                    <input type="radio" name="T" id="T" value="Activos"
                                        onclick="xajax.call('RenovarConcepto', {method: 'get', parameters:[T.value]}); return false;">Sólo
                                    Activos -->
                                    </div>
                                    <div style="margin-left: 10px;">
                                        Descricpion <br><input class="form-control" type="text" wire:model="name">
                                    </div>
                                    <div style="margin-left: 10px;">
                                        Unidades <br><input class="form-control" type="text" wire:model="unidad" disabled>
                                {{-- <td rowspan="8" align="center"><br>
                                    <table class="table table-responsive table-hover" style="font-size:8px;"
                                        frame="border" rules="none" border="1px">
                                        <caption>Referencias</caption>
                                        <tbody>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">AAOS</td>
                                                <td>Aporte Adicional Obra Social</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">AASS</td>
                                                <td>Aporte Adicional Seg. Social</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">ANR</td>
                                                <td>Asig. No Remunerat</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">BC</td>
                                                <td>Básico Categoría al inicio</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">BC1</td>
                                                <td>Básico Categoría Actual</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">B</td>
                                                <td>Básico Categoría actual</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">ANT</td>
                                                <td>Antiguedad</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">C</td>
                                                <td>Cantidad</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">MF</td>
                                                <td>Monto Fijo</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">U</td>
                                                <td>Unidades</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">D</td>
                                                <td>Total Descuentos</td>
                                            </tr>
                                            <tr>
                                                <td style="border-width: 0.5px;border: solid; border-color: #585858; text-align: center;"
                                                    bgcolor="#E0F2F7">RA</td>
                                                <td>Remuneración asignada</td>
                                            </tr>
                                        </tbody>
                                    </table><br>
                                    <input type="hidden" name="IdConcepto" id="IdConcepto" value="">
                                    <input type="button" class="btn-success btn" name="Agregar" id="Agregar"
                                        value="Agregar Concepto" title="Agrega un concepto nuevo"
                                        onclick="xajax.call('AgregarConcepto', {method: 'get', parameters:[Descripcion.value, Unidades.value, Haberes.checked, Rem.checked, NoRem.checked, Descuento.checked, MFijo.value, Calculo.value, MontoMaximo.value, Orden.value, Activo.checked]}); xajax.call('RecargarConcepto', {method: 'get', parameters:[]}); return false;"><br><br>
                                    <input type="button" class="btn btn-warning" name="Modificar" id="Modificar"
                                        value="Modificar Concepto"
                                        onclick="xajax.call('ModificarConcepto', {method: 'get', parameters:[IdConcepto.value, Descripcion.value, Unidades.value, Haberes.checked, Rem.checked, NoRem.checked, Descuento.checked, MFijo.value, Calculo.value, MontoMaximo.value, Orden.value, Activo.checked]}); xajax.call('RecargarConcepto', {method: 'get', parameters:[]}); return false;"
                                        title="Modifica los datos de un concepto ya agregado"><br><br>
                                    <input type="button" class="btn-primary btn" name="Actualizar" id="Actualizar"
                                        value="Actualizar Concepto"
                                        onclick="xajax.call('ActualizarConcepto', {method: 'get', parameters:[IdConcepto.value, Descripcion.value, Unidades.value, Haberes.checked, Rem.checked, NoRem.checked, Descuento.checked, MFijo.value, Calculo.value, MontoMaximo.value, Orden.value, Activo.checked]}); xajax.call('RecargarConcepto', {method: 'get', parameters:[]}); return false;"
                                        title="Genera un concepto nuevo y mantiene el anterior"><br><br>
                                    <input type="button" class="btn-info btn" value="Volver"
                                        onclick="javascript: window.location.href='../menu.php';"
                                        title="Volver a la ventana anterior">
                                </td> --}}
                                </div>
                            </tr>
                            <tr>
                                <td style="display: flex; margin-top:10px">
                                    <input style="margin-left: 15px;" type="checkbox" wire:model="haberes" > Haberes
                                    <input style="margin-left: 15px;" type="checkbox" wire:model="remunerativo"  > Remunerativo
                                    <input style="margin-left: 15px;" type="checkbox" wire:model="noremunerativo" > No Remunerativo
                                    <input style="margin-left: 15px;" type="checkbox" wire:model="descuento"  > Descuento
                                    <input style="margin-left: 15px;" type="checkbox" wire:model="activo" > Activo
                                </td>
                            </tr>
                            <tr>
                                <td style="display: flex; flex-wrap; ">
                                    <div class="form-control" style="margin: 8px"><p> Monto Fijo</p><input type="text" wire:model="montofijo"></div>
                                    <div class="form-control" style="margin: 8px" data-toggle="tooltip" data-placement="right" title="<p>RA: Remuneración Asignada</p> \n /n <br /> <p></p>BC: Básico Categoría</p>">Forma de Cálculo<input type="text" wire:model="calculo"></div>
                                    <div class="form-control" style="margin: 8px">Monto Máximo:<input type="text" wire:model="montomaximo"></div>
                                    <div class="form-control" style="margin: 8px">Orden <input type="text" wire:model="orden"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: flex; justify-content: center;">
                    @if (session()->has('messageModalOk'))
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3 w-2/3 text-center" role="alert">
                        <div class="flex">
                            <div> <p class="text-xm bg-lightgreen">{{ session('messageModalOk') }}</p> </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="NuevoConcepto" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-300 text-base leading-6 font-bold text-white-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Nuevo
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="ModificarValoresDeConcepto" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-300 text-base leading-6 font-bold text-white-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Modificar
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="ActualizaConcepto" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-300 text-base leading-6 font-bold text-white-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">                            
                            Actualizar Concepto
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="EliminarConcepto" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-300 text-base leading-6 font-bold text-white-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">                            
                            Eliminar
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="GestionarConceptoHide()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-yellow-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-yellow-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cerrar
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
