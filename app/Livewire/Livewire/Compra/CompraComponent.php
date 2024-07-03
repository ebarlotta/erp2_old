<?php

namespace App\Http\Livewire\Compra;

use Livewire\Component;
use App\Models\Proveedor;
use App\Models\Area;
use App\Models\Comprobante;
use App\Models\Cuenta;
use App\Models\EmpresaUsuario;
use App\Models\Iva;
use App\Models\Producto;
use App\Models\Compras_Productos;
use Illuminate\Support\Facades\DB;

use Afip;
use ElectronicBilling;

class CompraComponent extends Component
{
    public $areas, $cuentas, $ivas, $proveedores;
    public $detalles=[];       // Globales
    public $detalle;
    public $productos;
    public $empresa_id; public $tabActivo=1; public $comprobante_id;
    
    //Comprobantes
    public $iva_value=0;
    public $isModalOpen = false;
    public $giva=1;
    public $ModalDelete, $openModalDelete;
    public $ModalModify, $openModalModify;
    public $ModalAgregarDetalle, $openModalAgregarDetalle;
    public $ModalCerrarLibro;
    public $gfecha,$gproveedor, $gcomprobante, $gcuenta, $gdetalle, $ganio, $gmes, $garea, $gpartiva, $gbruto, $giva2, $gexento, $gimpinterno, $gperciva, $gretgan, $gperib, $gneto, $gmontopagado, $gcantidad;
    public $gselect_productos, $gprecio_prod, $gcantidad_prod, $glistado_prod;
    //Variables del filtro
    public $gfmes, $gfproveedor, $gfparticipa, $gfiva, $gfdetalle, $gfarea, $gfcuenta, $gfanio, $fgascendente, $gfsaldo; //Comprobantes
    
    
    // Deuda Proveedores
    public $darea, $ddesde, $dhasta, $danio;
    public $DeudaProveedoresFiltro, $MostrarDeudaProveedores; 
    public $deudaPDF;

    // Crédito Proveedores
    public $carea, $cdesde, $chasta, $canio;
    public $CreditoProveedoresFiltro, $MostrarCreditoProveedores;

    // Libros de Iva
    public $lmes,$lanio;
    public $MostrarLibros, $LibroFiltro;

    //Listado de filtros
    public $filtro, $combodetalle;                // Comprobantes

    public function render() {
        //dd($this->empresa_id);
        if ($this->gfanio==null) { $this->gfanio = date("Y"); } 
        
        if (!is_null(session('empresa_id'))) { $this->empresa_id = session('empresa_id'); } 
        else { 
            $userid=auth()->user()->id;
            $empresas= EmpresaUsuario::where('user_id',$userid)->get();
            return view('livewire.empresa.empresa-component')->with('empresas', $empresas); 
        }
        $this->areas = Area::where('empresa_id', $this->empresa_id)->ORDERBY('name')->get();
        $this->cuentas = Cuenta::where('empresa_id', $this->empresa_id)->ORDERBY('name')->get();
        $this->proveedores = Proveedor::where('empresa_id', $this->empresa_id)->ORDERBY('name')->get();
        $this->ivas = Iva::where('id','>',1)->get();
        $this->productos = Producto::where('empresa_id', $this->empresa_id)->orderBy('name','asc')->get();
        
        return view('livewire.compra.compra-component');
    }
    
    public function render2() {
        return view('livewire.compra.comprasimple');    
    }

    public function openModalDelete() { $this->ModalDelete = true;  }
    public function closeModalDelete() { $this->ModalDelete = false;  }

    public function openModalCerrarLibro() { $this->ModalCerrarLibro = true;  }
    public function BackModalPopover() { $this->ModalCerrarLibro = false;  }

    public function openModalModify() { $this->ModalModify = true;  }
    public function closeModalModify() { $this->ModalModify = false;  }

    public function openModalAgregarDetalle() { $this->ModalAgregarDetalle = true; $this->listado_productos(); }
    public function closeModalAgregarDetalle() { $this->ModalAgregarDetalle = false;  }

    public function RellenarCamposVacios() {
        if(is_null($this->gfecha)) $this->gfecha=now();
        if(is_null($this->gbruto)) $this->gbruto=0.00;
        if(is_null($this->gexento)) $this->gexento=0.00;
        if(is_null($this->gimpinterno)) $this->gimpinterno=0.00;
        if(is_null($this->gperciva)) $this->gperciva=0.00;
        if(is_null($this->gperib)) $this->gperib=0.00;
        if(is_null($this->gretgan)) $this->gretgan=0.00;
        if(is_null($this->gneto)) $this->gneto=0.00;
        if(is_null($this->gbruto)) $this->gbruto=0.00;
        if(is_null($this->gmontopagado)) $this->gmontopagado=0.00;
        if(is_null($this->gcantidad)) $this->gcantidad=0.00;
        if(is_null($this->giva2)) $this->giva2=0.00;        
    }

    public function store() {
        $this->RellenarCamposVacios();            

        $this->validate([
            'gfecha'            => 'required|date',
            'gbruto'            => 'numeric',
            'gpartiva'          => 'required',
            'giva2'             => 'numeric',
            'gexento'           => 'numeric',
            'gimpinterno'       => 'numeric',
            'gperciva'          => 'numeric',
            'gperib'            => 'numeric',
            'gretgan'           => 'numeric',
            'gneto'             => 'numeric',
            'gmontopagado'      => 'numeric', 
            'gcantidad'         => 'numeric',
            'ganio'             => 'required|integer',
            'gmes'              => 'required',
            'giva'              => 'required|integer',
            'garea'             => 'required|integer',
            'gcuenta'           => 'required|integer',
            'gproveedor'        => 'required|integer',
        ]);
        $cerrado = Comprobante::where('PasadoEnMes','=',$this->gmes)
            ->where('Anio','=',$this->ganio)
            ->where('empresa_id','=',session('empresa_id'))
            ->where('Cerrado','>',0)
            ->get();

            // dd(count($cerrado));
        if(!count($cerrado) || (count($cerrado) && $this->gpartiva='Si')) {
            Comprobante::create([
                'fecha'             => $this->gfecha,
                'comprobante'       => $this->gcomprobante,
                'detalle'           => $this->gdetalle,
                'BrutoComp'         => $this->gbruto,
                'ParticIva'         => $this->gpartiva,
                'MontoIva'          => $this->giva2,
                'ExentoComp'        => $this->gexento,
                'ImpInternoComp'    => $this->gimpinterno,
                'PercepcionIvaComp' => $this->gperciva,
                'RetencionIB'       => $this->gperib,
                'RetencionGan'      => $this->gretgan,
                'NetoComp'          => $this->gneto,
                'MontoPagadoComp'   => $this->gmontopagado, 
                'CantidadLitroComp' => $this->gcantidad,
                'Anio'              => $this->ganio,
                'PasadoEnMes'       => $this->gmes,
                'iva_id'            => $this->giva,
                'area_id'           => $this->garea,
                'cuenta_id'         => $this->gcuenta,
                'user_id'           => auth()->user()->id,
                'empresa_id'        => session('empresa_id'),
                'proveedor_id'      => $this->gproveedor,
            ]);
            //updateOrCreate
            $this->gfiltro();
            session()->flash('message', 'Comprobante Creado.');    
        } else {
            session()->flash('message3', 'No se puede agragar un comprobante a un libro ya Cerrado.');
            }
        {
        }
    }

