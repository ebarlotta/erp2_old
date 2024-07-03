<?php

namespace App\Http\Livewire\Venta;

use Livewire\Component;
use App\Models\Area;
use App\Models\Cliente;
use App\Models\Cuenta;
use App\Models\EmpresaUsuario;
use App\Models\Iva;
use App\Models\Producto;
use App\Models\Ventas_Productos;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use Facade\FlareClient\Http\Client;

class VentaMostradorComponent extends Component
{

    public $empresa_id;
    //Variables de Ventas a Mostrador
    public $productos;  // Listado de productos
    public $clientes;  // Listado de clientes
    public $productosseleccionados;  // Listado de productos que ya han sido cargados al carrito
    public $search; // nombre del producto a buscar
    public $searchCliente; // nombre del producto a buscar
    public $productoid; //Id del producto seleccionado
    public $nombreproducto; // Nombre del Producto seleccionado
    public $descripcion; //Descripción del producto
    public $cantidad = 1;   // Cantidad seleccionada por el usuario
    public $precioventa;   // Precio de venta del producto
    public $ModalProducto = false; //Modal que muestra la lista de productos para seleccionar
    public $ModalCliente = false; //Modal que muestra la lista de clientes para seleccionar
    public $ModalVenta = false;//Modal que muestra Venta exitosa o si falta agragar algo para cerrar la venta
    public $ModalError = false;//Modal que muestra Venta exitosa o si falta agragar algo para cerrar la venta
    public $idVenta; // Id de la venta actual
    public $VentaId = 0;
    public $html;
    public $orden = 1;
    public $suma = 0;
    public $montopagado = 0;
    public $cambio = 0;
    public $cliente_id, $iva_cliente, $cliente_name;
    public $iva_id, $monto_iva=0;
    public $problema;

    public function render()
    {
        if (!is_null(session('empresa_id'))) {
            $this->empresa_id = session('empresa_id');
        } else {
            $userid = auth()->user()->id;
            $empresas = EmpresaUsuario::where('user_id', $userid)->get();
            return view('livewire.empresa.empresa-component')->with('empresas', $empresas);
        }

        $this->productos = Producto::where('empresa_id', $this->empresa_id)->where('name', 'like', '%' . $this->search . '%')->orderBy('name', 'asc')->get();
        $this->clientes = Cliente::where('empresa_id', $this->empresa_id)->where('name', 'like', '%' . $this->searchCliente . '%')->orderBy('name', 'asc')->get();
        return view('livewire.ventasmostrador.ventamostrador')->extends('layouts.adminlte');
    }

    public function seleccionarproducto($id)
    {
        //Al hacer clic sobre el botón seleccionar, se cargan los datos del producto para poder completar con la cantidad
        $temp = Producto::find($id);
        $this->productoid = $id;
        $this->nombreproducto = $temp->name;
        $this->descripcion = $temp->descripcion;
        $this->precioventa = number_format($temp->precio_venta, 2, '.', ',');
    }

    public function seleccionarCliente($id)
    {
        //Al hacer clic sobre el botón seleccionar, se cargan los datos del Cliente
        $temp = Cliente::find($id);
        $this->cliente_id = $id;
        $this->cliente_name  = $temp->name;
        $this->openModalCliente();
        
        
        // $this->nombreproducto = $temp->name;
        // $this->descripcion = $temp->descripcion;
        // $this->precioventa = number_format($temp->precio_venta, 2, '.', ',');
    }

    public function registrar()
    {
        // Al hacer click sobre registrar se carga el producto al carrito

        if ($this->VentaId == 0) {
            $this->idVenta = Venta::updateOrCreate(['id' => $this->idVenta], [
                'fecha'             => now(),
                'comprobante'       => 0,
                'detalle'           => '',
                'BrutoComp'         => 0,
                'ParticIva'         => 'Temporal',
                'MontoIva'          => 0,
                'ExentoComp'        => 0,
                'ImpInternoComp'    => 0,
                'PercepcionIvaComp' => 0,
                'RetencionIB'       => 0,
                'RetencionGan'      => 0,
                'NetoComp'          => 0,
                'MontoPagadoComp'   => 0,
                'CantidadLitroComp' => 0,
                'Anio'              => date('Y'),
                'PasadoEnMes'       => idate("m"),
                'iva_id'            => 1,
                'area_id'           => 109,     //Mostrador
                'cuenta_id'         => 192,     // Venta
                'user_id'           => auth()->user()->id,
                'empresa_id'        => session('empresa_id'),
                'cliente_id'        => 202,     //Consumidor final
            ]);
            //session()->flash('message', 'Comprobante Creado.');
            $this->VentaId = $this->idVenta->id;
            $this->suma = 0;
        }
        $this->validate([
            'cantidad' => 'required',
        ]);

        Ventas_Productos::create([
            'productos_id'    => $this->productoid,
            'ventas_id'    => $this->idVenta->id,
            'precio'    => $this->precioventa,
            'cantidad'    => $this->cantidad,
            'orden' => $this->orden,
            'user_id' => auth()->user()->id,
        ]);

        $this->orden++;

        // $sql ="SELECT ventas__productos.*, productos.name, productos.descripcion FROM `ventas__productos` inner join productos WHERE ventas__productos.productos_id = productos.id and ventas_id=" . $this->idVenta->id;
        $this->CargarListado();
        //dd($this->productosseleccionados);
        // $this->productosseleccionados = Ventas_Productos::where('ventas_ids', $this->idVenta->id)
        // ->innerjoin('productos.id','=','ventas__productos.productos_id')
        // ->get();
        //$this->productosseleccionados = DB::select(DB::raw($sql));

        $this->openModalProducto();
    }

