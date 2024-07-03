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
use Afip;
use App\Models\Certificado;
use Illuminate\Support\Facades\Storage;


class VentaComponent extends Component
{

    public $areas, $cuentas, $ivas, $clientes;       // Globales
    public $empresa_id; public $tabActivo=1; public $venta_id;
    private $afip;
    public $certificado_tax_id, $certificado_crt, $certificado_key, $certificado_id, $certificado_alias;
    
    //Comprobantes
    public $iva_value=0;
    public $isModalOpen = false;
    public $giva=1;
    public $ModalDelete, $openModalDelete;
    public $ModalModify, $openModalModify;
    public $ModalAgregarDetalle, $openModalAgregarDetalle;
    public $ModalCerrarLibro;
    public $ModalGenerarFactura, $openModalGenerarFactura;
    public $gfecha,$gcliente, $gcomprobante, $gcuenta, $gdetalle, $ganio, $gmes, $garea, $gpartiva, $gbruto, $giva2, $gexento, $gimpinterno, $gperciva, $gretgan, $gperib, $gneto, $gmontopagado, $gcantidad;
    public $gselect_productos, $gprecio_prod, $gcantidad_prod, $glistado_prod;
    // public $gventa;
    //Variables del filtro
    public $gfmes, $gfcliente, $gfparticipa, $gfiva, $gfdetalle, $gfarea, $gfcuenta, $gfanio, $fgascendente, $gfsaldo; //Comprobantes
    
    // Deuda Clientes
    public $darea, $ddesde, $dhasta, $danio;
    public $DeudaClientesFiltro, $MostrarDeudaClientes; 
    public $deudaPDF;

    // Crédito Clientes
    public $carea, $cdesde, $chasta, $canio;
    public $CreditoClientesFiltro, $MostrarCreditoClientes;

    // Libros de Iva
    public $lmes,$lanio;
    public $MostrarLibros, $LibroFiltro;

    //Listado de filtros
    public $filtro;                 // Comprobantes

    public function render()
    {
        if (!is_null(session('empresa_id'))) { $this->empresa_id = session('empresa_id'); } 
        else { 
            $userid=auth()->user()->id;
            $empresas= EmpresaUsuario::where('user_id',$userid)->get();
            return view('livewire.empresa.empresa-component')->with('empresas', $empresas); 
        }

        $this->areas = Area::where('empresa_id', $this->empresa_id)->ORDERBy('name','asc')->get();
        $this->cuentas = Cuenta::where('empresa_id', $this->empresa_id)->ORDERBy('name','asc')->get();
        $this->clientes = Cliente::where('empresa_id', $this->empresa_id)->ORDERBy('name','asc')->get();
        $this->ivas = Iva::where('id','>',0)->get();
        // $this->productos = Producto::where('empresa_id', $this->empresa_id)->orderBy('name','asc')->get();

        $this->ConstructorFacturacion();

        return view('livewire.venta.venta-component');
    }

    public function openModalDelete() { $this->ModalDelete = true;  }
    public function closeModalDelete() { $this->ModalDelete = false;  }

    public function openModalCerrarLibro() { $this->ModalCerrarLibro = true;  }
    public function BackModalPopover() { $this->ModalCerrarLibro = false;  }

    public function openModalModify() { $this->ModalModify = true;  }
    public function closeModalModify() { $this->ModalModify = false;  }

    public function  openModalGenerarFactura() { $this->ModalGenerarFactura = true;  }
    public function closeModalGenerarFactura() { $this->ModalGenerarFactura = false;  }

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