    public function edit() {
        $this->RellenarCamposVacios();
        $comp = Comprobante::find($this->comprobante_id);
        if ($comp->Cerrado) { 
            $this->closeModalModify();
            session()->flash('message3', 'No se puede modificar un comprobante que se encuentra en un libro cerrado.'); 
        } else {
            $this->validate([
                'gfecha'            => 'required|date',
                'gbruto'            => 'numeric',
                'gpartiva'          => 'required',
                'giva2'             => 'numeric',
                'gexento'           => 'numeric',
                'gimpinterno'       => 'numeric',
                'gperciva'          => 'numeric',
                'gperib'            => 'numeric',
                'gretgan'           => 'numeric',
                'gneto'             => 'numeric',
                'gmontopagado'      => 'numeric', 
                'gcantidad'         => 'numeric',
                'ganio'             => 'required|integer',
                'gmes'              => 'required',
                'giva'              => 'required|integer',
                'garea'             => 'required|integer',
                'gcuenta'           => 'required|integer',
                'gproveedor'        => 'required|integer',
            ]);
            //dd($this->gbruto. " " . $this->giva);
            $comp->update([
                'fecha'             => $this->gfecha,
                'comprobante'       => $this->gcomprobante,
                'detalle'           => $this->gdetalle,
                'BrutoComp'         => $this->gbruto,
                'ParticIva'         => $this->gpartiva,
                'MontoIva'          => $this->giva2,
                'ExentoComp'        => $this->gexento,
                'ImpInternoComp'    => $this->gimpinterno,
                'PercepcionIvaComp' => $this->gperciva,
                'RetencionIB'       => $this->gperib,
                'RetencionGan'      => $this->gretgan,
                'NetoComp'          => $this->gneto,
                'MontoPagadoComp'   => $this->gmontopagado, 
                'CantidadLitroComp' => $this->gcantidad,
                'Anio'              => $this->ganio,
                'PasadoEnMes'       => $this->gmes,
                'iva_id'            => $this->giva,
                'area_id'           => $this->garea,
                'cuenta_id'         => $this->gcuenta,
                'user_id'           => auth()->user()->id,
                'empresa_id'        => session('empresa_id'),
                'proveedor_id'      => $this->gproveedor,
            ]);
            //updateOrCreate
            $this->gfiltro();
            $this->closeModalModify();
            session()->flash('message2', $this->comprobante_id ? 'Comprobante Actualizado.' : 'No se pudo modificar.');
        }
    }

    public function delete() {
        //$this->comprobante_id = $id;
        $a = Comprobante::find($this->comprobante_id);
        if($a->Cerrado==0) { 
            $a->delete(); 
            $this->comprobante_id=null;
            $this->gfiltro();
            session()->flash('message3', 'Comprobante Eliminado.');
        } else {
            session()->flash('message3', 'No se puede eliminar un comprobante que se encuentra en un libro Cerrado.');
        }
        $this->closeModalDelete();
    }

    public function CambiarTab($id) {
        $this->tabActivo=$id;
    }

