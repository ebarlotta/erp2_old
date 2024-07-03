<div>
    @if($ModalProducto)
        <!-- <div class="flex items-end justify-center mt-24 pt-4 px-4 pb-20 text-center sm:block sm:p-0" style="background-color: beige; "> -->
        <div class="fixed inset-0 transition-opacity" style="z-index: 999;">
            <div class="absolute inset-0 bg-gray-500 opacity-75">

            </div>
        </div>
        <div class="modal-dialog modal-lg" style="position:absolute; margin: 0 auto; z-index: 1000; text-align: center;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1d36b6;color: white">
                    <h4 class="modal-title">Busqueda del producto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="openModalProducto()">
                        <span aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">
                    <div class="table table-responsive">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="example1_length"><label>Mostrar <select name="example1_length" aria-controls="example1" class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> Productos</label></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div id="example1_filter" class="dataTables_filter"><label>Buscador:<input type="search" wire:model="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example1" class="table table-bordered table-striped table-sm dataTable no-footer dtr-inline" aria-describedby="example1_info">
                                        <thead>
                                            <tr>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Selecionar: activate to sort column ascending">
                                                    <center>Selecionar</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Imagen: activate to sort column ascending">
                                                    <center>Imagen</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Nombre: activate to sort column ascending">
                                                    <center>Nombre</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Descripción: activate to sort column ascending">
                                                    <center>Descripción</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Stock: activate to sort column ascending">
                                                    <center>Stock</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Precio venta: activate to sort column ascending">
                                                    <center>Precio venta</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($productos as $prod)
                                            <tr class="odd">
                                                <td> <button class="btn btn-info" id="btn_selecionar{{ $prod->id}}" wire:click="seleccionarproducto({{ $prod->id}})"> Selecionar </button></td>
                                                <td> <img src="{{ asset('images/'. $prod->ruta) }}" alt="Sin imagen" style="height: 50px;width: 50px;"> </td>
                                                <td>{{ $prod->name }}</td>
                                                <td>{{ $prod->descripcion }}</td>
                                                <td class="text-right">{{ number_format($prod->existencia, 2, ',', '.') }}</td>
                                                <td class="text-right">{{ number_format($prod->precio_venta, 2, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Mostrando 1 a 5 de 7 Productos</div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Anterior</a></li>
                                            <li class="paginate_button page-item active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                            <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
                                            <li class="paginate_button page-item next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0" class="page-link">Siguiente</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group"> <input type="text" id="id_producto" hidden=""> <label for="">Producto</label> <input type="text" wire:model="nombreproducto" class="form-control" disabled=""> </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"> <label for="">Descripcion</label> <input type="text" wire:model="descripcion" class="form-control" disabled=""> </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group"> <label for="">Cantidad</label> <input type="number" wire:model="cantidad" class="form-control" min="0" value="1"> </div>
                                @error('cantidad') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3">
                                <div class="form-group"> <label for="">Precio Unitario</label> <input type="text" wire:model="precioventa" class="form-control" disabled=""> </div>
                            </div>
                        </div> <button style="float: right" id="btn_registrar_carrito" class="btn btn-primary" wire:click="registrar()">Registrar</button>
                        <div id="respuesta_carrito"></div>
                    </div>
                </div>
            </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
        <!-- </div> <br><br> -->
    @endif
    @if($ModalCliente)
        <!-- <div class="flex items-end justify-center mt-24 pt-4 px-4 pb-20 text-center sm:block sm:p-0" style="background-color: beige; "> -->
        <div class="fixed inset-0 transition-opacity" style="z-index: 999;">
            <div class="absolute inset-0 bg-gray-500 opacity-75">

            </div>
        </div>
        <div class="modal-dialog modal-lg" style="position:absolute; margin: 0 auto; z-index: 1000; text-align: center;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1d36b6;color: white">
                    <h4 class="modal-title">Busqueda de Clientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="openModalCliente()">
                        <span aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">
                    <div class="table table-responsive">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="example1_length"><label>Mostrar <select name="example1_length" aria-controls="example1" class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> Clientes</label></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div id="example1_filter" class="dataTables_filter"><label>Buscador:<input type="search" wire:model="searchCliente" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example1" class="table table-bordered table-striped table-sm dataTable no-footer dtr-inline" aria-describedby="example1_info">
                                        <thead>
                                            <tr>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Selecionar: activate to sort column ascending">
                                                    <center>Selecionar</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Imagen: activate to sort column ascending">
                                                    <center>Imagen</center>
                                                </th>
                                                <!-- <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Nombre: activate to sort column ascending">
                                                    <center>Nombre</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Descripción: activate to sort column ascending">
                                                    <center>Descripción</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Stock: activate to sort column ascending">
                                                    <center>Stock</center>
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Precio venta: activate to sort column ascending">
                                                    <center>Precio venta</center>
                                                </th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clientes as $cliente)
                                            <tr class="odd">
                                                <td> <button class="btn btn-info" id="btn_selecionar{{ $cliente->id}}" wire:click="seleccionarCliente({{ $cliente->id}})"> Selecionar </button></td>
                                                <td> <img src="{{ asset('images/'. $cliente->ruta) }}" alt="Sin imagen" style="height: 50px;width: 50px;"> </td>
                                                <td>{{ $cliente->name }}</td>
                                                <!-- <td>{{ $cliente->descripcion }}</td> -->
                                                <!-- <td class="text-right">{{ number_format($cliente->existencia, 2, ',', '.') }}</td>
                                                <td class="text-right">{{ number_format($cliente->precio_venta, 2, ',', '.') }}</td> -->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Mostrando 1 a 5 de 7 Productos</div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Anterior</a></li>
                                            <li class="paginate_button page-item active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                            <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
                                            <li class="paginate_button page-item next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0" class="page-link">Siguiente</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
        <!-- </div> <br><br> -->
    @endif

    @if($ModalVenta)
        <div class="fixed inset-0 transition-opacity" style="z-index: 999;">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="modal-dialog modal-lg" style="position:absolute; margin: 0 auto; z-index: 1000; text-align: center;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1d36b6;color: white">
                    <h4 class="modal-title">Cerrar Comprobante</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="openModalVenta();">
                        <span aria-hidden="true">×</span> 
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table table-responsive">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row  bg-green-600">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Mostrando 1 a 5 de 7 Productos</div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    COMPROBANTE ALMACENADO
                                </div>
                                <div class="p-3 mb-3" wire:click="openModalVenta();">
                                    <input type="button" value="Cerrar" name="" id="" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
        <!-- </div> <br><br> -->
    @endif

    @if($ModalError)
        <div class="fixed inset-0 transition-opacity" style="z-index: 999;">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="modal-dialog modal-lg" style="position:absolute; margin: 0 auto; z-index: 1000; text-align: center;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1d36b6;color: white">
                    <h4 class="modal-title">Cerrar Comprobante</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="openModalVenta();">
                        <span aria-hidden="true">×</span> 
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table table-responsive">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row  bg-red-600">
                                <div class="col-sm-12 col-md-7">
                                    OCURRIO UN ERROR: {{ $problema }}
                                </div>
                                <div class="p-3 mb-3" wire:click="openModalError();">
                                    <input type="button" value="Cerrar" name="" id="" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
        <!-- </div> <br><br> -->
    @endif

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Ventas -  <input type="text" style="text-align: center" value="{{ $cliente_id ? $cliente_name : 'Ninguno' }}" disabled=""></h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-shopping-bag"></i> Venta Nro <input type="text" style="text-align: center" value="{{ $idVenta ? $idVenta->id : 'Ninguna' }}" disabled=""> 
                                <b>Carrito </b>
                                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-buscar_producto"> <i class="fa fa-search"></i> Buscar producto </button> -->
                                <button type="button" class="btn btn-primary" wire:click="openModalProducto()"> <i class="fa fa-search"></i> Buscar Producto </button> <!-- modal para visualizar datos de los proveedor -->
                                <button type="button" class="btn btn-primary" wire:click="openModalCliente()"> <i class="fa fa-search"></i> Buscar Cliente </button> <!-- modal para visualizar datos de los proveedor -->
                            </h3>
                            <div class="card-tools"> <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button> </div>
                        </div>

                        <div class="card-body"> 
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #e7e7e7;text-align: center">Nro</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Producto</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Descripcion</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Cantidad</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Precio Unitario</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Precio SubTotal</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($idVenta)
                                        @if($productosseleccionados)
                                        @foreach($productosseleccionados as $prod)
                                        <tr>
                                            <td>
                                                <center>{{ $prod->orden}}</center>
                                            </td>
                                            <td>{{ $prod->name }}</td>
                                            <td>{{ $prod->descripcion }}</td>
                                            <td>
                                                <center><span>{{ $prod->cantidad }} </span></center>
                                            </td>
                                            <td>
                                                <center>{{ $prod->precio }}</center>
                                            </td>
                                            <td>
                                                <center>{{ $prod->precio*$prod->cantidad }}</center>
                                            </td>
                                            <td>
                                                <center>
                                                    <button type="submit" class="btn btn-danger btn-sm" wire:click="eliminarItem({{ $prod->id}},{{ $prod->ventas_id}})">
                                                        <i class="fa fa-trash"></i>
                                                        Borrar
                                                    </button>
                                                    </form>
                                                </center>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        @else
                                        <tr>
                                            <td colspan="7">
                                                Sin productos
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th colspan="5"></th>
                                            <th style="background-color: #fff819">
                                                <center>{{ $suma }}</center>
                                            </th>
                                            <th style="background-color: #e7e7e7;text-align: right">Total</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-shopping-basket"></i> Registrar venta</h3>
                            <div class="card-tools"> <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button> </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group"> 
                                        <label for="">Monto a cancelar</label> 
                                        <input type="text" class="form-control" id="total_a_cancelar" id="suma" style="text-align: center;background-color: #fff819" value="{{ $suma }}" disabled="">
                                    </div>
                                </div>
                                <!-- <div class="row"> -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Total pagado</label> <input type="text" id="montopagado" class="form-control" wire:model="montopagado" wire:change="calcular();">
                                        @error('montopagado') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Cambio</label>
                                        <input type="text" class="form-control" id="cambio" disabled=""  wire:model="cambio">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"> 
                                        <br>
                                        <button id="btn_guardar_venta" class="btn btn-primary btn-block" style="margin-top: 0.5rem;" wire:click="CerrarVenta();">Guardar venta</button>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                            <!-- <hr> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>  
        function myFunction() {  
        var x,y,suma,text;  
        x = document.getElementById("suma").value;  
        y = document.getElementById("montopagado").value;  
        if (isNaN(x) || isNaN(y)) {  
            text = "Es necesarios introducir dos números válidos";  
        } else {  
            //si no ponemos parseFloat concatenaría x con y  
            suma=parseFloat(x)+parseFloat(y);  
            text= suma;  
        }  
        document.getElementById("cambio").innerHTML = text;  
        }  
    </script>
</div>