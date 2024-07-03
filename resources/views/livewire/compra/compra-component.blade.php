@extends('layouts.app2')
<div>
	<x-tituloslim>Comprobantes de Compras</x-tituloslim>
	<div class="content-center block">
		<div class="bg-white p-2 text-center rounded-lg shadow-lg w-full">
			
			<!-- Tabs  -->
			<div class="flex flex-wrap" id="tabs-id">
				<div class="w-full">
					<ul class="flex mb-0 list-none flex-wrap pb-4 flex-row">
						<li class="-mb-px mr-2 last:mr-0 flex-auto text-center" style="text-decoration: none;">
							@if($tabActivo==1)
								<a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(1)">
							@else 
								<a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(1)">
							@endif
								<i class="fas fa-space-shuttle text-base mr-1"></i> Gestionar Comprobantes
								<!-- <button class="btn btn-" wire:click="Ejecutar">Ejecutar</button> -->
								</a>
						</li>
						<li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
							@if($tabActivo==2)
								<a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(2)">
							@else 
								<a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(2)">
							@endif
								<i class="fas fa-cog text-base mr-1"></i> Deuda a Proveedores
							</a>
						</li>
						<li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
							@if($tabActivo==3)
								<a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="CambiarTab(3)">
							@else 
								<a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-pink-600 bg-white" wire:click="CambiarTab(3)">
							@endif
								<i class="fas fa-briefcase text-base mr-1"></i> Crédito de Proveedores
							</a>
						</li>
						<li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
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
						</li>
					</ul>
					<div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
						<div class="tab-content tab-space">
							<div class="{{ $tabActivo != 1 ? 'hidden' : '' }}">
								<div style="background-color: #E3F6CE" class="block">
									<!-- Botones -->
									<div class="flex justify-center">
										<div class="flex flex-wrap justify-center fse-1">
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
												{{-- @if (session()->has('message2'))
													<div class="bg-yellow-300 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-2 shadow-lg my-2" role="alert">
																<p class="text-xm bg-lightgreen">{{ session('message2') }}</p>
													</div>
												@endif --}}
												@if (session()->has('message3'))
													<div class="rounded-md bg-red-300 px-6 py-1 mx-2 mt-3" role="alert">
														{{ session('message3') }}
													</div>
												@endif
											</div>
										</div>
									</div>
									<!-- Modals -->
									@if ($this->ModalDelete)
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
														<input class="ml-2 w-full text-xs rounded-md h-7 text-center" style="disabled" type="text" wire:model="gcantidad_prod" placeholder="Agregar elemntos al Stock General">
												</div>
													@if($comprobante_id)
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
																	@foreach ($glistado_prod as $detallep)
																		<tr>
																			<td class="text-left pl-3" style="border: solid 1px #777;">{{ $detallep->name }}</td>
																			<td class="text-right pr-3" style="border: solid 1px #777;">{{ number_format($detallep->cantidad, 2,'.','') }}</td>
																			<td class="text-right pr-3" style="border: solid 1px #777;">{{ number_format($detallep->precio, 2,'.','') }}</td>
																			<td style="border: solid 1px #777;"><button class="rounded-md border px-4 mt-1 bg-red-300 text-base leading-6 font-bold text-gray-900 shadow-sm hover:bg-red-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click="eliminar_detalle({{$detallep->id}})">X</button>
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
									@endif

									<!-- Gestionar Comprobantes -->
									<div class="flex flex-wrap mt-3 justify-around" style="font-size: 14px;">
										<div class="w-34 mr-1 grid text-left">
											<label style="font-size: 80%;">Fecha</label>
											<input class="w-full rounded-md h-8 text-xs" type="date" wire:model="gfecha">
											@error('gfecha') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="w-44 mr-1 grid text-left">
											<label style="font-size: 80%;">Proveedor</label>
											<select class="px-2 w-full rounded-md h-8 leading-none" wire:model="gproveedor">
												<option value=" "> </option>
												@foreach ($proveedores as $proveedor)
													<option value="{{ $proveedor->id }}">
														{{ $proveedor->name }}
													</option>
												@endforeach
											</select>
											@error('gproveedor') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="w-36 mr-1 grid text-left">
											<label style="font-size: 80%;">Comprobante</label>
											<input class="px-2 w-full rounded-md h-8" type="text" wire:model="gcomprobante">
										</div>
										<div class="w-32 mr-1 grid text-left">
											<label style="font-size: 80%;">Participa Iva</label>
											<select class="w-4/5 px-2 rounded-md h-8 leading-none" wire:model="gpartiva">
												<option value=""></option>
												<option value="Si">Si</option>
												<option value="No">No</option>
												<option value="Ganancias">Ganancias</option>
												<option value="IB">IB</option>
												<option value="BsPersonal">BsPersonal</option>
											</select>
											@error('gpartiva') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="w-40 mr-1 grid text-left">
											<label style="font-size: 80%;">Detalle</label>
											<input type="text" class="px-2 w-full rounded-md h-8" wire:model="gdetalle">
										</div>
										<div class="w-24 mr-1 grid text-left">
											<label style="font-size: 80%;">Año</label>
											<select class="w-2/3 px-2 rounded-md h-8 leading-none" wire:model="ganio">
												<option value=""></option>
												<option value="2024">2024</option>
												<option value="2023">2023</option>
												<option value="2022">2022</option>
												<option value="2021">2021</option>
												<option value="2020">2020</option>
												<option value="2019">2019</option>
												<option value="2018">2018</option>
												<option value="2017">2017</option>
												<option value="2016">2016</option>
												<option value="2015">2015</option>
												<option value="2014">2014</option>
												<option value="2013">2013</option>
												<option value="2012">2012</option>
											</select>
											@error('ganio') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="w-24 mr-1 grid text-left">
											<label style="font-size: 80%;">Mes</label>
											<select class="w-full px-2 rounded-md h-8 leading-none" wire:model="gmes">
												<option value=""></option>
												<option value="1">enero
												</option>
												<option value="2">febrero
												</option>
												<option value="3">marzo
												</option>
												<option value="4">abril
												</option>
												<option value="5">mayo
												</option>
												<option value="6">junio
												</option>
												<option value="7">julio
												</option>
												<option value="8">agosto
												</option>
												<option value="9">
													setiembre
												</option>
												<option value="10">octubre
												</option>
												<option value="11">
													noviembre
												</option>
												<option value="12">
													diciembre
												</option>
											</select>
											@error('gmes') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="w-32 mr-1 grid text-left">
											<label style="font-size: 80%;">Areas</label>
											<select class="w-full px-2 rounded-md h-8 leading-none" wire:model="garea">
												<option value=" "> </option>
												@foreach ($areas as $area)
													<option value="{{ $area->id }}">
														{{ $area->name }}
													</option>
												@endforeach
											</select>
											@error('garea') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="w-32 mr-1 grid text-left">
											<label style="font-size: 80%;">Cuentas</label>
											<select class="w-full px-2 rounded-md h-8 leading-none" wire:model="gcuenta" >
												<option value=" "> </option>
												@foreach ($cuentas as $cuenta)
													<option value="{{ $cuenta->id }}">
														{{ $cuenta->name }}
													</option>
												@endforeach
											</select>
											@error('gcuenta') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
									
										<div class="mr-1 w-28 grid text-left">
											<label style="font-size: 80%;">Bruto</label>
											<input class="num w-full text-right rounded-md h-8" type="text" id="Bruto" name="Bruto" wire:model="gbruto" wire:keyup="CalcularIva()">
											@error('gbruto') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="w-28 mr-1 grid text-left">
											<label style="font-size: 80%;">IVA</label>
											<select class="w-full rounded-md h-8 leading-none" wire:model="giva" wire:change="CalcularIva()">
												<option value="1" selected>Iva 0%</option>
												@foreach ($ivas as $iva)
													<option value="{{ $iva->id }}">
														{{ $iva->descripcion }}
													</option>
												@endforeach
											</select>
											@error('giva') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-24 grid text-left">
											<label style="font-size: 80%;">Iva</label>
											<input class="w-full text-right rounded-md h-8 leading-none" disabled type="text" wire:model="giva2">
											@error('giva2') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-28 grid text-left">
											<label style="font-size: 80%;">Exento</label>
											<input class="num w-full text-right rounded-md h-8 leading-none" type="text" wire:model="gexento" wire:keyup="CalcularNeto()">
											@error('gexento') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-24 grid text-left">
											<label style="font-size: 80%;">Imp.Interno</label>
											<input class="num w-full text-right rounded-md h-8" type="text" wire:model="gimpinterno" wire:keyup="CalcularNeto()">
											@error('gimpinterno') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-28 grid text-left">
											<label style="font-size: 80%;">Ret/Perc.Iva</label>
											<input class="num w-full text-right rounded-md h-8" type="text" wire:model="gperciva" wire:keyup="CalcularNeto()">
											@error('gperciva') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-28 grid text-left">
											<label style="font-size: 80%;">Ret/Perc.IB</label>
											<input class="num w-full text-right rounded-md h-8" type="text" wire:model="gperib" wire:keyup="CalcularNeto()">
											@error('gperib') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-28 grid text-left">
											<label style="font-size: 80%;">RetGan</label>
											<input class="num w-full text-right rounded-md h-8" type="text" wire:model="gretgan" wire:keyup="CalcularNeto()">
											@error('gretgan') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-28 grid text-left">
											<label style="font-size: 80%;">Neto</label>
											<input class="w-full text-right rounded-md h-8" type="text" wire:model="gneto">
											@error('gneto') <span class="text-red-500">{{ $message }}</span>@enderror
										</div>
										<div class="mr-1 w-28 grid text-left">
											<label style="font-size: 80%;" wire:click="copiarMontoPagado()">Monto Pagado</label>
											<input class="num w-full text-right rounded-md h-8" type="text" wire:model="gmontopagado">
										</div>
										<div class="mr-1 w-2 grid text-left0">
											<label style="font-size: 80%;">Cantidad</label>
											<input class="num w-full text-right rounded-md h-8" type="text" wire:model="gcantidad">
										</div>
									</div>
									{{-- <div> --}}

										<div class="flex flex-wrap fse-1" style="background-color: rgb(199, 233, 233); font-size: 14px;">
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Mes</label>
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfmes" wire:change="gfiltro()">
													<option value=""></option>
													<option value="1">Enero</option>
													<option value="2">Febrero</option>
													<option value="3">Marzo</option>
													<option value="4">Abril</option>
													<option value="5">Mayo</option>
													<option value="6">Junio</option>
													<option value="7">Julio</option>
													<option value="8">Agosto</option>
													<option value="9">Setiembre</option>
													<option value="10">Octubre</option>
													<option value="11">Noviembre</option>
													<option value="12">Diciembre</option>
												</select></div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Proveedor</label>
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfproveedor" wire:change="gfiltro()">
													<option value=""></option>
													@foreach ($proveedores as $proveedor)
														<option value="{{ $proveedor->id }}">
															{{ $proveedor->name }}</option>
													@endforeach
												</select>
											</div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">ParticipaIva</label>
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfparticipa" wire:change="gfiltro()">
													<option value=""></option>
													<option value="Si">Si</option>
													<option value="No">No</option>
													<option value="Ganancias">Ganancias</option>
													<option value="BsPers">Bs. Pers.</option>
												</select>
											</div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Iva</label>
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfiva" wire:change="gfiltro()">
													<option value=""></option>
													@foreach ($ivas as $iva)
														<option value="{{ $iva->id }}">
															{{ $iva->descripcion }}</option>
													@endforeach
												</select>
											</div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Detalle</label>
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfdetalle" wire:change="gfiltro()">
													<option value="">Todos</option>
														{!! $combodetalle !!}
												</select>
											</div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Area</label>
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfarea" wire:change="gfiltro()">
													<option value=""></option>
													@foreach ($areas as $area)
														<option value="{{ $area->id }}">{{ $area->name }}
														</option>
													@endforeach
												</select>
											</div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Cuenta</label>
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfcuenta" wire:change="gfiltro()">
													<option value=""></option>
													@foreach ($cuentas as $cuenta)
														<option value="{{ $cuenta->id }}">{{ $cuenta->name }}
														</option>
													@endforeach
												</select>
											</div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Año</label>
												<!-- <select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfanio" wire:change="gfiltro()"> -->
												<select class="px-2 rounded-md h-7 py-0 leading-none" wire:model="gfanio" wire:change="gsetanio(gfanio)">
													<option value="2024">2024</option>
													<option value="2023">2023</option>
													<option value="2022">2022</option>
													<option value="2021">2021</option>
													<option value="2020">2020</option>
													<option value="2019">2019</option>
													<option value="2018">2018</option>
													<option value="2017">2017</option>
													<option value="2016">2016</option>
													<option value="2015">2015</option>
													<option value="2014">2014</option>
													<option value="2013">2013</option>
												</select>
											</div>
											<div class="border px-2 grid text-left">
												<label style="font-size: 80%;">Asc. C/Saldo</label>
												<div class="flex text-right">
												<input class=" mr-2 rounded-sm py-0" type="checkbox" checked wire:model="fgascendente" wire:change="gfiltro()">
												<input class=" mr-2 rounded-sm py-0" type="checkbox" wire:model="gfsaldo" wire:change="gfiltro()">
												</div>
											</div>
										</div>
										{!! $filtro !!}
									{{-- </div> --}}
								</div>
							</div>