    public function gfiltro(){
        
        $sql = $this->ProcesaSQLFiltro('comprobantes'); // Procesa los campos a mostrar
        // if ($this->fgascendente) { $sql=$sql . " ASC"; } else { $sql=$sql . " DESC"; }
        // $registros = DB::select(DB::raw($sql));       // Busca el recordset
        $registros = DB::select($sql);       // Busca el recordset
        
        // dd($sql);
        // Extrae los distintos Detalles si es que los hay
        $sqlDetalle = "SELECT DISTINCT detalle " . substr($sql,9);
        // $sqlDetalle = substr($sqlDetalle,0,-37);
        $this->detalles = DB::select(DB::raw($sqlDetalle));        
        //Dibuja el combo Detalles
        $this->combodetalle = '';
        foreach ($this->detalles as $detalle) {
            $this->combodetalle = $this->combodetalle . 
            '<option value="' . $detalle->detalle .'">'. $detalle->detalle . '</option>';
        }
			
        //dd($this->combodetalle);
        //dd($sql);
        //dd(json_encode($this->detalles)); 
        //Dibuja el filtro
        $Saldo=0;
        
        // <div class=\"table-responsive-sm\">
        $this->filtro="
                <table class=\"table table-striped small\" style=\"font-size:12px; word-wrap: anywhere;\">
                <thead>
                  <tr>
                    <th scope=\"col\">Fecha</th>
                    <th class=\"p-0\" scope=\"col\">Comprobante</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Proveedor</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Detalle</th>
                    <th class=\"p-0\" scope=\"col\">Bruto</th>
                    <th class=\"p-0\" scope=\"col\">Iva</th>
                    <th class=\"p-0\" scope=\"col\">Exento</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Imp.Interno</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Percec.Iva</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Retenc.IB</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Retenc.Gan</th>
                    <th class=\"p-0\" scope=\"col\">Neto</th>
                    <th class=\"p-0\" scope=\"col\">Pagado</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Saldo</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Cant.Litros</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Part.Iva</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Mes</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Area</th>
                    <th class=\"p-0 col d-none d-sm-table-cell\" scope=\"col\">Cuenta</th>
                  </tr>
                </thead>";
                
            $Cantidad = 0; $MontoPagado = 0; $Neto = 0; $RetGan = 0; $RetIB = 0; $PerIva = 0; $Exento = 0 ;$ImpInterno = 0; $Bruto = 0; $MontoIvaT =0; $NetoT = 0;
        foreach($registros as $registro) {
            //dd($registro);
            $Fecha = substr($registro->fecha,8,2) ."-". substr($registro->fecha,5,2) ."-". substr($registro->fecha,0,4);
            $Area=Area::find($registro->area_id);
            $Cuenta=Cuenta::find($registro->cuenta_id);
            $Iva=Iva::find($registro->iva_id);
            $Proveedor=Proveedor::find($registro->proveedor_id);
            //dd($Iva->monto);
            if($Iva->monto==0) { $MontoIva=0; } else {
                $MontoIva=($registro->BrutoComp*$Iva->monto/100);
            }
            //dd(number_format($MontoIva, 2,'.',''));
            $Neto = $Neto + $registro->NetoComp;
            //Sumatoria de los registros encontrados para subtotal
            $Bruto = $Bruto + $registro->BrutoComp;
            $MontoIvaT = $MontoIvaT + $registro->MontoIva;
            $Exento = $Exento + $registro->ExentoComp;
            $ImpInterno= $ImpInterno + $registro->ImpInternoComp;
            $PerIva = $PerIva + $registro->PercepcionIvaComp;
            $RetIB = $RetIB + $registro->RetencionIB;
            $RetGan = $RetGan + $registro->RetencionGan;
            $MontoPagado = $MontoPagado + $registro->MontoPagadoComp;
            $Saldo=$Saldo+$registro->NetoComp-$registro->MontoPagadoComp;
            $Cantidad=$Cantidad+$registro->CantidadLitroComp;
            $NetoT = $NetoT + $registro->NetoComp;
            
            $this->filtro=$this->filtro."
            <tr wire:click=\"gCargarRegistro(". $registro->id .")\">
                <td class=\"p-0\">".substr($Fecha,0,6).substr($Fecha,8,2)."</td>
                <td class=\"p-0\">$registro->comprobante</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-left\">$Proveedor->name</td>
                <td style=\"max-width:200px; width:200px; overflow: hidden;\" class=\"p-0 col d-none d-sm-table-cell text-left\">$registro->detalle</td>
                <td class=\"p-0 text-right\">".number_format($registro->BrutoComp, 2,'.','')."</td>
                <td class=\"p-0 text-right\">".number_format($MontoIva, 2,'.','')."</td>
                <td class=\"p-0 text-right\">".number_format($registro->ExentoComp, 2,'.','')."</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">".number_format($registro->ImpInternoComp, 2,'.','')."</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">".number_format($registro->PercepcionIvaComp, 2,'.','')."</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">".number_format($registro->RetencionIB, 2,'.','')."</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">".number_format($registro->RetencionGan, 2,'.','')."</td>
                <td class=\"p-0 text-right\">".number_format($registro->NetoComp, 2,'.','')."</td>
                <td class=\"p-0 text-right\">".number_format($registro->MontoPagadoComp, 2,'.','')."</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">".number_format($Saldo, 2,'.','')."</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">".number_format($registro->CantidadLitroComp, 2,'.','')."</td>
                <td class=\"p-0 col d-none d-sm-table-cell\">$registro->ParticIva</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">" . $this->ConvierteMesEnTexto($registro->PasadoEnMes) . "</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">$Area->name</td>
                <td class=\"p-0 col d-none d-sm-table-cell text-right\">$Cuenta->name</td>
            </tr>";
            // $this->filtro=$this->filtro."
            //     <div class=\"flex col-12 fse-1 md:fse-0\" wire:click=\"gCargarRegistro(". $registro->id .")\">
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary\"\">$Fecha</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary\"\">$registro->comprobante</div>
            //     <div class=\"col-2 border border-secondary\"\">$Proveedor->name</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary text-center hidden sm:hidden md:hidden lg:block xl:block \"\">$registro->detalle</div>
            //     <div class=\"col-2 border border-secondary\"\">".number_format($registro->BrutoComp, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary\"\">".number_format($MontoIva, 2,'.','')."</div>
            //     <div class=\"col-2 border border-secondary\"\">".number_format($registro->ExentoComp, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">".number_format($registro->ImpInternoComp, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">".number_format($registro->PercepcionIvaComp, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">".number_format($registro->RetencionIB, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">".number_format($registro->RetencionGan, 2,'.','')."</div>
            //     <div class=\"col-2 border border-secondary\"\">".number_format($registro->NetoComp, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary\"\">".number_format($registro->MontoPagadoComp, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">".number_format($Saldo, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">".number_format($registro->CantidadLitroComp, 2,'.','')."</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">$registro->ParticIva</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">" . $this->ConvierteMesEnTexto($registro->PasadoEnMes) . "</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">$Area->name</div>
            //     <div class=\"xs:col-flex1 md:col-flex border border-secondary hidden sm:hidden md:hidden lg:block xl:block \"\">$Cuenta->name</div>
            // </div>";


            // $this->filtro=$this->filtro."<tr class=\"bg-red-200 hover:bg-red-100\" wire:click=\"gCargarRegistro(". $registro->id .")\"><td class=\"border border-green-600\">$Fecha</td><td class=\"border border-green-600 text-right\">$registro->comprobante</td><td class=\"border border-green-600\">$Proveedor->name</td><td class=\"border border-green-600 text-right\">$registro->detalle</td><td class=\"border border-green-600 text-right\">".number_format($registro->BrutoComp, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($MontoIva, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($registro->ExentoComp, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($registro->ImpInternoComp, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($registro->PercepcionIvaComp, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($registro->RetencionIB, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($registro->RetencionGan, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($registro->NetoComp, 2,'.','')."</td><td class=\"text-red-600 border border-green-600 text-right\">".number_format($registro->MontoPagadoComp, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($Saldo, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($registro->CantidadLitroComp, 2,'.','')."</td><td class=\"border border-green-600\">$registro->ParticIva</td><td class=\"border border-green-600\">" . $this->ConvierteMesEnTexto($registro->PasadoEnMes) . "</td><td class=\"border border-green-600\">".$Area->name."</td><td class=\"border border-green-600\">".$Cuenta->name."</td></tr>
            // </tr>";
        }

        $this->filtro=$this->filtro."<tr>
        <td class=\"col d-none d-sm-table-cell\"></td>
        <td class=\"col d-none d-sm-table-cell\"></td>
        <td></td>
        <td>Totales</td>
        <td class=\"p-0 text-right\">".number_format($Bruto, 2,'.','')."</td>
        <td class=\"p-0 text-right\">".number_format($MontoIvaT, 2,'.','')."</td>
        <td class=\"p-0 text-right\">".number_format($Exento, 2,'.','')."</td>
        <td class=\"p-0 d-none d-sm-table-cell text-right\">".number_format($ImpInterno, 2,'.','')."</td>
        <td class=\"p-0 d-none d-sm-table-cell text-right\">".number_format($PerIva, 2,'.','')."</td>
        <td class=\"p-0 d-none d-sm-table-cell text-right\">".number_format($RetIB, 2,'.','')."</td>
        <td class=\"p-0 d-none d-sm-table-cell text-right\">".number_format($RetGan, 2,'.','')."</td>
        <td class=\"p-0 text-right\">".number_format($NetoT, 2,'.','')."</td>
        <td class=\"p-0 text-right\">".number_format($MontoPagado, 2,'.','')."</td>
        <td class=\"p-0 d-none d-sm-table-cell text-right\">".number_format($Saldo, 2,'.','')."</td>
        <td class=\"p-0 d-none d-sm-table-cell text-right\">".number_format($Cantidad, 2,'.','')."</td>
        <td class=\"p-0 d-none d-sm-table-cell\"></td>
        <td class=\"p-0 d-none d-sm-table-cell\"></td>
        <td class=\"p-0 d-none d-sm-table-cell\"></td>
        <td class=\"p-0 d-none d-sm-table-cell\"></td>
    </tr> 
    </tbody>
        </table>
        
    </div>";
    

        // $this->filtro=$this->filtro."
        // <div class=\"col-12 fse-1  md:fse-0 flex border table-auto w-full border border-green-800 border-collapse bg-gray-300 rounded-md\">
            
        //         <div class=\"xs:col-flex1 md:col-flex border\"></div>
        //         <div class=\"xs:col-flex1 border\"></div>
        //         <div class=\"col-2 border\">Totales</div>
        //         <div class=\"xs:col-flex1 text-center hidden sm:hidden md:hidden lg:block xl:block \"></div>
        //         <div class=\"col-2 border\"\">".number_format($Bruto, 2,'.','')."</div>
        //         <div class=\"xs:col-flex1 border\"\">".number_format($MontoIvaT, 2,'.','')."</div>
        //         <div class=\"col-2\"\">".number_format($Exento, 2,'.','')."</div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\">".number_format($ImpInterno, 2,'.','')."</div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\">".number_format($PerIva, 2,'.','')."</div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\">".number_format($RetIB, 2,'.','')."</div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\">".number_format($RetGan, 2,'.','')."</div>
        //         <div class=\"col-2 border\"\">".number_format($NetoT, 2,'.','')."</div>
        //         <div class=\"xs:col-flex1 border\"\">".number_format($MontoPagado, 2,'.','')."</div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\">".number_format($Saldo, 2,'.','')."</div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\">".number_format($Cantidad, 2,'.','')."</div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\"></div>
        //         <div class=\"hidden sm:hidden md:hidden lg:block xl:block\"\"></div>
        //         <div class=\"xs:col-flex1\"\"></div>
        //         <div class=\"xs:col-flex1\"\"></div>
        // </div>    ";
        

        // $this->filtro = $this->filtro."<tr class=\"bg-gradient-to-r from-purple-400 via-pink-500 to-red-500\"><td></td><td></td><td></td><td class=\"border border-green-600\">Totales</td><td class=\"border border-green-600 text-right\">".number_format($Bruto, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($MontoIvaT, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($Exento, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($ImpInterno, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($PerIva, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($RetIB, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($RetGan, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($NetoT, 2,'.','')."</td><td class=\"border border-green-600 text-right\">".number_format($MontoPagado, 2,'.','')."</td><td class=\"border border-green-600 text-right\"><strong>".number_format($Saldo, 2,'.','')."</strong></td><td class=\"border border-green-600 text-right\">".number_format($Cantidad, 2,'.','')."</td></tr>";
        // $this->filtro=$this->filtro."</table>";
        
    }

