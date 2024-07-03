<div>
    <x-titulo>Liquidaciones de Haberes</x-titulo>
    <x-slot name="header">
        <div class="flex">
            <!-- //Comienza en submenu de encabezado -->

            <!-- Navigation Links -->
            @livewire('submenu')
        </div>
    </x-slot>

    <div class="content-center flex">

        <div class="bg-white p-2 text-center rounded-lg shadow-lg w-full">
            @if ($ModalAgregar)
                @include('livewire.haberes.altaconcepto')
            @endif
            @if ($ModificarEscalaShow)
                @include('livewire.haberes.modificarescala')
            @endif
            @if ($ModificarConceptoShow)
                @include('livewire.haberes.modificarconcepto')
            @endif
            @if ($EliminarConceptoReciboShow)
                @include('livewire.haberes.eliminarconceptorecibo')
            @endif
            {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                @if (session()->has('messageOk'))
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                        role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-xm bg-lightgreen">{{ session('messageOk') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Mensaje mostrado cuando se migran los conceptos de un recibo nuevo -->
                @if (session()->has('messageOk1')) 
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                        role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-xm bg-lightgreen">{{ session('messageOk1') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if (session()->has('messageError'))
                    <div class="bg-warning border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                        role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-xm bg-lightgreen">{{ session('messageError') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <font size="1">
                    <table class="table table-responsive table-hover" border="1">
                        <tbody bordercolor="#FFFFFF" style="font-family : Verdana; font-size : 15px; font-weight : 300;"
                            bgcolor="#AFF3F7">
                            <tr align="center">
                                <td valign="top" style="min-width: 80%">
                                    
                                    <table style="font-size:10px;" class="table table-responsive table-hover" border="1">
                                        <tbody bordercolor="#FFFFFF" style="font-family : Verdana; font-size : 12px; font-weight : 300;" bgcolor="#EFF3F7">
                                            <tr style="vertical-align: middle;">
                                                <td align="center">Año</td>
                                                <td align="center">Mes de Liquidación</td>
                                                <td align="center">Empleados</td>
                                            </tr>
                                            <tr style="vertical-align: middle;">
                                                <td style="vertical-align: top;">
                                                    <select class="form-control" wire:model="anio">
                                                        <option value="2024" selected>2024</option>
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
                                                    <select class="form-control" wire:model="mes" wire:click="CargarEmpleadosActivosEnEsePeriodo();">
                                                        <option value="00">-</option>
                                                        <option value="01">enero</option>
                                                        <option value="02">febrero</option>
                                                        <option value="03">marzo</option>
                                                        <option value="04">abril</option>
                                                        <option value="05">mayo</option>
                                                        <option value="06">junio</option>
                                                        <option value="07">julio</option>
                                                        <option value="08">agosto</option>
                                                        <option value="09">setiembre</option>
                                                        <option value="10">octubre</option>
                                                        <option value="11">noviembre</option>
                                                        <option value="12">diciembre</option>
                                                        <option value="13">1erSAC</option>
                                                        <option value="14">2doSAC</option>
                                                        <option value="15">Vacaciones</option>
                                                    </select>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    @if ($EmpleadosActivos)
                                                        <select class="form-control" wire:model="IdEmpleado">
                                                            <option value="00" selected>-</option>
                                                            @foreach ($EmpleadosActivos as $empleado)
                                                                <option  style="text-decoration:line-through; color: rgb(246, 250, 0); background-color: rgb(5, 26, 1);" value="{{ $empleado['id'] }}" wire:click="cargaIdEmpleado({{ $empleado['id'] }});">{{ ucwords(strtolower($empleado['name'])) }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div id="DivRecibo">
                                        <div style="background-color: rgb(156 163 175 / var(--tw-bg-opacity));">
                                            {{ session('message') }}
                                            @if ($IdRecibo)
                                                <table class="table table-responsive table-hover" style="font-size:12px;" border="1">
                                                    <tbody style="height: 100px; overflow-y: auto;">
                                                        <tr>
                                                            <td colspan="2" style="border-bottom-width: 2px;border-color: black;">
                                                                <strong>Nombre de la Empresa:{{ $NombreEmpresa }}</strong>
                                                            </td>
                                                            <td  colspan="2" align="center" style="border-bottom-width: 2px;border-color: black;">
                                                                <strong>CUIT DE LA EMPRESA: {{ $Cuit }}</strong>
                                                            </td>
                                                            <td colspan="2" align="right" style="border-bottom-width: 2px;border-color: black;">
                                                                Dirección: {{ $DireccionEmpresa }}
                                                            </td>
                                                        </tr>
                                                        <tr bgcolor="lightGray">
                                                            <td align="center"><strong>APELLIDO Y NOMBRES</strong></td>
                                                            <td align="center"><strong>CUIL DEL EMPLEADO</strong></td>
                                                            <td align="center"><strong>CONVENIO</strong></td>
                                                            <td align="center"><strong>SECCION</strong></td>
                                                            <td colspan="2" align="center"><strong>FECHA INGRESO/ANT</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center">{{ ucwords($NombreEmpleado) }}</td>
                                                            <td align="center">{{ $Cuil }}</td>
                                                            <td align="center">{{ $CCT }} </td>
                                                            <td align="center">{{ $Seccion }}</td>
                                                            <td colspan="2" align="center">{{ substr($FechaIngreso, 0, 10) }} - {{ $ano_diferencia.'a'.$mes_diferencia.'m' }}</td>
                                                        </tr>
                                                        <tr bgcolor="lightGray">
                                                            <td align="center"><strong>CATEGORIA</strong></td>
                                                            <td align="center"><strong>CALIFICACION PROFESIONAL</strong></td>
                                                            <td align="center"><strong>PERIODO DE PAGO</strong></td>
                                                            <td align="center"><b>LEGAJO Nº </b>
                                                            <td colspan="2" align="center"><strong>REMUNERACION ASIGNADA</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-bottom-width: 2px;border-color: black;">{{ $NombreCategoria }}</td>
                                                            <td align="center" style="border-bottom-width: 2px;border-color: black;">{{ $NombreSubCategoria }}</td>
                                                            <td align="center" style="border-bottom-width: 2px;border-color: black;"><strong>{{ $PerPago }}</strong></td>
                                                            <td align="center" style="border-bottom-width: 2px;border-color: black;"><strong>{{ $Legajo }}</strong></td>
                                                            <td colspan="2" align="center" style="border-bottom-width: 2px;border-color: black;">$ {{ number_format($TotHaberes, 2, ',', '.') }}</td>
                                                        </tr>
                                                        <tr bgcolor="lightGray">
                                                            <td align="left"><strong>CÓDIGO CONCEPTOS</strong></td>
                                                            <td align="center"><strong>UNIDADES</strong></td>
                                                            <td align="right"><strong>REM.SUJETAS A<br>RETENCIONES</strong></td>
                                                            <td align="right"><strong>REMUNERACIONES<br> EXENTAS</strong></td>
                                                            <td align="right"><strong>DESCUENTOS</strong></td>
                                                            <td align="center"><strong>Acciones</strong></td>
                                                        </tr>
                                                        @if ($Conceptos)
                                                            @foreach ($Conceptos as $Concepto)
                                                                <tr wire:click="ModificarConceptoShow({{ $Concepto['id'] }} ,'{{ $Concepto['name']}}' ,{{ $Concepto['cantidad'] }})">
                                                                    <td>{{ substr(str_repeat(0, 4).$Concepto['orden'], - 4); }} {{ $Concepto['name'] }}</td>
                                                                    <td align="center">{{ '   '.$Concepto['cantidad'] }}</td>
                                                                    <td align="right">{{ number_format($Concepto['Rem'], 2, ',', '.') }}</td>
                                                                    <td align="right">{{ number_format($Concepto['NoRem'], 2, ',', '.') }}</td>
                                                                    <td align="right">{{ number_format($Concepto['Descuento'], 2, ',', '.') }}</td>
                                                                    <td colspan="2" align="center">
                                                                        <a href="#" class="rounded-md bg-red-300 px-6 mx-2 py-1 mt-3" wire:click="EliminarConceptoReciboShow({{ $Concepto['id'] }} ,'{{ $Concepto['name']}}' ,{{ $Concepto['cantidad'] }})">Eliminar</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        <tr>
                                                            {{-- onclick="var  xxx='modificarconcepto.php?Detalle=0&amp;IdConcepto=0&amp;Cantidad=0&amp;Recibo=0'; window.open(xxx,'nuevaVentana','width=300, height=400'); " --}}
                                                            <td>-</td>
                                                            <td align="center">-</td>
                                                            <td align="right">-</td>
                                                            <td align="right">-</td>
                                                            <td align="right">-</td>
                                                            <td align="center"><a href="#" class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" wire:click="MostrarOcultarModalAgregar">Agregar</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center"><strong></strong></td>
                                                            <td align="center"><strong></strong></td>
                                                            <td align="right"><strong>{{ number_format($AcumRem, 2, ',', '.') }}</strong></td>
                                                            <td align="right"><strong>{{ number_format($AcumNoRem, 2, ',', '.') }}</strong></td>
                                                            <td align="right"><strong>{{ number_format($AcumDescuento, 2, ',', '.') }}</strong></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom-width: 2px;border-color: black;"></td>
                                                            <td colspan="2" align="center" style="border-bottom-width: 2px;border-color: black;"></td>
                                                            <td colspan="2" align="center" style="border-bottom-width: 2px;border-color: black;"><strong>NETO A COBRAR</strong></td>
                                                            <td bgcolor="lightGray" align="center" style="border-bottom-width: 2px;border-color: black; font-weight: bold;">
                                                                <b>$ {{ number_format($NetoACobrar, 2, ',', '.') }} </b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6" bgcolor="lightGray"><strong>Son pesos: {{ strtoupper($NetoACobrarLetras)}} </strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>LUGAR</strong></td>
                                                            <td align="left">{{ $LugarPago }}</td>
                                                            <td><strong>BANCO</strong></td>
                                                            <td align="left">{{ $Banco }}</td>
                                                            <td rowspan="6" colspan="2">Recibí el importe de esta liquidación de pago de mi remuneración correspondiente al período indicado y duplicado de la misma conforme a la ley vigente.<br><br><br><br>
                                                                <center><strong>FIRMA EMPLEADO/R</strong></center>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>FECHA DE LIQUIDACIÓN</strong></td>
                                                            <td align="left">01-02-2022</td>
                                                            <td><strong>ULTIMA LIQUIDACIÓN</strong></td>
                                                            <td align="left">202112</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>FECHA DEPOSITO</strong></td>
                                                            <td align="left">{{ substr($FechaUltLiq,8,2).'-'.substr($FechaUltLiq,5,2).'-'. substr($FechaUltLiq,0,4) }}</td>
                                                            <td><strong>ART 12 LEY 17250</strong></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @else
                                                <h1>SELECCIONE UN EMPLEADO</h1>
                                            @endif
            </div>
        </div>
        </td>
        <td>
            <!-- //Boton Alta Recibo  -->
            <div class="General">
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;" title="Dibuja el recibo por Pantalla" wire:click="cargaIdEmpleado({{ $IdEmpleado }})">Graficar Recibo</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Genera un nuevo recibo de sueldo para el mes seleccionado"
                        wire:click="AltaRecibo({{ $anio }},'{{ $mes }}')">Alta
                        Recibo</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Genera un nuevo recibo del primer Aguinaldo" wire:click="AltaRecibo({{ $anio }},'{{ 13 }}')">1erSAC</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Genera un nuevo recibo del segundo Aguinaldo" wire:click="AltaRecibo({{ $anio }},'{{ 14 }}')">2doSAC</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Genera un nuevo recibo de Vacaciones" wire:click="AltaRecibo({{ $anio }},'{{ 15 }}')">Vacaciones</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Genera un nuevo conjunto de recibos de sueldo para el mes seleccionado">Alta
                        Grupal</button>
                </div>
                {{-- <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Agrega un nuevo concepto al recibo">Administrar
                        Conceptos</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Genera una vista previa del recibo">Graficar</button>
                </div> --}}
                <div>
                    <a href="{{ URL::to('/pdf/recibos'.'/'.$anio.'/'.$mes.'/'.$empleadoseleccionado) }}" target="_blank">
						<button class="rounded-md bg-green-300 px-8 py-1 ml-4 mt-6" style="color: black;">Imprimir PDF</button>
					</a><br>

                    <!-- <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Genera un archivo PDF del recibo">Imprimir PDF</button> -->
                </div>
                <div>
                    {{-- <a href="#" class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" wire:click="ModificarEscalaShow">Modificar Escala</a> --}}
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;" title="Cambia la escala salarial con la que se liquida el recibo" wire:click="ModificarEscalaShow">Modificar Escala</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;"
                        title="Elimina el recibo seleccionado" wire:click="EliminarRecibo">Eliminar Recibo</button>
                </div>
                <div>
                    <button class="rounded-md bg-green-300 px-6 mx-2 py-1 mt-3" style="box-shadow: 2px 2px 5px #999;" title="Elimina el recibo seleccionado" wire:click="GestionarConceptosShow()">Gestionar Conceptos</button>
                    @if ($GestionarConceptos)
                        @include('livewire.haberes.gestionarconceptos')
                    @endif
                </div>
            </div>
        </td>
        </tr>
        </tbody>
        </table>
        </font>
    </div>
</div>
</div>
</div>
