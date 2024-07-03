@extends('layouts.app2')
<div>
    <div class="">
        @if (session()->has('message'))
            <div class="rounded-md bg-red-300 px-6 mx-2 py-1 mt-3" role="alert">
                    {{ session('message') }}</p>
            </div>
        @endif
        <ul class="mb-0 list-none pb-4 d-flex flex-row">
            <li class="-mb-px mr-2 last:mr-0 flex-auto text-center" style="text-decoration: none;">
                @if($tabActivo==1)
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(1)">
                @else 
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(1)">
                @endif
                    <i class="fas fa-space-shuttle text-base mr-1"></i> Generar Certificado
                </a>
            </li>
            <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                @if($tabActivo==2)
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(2)">
                @else 
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(2)">
                @endif
                    <i class="fas fa-cog text-base mr-1"></i> Autorizar Certificado
                </a>
            </li>
            <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                @if($tabActivo==3)
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(3)">
                @else 
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(3)">
                @endif
                    <i class="fas fa-briefcase text-base mr-1"></i> Datos de Clientes
                </a>
            </li>
            <!-- <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                @if($tabActivo==4)
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(4)">
                @else 
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(4)">
                @endif
                    <i class="fas fa-cog text-base mr-1"></i> Cuentas Corrientes
                </a>
            </li>
            <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                @if($tabActivo==5)
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(5)">
                @else 
                    <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(5)">
                @endif
                    <i class="fas fa-briefcase text-base mr-1"></i> Libros de Iva
                </a>
            </li> -->
        </ul>
        @if($demora==true)
            <div class="inset-0 fixed">
                <div class="absolute flex justify-center w-full mt-10 p-18">
                    <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                        <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            Espere unos segundos mientras se procesa la información ingresada...
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div>
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                <div class="tab-content tab-space">
                    <div class="{{ $tabActivo != 1 ? 'hidden' : '' }}">
                        <input type="text" name="" id="" wire:click="Facturar()" value="Facturar">
                        <input type="text" class="bnt btn-info" wire:click="Emitidos()" value="Emitido">
                        <span wire:loading>
                            <div class="inset-0 fixed">
                                <div class="absolute flex justify-center w-full mt-6 p-18">
                                    <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                                        <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            Espere unos segundos mientras se procesa la información ingresada...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <div style="background-color: rgb(137, 168, 168); font-size: 14px;" class="d-flex p-3">
                            <div class="col-3 grid text-left">
                                <label style="font-size: 80%;">CUIT de la persona (física/jurídica) </label>
                                <input class="w-full text-center rounded-md h-8" type="text" wire:model="txttax_id" data-toggle="tooltip" data-placement="top" title="CUIT de la persona (física/jurídica) al cual le queremos generar el certificado.">
                                @error('txttax_id') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-3 grid text-left">
                                <label style="font-size: 80%;">CUIT Usuario para ingresar a AFIP (Representante) </label>
                                <input class="w-full text-left rounded-md h-8 pl-2" type="text" wire:model="txtusername" data-toggle="tooltip" data-placement="top" title="Usuario para ingresar a AFIP (Representante). CUIT sin guiones">
                                @error('txtusername') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-3 grid text-left">
                                <label style="font-size: 80%;">Contraseña </label>
                                <input class="w-full text-center rounded-md h-8" type="password" wire:model="txtpassword" data-toggle="tooltip" data-placement="top" title="Contraseña para ingresar a AFIP.">
                                @error('txtpassword') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-3 grid text-left">
                                <label style="font-size: 80%;">Alias </label>
                                <input class="w-full text-right rounded-md h-8 pl-2" type="text" wire:model="txtalias" data-toggle="tooltip" data-placement="top" title="Alias para el certificado (Nombre para reconocerlo en AFIP)">
                                @error('txtalias') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div style="background-color: rgb(137, 168, 168); font-size: 14px;" class="d-flex p-3">
                            <div class="col-6 grid text-left">
                                <label for="">Certificado</label>
                                <textarea rows="10" class="rounded-md w-full"  >
                                    {{-- // Mostramos el certificado por pantalla
                                    var_dump($res->cert); --}}
                                    {!! $certificado_crt !!}
                                </textarea>
                            </div>
                            <div class="col-6 grid text-left">
                                <label for="">Key</label>
                                <textarea rows="10" class="rounded-md w-full">
                                    {{-- // Mostramos la key por pantalla
                                    var_dump($res->key); --}}
                                    {{ $certificado_key }}
                                </textarea>
                            </div>
                        </div>
                        <div class="d-flex" style="background-color: rgb(137, 168, 168); font-size: 14px;" >
                            <div class="p-3 col-3">
                                <label style="font-size: 80%; margin-right: 5px;">Estado del <br>Certificado</label>
                                <input style="width: 33px; padding-top: 14px; background-color: {{ $estado_color }}"  class="rounded-xl h-8"  disabled>
                                <label style="padding-bottom: 9px;vertical-align: middle;padding-left: 6px;">{{ $estado }}</label>
                            </div>
                            <div class="p-3 col-3">
                                <label style="font-size: large; margin-right: 5px;"><b>Modo de Trabajo</b></label>
                                <label style="padding: 8px; border-radius: 4px; margin: 3px; font-size: large; {{  $certificado_production ?  'background-color: lightcoral;' : 'background-color: lightgreen;'}}">{{ $certificado_production ? 'PRODUCCIÓN' : 'TESTING' }}</label>
                            </div>
                            <div class="col-6 text-center d-flex justify-center">
                                <input class="btn btn-info shadow-md p-3 m-3" type="button" value="Generar Certificado" wire:click="GenerarCertificado()">
                            </div>
                            <div class="col-3"></div>
                        </div>
                            
                            <!-- Botones -->
                            {{-- <div class="flex justify-center">
                                <div class="block">
                                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="store">Agregar</button>
                                    <button class="rounded-md bg-yellow-300 px-6 py-1 mx-2 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="openModalModify">Modificar</button>
                                    <button class="rounded-md bg-red-300 px-6 py-1 mx-2 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="openModalDelete">Eliminar</button>
                                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="openModalAgregarDetalle">Agregar Productos</button>
                                
                                    <div class=" right-0">
                                        @if (session()->has('message'))
                                            <div class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" role="alert">
                                                    {{ session('message') }}</p>
                                            </div>
                                        @endif
        
                                        @if (session()->has('message2'))
                                            <div class="rounded-md bg-yellow-300 px-6 py-1 mx-2 mt-3" role="alert">
                                                {{ session('message2') }}
                                            </div>
                                        @endif
                                        @if (session()->has('message3'))
                                            <div class="rounded-md bg-red-300 px-6 py-1 mx-2 mt-3" role="alert">
                                                {{ session('message3') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> --}}
                            <!-- Modals -->
                            {{-- @if ($this->ModalDelete)
                                <div class="inset-0 fixed">
                                    <div class="absolute flex justify-center w-full mt-10 p-18">
                                        <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                                            <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                Los datos van a ser eliminados, seguro que quiere continuar con la operación?
                                        </div>
                                            <div class="flex justify-end">
                                                <!-- Botón de Eliminar-->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-red-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="delete()">Eliminar</button>
                                                <!-- Botón de Cerrar -->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-yellow-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-yellow-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="closeModalDelete()">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
        
                            @if ($this->ModalModify)
                                <div class="inset-0 fixed">
                                    <div class="absolute flex justify-center w-full mt-10 p-18">
                                        <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                                            <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                Los datos van a ser modificados, seguro que quiere continuar con la operación?
                                        </div>
                                            <div class="flex justify-end">
                                                <!-- Botón de Eliminar-->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-red-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="edit()">Modificar</button>
                                                <!-- Botón de Cerrar -->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-yellow-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-yellow-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="closeModalModify()">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
        
                            @if ($this->ModalAgregarDetalle)
                                <div class="inset-0 fixed">
                                    <div class="absolute flex justify-center w-full mt-10 p-18">
                                        <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                                            <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <input class="ml-2 w-full text-xs rounded-md h-7 text-center" style="disabled" type="text" placeholder="Sacar elemntos al Stock General">
                                        </div>
                                            @if($venta_id)
                                                <div class="flex flex-wrap mt-3 text-xs justify-left">
                                                    <div class="w-40 mr-1">
                                                        <label for="">Producto</label><br>
                                                        <select class="ml-2 w-full text-xs rounded-md h-7 leading-none" wire:model="gselect_productos">
                                                            <option></option>
                                                            @foreach ($productos as $producto)
                                                                <option value="{{ $producto->id}}">{{ $producto->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('gselect_productos') <span class="text-red-500">{{ $message }}</span>@enderror
                                                    </div>
        
                                                    <div class="w-40 mr-1">
                                                        <label for="">Cantidad</label><br>
                                                        <input class="ml-2 w-full text-xs rounded-md h-7 text-right" type="text" wire:model="gcantidad_prod">
                                                        @error('gcantidad_prod') <span class="text-red-500">{{ $message }}</span>@enderror
                                                    </div>
                                                    <div class="w-40 mr-1">
                                                        <label for="">Precio</label><br>
                                                        <input class="ml-2 w-full text-xs rounded-md h-7 text-right" type="text" wire:model="gprecio_prod">
                                                        @error('gprecio_prod') <span class="text-red-500">{{ $message }}</span>@enderror
                                                    </div>
                                                    <div class="w-36 mt-2">
                                                        <!-- Botón de Agregar-->
                                                        <button class="rounded-md border px-4 py-1 mt-1 bg-green-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-geen-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="agregar_detalle()">Agregar</button>
                                                    </div>
                                                </div>
                                                <div class="flex mt-2 ml-2">
                                                    <table class="w-full text-xs rounded-md" style="border: solid 1px #777; background-color: beige;">
                                                        <tr style="background-color: lightblue;">
                                                            <th colspan="4">Productos Relacionados</th>
                                                        </tr>
                                                        <tr style="background-color: lightblue;">
                                                            <td style="border: solid 1px #777;">Nombre del producto</td>
                                                            <td style="border: solid 1px #777;">Cantidad</td>
                                                            <td style="border: solid 1px #777;">Precio</td>
                                                            <td style="border: solid 1px #777;">Opc.</td>
                                                        </tr>
                                                        
                                                        @if($glistado_prod)
                                                            @foreach ($glistado_prod as $detalle)
                                                                <tr>
                                                                    <td class="text-left pl-3" style="border: solid 1px #777;">{{ $detalle->name }}</td>
                                                                    <td class="text-right pr-3" style="border: solid 1px #777;">{{ number_format($detalle->cantidad, 2,'.','') }}</td>
                                                                    <td class="text-right pr-3" style="border: solid 1px #777;">{{ number_format($detalle->precio, 2,'.','') }}</td>
                                                                    <td style="border: solid 1px #777;"><button class="rounded-md border px-4 mt-1 bg-red-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="eliminar_detalle({{$detalle->id}})">X</button>
                                                                        </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </table>
                                                </div>
                                            @else
                                                <div class="flex flex-wrap mt-3 text-xs justify-center">
                                                    <label for="" class="bg-red-300 p-2 rounded">Debe al menos seleccionar algún comprobante para poder relacionarlo con los productos en el stock</label>
                                                </div>
                                            @endif
                                            <div class="flex justify-end">
                                                <!-- Botón de Cerrar -->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-yellow-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-yellow-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="closeModalAgregarDetalle()">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
        
                            <!-- Gestionar Comprobantes -->
                            {{-- <div class="flex flex-wrap mt-3 justify-around" style="font-size: 14px;">
              
                            </div>
                            <div>
                            <div class="flex flex-wrap" style="background-color: rgb(199, 233, 233); font-size: 14px;">
                                
                                <div class="border px-2">Asc. C/Saldo<br>
                                    <input class=" mr-2 rounded-sm py-0" type="checkbox" checked wire:model="fgascendente" wire:change="gfiltro()">
                                    <input class=" mr-2 rounded-sm py-0" type="checkbox" wire:model="gfsaldo" wire:change="gfiltro()">
                                </div>
                            </div>
        
                                {!! $filtro !!}
                            </div> --}}
                    </div>
                
                    <div class="{{ $tabActivo != 2 ? 'hidden' : '' }}">
                        <div style="background-color: #E3F6CE" class="block">
                            <!-- Botones -->
                            <div class="flex">
                                <div class="w-80">
                                    @if($this->certificados)
                                    {{-- @if($txtvisible_guardar) --}}
                                            <table class="table table-striped w-80" style="width: 80%;">
                                                <tr style="background-color: #777;">
                                                    <td style="border: 1px solid black;">Certificados</td>
                                                    <td style="border: 1px solid black;">Punto de Venta Habilitado</td>
                                                    <td style="border: 1px solid black;">Opciones</td>
                                                </tr>
                                                @foreach($certificados as $certificado)
                                                <tr>
                                                    <td style="border: 1px solid black;vertical-align: middle;">
                                                        {{ $certificado->alias }}
                                                    </td>
                                                    <td style="border: 1px solid black;vertical-align: middle;">
                                                        {{-- Seleccionar Punto de Venta --}}
                                                        {{-- <select name="" id=""> --}}
                                                            {{ $certificado_PtoVta }}
                                                            {{-- @foreach($puntosdeventas as $puntodeventa)
                                                                <option value=""></option>
                                                            @endforeach --}}
                                                        {{-- </select> --}}
                                                    </td>
                                                    <td style="border: 1px solid black;">
                                                        <input class="btn btn-info shadow-md p-3 m-3" type="button" value="Solicitar Autorización" wire:click="AutorizarCertificado()">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            
                                            {{-- @endif --}}
                                    @endif
                                </div>
                                {{-- <div class="block">
                                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="store">Agregar</button>
                                    <button class="rounded-md bg-yellow-300 px-6 py-1 mx-2 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="openModalModify">Modificar</button>
                                    <button class="rounded-md bg-red-300 px-6 py-1 mx-2 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="openModalDelete">Eliminar</button>
                                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;" wire:click="openModalAgregarDetalle">Agregar Productos</button>
                                
                                    <div class=" right-0">
                                        @if (session()->has('message'))
                                            <div class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" role="alert">
                                                    {{ session('message') }}</p>
                                            </div>
                                        @endif
        
                                        @if (session()->has('message2'))
                                            <div class="rounded-md bg-yellow-300 px-6 py-1 mx-2 mt-3" role="alert">
                                                {{ session('message2') }}
                                            </div>
                                        @endif
                                        @if (session()->has('message3'))
                                            <div class="rounded-md bg-red-300 px-6 py-1 mx-2 mt-3" role="alert">
                                                {{ session('message3') }}
                                            </div>
                                        @endif
                                    </div>
                                </div> --}}
                            </div>
                            <!-- Modals -->
                            {{-- @if ($this->ModalDelete)
                                <div class="inset-0 fixed">
                                    <div class="absolute flex justify-center w-full mt-10 p-18">
                                        <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                                            <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                Los datos van a ser eliminados, seguro que quiere continuar con la operación?
                                        </div>
                                            <div class="flex justify-end">
                                                <!-- Botón de Eliminar-->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-red-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="delete()">Eliminar</button>
                                                <!-- Botón de Cerrar -->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-yellow-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-yellow-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="closeModalDelete()">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
        
                            {{-- @if ($this->ModalModify)
                                <div class="inset-0 fixed">
                                    <div class="absolute flex justify-center w-full mt-10 p-18">
                                        <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                                            <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                Los datos van a ser modificados, seguro que quiere continuar con la operación?
                                        </div>
                                            <div class="flex justify-end">
                                                <!-- Botón de Eliminar-->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-red-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="edit()">Modificar</button>
                                                <!-- Botón de Cerrar -->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-yellow-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-yellow-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="closeModalModify()">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
        
                            {{-- @if ($this->ModalAgregarDetalle)
                                <div class="inset-0 fixed">
                                    <div class="absolute flex justify-center w-full mt-10 p-18">
                                        <div class=" bg-gray-400 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="dialog">
                                            <div class=" bg-gray-400 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <input class="ml-2 w-full text-xs rounded-md h-7 text-center" style="disabled" type="text" placeholder="Sacar elemntos al Stock General">
                                        </div>
                                            @if($venta_id)
                                                <div class="flex flex-wrap mt-3 text-xs justify-left">
                                                    <div class="w-40 mr-1">
                                                        <label for="">Producto</label><br>
                                                        <select class="ml-2 w-full text-xs rounded-md h-7 leading-none" wire:model="gselect_productos">
                                                            <option></option>
                                                            @foreach ($productos as $producto)
                                                                <option value="{{ $producto->id}}">{{ $producto->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('gselect_productos') <span class="text-red-500">{{ $message }}</span>@enderror
                                                    </div>
        
                                                    <div class="w-40 mr-1">
                                                        <label for="">Cantidad</label><br>
                                                        <input class="ml-2 w-full text-xs rounded-md h-7 text-right" type="text" wire:model="gcantidad_prod">
                                                        @error('gcantidad_prod') <span class="text-red-500">{{ $message }}</span>@enderror
                                                    </div>
                                                    <div class="w-40 mr-1">
                                                        <label for="">Precio</label><br>
                                                        <input class="ml-2 w-full text-xs rounded-md h-7 text-right" type="text" wire:model="gprecio_prod">
                                                        @error('gprecio_prod') <span class="text-red-500">{{ $message }}</span>@enderror
                                                    </div>
                                                    <div class="w-36 mt-2">
                                                        <!-- Botón de Agregar-->
                                                        <button class="rounded-md border px-4 py-1 mt-1 bg-green-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-geen-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="agregar_detalle()">Agregar</button>
                                                    </div>
                                                </div>
                                                <div class="flex mt-2 ml-2">
                                                    <table class="w-full text-xs rounded-md" style="border: solid 1px #777; background-color: beige;">
                                                        <tr style="background-color: lightblue;">
                                                            <th colspan="4">Productos Relacionados</th>
                                                        </tr>
                                                        <tr style="background-color: lightblue;">
                                                            <td style="border: solid 1px #777;">Nombre del producto</td>
                                                            <td style="border: solid 1px #777;">Cantidad</td>
                                                            <td style="border: solid 1px #777;">Precio</td>
                                                            <td style="border: solid 1px #777;">Opc.</td>
                                                        </tr>
                                                        
                                                        @if($glistado_prod)
                                                            @foreach ($glistado_prod as $detalle)
                                                                <tr>
                                                                    <td class="text-left pl-3" style="border: solid 1px #777;">{{ $detalle->name }}</td>
                                                                    <td class="text-right pr-3" style="border: solid 1px #777;">{{ number_format($detalle->cantidad, 2,'.','') }}</td>
                                                                    <td class="text-right pr-3" style="border: solid 1px #777;">{{ number_format($detalle->precio, 2,'.','') }}</td>
                                                                    <td style="border: solid 1px #777;"><button class="rounded-md border px-4 mt-1 bg-red-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="eliminar_detalle({{$detalle->id}})">X</button>
                                                                        </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </table>
                                                </div>
                                            @else
                                                <div class="flex flex-wrap mt-3 text-xs justify-center">
                                                    <label for="" class="bg-red-300 p-2 rounded">Debe al menos seleccionar algún comprobante para poder relacionarlo con los productos en el stock</label>
                                                </div>
                                            @endif
                                            <div class="flex justify-end">
                                                <!-- Botón de Cerrar -->
                                                <button class="rounded-md border m-6 px-4 py-2 bg-yellow-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-yellow-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="closeModalAgregarDetalle()">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif         --}}
                        </div>
                    </div>

                    <div class="{{ $tabActivo != 3 ? 'hidden' : '' }}">
                        <input type="button" value="Obtener Datos" wire:click="ObtenerDatosCliente();">
                        {!! $datosCliente !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