    public function ConvierteMesEnTexto($id) {
        switch ($id) {
            case 1 : $caso="Enero"; break;
            case 2 : $caso="Febrero"; break;
            case 3 : $caso="Marzo"; break;
            case 4 : $caso="Abril"; break;
            case 5 : $caso="Mayo"; break;
            case 6 : $caso="Junio"; break;
            case 7 : $caso="Julio"; break;
            case 8 : $caso="Agosto"; break;
            case 9 : $caso="Setiembre"; break;
            case 10 : $caso="Octubre"; break;
            case 11 : $caso="Noviembre"; break;
            case 12 : $caso="Diciembre"; break;
        }
        return $caso;
    }
    public function gsetanio($dato){
        $this->gfanio=$dato;
        $this->gfiltro();
    }
    public function ProcesaSQLFiltro($interfaz){
        $sql='';
        switch ($interfaz) {
            case "comprobantes" : {
                //Mes 	Proveedor 	ParticipaIva 	Iva 	Detalle 	Area 	Cuenta 	Año 	Asc. C/Saldo
                //dd($this->gfmes);
                if ($this->gfmes<>null) $sql=" PasadoEnMes=" . $this->gfmes;
                if ($this->gfproveedor) $sql=$sql ? $sql=$sql." and proveedor_id=" . $this->gfproveedor : " proveedor_id=" . $this->gfproveedor;
                if ($this->gfparticipa) $sql=$sql ? $sql=$sql." and ParticIva='" . $this->gfparticipa . "'" : " ParticIva='" . $this->gfparticipa . "'";
                if ($this->gfiva) $sql=$sql ? $sql=$sql." and iva_id=" . $this->gfiva : " iva_id=" . $this->gfiva;
                //dd($this->gfdetalle);
                //if ($this->gfdetalle<>null) $sql=$sql ? $sql=$sql." and detalle='" . $this->gfdetalle . "'" : " ";
                // if ($this->gfdetalle=="Todos") $sql=$sql ? $sql=$sql." and detalle='" . $this->gfdetalle . "'" : " detalle='" . $this->gfdetalle . "'";
                if ($this->gfdetalle<>null) {
                    
                    if ($this->gfdetalle<>"Todos") $sql=$sql . " and detalle='" . $this->gfdetalle. "'";
                }
                //dd($sql);
                if ($this->gfarea) $sql=$sql ? $sql=$sql." and area_id=" . $this->gfarea : " area_id=" . $this->gfarea;
                if ($this->gfcuenta) $sql=$sql ? $sql=$sql." and cuenta_id=" . $this->gfcuenta : " cuenta_id=" . $this->gfcuenta;
                if ($this->gfanio) $sql=$sql ? $sql=$sql." and Anio=" . $this->gfanio : " Anio=" . $this->gfanio;
                $sql=$sql ? $sql=$sql." and empresa_id=" . session('empresa_id') : $sql." empresa_id=" . session('empresa_id');
                //Fecha	Comprobante	Proveedor	Detalle	Bruto	Iva	exento	Imp.Interno	Percec.Iva	Retenc.IB	Retenc.Gan	Neto	Pagado	Saldo	Cant.Litros	Partic.Iva	Pasado EnMes	Area	Cuenta
                $sql = "SELECT * FROM comprobantes WHERE" . $sql; // . " ORDER BY fecha, comprobante";
                // dd($sql);
                if ($this->fgascendente) { $sql=$sql . " ORDER BY fecha, comprobante"; } else { $sql=$sql . " ORDER BY fecha DESC, comprobante DESC"; }
                break;
            }
            case "deuda" : {
                
                //"SELECT proveedors.name as Name, Saldos.Saldo as Saldo FROM proveedors, (SELECT sum(NetoComp-MontoPagadoComp) as Saldo, comprobantes.proveedor_id as idproveedor FROM comprobantes WHERE fecha>='2021-09-01' and fecha<='2021-09-30' and empresa_id=1     GROUP BY comprobantes.proveedor_id ) as Saldos WHERE proveedors.id = Saldos.idproveedor and Saldos.Saldo>1
                
                if ($this->darea==0) { $darea=''; } else { $darea=' and comprobantes.area_id='.$this->darea; }  //Comprueba si se ha seleccionado un area en especìfico
                if ($this->danio==0) { $danio=''; } else { $danio=' and comprobantes.Anio='.$this->danio; }  //Comprueba si se ha seleccionado un año en especìfico

                // $sql="SELECT proveedors.name as Name, Saldos.Saldo as Saldo FROM proveedors, (SELECT sum(NetoComp-MontoPagadoComp) as Saldo, comprobantes.proveedor_id as idproveedor FROM comprobantes WHERE fecha>='". $this->ddesde."' and fecha<='". $this->dhasta."' and empresa_id=". session('empresa_id')." $darea $danio GROUP BY comprobantes.proveedor_id ) as Saldos WHERE proveedors.id = Saldos.idproveedor and Saldos.Saldo>1"; 
                
                // $sql = DB::table('proveedors')
                //     ->join('comprobantes',function ($query) {
                //         $query->selectRaw('sum(NetoComp-MontoPagadoComp) as Saldo')
                //             ->from('comprobantes')
                //             ->groupBy('proveedor_id');
                // })
                // ->get();
                
                
                //->where('comprobantes.empresa_id','=',session('empresa_id'))
                //->where('proveedors.empresa_id','=',session('empresa_id'))

                //->select(DB::raw('SUM(NetoComp-MontoPagadoComp) as Saldo'))
                    //->where(DB::raw('SUM(NetoComp-MontoPagadoComp),'>',1)
                    //->havingRaw('SUM(NetoComp-MontoPagadoComp) > ?', [1])
                    // ->whereBetween('fecha',["'".$this->ddesde."'","'".$this->dhasta."'"])
                //    ->groupBy('comprobantes.proveedor_id')
                    
                    // dd($sql);
// $proveedores = Proveedor::where('empresa_id','=',session('empresa_id'))->get();
// $comprobantes = Comprobante::where('empresa_id','=',session('empresa_id'))->get();
                    // $merded = $comprobantes->merge($proveedores);

                    $sql = DB::table('comprobantes')
                    ->selectRaw('sum(NetoComp-MontoPagadoComp) as Saldo, proveedors.id')
                    ->join('proveedors', 'comprobantes.proveedor_id', '=', 'proveedors.id')
                    ->groupBy('proveedors.id')
                    // ->whereRaw('(NetoComp-MontoPagadoComp)>1')
                    //->whereBetween('comprobantes.fecha',["'".$this->ddesde."'","'".$this->dhasta."'"])
                    ->where('comprobantes.fecha','>=',$this->ddesde)
                    ->where('comprobantes.fecha','<=',$this->dhasta)
                    ->where('comprobantes.empresa_id','=',session('empresa_id'))
                    //->orderByDesc('avg_salary')
                    ->get();
                    
                    // dd($sql);

                $this->MostrarDeudaProveedores=true;break;
            };
            case "credito" : {
                //"SELECT proveedors.name as Name, Saldos.Saldo as Saldo FROM proveedors, (SELECT sum(NetoComp-MontoPagadoComp) as Saldo, comprobantes.proveedor_id as idproveedor FROM comprobantes WHERE fecha>='2021-09-01' and fecha<='2021-09-30' and empresa_id=1     GROUP BY comprobantes.proveedor_id ) as Saldos WHERE proveedors.id = Saldos.idproveedor and Saldos.Saldo<1
                
                if ($this->carea==0) { $carea=''; } else { $carea=' and comprobantes.area_id='.$this->carea; }  //Comprueba si se ha seleccionado un area en especìfico
                if ($this->canio==0) { $canio=''; } else { $canio=' and comprobantes.Anio='.$this->canio; }  //Comprueba si se ha seleccionado un año en especìfico

                // $sql="SELECT proveedors.name as Name, Saldos.Saldo as Saldo FROM proveedors, (SELECT sum(NetoComp-MontoPagadoComp) as Saldo, comprobantes.proveedor_id as idproveedor FROM comprobantes WHERE fecha>='". $this->cdesde."' and fecha<='". $this->chasta."' and empresa_id=". session('empresa_id')." $carea  $canio  GROUP BY comprobantes.proveedor_id ) as Saldos WHERE proveedors.id = Saldos.idproveedor and Saldos.Saldo<1";
                $sql = DB::table('comprobantes')
                    ->selectRaw('sum(NetoComp-MontoPagadoComp) as Saldo, proveedors.id')
                    ->join('proveedors', 'comprobantes.proveedor_id', '=', 'proveedors.id')
                    ->groupBy('proveedors.id')
                    // ->whereRaw('(NetoComp-MontoPagadoComp)<1')
                    //->whereBetween('comprobantes.fecha',["'".$this->ddesde."'","'".$this->dhasta."'"])
                    ->where('comprobantes.fecha','>=',$this->cdesde)
                    ->where('comprobantes.fecha','<=',$this->chasta)
                    ->where('comprobantes.empresa_id','=',session('empresa_id'))
                    //->orderByDesc('avg_salary')
                    ->get();
                $this->MostrarCreditoProveedores=true;break;
            };
            case "libro" : {
                $sql="SELECT PasadoEnMes, Max(Cerrado) as Cerrado FROM comprobantes WHERE ParticIva='Si' and Anio=" . $this->lanio . " and empresa_id='".session('empresa_id')."' GROUP BY PasadoEnMes, Anio";
                $this->MostrarLibros=true;break;
            };
        }
        //dd($sql);
        return $sql;
    }

