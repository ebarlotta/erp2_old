<div>
    <div style="background-color: rgb(160, 178, 212); padding-top:20px;padding-bottom:20px;">    
        <div class="d-flex justify-center">
            <div class="bg-green-300 rounded col-3 text-center">I</div>
            <div class="col-1"></div>
            <div class="bg-red-300 rounded col-3 text-center">E</div>
            <div class="col-1"></div>
            <div class="bg-yellow-300 rounded col-3 text-center">P</div>
        </div>
        <div class="justify-center" style="display: block;text-align: center;">
            <input class="num fs-12 col-6 rounded py-2 my-3" type="text" value="Monto" style="text-align: center;" wire:model="monto_simple">
            @error('monto_simple') <span>Faltan Datos</span>@enderror
            @if(session()->has('message')) 
                <div style="position: fixed; top: 153px;">
                    <img src="fuegos-artificiales.gif" alt="">
                </div>
            @endif
            <input class="fs-10 col-6 rounded py-2 my-3" type="date" value="Fecha" style="text-align: center;" wire:model="fecha_simple">
            <div class="d-flex col-11 col-offset-1" style="text-align: center;">
                <select class="bg-blue col-11 rounded h-8 my-3 col-11"  wire:model="iva_simple" style="background-color: blue; color:white;text-align: center;">
                    <option value="">Ivas</option>
                    @foreach ($ivas as $iva)
                        <option value="{{ $iva->id }}">{{ $iva->monto }}</option>
                    @endforeach
                </select>
                <div class="col-1">
                    <label for="">Iva?</label><br>
                    <input type="checkbox" wire:model="partiva_simple" value="PartIva">
                </div>
            </div>

            @if($this->modulo=='Compras')
                <!-- Proveedor -->
                <select class="bg-blue col-11 rounded h-8 my-3"  wire:model="proveedor_simple" style="background-color: blue; color:white;text-align: center;">
                    <option value="">Proveedor</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}">{{ $proveedor->name }}</option>
                    @endforeach
                </select>
            @else
                <!-- Cliente -->
                <select class="bg-blue col-11 rounded h-8 my-3"  wire:model="cliente_simple" style="background-color: blue; color:white;text-align: center;">
                    <option value="">Cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                    @endforeach
                </select>
            @endif
            <!-- Area -->
            <select class="bg-green col-11 rounded h-8 my-3"  wire:model="area_simple" style="background-color: green; color:white;text-align: center;">
                <option value="">Área</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
            <!-- Cuenta  -->
            <select class="bg-yellow col-11 rounded h-8 my-3"  wire:model="cuenta_simple" style="background-color: yellow; color:white;text-align: center;">
                <option value="">Cuenta</option>
                @foreach ($cuentas as $cuenta)
                    <option value="{{ $cuenta->id }}">{{ $cuenta->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="text-align: center;">
            @if($this->modulo=='Compras')
                {{-- GuardarCompraSimple --}}
                <label class="green col-12" style="color: green; font-size: 18px;"><b>Egresos</b></label>
                <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="GuardarCompraSimple()">
                    {{-- <button wire:click="GuardarCompraSimple()">Guardar</button> --}}
                    <input class="white bg-green-500 col-6 rounded" type="button" value="Guardar" style="color: white; font-size: 20px;">
                </a>
            @else
                {{-- GuardarVentaSimple --}}
                <label class="green col-12" style="color: green; font-size: 18px;"><b>Ingresos</b></label>
                <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-green-600" wire:click="GuardarVentaSimple()">
                    {{-- <button wire:click="GuardarVentaSimple()">Guardar</button> --}}
                    <input class="white bg-green-500 col-6 rounded" type="button" value="Guardar" style="color: white; font-size: 20px;">
                </a>
            @endif
        </div>
    </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="js/jquery.mask.js"></script>
    <script src="js/examples.js"></script>
</div>
