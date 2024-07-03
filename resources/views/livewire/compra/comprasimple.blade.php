<div>
    {{-- @extends('layouts.app2') --}}
    <div style="background-color: rgb(160, 178, 212); padding-top:20px;padding-bottom:20px;">
        {{-- <div class="grid grid-cols-3 gap-4 place-content-arround justify-center">
            <div class="bg-green-300 rounded w-10 text-center">I</div>
            <div class="bg-red-300 rounded w-10 text-center">E</div>
            <div class="bg-yellow-300 rounded w-10 text-center">P</div>
        </div>
        <div class="flex flex-wrap justify-center">
            <input class="fs-12 col-12" type="text" value="Monto">
            <input class="fs-10 col-12" type="date" value="Fecha">
            <!-- Cliente -->
            <select class="bg-blue col-12" name="" id="">
                <option value=""></option>
            </select>
            <!-- Area -->
            <select class="bg-green col-12" name="" id="">
                <option value=""></option>
            </select>
            <!-- Cuenta  -->
            <select class="bg-yellow col-12" name="" id="">
                <option value=""></option>
            </select>
        </div>
        <div>
            <label class="green col-12" for=""></label>
            <input class="white bg-green-300 col-12" type="button" value="Guardar">
        </div> --}}
    
        <div class="d-flex justify-center">
            <div class="bg-green-300 rounded col-3 text-center">I</div>
            <div class="col-1"></div>
            <div class="bg-red-300 rounded col-3 text-center">E</div>
            <div class="col-1"></div>
            <div class="bg-yellow-300 rounded col-3 text-center">P</div>
        </div>
        <div class="justify-center" style="display: block;text-align: center;">
            <input class="fs-12 col-6 rounded py-2 my-3" type="text" value="Monto" style="text-align: center;" wire:model="monto_simple">
            <input class="fs-10 col-6 rounded py-2 my-3" type="date" value="Fecha" style="text-align: center;">
    
            @error('monto_simple') <span>Faltan Datos</span>@enderror
    
                <input class="fs-10 col-6 rounded py-2 my-3" type="date" value="Fecha" style="text-align: center;">
                
            <!-- Cliente -->
            <select class="bg-blue col-11 rounded h-8 my-3" wire:model="cliente_simple" style="background-color: blue; color:white;text-align: center;">
                <option value="">Cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                @endforeach
            </select>
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
            <label class="green col-12" style="color: green; font-size: 18px;"><b>Ingresos</b></label>
            {{-- <a wire:click="GuardarCompraSimple()"> --}}
            <a class="text-xs font-bold uppercase px-5 py-1 shadow-lg rounded block leading-normal text-white bg-pink-600" wire:click="GuardarCompraSimple()">
    
                <button wire:click="GuardarCompraSimple()">Guardar</button>
                <input class="white bg-green-500 col-6 rounded" type="button" value="Guardar" style="color: white; font-size: 20px;">
            </a>
        </div>
    </div>
    </div>