    public function gCargarRegistro($id) {
        $registro=Comprobante::find($id);
        $this->comprobante_id = $id;
        $this->id = $id; //Utilizado para buscar el registro para eliminar
        $this->gfecha= substr($registro->fecha,0,10);
        $this->gcomprobante=$registro->comprobante;
        $this->gdetalle=$registro->detalle;
        //dd($registro);
        //dd($this->gdetalle);
        $this->gbruto=number_format($registro->BrutoComp, 2, '.','');
        $this->gpartiva=$registro->ParticIva;
        $a=Iva::find($registro->iva_id);
        $this->iva_value= $a->monto;
        //  dd($this->iva_value);
        $this->giva2=number_format($registro->MontoIva, 2, '.','');
        $this->gexento=number_format($registro->ExentoComp, 2, '.','');
        $this->gimpinterno=number_format($registro->ImpInternoComp, 2, '.','');
        $this->gperciva=number_format($registro->PercepcionIvaComp, 2, '.','');
        $this->gperib=number_format($registro->RetencionIB, 2, '.','');
        $this->gretgan=number_format($registro->RetencionGan, 2, '.','');
        $this->gneto=number_format($registro->NetoComp, 2, '.','');
        $this->gmontopagado=number_format($registro->MontoPagadoComp, 2, '.','');
        $this->gcantidad=number_format($registro->CantidadLitroComp, 2, '.','');
        $this->ganio=$registro->Anio;
        $this->gmes=$registro->PasadoEnMes;
        $this->garea=$registro->area_id;
        $this->gcuenta=$registro->cuenta_id;
        $this->giva=$registro->iva_id;
        $this->gproveedor=$registro->proveedor_id;
        
        $this->validate([
            'gfecha'            => 'required|date',
            'gbruto'            => 'numeric',
            'gpartiva'          => 'required',
            'giva2'             => 'numeric',
            'gexento'           => 'numeric',
            'gimpinterno'       => 'numeric',
            'gperciva'          => 'numeric',
            'gperib'            => 'numeric',
            'gretgan'           => 'numeric',
            'gneto'             => 'numeric',
            'gmontopagado'      => 'numeric', 
            'gcantidad'         => 'numeric',
            'ganio'             => 'required|integer',
            'gmes'              => 'required',
            'giva'              => 'required|integer',
            'garea'             => 'required|integer',
            'gcuenta'           => 'required|integer',
            'gproveedor'        => 'required|integer',
        ]);
    }