    public function ConstructorFacturacion() {

        $certificados = Certificado::where('empresa_id','=',session('empresa_id'))->get();
        // console.log($certificados);
        if(count($certificados)) { 
            $this->certificado_id = $certificados[0]['id'];
            // dd($certificados[0]['id']);
            $this->certificado_tax_id = $certificados[0]['tax_id'];
            $this->certificado_alias = $certificados[0]['alias'];
            $this->certificado_crt = Storage::disk('local')->get('certificados/'.$certificados[0]['tax_id'].'_'.$certificados[0]['alias'].'.crt');
            $this->certificado_key = Storage::disk('local')->get('certificados/'.$certificados[0]['tax_id'].'_'.$certificados[0]['alias'].'.key');
            $this->afip = new Afip(array(
                'CUIT' => $this->certificado_tax_id,
                'cert' => $this->certificado_crt,
                'key' =>  $this->certificado_key,
                'access_token' => env('AFIP_ACCESS_TOKEN'),
            ));     
        }
        
    }
    public function GenerarFactura() {
        
        // CUIT del contribuyente
        $tax_id = 30712141790;
        $afip = new Afip(array(
            'CUIT' => $this->certificado_tax_id,
            'cert' => $this->certificado_crt,
            'key' =>  $this->certificado_key,
            'access_token' => env('AFIP_ACCESS_TOKEN'),
        ));

        $taxpayer_details = $afip->RegisterInscriptionProof->GetTaxpayerDetails($tax_id);
        dd($taxpayer_details);

        // $res['CAE']; //CAE asignado el comprobante
        // $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
        session()->flash('message3', 'Ingresó a generar factura. el valor del modal es '. $this->ModalGenerarFactura);
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
            'gcliente'        => 'required|integer',
        ]);
        $cerrado = Venta::where('PasadoEnMes','=',$this->gmes)
            ->where('Anio','=',$this->ganio)
            ->where('empresa_id','=',session('empresa_id'))
            ->where('Cerrado','>',0)
            ->get();
        if(!count($cerrado) || (count($cerrado) && $this->gpartiva='Si')) {
            Venta::create([
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
                'cliente_id'      => $this->gcliente,
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
        $comp = Venta::find($this->venta_id);
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
                'gcliente'        => 'required|integer',
            ]);
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
                'cliente_id'      => $this->gcliente,
            ]);
            //updateOrCreate
            $this->gfiltro();
            $this->closeModalModify();
            session()->flash('message2', $this->venta_id ? 'Comprobante Actualizado.' : 'No se pudo modificar.');
        }
    }

    public function delete() {
        $a = Venta::find($this->venta_id);
        if($a->Cerrado==0) { 
            $a->delete(); 
            $this->venta_id=null;
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
        
        $sql = $this->ProcesaSQLFiltro('ventas'); // Procesa los campos a mostrar
        $registros = DB::select(DB::raw($sql));       // Busca el recordset
        //Dibuja el filtro
        $Saldo=0;

        $this->filtro="
            <div class=\"table-responsive-sm\">
                <table class=\"table table-striped\" style=\"font-size:13px; padding: 0rem 0rem;\">
                <thead>
                  <tr>
                    <th scope=\"col\">Fecha</th>
                    <th scope=\"col\">Comprobante</th>
                    <th scope=\"col\">Cliente</th>
                    <th scope=\"col\"></th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Detalle</th>
                    <th scope=\"col\">Bruto</th>
                    <th scope=\"col\">Iva</th>
                    <th scope=\"col\">Exento</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Imp.Interno</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Percec.Iva</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Retenc.IB</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Retenc.Gan</th>
                    <th scope=\"col\">Neto</th>
                    <th scope=\"col\">Pagado</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Saldo</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Cant.Litros</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Partic.Iva</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Pasado EnMes</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Area</th>
                    <th class=\"col d-none d-sm-table-cell\" scope=\"col\">Cuenta</th>
                  </tr>
                </thead>";
            $Cantidad = 0; $MontoPagado = 0; $Neto = 0; $RetGan = 0; $RetIB = 0; $PerIva = 0; $Exento = 0 ;$ImpInterno = 0; $Bruto = 0; $MontoIvaT =0; $NetoT = 0;
        foreach($registros as $registro) {
            $Fecha = substr($registro->fecha,8,2) ."-". substr($registro->fecha,5,2) ."-". substr($registro->fecha,0,4);
            $Area=Area::find($registro->area_id);
            $Cuenta=Cuenta::find($registro->cuenta_id);
            $Iva=Iva::find($registro->iva_id);
            $Cliente=Cliente::find($registro->cliente_id);
            if($Iva->monto==0) { $MontoIva=0; } else {
                $MontoIva=($registro->BrutoComp*$Iva->monto/100);
            }
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
                <td>$Fecha</td>
                <td>$registro->comprobante</td>
                <td class=\" text-left\">$Cliente->name</td>";
                
                //Comprobante común de color gris
                if($registro->ParticIva=='No') { $this->filtro=$this->filtro."<td class=\" text-left\"><div style=\"background-color: lightslategray;width: 20px;border-radius: 7px;height: 20px;margin-right: 3px;\"></div></td>"; }
                // Si va a ser registrado en iva entonces
                if($registro->ParticIva=='Si') {
                    // Si está cerrado lo coloca de color marrón
                    if($registro->Cerrado>1) { $this->filtro=$this->filtro."<td class=\" text-left\"><div style=\"background-color: brown;width: 20px;border-radius: 7px;height: 20px;margin-right: 3px;\"></div></td>"; }                
                    // Si Es un comprobante que está preparado para ser enviado a AFIP
                    if($registro->Cerrado==0)  { $this->filtro=$this->filtro."<td class=\" text-left\"><div wire:click=\"openModalGenerarFactura();\" style=\"background-color: rgb(238, 238, 79);width: 20px;border-radius: 7px;height: 20px;margin-right: 3px;\" value=\" >\"> </div></td>"; }
                    // Si es un comprobante que ha sido enviado a AFIP pero todavía no se encuentra cerrado
                    if($registro->Cerrado==-1) { $this->filtro=$this->filtro."<td class=\" text-left\"><div style=\"background-color: rgb(242, 120, 120); width: 20px;border-radius: 7px;height: 20px;margin-right: 3px;\"><img src=\"images/archivo-pdf.svg\"></div></td>"; }
                }
                
                $this->filtro=$this->filtro."
                <td class=\" d-none d-sm-table-cell text-left\">$registro->detalle</td>
                <td class=\"col text-right\">".number_format($registro->BrutoComp, 2,'.','')."</td>
                <td class=\"col text-right\">".number_format($MontoIva, 2,'.','')."</td>
                <td class=\"col text-right\">".number_format($registro->ExentoComp, 2,'.','')."</td>
                <td class=\"col d-none d-sm-table-cell text-right\">".number_format($registro->ImpInternoComp, 2,'.','')."</td>
                <td class=\"col d-none d-sm-table-cell text-right\">".number_format($registro->PercepcionIvaComp, 2,'.','')."</td>
                <td class=\"col d-none d-sm-table-cell text-right\">".number_format($registro->RetencionIB, 2,'.','')."</td>
                <td class=\"col d-none d-sm-table-cell text-right\">".number_format($registro->RetencionGan, 2,'.','')."</td>
                <td class=\"col text-right\">".number_format($registro->NetoComp, 2,'.','')."</td>
                <td class=\"col text-right\">".number_format($registro->MontoPagadoComp, 2,'.','')."</td>
                <td class=\"col d-none d-sm-table-cell text-right\">".number_format($Saldo, 2,'.','')."</td>
                <td class=\"col d-none d-sm-table-cell text-right\">".number_format($registro->CantidadLitroComp, 2,'.','')."</td>
                <td class=\"col d-none d-sm-table-cell\">$registro->ParticIva</td>
                <td class=\"col d-none d-sm-table-cell text-right\">" . $this->ConvierteMesEnTexto($registro->PasadoEnMes) . "</td>
                <td class=\"col d-none d-sm-table-cell text-right\">$Area->name</td>
                <td class=\"col d-none d-sm-table-cell text-right\">$Cuenta->name</td>
            </tr>";
        }

        $this->filtro=$this->filtro."<tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Totales</td>
        <td class=\"col text-right\">".number_format($Bruto, 2,'.','')."</td>
        <td class=\"col text-right\">".number_format($MontoIvaT, 2,'.','')."</td>
        <td class=\"col text-right\">".number_format($Exento, 2,'.','')."</td>
        <td class=\"col d-none d-sm-table-cell text-right\">".number_format($ImpInterno, 2,'.','')."</td>
        <td class=\"col d-none d-sm-table-cell text-right\">".number_format($PerIva, 2,'.','')."</td>
        <td class=\"col d-none d-sm-table-cell text-right\">".number_format($RetIB, 2,'.','')."</td>
        <td class=\"col d-none d-sm-table-cell text-right\">".number_format($RetGan, 2,'.','')."</td>
        <td class=\"col text-right\">".number_format($NetoT, 2,'.','')."</td>
        <td class=\"col text-right\">".number_format($MontoPagado, 2,'.','')."</td>
        <td class=\"col d-none d-sm-table-cell text-right\">".number_format($Saldo, 2,'.','')."</td>
        <td class=\"col d-none d-sm-table-cell text-right\">".number_format($Cantidad, 2,'.','')."</td>
        <td class=\"col d-none d-sm-table-cell text-right\"></td>
        <td class=\"col d-none d-sm-table-cell text-right\"></td>
        <td class=\"col d-none d-sm-table-cell text-right\"></td>
        <td class=\"col d-none d-sm-table-cell text-right\"></td>
    </tr> 
    </tbody>
        </table>
        </div>
    </div>";
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

    public function ProcesaSQLFiltro($interfaz){
        $sql='';
        switch ($interfaz) {
            case "ventas" : {
                //Mes 	Cliente 	ParticipaIva 	Iva 	Detalle 	Area 	Cuenta 	Año 	Asc. C/Saldo
                if ($this->gfmes) $sql=" PasadoEnMes=" . $this->gfmes;
                if ($this->gfcliente) $sql=$sql ? $sql=$sql." and cliente_id=" . $this->gfcliente : " cliente_id=" . $this->gfcliente;
                if ($this->gfparticipa) $sql=$sql ? $sql=$sql." and ParticIva='" . $this->gfparticipa . "'" : " ParticIva='" . $this->gfparticipa . "'";
                if ($this->gfiva) $sql=$sql ? $sql=$sql." and iva_id=" . $this->gfiva : " iva_id=" . $this->gfiva;
                if ($this->gfdetalle) $sql=$sql ? $sql=$sql." and detalle='" . $this->gfdetalle . "'" : " detalle='" . $this->gfdetalle . "'";
                if ($this->gfarea) $sql=$sql ? $sql=$sql." and area_id=" . $this->gfarea : " area_id=" . $this->gfarea;
                if ($this->gfcuenta) $sql=$sql ? $sql=$sql." and cuenta_id=" . $this->gfcuenta : " cuenta_id=" . $this->gfcuenta;
                if ($this->gfanio) $sql=$sql ? $sql=$sql." and Anio=" . $this->gfanio : " Anio=" . $this->gfanio;
                $sql=$sql ? $sql=$sql." and empresa_id=" . session('empresa_id') : $sql." empresa_id=" . session('empresa_id');;
                //Fecha	Comprobante	Cliente	Detalle	Bruto	Iva	exento	Imp.Interno	Percec.Iva	Retenc.IB	Retenc.Gan	Neto	Pagado	Saldo	Cant.Litros	Partic.Iva	Pasado EnMes	Area	Cuenta
                $sql = "SELECT * FROM ventas WHERE" . $sql . " ORDER BY fecha, comprobante";
                if ($this->fgascendente) $sql=$sql . " ASC";
                // dd($sql);
                break;
            }
            case "deuda" : {
                if ($this->darea==0) { $darea=''; } else { $darea=' and ventas.area_id='.$this->darea; }  //Comprueba si se ha seleccionado un area en especìfico
                if ($this->danio==0) { $danio=''; } else { $danio=' and ventas.Anio='.$this->danio; }  //Comprueba si se ha seleccionado un año en especìfico
                    $sql = DB::table('ventas')
                    ->selectRaw('sum(NetoComp-MontoPagadoComp) as Saldo, clientes.id')
                    ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
                    ->groupBy('clientes.id')
                    ->where('ventas.fecha','>=',$this->ddesde)
                    ->where('ventas.fecha','<=',$this->dhasta)
                    ->where('ventas.empresa_id','=',session('empresa_id'))
                    ->get();
                $this->MostrarCreditoClientes=true;break;
            };
            case "credito" : {
                if ($this->carea==0) { $carea=''; } else { $carea=' and ventas.area_id='.$this->carea; }  //Comprueba si se ha seleccionado un area en especìfico
                if ($this->canio==0) { $canio=''; } else { $canio=' and ventas.Anio='.$this->canio; }  //Comprueba si se ha seleccionado un año en especìfico

                $sql = DB::table('ventas')
                    ->selectRaw('sum(NetoComp-MontoPagadoComp) as Saldo, clientes.id')
                    ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
                    ->groupBy('clientes.id')
                    ->where('ventas.fecha','>=',$this->cdesde)
                    ->where('ventas.fecha','<=',$this->chasta)
                    ->get();
                $this->MostrarCreditoClientes=true;break;
            };
            case "libro" : {
                $sql="SELECT PasadoEnMes, Max(Cerrado) as Cerrado FROM ventas WHERE ParticIva='Si' and Anio=" . $this->lanio . " and empresa_id='".session('empresa_id')."' GROUP BY PasadoEnMes, Anio";
                $this->MostrarLibros=true;break;
            };
        }
        return $sql;
    }

    public function gCargarRegistro($id) {
        $registro=Venta::find($id);
        $this->venta_id = $id;
        $this->id = $id; //Utilizado para buscar el registro para eliminar
        $this->gfecha= substr($registro->fecha,0,10);
        // $this->gventa=$registro->comprobante;
        $this->gcomprobante=$registro->comprobante;
        $this->gdetalle=$registro->detalle;
        $this->gbruto=number_format($registro->BrutoComp, 2, '.','');
        $this->gpartiva=$registro->ParticIva;
        $a=Iva::find($registro->iva_id);
        $this->iva_value= $a->monto;
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
        $this->gcliente=$registro->cliente_id;

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
            'gcliente'        => 'required|integer',
        ]);
    }

    public function CalcularIva() {
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
    }

    public function CalcularNeto() {
        $this->gneto = number_format( floatval($this->gbruto) + floatval($this->giva2) + floatval($this->gexento) + floatval($this->gimpinterno) + floatval($this->gperciva) + floatval($this->gperib) + floatval($this->gretgan),2 ,'.','' ) ;
    }

    public function CalcularDeudaClientes($ret) {
        $registros = $this->ProcesaSQLFiltro('deuda'); // Procesa los campos a mostrar

        $this->deudaPDF = $registros;
        //Dibuja el filtro
        $Saldo=0;
        $this->DeudaClientesFiltro = "<table class=\"mt-6\" style=\"width:300px\">
            <tr class=\"bg-blue-200 border border-blue-500\">
                <td class=\"center bg-gray-300\">Nombre</td>
                <td class=\"center bg-gray-300\">Deuda</td>
            </tr>";
        foreach($registros as $registro) {
            if ($registro->Saldo>1) {
                $cliente = Cliente::find($registro->id);
                $this->DeudaClientesFiltro = $this->DeudaClientesFiltro .
                "<tr>
                    <td class=\"bg-gray-100 border border-blue-500 text-left\">
                        $cliente->name
                        <div class=\"tooltip\">
                            <span class=\"tooltiptext\">
                                Teléfono: ".$cliente->telefono."<br>
                                Email: ".$cliente->email."
                            </span>
                        </div>
                    </td>
                    <td class=\"bg-gray-100 border border-blue-500 text-right\">" . number_format($registro->Saldo,2,',','.') . "
                    </td>
                </tr>";
            //     "<tr>
            //     <td class=\"bg-gray-100 border border-blue-500 text-left tooltip\"><span class=\"tooltiptext\">
            //     Teléfono: ".$cliente->telefono."<br>Email: ".$cliente->email."</span>" . $cliente->name . "</td>
            //     <td class=\"bg-gray-100 border border-blue-500 text-right\">" . number_format($registro->Saldo,2,',','.') . "</td>
            // </tr>";
                $Saldo = $Saldo + $registro->Saldo;
            }
        }
        $this->DeudaClientesFiltro = $this->DeudaClientesFiltro .
            "<tr class=\"bg-green-500 w-36\">
                <td class=\"colspan-2 bg-gray-300\">Total Deuda</td>
                <td class=\"text-right bg-gray-300\"><b>".number_format($Saldo,2,',','.')."</b></td>
            </tr>
            </table>";
        if($ret) return $this->DeudaClientesFiltro;
    }

    public function CalcularCreditoClientes() {
        $registros = $this->ProcesaSQLFiltro('credito'); // Procesa los campos a mostrar
        //Dibuja el filtro
        $Saldo=0;
        $this->CreditoClientesFiltro = "<table class=\"mt-6\" style=\"width:300px\">
            <tr class=\"bg-blue-200 border border-blue-500\">
                <td class=\"center bg-gray-400\">Nombre</td>
                <td class=\"center bg-gray-400\">Crédito</td>
            </tr>";
        foreach($registros as $registro) {
            if($registro->Saldo<1) {
                $cliente = Cliente::find($registro->id);
                $this->CreditoClientesFiltro = $this->CreditoClientesFiltro .
                "<tr>
                    <td class=\"bg-gray-100 border border-blue-500 text-left tooltip\" wire:click=\"copy(".$cliente->id.")\"><span class=\"tooltiptext\">
                    Teléfono: ".$cliente->telefono."<br>Email: ".$cliente->email."</span>" . $cliente->name . "</td>
                    <td class=\"bg-gray-100 border border-blue-500 text-right\">" . number_format($registro->Saldo * -1 ,2,',','.') . "</td>
                </tr>";
                $Saldo = $Saldo + $registro->Saldo * -1;
            }
        }
        $this->CreditoClientesFiltro = $this->CreditoClientesFiltro .
            "<tr class=\"bg-green-500\">
                <td class=\"colspan-2 bg-gray-400\">Total Crédito</td>
                <td class=\"bg-gray-400 text-right\"><b>".number_format($Saldo,2,',','.')."</b></td>
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
        $registros = DB::table('ventas')              // Busca la última página utilizada para la empresa seleccionada
            ->where('empresa_id','=',session('empresa_id'))
            ->where('ParticIva','=','Si')
            ->groupByRaw('Anio,PasadoEnMes,Cerrado')
            ->orderBy('Cerrado')
            ->get();
        $UltimaPaginaCerrada = $registros->last()->Cerrado; // Asigna el valor en $UltimaPaginaCerrada
        $registros = DB::table('ventas')        // Carga todos los registros que van a ser modificados que corresponden al mes y año
            ->where('Anio','=',$this->lanio)
            ->where('PasadoEnMes','=',$this->lmes)
            ->where('empresa_id','=',session('empresa_id'))
            ->where('ParticIva','=','Si')
            ->orderByRaw('fecha,comprobante,BrutoComp')
            ->get();
        $UltimaPaginaCerrada++;
        foreach($registros as $registro) {
            $reg = Venta::find($registro->id);
            if ($i==35) { $UltimaPaginaCerrada++; $i=0; }
            $reg->Cerrado = $UltimaPaginaCerrada;
            $reg->save();
            $i++;
        }
        $this->MostrarLibros();
    }

    public function agregar_detalle() {
        
        $this->validate([
            'venta_id'    => 'required',
            'gselect_productos' => 'required|numeric',
            'gcantidad_prod'    => 'required|numeric',
            'gprecio_prod'      => 'required|numeric',
        ]);
        $detalle = new Ventas_Productos;
        $detalle->ventas_id = $this->venta_id;
        $detalle->productos_id = $this->gselect_productos;
        $detalle->cantidad = $this->gcantidad_prod;
        $detalle->precio = $this->gprecio_prod;
        $detalle->user_id = $userid=auth()->user()->id;

        $detalle->save();

        // Decrementa la cantidad de stock del producto porque es una venta
        $producto = Producto::find($this->gselect_productos);
        $producto->existencia = $producto->existencia - abs($this->gcantidad_prod);
        $producto->save();

        $this->listado_productos();
    }

    public function eliminar_detalle($id_detalle) {
        
        //Encuentra el detalle a eliminar pasa buscar la cantidad que tiene que eliminar
        $eliminar = Ventas_Productos::find($id_detalle);
        $cant_a_eliminar = $eliminar->cantidad*-1;

        $detalle = new Ventas_Productos;
        $detalle->ventas_id = $this->venta_id;
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
        $this->glistado_prod = Ventas_Productos::join('productos','ventas__productos.productos_id','productos.id')
        ->where('ventas_id',$this->venta_id)
        ->get(['ventas__productos.*','productos.name']);
    }

    public function copiarMontoPagado() {
        $this->gmontopagado= $this->gneto;
    }
}
