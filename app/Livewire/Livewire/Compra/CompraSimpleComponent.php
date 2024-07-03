<?php

namespace App\Http\Livewire\Compra;

use Livewire\Component;
use App\Models\Area;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Cuenta;
use App\Models\Iva;
use App\Models\Proveedor;
use App\Models\Venta;
use Illuminate\Http\Request;


class CompraSimpleComponent extends Component
{
    public $areas, $cuentas, $clientes, $proveedores, $ivas;
    public $fecha_simple=false,$monto_simple, $partiva_simple, $iva_simple, $area_simple, $cuenta_simple, $cliente_simple, $proveedor_simple;

    public $modulo; // Permite hacer elección de módulo a utilizar

    public function render(Request $request)  
    {        
        // dd($request);
        // dd(request()->getRequestUri());
        switch (request()->getRequestUri()) {
            case "/VentaSimple?Compras" : $this->modulo='Compras'; break;
            case "/VentaSimple?Ventas" : $this->modulo='Ventas'; break;
            case "/VentaSimple?Prestamos" : $this->modulo='Prestamos'; break;
            default: ;
        }

        // dd($this->modulo);
        $this->areas = Area::where('empresa_id', session('empresa_id'))->ORDERBY('name')->get();
        $this->cuentas = Cuenta::where('empresa_id', session('empresa_id'))->ORDERBY('name')->get();
        $this->clientes = Cliente::where('empresa_id', session('empresa_id'))->ORDERBY('name')->get();
        $this->proveedores = Proveedor::where('empresa_id', session('empresa_id'))->ORDERBY('name')->get();
        $this->ivas = Iva::where('id','>',0)->get();
        return view('livewire.compra.compra-simple-component')->with(['areas' => $this->areas, 'cuentas' => $this->cuentas,'clientes' => $this->clientes,'ivas' => $this->ivas]);    

        // return view('livewire.compra.compra-simple-component');
    }

    // ==========================================================================================
    //                      C O M P R A                   S I M P L E 
    // ==========================================================================================

    public function GuardarVentaSimple() {
        $this->validate([
            'monto_simple'            => 'numeric',
        //     'fecha_simple'            => 'required|date',
        //     'cliente_simple'        => 'required|integer',
        //     'area_simple'             => 'required|integer',
        //     'cuenta_simple'           => 'required|integer',
        //     'partiva_simple'          => 'required',
        //     'iva_simple'              => 'required|integer',
        //     // 'neto_simple'             => 'numeric',
        ]);

        $anio = substr($this->fecha_simple,0,4);
        $mes = substr($this->fecha_simple,5,2);
        $iva = Iva::where('id','=',$this->iva_simple)->get();  // En base ak id de iva, trae el monto numérico
        if(count($iva)) { $iva = $iva[0]['monto']; } // $this->iva_simple; // Monto nuperico de Iva }
        else { $iva = 0; dd($iva); }

        if($this->partiva_simple==true) { $partiva_simple='Si'; } else { $partiva_simple='No'; }

        $a = Venta::create([
            'fecha'             => $this->fecha_simple,
            'comprobante'       => 0,
            'detalle'           => '',
            'BrutoComp'         => (double) number_format($this->monto_simple/(1+$iva/100), 2, '.', ','),
            'ParticIva'         => $partiva_simple,
            'MontoIva'          => (double) number_format($this->monto_simple - $this->monto_simple/(1+$iva/100), 2, '.', ','),
            'ExentoComp'        => 0,
            'ImpInternoComp'    => 0,
            'PercepcionIvaComp' => 0,
            'RetencionIB'       => 0,
            'RetencionGan'      => 0,
            'NetoComp'          => (double) number_format($this->monto_simple, 2, '.', ','),
            'MontoPagadoComp'   => (double) number_format($this->monto_simple, 2, '.', ','),
            'CantidadLitroComp' => 0,
            'Anio'              => $anio,
            'PasadoEnMes'       => (int) $mes,
            'iva_id'            => number_format($this->iva_simple, 2, '.', ','),
            'area_id'           => (int) $this->area_simple,
            'cuenta_id'         => (int) $this->cuenta_simple,
            'user_id'           => auth()->user()->id,
            'empresa_id'        => session('empresa_id'),
            'cliente_id'        => (int) $this->cliente_simple,
        ]);
        // dd($a);
        session()->flash('message', 'Comprobante Creado.');  
    }

    public function GuardarCompraSimple() {
        $this->validate([
            'monto_simple'            => 'numeric',
        //     'fecha_simple'            => 'required|date',
        //     'cliente_simple'        => 'required|integer',
        //     'area_simple'             => 'required|integer',
        //     'cuenta_simple'           => 'required|integer',
        //     'partiva_simple'          => 'required',
        //     'iva_simple'              => 'required|integer',
        //     // 'neto_simple'             => 'numeric',
        ]);

        $anio = substr($this->fecha_simple,0,4);
        $mes = substr($this->fecha_simple,5,2);
        $iva = Iva::where('id','=',$this->iva_simple)->get();  // En base ak id de iva, trae el monto numérico
        if(count($iva)) { $iva = $iva[0]['monto']; } // $this->iva_simple; // Monto nuperico de Iva }
        else { $iva = 0; dd($iva); }

        if($this->partiva_simple==true) { $partiva_simple='Si'; } else { $partiva_simple='No'; }

        $a = Comprobante::create([
            'fecha'             => $this->fecha_simple,
            'comprobante'       => 0,
            'detalle'           => '',
            'BrutoComp'         => (double) number_format($this->monto_simple/(1+$iva/100), 2, '.', ','),
            'ParticIva'         => $partiva_simple,
            'MontoIva'          => (double) number_format($this->monto_simple - $this->monto_simple/(1+$iva/100), 2, '.', ','),
            'ExentoComp'        => 0,
            'ImpInternoComp'    => 0,
            'PercepcionIvaComp' => 0,
            'RetencionIB'       => 0,
            'RetencionGan'      => 0,
            'NetoComp'          => (double) number_format($this->monto_simple, 2, '.', ','),
            'MontoPagadoComp'   => (double) number_format($this->monto_simple, 2, '.', ','),
            'CantidadLitroComp' => 0,
            'Anio'              => $anio,
            'PasadoEnMes'       => (int) $mes,
            'iva_id'            => number_format($this->iva_simple, 2, '.', ','),
            'area_id'           => (int) $this->area_simple,
            'cuenta_id'         => (int) $this->cuenta_simple,
            'user_id'           => auth()->user()->id,
            'empresa_id'        => session('empresa_id'),
            'proveedor_id'        => (int) $this->proveedor_simple,
        ]);
        // dd($a);
        session()->flash('message', 'Comprobante Creado.');  
    }    
}