    public function CalcularIva() {
        //dd($this->iva_value);$a=Iva::find($registro->iva_id);
        $a=Iva::find($this->giva);
        if ($this->gbruto=="") $this->gbruto=0.00;
        $this->iva_value= $a->monto;
        if($this->iva_value<>0) {
            $result = (float)$this->gbruto * (float)$this->iva_value / 100;
            (float) $this->giva2 = number_format($result,2,'.','');
        } else {
            (float) $this->giva2 = 0;
        }

        $this->CalcularNeto();
        //$this->gfsaldo = $this->gneto-$this->gmontopagado;
    }

    public function CalcularNeto() {
        $this->gneto = number_format( floatval($this->gbruto) + floatval($this->giva2) + floatval($this->gexento) + floatval($this->gimpinterno) + floatval($this->gperciva) + floatval($this->gperib) + floatval($this->gretgan),2 ,'.','' ) ;
    }

    public function copiarMontoPagado() {
        $this->gmontopagado= $this->gneto;
    }

    public function CalcularDeudaProveedores($ret) {
        $registros = $this->ProcesaSQLFiltro('deuda'); // Procesa los campos a mostrar
        //dd($sql);
        //$registros = DB::select(DB::raw($sql));       // Busca el recordset

        // $registros = DB::table('comprobantes')
        //             ->selectRaw('sum(NetoComp-MontoPagadoComp) as Saldo, proveedors.id')
        //             ->join('proveedors', 'comprobantes.proveedor_id', '=', 'proveedors.id')
        //             ->groupBy('proveedors.id')
        //             //->whereBetween('comprobantes.fecha',["'".$this->ddesde."'","'".$this->dhasta."'"])
        //             ->where('comprobantes.fecha','>=',$this->ddesde)
        //             ->where('comprobantes.fecha','<=',$this->dhasta)
        //             //->orderByDesc('avg_salary')
        //             ->get();

        $this->deudaPDF = $registros;
        //dd($this->deudaPDF);
        //Dibuja el filtro
        $Saldo=0;
        $this->DeudaProveedoresFiltro = "<table class=\"mt-6\" style=\"width:300px\">
            <tr class=\"bg-blue-200 border border-blue-500\">
                <td class=\"center bg-gray-300\"><b>Nombre</b></td>
                <td class=\"center bg-gray-300\"><b>Deuda</b></td>
            </tr>";
        foreach($registros as $registro) {
            if ($registro->Saldo>1) {
                $proveedor = Proveedor::find($registro->id);
                $this->DeudaProveedoresFiltro = $this->DeudaProveedoresFiltro .
                "<tr>
                    <td class=\"bg-gray-100 border border-blue-500 text-left pl-4\">
                        $proveedor->name
                        <div class=\"tooltip\">
                            <span class=\"tooltiptext\">
                                Teléfono: ".$proveedor->telefono."<br>
                                Email: ".$proveedor->email."
                            </span>
                        </div>
                    </td>
                    <td class=\"bg-gray-100 border border-blue-500 text-right pr-4\">" . number_format($registro->Saldo,2,',','.') . "
                    </td>
                </tr>";

                $Saldo = $Saldo + $registro->Saldo;
            }
        }
        $this->DeudaProveedoresFiltro = $this->DeudaProveedoresFiltro .
            "<tr class=\"bg-green-500 w-36\">
                <td class=\"colspan-2 bg-gray-300\">Total Deuda</td>
                <td class=\"text-right bg-gray-300 pr-4\"><b>".number_format($Saldo,2,',','.')."</b></td>
            </tr>
            </table>";
            //dd("filtro" . $this->DeudaProveedoresFiltro);
            //$this->deudaPDF = $this->DeudaProveedoresFiltro;
        if($ret) return $this->DeudaProveedoresFiltro;
        //SELECT proveedors.name, Saldos.Saldo FROM proveedors, (SELECT sum(NetoComp-MontoPagadoComp) as Saldo, comprobantes.proveedor_id as idproveedor FROM comprobantes WHERE empresa_id=1 GROUP BY comprobantes.proveedor_id ) as Saldos WHERE proveedors.id = Saldos.idproveedor and Saldos.Saldo>1
    }

    public function CalcularCreditoProveedores() {
        $registros = $this->ProcesaSQLFiltro('credito'); // Procesa los campos a mostrar
        // $sql = $this->ProcesaSQLFiltro('credito'); // Procesa los campos a mostrar
        // $registros = DB::select(DB::raw($sql));       // Busca el recordset

        //Dibuja el filtro
        $Saldo=0;
        $this->CreditoProveedoresFiltro = "<table class=\"mt-6\" style=\"width:300px\">
            <tr class=\"bg-blue-200 border border-blue-500\">
                <td class=\"center bg-gray-400\"><b>Nombre</b></td>
                <td class=\"center bg-gray-400\"><b>Crédito</b></td>
            </tr>";
        foreach($registros as $registro) {
            if($registro->Saldo<-1) {
                $proveedor = Proveedor::find($registro->id);
                // "<tr>
                //     <td class=\"bg-gray-100 border border-blue-500 text-left tooltip\" wire:click=\"copy(".$proveedor->id.")\"><span class=\"tooltiptext\">
                //     Teléfono: ".$proveedor->telefono."<br>Email: ".$proveedor->email."</span>" . $proveedor . "</td>
                //     <td class=\"bg-gray-100 border border-blue-500 text-right\">" . number_format($registro->Saldo * -1 ,2,',','.') . "</td>
                // </tr>";
                $this->CreditoProveedoresFiltro = $this->CreditoProveedoresFiltro .
                "<tr>
                    <td class=\"bg-gray-100 border border-blue-500 text-left pl-4\">" . $proveedor->name . "</td>
                    <td class=\"bg-gray-100 border border-blue-500 text-right pr-4\">" . number_format($registro->Saldo * -1 ,2,',','.') . "</td>
                </tr>";
                $Saldo = $Saldo + $registro->Saldo * -1;
            }
        }
        
        $this->CreditoProveedoresFiltro = $this->CreditoProveedoresFiltro .
            "<tr class=\"bg-green-500\">
                <td class=\"colspan-2 bg-gray-400\">Total Crédito</td>
                <td class=\"bg-gray-400 text-right pr-4\"><b>".number_format($Saldo,2,',','.')."</b></td>
            </tr>
            </table>";
    }