    public function openModalProducto() {
        $this->ModalProducto = !$this->ModalProducto;
        //$this->CargarListado();
    }

    public function openModalCliente() {
        $this->ModalCliente = !$this->ModalCliente;
        //$this->CargarListado();
    }

    public function openModalVenta() {
        $this->ModalVenta = !$this->ModalVenta;
        //$this->CargarListado();
    }

    public function openModalError() {
        $this->ModalError = !$this->ModalError;
    }

    public function CargarListado()
    {
        //dd($this->idVenta->id);
        if (!is_null($this->idVenta->id)) {
            //$sql = 'SELECT *, row_number() OVER () as row_number from ventas__productos inner join productos on ventas__productos.productos_id = productos.id where ventas_id = '.$this->idVenta->id;
            //$this->productosseleccionados = DB::select(DB::raw($sql)); 
            // dd( $this->productosseleccionados );
            $this->productosseleccionados = Ventas_Productos::where('ventas_id', $this->idVenta->id)
                ->join('productos', 'ventas__productos.productos_id', '=', 'productos.id')
                ->get();
            $this->suma = 0;
            foreach ($this->productosseleccionados as $prod) {
                $this->suma = $this->suma + $prod->precio * $prod->cantidad;
            }
        } else {
            dd('paso');
        }
    }

    public function eliminarItem($productos_id, $ventas_id)
    {
        $sql = 'DELETE FROM ventas__productos WHERE productos_id=' . $productos_id . ' and ventas_id=' . $ventas_id;
        $datos = DB::select(DB::raw($sql));
        $this->orden--;
        $this->Reordenar();
        $this->CargarListado();
    }

    public function Reordenar()
    {
        $productosseleccionados = Ventas_Productos::where('ventas_id', $this->idVenta->id)
            ->join('productos', 'ventas__productos.productos_id', '=', 'productos.id')
            ->get('ventas__ public $orden = 1;
            public $suma = 0;
            public $montopagado = 0;
            public $cambio = 0;productos.*');
        $orden = 1;
        foreach ($productosseleccionados as $prod) {
            $affectedRows = Ventas_Productos::where("id", $prod->id)->update(["orden" => $orden]);
            $orden++;
        }
    }

    public function calcular()
    {
        $this->validate([
            'montopagado' => 'numeric|min:1',
        ]);
        $this->cambio = number_format($this->suma - $this->montopagado,2,'.');
    }

    public function BuscarElIvaDelCliente() {
        $a = Cliente::where('name',$this->cliente_name)
        ->join('ivas','clientes.iva_id','ivas.id')
        ->where('empresa_id',session('empresa_id'))->get();
        //dd($a[0]['monto']);
        if(count($a)) {
            $this->monto_iva = $a[0]['monto']; // Mantiene el valor y también lo devuelve
            return $this->monto_iva;
        }
    }

    public function BuscarMayorNumeroDeFactura() {
        $sql = 'SELECT * FROM ventas WHERE empresa_idñ=' .session('empresa_id') . ' and comprobante like "F_-000__-0_______" ORDER by comprobante DESC';
        $datos = Venta::where('empresa_id',session('empresa_id'))
        ->where('comprobante', 'like','F_-000__-0_______')
        ->orderby('comprobante','desc')
        ->get();
        if($datos) {
            $a = ((int)substr($datos[0]['comprobante'],9));
            $a++;
            $a = str_pad($a, 8, "0", STR_PAD_LEFT); 
            if ($this->monto_iva==0) { return substr($datos[0]['comprobante'],0,1)."C-".substr($datos[0]['comprobante'],3,5)."-$a"; } 
            else { return substr($datos[0]['comprobante'],0,1)."A-".substr($datos[0]['comprobante'],3,5)."-$a"; }
        } else {
            if ($this->monto_iva==0) { return "FC-00001-00000001"; } else { return "FA-00001-00000001"; }
        }      
    }

    public function CerrarVenta()
    {
        $nro_Fact=$this->BuscarMayorNumeroDeFactura();
        //dd($nro_Fact);
        $a = $this->BuscarElIvaDelCliente();
        if($a == 0) { $bruto=$this->suma; $neto = $this->suma; $montoiva = 0; }
        else { $neto = $this->suma; $montoiva = $this->suma*$a/100; $bruto=$neto-$montoiva; }

        $this->problema='Nada';
        if(is_null($this->cliente_id)) { $this->problema='Debe Seleccionar Cliente'; }
        
        if($this->problema=='Nada') {
            $b= Venta::where("id", $this->VentaId)
                ->update(["ParticIva" => 'Si','comprobante'=>$nro_Fact,'cliente_id'=>$this->cliente_id, 'BrutoComp' =>$bruto, 'MontoIva' => $montoiva, 'NetoComp' =>$neto, 'MontoPagadoComp'=>$this->montopagado]);
            if($b) {
                $this->openModalVenta();
                $this->orden = 1;
                $this->suma = 0;
                $this->montopagado = 0;
                $this->cambio = 0;
                $this->VentaId = 0;
                //$this->idVenta->id=0;
            }
        } else {
            $this->openModalError();
        }
        
    }
}
