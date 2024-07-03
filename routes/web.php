<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/', EmpresaComponent::class)->name('inicio');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('areas',AreaComponent::class)->name('areas');
Route::get('cuentas',CuentaComponent::class)->name('cuentas');
Route::get('proveedores',ProveedorComponent::class)->name('proveedores');
Route::get('clientes',ClienteComponent::class)->name('clientes');
Route::get('empleados',EmpleadoComponent::class)->name('empleados');

Route::get('VentaSimple',CompraSimpleComponent::class)->name('VentaSimple');
// Route::get('VentaSimple/compra',CompraSimpleComponent::class)->name('VentaSimple');
Route::get('compras',CompraComponent::class)->name('compras');
Route::get('ventas',VentaComponent::class)->name('ventas');
Route::get('ventasmostrador',VentaMostradorComponent::class)->name('ventasmostrador');

Route::get('empresas',EmpresaComponent::class)->name('empresas');
//Route::get('empresagestion',EmpresaGestion::class)->name('empresagestion');
Route::get('modulos',ModuloComponent::class)->name('modulos');

Route::get('pdf/deuda/{ddesde}/{dhasta}', [ImprimirPDF::class, 'DeudaPFD'])->name('DeudaPFD');
Route::get('pdf/credito/{cdesde}/{chasta}', [ImprimirPDF::class, 'CreditoPFD'])->name('CreditoPFD');
Route::get('pdf/ivacompras/{anio}/{mes}', [ImprimirPDF::class, 'IvaCompras'])->name('IvaCompras');
Route::get('pdf/ivaventas/{anio}/{mes}', [ImprimirPDF::class, 'IvaVentas'])->name('IvaVentas');

Route::get('pdf/recibos/{anio}/{mes}/{empleadoseleccionado}', [ImprimirPDF::class, 'Recibo'])->name('Recibos');

//Route::get('pdf/deuda/{ddesde}/{dhasta}', [ImprimirPDF::class, 'DeudaPFD'])->name('DeudaPFD');
//Route::get('pdf/credito/{cdesde}/{chasta}', [ImprimirPDF::class, 'CreditoPFD'])->name('CreditoPFD');

Route::get('producto/addtag/{product_id}/{tag_id}', [Productos::class, 'addtag'])->name('producto.addtag');
Route::get('producto/deltag/{product_id}/{tag_id}', [Productos::class, 'deltag'])->name('producto.deltag');
Route::get('producto/tag', [Productos::class, 'tag'])->name('producto.tag');
Route::get('producto/{producto}/tagedit', [Productos::class, 'tagedit'])->name('producto.tagedit');

Route::resource('producto',Productos::class);

Route::get('producto/productobajas', [Productos::class, 'productobajas'])->name('producto.productobajas');

// Route::get('carts/single/{{id}}',[Cart::class,'single'])->name('cart.single');
Route::get('carts',Cart::class)->name('carts');
// Route::get('payments',[Cart::class,'payment_index'])->name('payments');
//Route::get('payments',[Cart::class,'payment_index'])->name('payments');
Route::get('payments',PaymentComponent::class)->name('payments');

//Route::get('deletion/?id=abc123',EmpleadoComponent::class)->name('deletion'); //Eliminación de datos en Facebook

// Route::get('/search/', 'ProveedorComponent@search')->name('search');

Route::post('/home',[HomeController::class,'upload']);

Route::get('roles', RolesComponent::class)->name('roles');
Route::get('gestionmodulos', GestionModuloComponent::class)->name('gestionmodulos');

Route::get('pruebas', [Chart::class, 'index'])->name('pruebas');