    public function MostrarLibros() {
        if($this->lmes && $this->lanio) {
            $sql = $this->ProcesaSQLFiltro('libro'); // Procesa los campos a mostrar
            $registros = DB::select(DB::raw($sql));       // Busca el recordset
            //Dibuja el filtro
            $Saldo=0;
            $this->LibroFiltro ="<table class=\"w-full mt-8  shadow-lg\" ><tr><td class=\"bg-gray-300 border border-green-400\">Mes</td><td class=\"bg-gray-300 border border-green-400\">Estado</td>";
            //dd($registros);
            foreach ($registros as $libro) {
                $NombreMes = $this->ConvierteMesEnTexto($libro->PasadoEnMes);
                if($libro->Cerrado>0) { $AbiertoCerrado = 'Cerrado'; } else { $AbiertoCerrado = 'Abierto'; }
                $this->LibroFiltro = $this->LibroFiltro . "<tr><td class=\"bg-gray-100 border border-green-400\">". $NombreMes . "</td><td class=\"bg-gray-100 border border-green-400\">" . $AbiertoCerrado . "</td>";
            }
            $this->LibroFiltro = $this->LibroFiltro . "</tr></table>";
        }
    }

    public function CerrarLibro() {
        //$sSql="SELECT * FROM tblComprobantes WHERE Anio=$LibroAnio and Empresa='".$_SESSION['CuitEmpresa']."' and PasadoEnMes='$LibroMes' and ParticipaEnIva='Si'";
        $i=0;
        dd('jhkhkjh');
        $registros = DB::table('comprobantes')              // Busca la última página utilizada para la empresa seleccionada
            ->where('empresa_id','=',session('empresa_id'))
            ->where('ParticIva','=','Si')
            ->groupByRaw('Anio,PasadoEnMes,Cerrado')
            ->orderBy('Cerrado')
            ->get();
        dd($registros);
        $UltimaPaginaCerrada = $registros->last()->Cerrado; // Asigna el valor en $UltimaPaginaCerrada
        $registros = DB::table('comprobantes')        // Carga todos los registros que van a ser modificados que corresponden al mes y año
            ->where('Anio','=',$this->lanio)
            ->where('PasadoEnMes','=',$this->lmes)
            ->where('empresa_id','=',session('empresa_id'))
            ->where('ParticIva','=','Si')
            ->orderByRaw('fecha,comprobante,BrutoComp')
            ->get();
        $UltimaPaginaCerrada++;
        foreach($registros as $registro) {
            $reg = Comprobante::find($registro->id);
            if ($i==35) { $UltimaPaginaCerrada++; $i=0; }
            $reg->Cerrado = $UltimaPaginaCerrada;
            $reg->save();
            $i++;
        }
        $this->MostrarLibros();
    }

    public function agregar_detalle() {
        
        $this->validate([
            'comprobante_id'    => 'required',
            'gselect_productos' => 'required|numeric',
            'gcantidad_prod'    => 'required|numeric',
            'gprecio_prod'      => 'required|numeric',
        ]);
        $detalle = new Compras_Productos;
        $detalle->comprobantes_id = $this->comprobante_id;
        $detalle->productos_id = $this->gselect_productos;
        $detalle->cantidad = $this->gcantidad_prod;
        $detalle->precio = $this->gprecio_prod;
        $detalle->user_id = $userid=auth()->user()->id;

        $detalle->save();

        // Incrementa la cantidad de stock del producto
        $producto = Producto::find($this->gselect_productos);
        $producto->existencia = $producto->existencia + abs($this->gcantidad_prod);
        $producto->save();

        $this->listado_productos();
    }

    public function eliminar_detalle($id_detalle) {
        
        //Encuentra el detalle a eliminar pasa buscar la cantidad que tiene que eliminar
        $eliminar = Compras_Productos::find($id_detalle);
        //dd($eliminar);
        $cant_a_eliminar = $eliminar->cantidad*-1;


        $detalle = new Compras_Productos;
        $detalle->comprobantes_id = $this->comprobante_id;
        $detalle->productos_id = $eliminar->productos_id;
        $detalle->cantidad = $cant_a_eliminar;  //Actualiza el valor con la cantidad a eliminar
        $detalle->precio = $eliminar->precio;
        $detalle->user_id = $userid=auth()->user()->id;

        $detalle->save();
        
        // Incrementa la cantidad de stock del producto
        $producto = Producto::find($eliminar->productos_id);
        $producto->existencia = $producto->existencia + $cant_a_eliminar;
        $producto->save();

        $this->listado_productos();
    }
    
    public function listado_productos() {
        
        $this->glistado_prod = Compras_Productos::join('productos','compras__productos.productos_id','productos.id')
        ->where('comprobantes_id',$this->comprobante_id)
        ->get(['compras__productos.*','productos.name']);
        //falta el where
        //dd($this->glistado_prod);
    }


    
    public function GenerarCertificado() {
        // CUIT al cual le queremos generar el certificado
        $tax_id = 20255083571; 

        // Usuario para ingresar a AFIP.
        // Para la mayoria es el mismo CUIT, pero al administrar
        // una sociedad el CUIT con el que se ingresa es el del administrador
        // de la sociedad.
        $username = '20255083571'; 

        // Contraseña para ingresar a AFIP.
        $password = 'sOCIEDAD2023';

        // Alias para el certificado (Nombre para reconocerlo en AFIP)
        // un alias puede tener muchos certificados, si estas renovando
        // un certificado podes utilizar el mismo alias
        $alias = 'afipsdkCertificado';

        // Creamos una instancia de la libreria
        $afip = new Afip(array('CUIT' => $tax_id ));

        // Creamos el certificado (¡Paciencia! Esto toma unos cuantos segundos)
        $res = $afip->CreateCert($username, $password, $alias);

        // Mostramos el certificado por pantalla
        var_dump($res->cert);

        // Mostramos la key por pantalla
        var_dump($res->key);

        dd($res->cert);
    }