{{-- Deuda a Proveedores --}}
{{-- =================== --}}
							<div class="{{ $tabActivo != 2 ? 'hidden' : '' }}">
								<div class="flex justify-center">
									<div class="flex">
										<div class="block mb-4 justify-start">
											<label for="">Áreas a incluir</label><br>
											<select class="ml-2 text-xs rounded-md h-7 leading-none mr-5" wire:model="darea">
												<option value="0">Todas</option>
												@foreach ($areas as $area)
													<option value="{{ $area->id }}">{{ $area->name }}</option>
												@endforeach
											</select>
										</div>
										<div class="block mb-4">
											<label for="">Años a incluir </label><br>
											<select class=" text-xs rounded-md h-7 py-0 leading-none" wire:model="danio">
												<option value="0">Todos</option>
												<option value="2024">2024</option>
												<option value="2023">2023</option>
												<option value="2022">2022</option>
												<option value="2021">2021</option>
												<option value="2020">2020</option>
												<option value="2019">2019</option>
												<option value="2018">2018</option>
												<option value="2017">2017</option>
												<option value="2016">2016</option>
												<option value="2015">2015</option>
												<option value="2014">2014</option>
												<option value="2013">2013</option>
											</select>
										</div>
									</div>
								</div>
								<div class="block justify-center">
									<div class="flex justify-center">
										<div class="block mb-4 justify-center">
											Desde <br>
											<input class="text-xs rounded-md h-7 ml-5" type="date" wire:model="ddesde">
										</div>
									
										<div class="block mb-4 justify-center">
											Hasta <br>
											<input class="ml-2 text-xs rounded-md h-7" type="date" wire:model="dhasta"><br>
										</div>
									</div>

									<div class="flex mt-4 justify-center">
										<div class="block mb-4 justify-start">
											<button class="rounded-md bg-green-300 px-8 py-1 mx-2 mt-3" wire:click="CalcularDeudaProveedores(0)">Solicitar Listado</button>
											<a href="{{ URL::to('/pdf/deuda'.'/'.$ddesde.'/'.$dhasta) }}" target="_blank">
												<button class="rounded-md bg-yellow-500 px-8 py-1 mx-2 mt-3" style="color: black;">Generar PDF</button>
											</a>
										</div>
									</div>

									<div class="flex justify-center w-full">
										@if ($MostrarDeudaProveedores)
											{!! $DeudaProveedoresFiltro !!}
										@endif
									</div>
								</div>								
							</div>
{{-- Crédito de Proveedores --}}
{{-- ======================= --}}							
							<div class="{{ $tabActivo != 3 ? 'hidden' : '' }}">
								<div class="block">
									{{-- Areas / Años --}}
									<div class="flex justify-center">
										<div class="flex">
											<div class="block mb-4 justify-start">
												<label for="">Àreas a incluir</label><br>
												<select class="ml-2 text-xs rounded-md h-7 leading-none mr-5" wire:model="carea">
													<option value="0">Todas</option>
													@foreach ($areas as $area)
													<option value="{{ $area->id }}">{{ $area->name }}</option>
													@endforeach
												</select>
											</div>
											<div class="block mb-4">
												<label for="">Años a incluir</label><br>
												<select class=" text-xs rounded-md h-7 py-0 leading-none" wire:model="canio">
													<option value="0">Todos</option>
													<option value="2024">2024</option>
													<option value="2023">2023</option>
													<option value="2022">2022</option>
													<option value="2021">2021</option>
													<option value="2020">2020</option>
													<option value="2019">2019</option>
													<option value="2018">2018</option>
													<option value="2017">2017</option>
													<option value="2016">2016</option>
													<option value="2015">2015</option>
													<option value="2014">2014</option>
													<option value="2013">2013</option>
												</select>
											</div>
										</div>
									</div>
									{{-- Desde / Hasta --}}
									<div class="flex justify-center">
										<div class="flex justify-center">
											<div class="block mb-4 justify-start">
												Desde <br>
												<input class="text-xs rounded-md h-7 ml-5" type="date" wire:model="cdesde"><br>
											</div>
										
											<div class="block mb-4 justify-center">
												Hasta <br>
												<input class="ml-2 text-xs rounded-md h-7" type="date" wire:model="chasta"><br>
											</div>
										</div>
									</div>
									{{-- Solicita / Generar --}}
									<div class="flex justify-center">
										<div class="flex mt-4 justify-center">
											<div class="block mb-4 justify-start">
												<button class="rounded-md bg-green-300 px-8 py-1 mx-2 mt-3" wire:click="CalcularCreditoProveedores()">Solicitar Listado</button>
												<a href="{{ URL::to('/pdf/credito'.'/'.$cdesde.'/'.$chasta) }}" target="_blank">
													<button class="rounded-md bg-yellow-500 px-8 	py-1 mx-2 mt-3" style="color: black;">Generar PDF</button>
												</a>
											</div>
										</div>
									</div>
									{{-- Filtro --}}
									<div class="flex justify-center">
										<div class="flex justify-center w-full">
											@if ($MostrarCreditoProveedores)
												{!! $CreditoProveedoresFiltro !!}
											@endif
										</div>
									</div>
								</div>
							</div>
{{-- Cuentas Corrientes  --}}
{{-- =================== --}}
							<div class="{{ $tabActivo != 4 ? 'hidden' : '' }}">
								<div class="flex flex-auto justify-center">
									<img src="{{ asset('images/under-construction.jpg') }}" alt="" class="w-36">
								</div>
							</div>
{{-- Libros de IVA  --}}
{{-- ============== --}}
							<div class="{{ $tabActivo != 5 ? 'hidden' : '' }}">
								<div class="flex flex-auto justify-center">
									<div>
										<table>
											<tr>
												<td>
													<label for="">Mes</label><br>
													<select class="mr-4 w-full text-xs px-2 rounded-md h-7 leading-none" wire:model="lmes" wire:change="MostrarLibros()">
														<option value=""></option>
														<option value="1">enero
														</option>
														<option value="2">febrero
														</option>
														<option value="3">marzo
														</option>
														<option value="4">abril
														</option>
														<option value="5">mayo
														</option>
														<option value="6">junio
														</option>
														<option value="7">julio
														</option>
														<option value="8">agosto
														</option>
														<option value="9">
															setiembre
														</option>
														<option value="10">octubre
														</option>
														<option value="11">
															noviembre
														</option>
														<option value="12">
															diciembre
														</option>
													</select>
													<label for="">Año</label><br>
													<select class="mr-4 w-full text-xs rounded-md h-7 leading-none" wire:model="lanio" wire:change="MostrarLibros()">
														<option value=""></option>
														<option value="2024">2024</option>
														<option value="2023">2023</option>
														<option value="2022">2022</option>
														<option value="2021">2021</option>
														<option value="2020">2020</option>
														<option value="2019">2019</option>
														<option value="2018">2018</option>
														<option value="2017">2017</option>
														<option value="2016">2016</option>
														<option value="2015">2015</option>
														<option value="2014">2014</option>
														<option value="2013">2013</option>
														<option value="2012">2012</option>
													</select>
												</td>
												<td>
													<a href="{{ URL::to('/pdf/ivacompras'.'/'.$lanio.'/'.$lmes) }}" target="_blank">
														<button class="rounded-md bg-green-300 px-8 py-1 ml-4 mt-6" style="color: black;">Imprimir Libro</button>
													</a><br>
													<button class="rounded-md bg-yellow-300 px-8 py-1 ml-4 mt-6 white" wire:click="openModalCerrarLibro()">Cerrar Libro</button>
												</td>
											</tr>
										</table>
										@if($ModalCerrarLibro)
											@include('livewire.compra.modalcerrarlibrocompras')
										@endif
										<div class="w-full">
											@if ($MostrarLibros)
												{!! $LibroFiltro !!}
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="BotonVolver2 form-group col-md-2">
</div>
<footer class="text-center text-xs bg-gray-400 mt-px3 pb-2">
	Desarrollado por: Ing. Enzo Gabriel Barlotta - Información de Contacto<a href="mailto:ebarlotta@yahoo.com.ar">
		ebarlotta@yahoo.com.ar</a>
	{{-- &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info"
		onclick="javascript: window.location.href='../../sistema/menu.php';">&nbsp;&nbsp;&nbsp;Volver&nbsp;&nbsp;&nbsp;</button> --}}
</footer>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/jquery.mask.js"></script>
<script src="js/examples.js"></script>