    public function AutorizarCertificado() {
        // CUIT al cual le queremos generar la autorización
        $tax_id = 20255083571; 

        // Usuario para ingresar a AFIP.
        // Para la mayoria es el mismo CUIT, pero al administrar
        // una sociedad el CUIT con el que se ingresa es el del administrador
        // de la sociedad.
        $username = '20255083571'; 

        // Contraseña para ingresar a AFIP.
        $password = 'sOCIEDAD2023';

        // Alias del certificado a autorizar (previamente creado)
        $alias = 'afipsdkCertificado';

        // Id del web service a autorizar
        $wsid = 'wsfe';

        // Creamos una instancia de la libreria
        $afip = new Afip(array('CUIT' => $tax_id ));

        // Creamos la autorizacion (¡Paciencia! Esto toma unos cuantos segundos)
        $res = $afip->CreateWSAuth($username, $password, $alias, $wsid);

        // Mostramos el resultado por pantalla
        var_dump($res);
    }

    public function Emitidos() {

        // Certificado (Puede estar guardado en archivos, DB, etc)
        $cert = file_get_contents('/home/enzo/Escritorio/erp.softxplus.com/app/Http/Livewire/Compra/certificado.crt');

        // Key (Puede estar guardado en archivos, DB, etc)
        $key = file_get_contents('/home/enzo/Escritorio/erp.softxplus.com/app/Http/Livewire/Compra/key.key');

        // Tu CUIT
        // $tax_id = 30712141790;
        $tax_id = 20255083571;

        $afip = new Afip(array(
            'CUIT' => $tax_id,
            'cert' => $cert,
            'key' => $key
        ));

        $voucher_types = $afip->ElectronicBilling->GetVoucherTypes();
        $CbteTipo = $voucher_types[0]->Id;
        // dd($CbteTipo);

        $sales_points = $afip->ElectronicBilling->GetSalesPoints();
        $PtoVta = $sales_points[0]->Nro;
        // dd($PtoVta);
        /**
        * @param int $number 		Number of voucher to get information
        * @param int $sales_point 	Sales point of voucher to get information
        * @param int $type 			Type of voucher to get information */
        $number = 2;
        $sales_point = $PtoVta;
        $a = $afip->ElectronicBilling->GetVoucherInfo($number, $sales_point, $CbteTipo);
        dd($a);
    }

    public function Facturar(){

        // Certificado (Puede estar guardado en archivos, DB, etc)
        $cert = file_get_contents('/home/enzo/Escritorio/erp.softxplus.com/app/Http/Livewire/Compra/certificado.crt');

        // Key (Puede estar guardado en archivos, DB, etc)
        $key = file_get_contents('/home/enzo/Escritorio/erp.softxplus.com/app/Http/Livewire/Compra/key.key');

        // Tu CUIT
        // $tax_id = 30712141790;
        $tax_id = 20255083571;

        $afip = new Afip(array(
            'CUIT' => $tax_id,
            'cert' => $cert,
            'key' => $key
        ));


        // Para hacer pruebas
        // Tu CUIT
        // $tax_id = 20111111112;
        // $afip = new Afip(array('CUIT' => 20255083571));

        $voucher_types = $afip->ElectronicBilling->GetVoucherTypes();
        $CbteTipo = $voucher_types[0]->Id;
        // dd($CbteTipo);

        $sales_points = $afip->ElectronicBilling->GetSalesPoints();
        $PtoVta = $sales_points[0]->Nro;
        // dd($PtoVta);

        $DocTipos = $afip->ElectronicBilling->GetDocumentTypes();
        $DocTipo = $DocTipos[0]->Id;    // 0: CUIT  y 9:DNI
        // dd($DocTipos);

        $TiposIva = $afip->ElectronicBilling->GetAliquotTypes();
        $TipoIva = $TiposIva[2]->Id;   // El Concepto del arrary en la posición 2, tiene en su ID el valor 5 que es IVA al 21%
        // dd($TipoIva);

        $Conceptos = $afip->ElectronicBilling->GetConceptTypes();
        $Concepto = $Conceptos[2]->Id;  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
        // El Concepto del arrary en la posición 2, tiene en su ID el valor 3 que es Productos y Servicios
        // dd($Conceptos); 

        $Tributos = $afip->ElectronicBilling->GetTaxTypes();
        $Tributo = $Tributos[4]->Id; // El Concepto del arrary en la posición 4, tiene en su ID el valor 99 que es Otros Tributos
        $TributoDesc = $Tributos[4]->Desc; 
        // dd($Tributo);


        //$CbteNro = $afip->ElectronicBilling->GetLastVoucher($PtoVta,$CbteTipo);
        //dd($CbteNro);


        $data = array(
            'CantReg' 		=> 1, // Cantidad de comprobantes a registrar
            'PtoVta' 		=> $PtoVta, // Punto de venta
            'CbteTipo' 		=> $CbteTipo, // Tipo de comprobante (ver tipos disponibles) 
            'Concepto' 		=> $Concepto, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 		=> $DocTipo, // Tipo de documento del comprador (ver tipos disponibles)
            'DocNro' 		=> 20111111112, // Numero de documento del comprador
            'CbteDesde' 	=> 1, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> 1, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
            'CbteFch' 		=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 		=> 189.3, // Importe total del comprobante
            'ImpTotConc' 	=> 0, // Importe neto no gravado
            'ImpNeto' 		=> 150, // Importe neto gravado
            'ImpOpEx' 		=> 0, // Importe exento de IVA
            'ImpIVA' 		=> 31.5, //Importe total de IVA
            'ImpTrib' 		=> 7.8, //Importe total de tributos
            'FchServDesde' 	=> intval(date('Ymd')), // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'FchServHasta' 	=> intval(date('Ymd')), // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'FchVtoPago' 	=> intval(date('Ymd')), // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'MonId' 		=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 	    => 1, 
            'Tributos' 		=> array( // (Opcional) Tributos asociados al comprobante
                array(
                    'Id' 		=>  $Tributo, // Id del tipo de tributo (ver tipos disponibles) 
                    'Desc' 		=>  $TributoDesc, //'Ingresos Brutos', // (Opcional) Descripcion
                    'BaseImp' 	=> 150, // Base imponible para el tributo
                    'Alic' 		=> 5.2, // Alícuota
                    'Importe' 	=> 7.8 // Importe del tributo
                )
            ),
	        'Iva' 			=> array( // (Opcional) Alícuotas asociadas al comprobante
		        array(
			        'Id' 		=> $TipoIva, // Id del tipo de IVA (ver tipos disponibles) 
        			'BaseImp' 	=> 150, // Base imponible
		        	'Importe' 	=> 31.5 // Importe 
                ),
            ),
        );

        $a = $afip->ElectronicBilling->CreateNextVoucher($data);

        dd($a);

        // include('/home/enzo/Escritorio/erp.softxplus.com/app/Http/Livewire/Compra/ElectronicBilling.php');
        // $Factura = new ElectronicBilling('wsfe');
        // $sales_points = $Factura->GetDocumentTypes();

        // $sales_points = $afip->ElectronicBilling->GetDocumentTypes();
        // $sales_points = $afip->ElectronicBilling->GetAliquotTypes();
    }


    
}