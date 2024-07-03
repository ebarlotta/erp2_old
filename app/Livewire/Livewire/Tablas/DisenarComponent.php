<?php

namespace App\Http\Livewire\Tablas;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Tabla;
use App\Models\Venta;
use App\Models\Comprobante;

use App\Models\Area;
use App\Models\Cuenta;
use App\Models\Registro;
use App\Models\TablaUsuario;
use Illuminate\Support\Facades\DB;



class DisenarComponent extends Component
{
    public $name, $cantidadfila, $cantidadcolumna, $encabezadocolumna;
    public $ListadeTablas;
    public $mostrartabla=null;
    public $tabla_id, $registro_id;

    public $txttitulo, $txtcantidadfila, $txtcantidadcolumna;
    public $txtfila, $txtcolumna, $txtcolorfondo, $txtalineacion, $txtexpresion;
    
    public function render()
    {
        $this->CargarTablas();
        return view('livewire.tablas.disenar-component',['datos'=> $this->ListadeTablas])->extends('layouts.adminlte');
    }

    public function Resetear() {
        $this->reset('cantidadfila','cantidadcolumna','encabezadocolumna','name');
    }

    public function store() {
        $this->validate([
            'name' => 'required',
            'cantidadfila' => 'required|integer',
            'cantidadcolumna' => 'required|integer',
            'encabezadocolumna' => 'required',
        ]);
        Tabla::updateOrCreate(['id' => $this->id], [
            'name' => $this->name,
            'cantidadfila' => $this->cantidadfila,
            'cantidadcolumna' => $this->cantidadcolumna,
            'encabezadocolumna' => $this->encabezadocolumna,
            'empresa_id' => session('empresa_id'),
        ]);

        session()->flash('message', $this->id ? 'Tabla Actualizada.' : 'Tabla Creada.');
        $this->Resetear();
        $this->CargarTablas();
    }

    public function CargarTablas() {
        $this->ListadeTablas = TablaUsuario::join('tablas', 'tabla_usuarios.tabla_id','=', 'tablas.id')
        ->where('tablas.empresa_id','=',session('empresa_id'))
        ->where('tabla_usuarios.user_id','=',auth()->user()->id)
        ->get();
    }

    public function DibujarTabla($id) {
        $this->tabla_id = $id;
        $tabla = Tabla::find($id);
        $this->mostrartabla = true;
        $this->cantidadfila = $this->txtcantidadfila = $tabla->cantidadfila;
        $this->cantidadcolumna = $this->txtcantidadcolumna = $tabla->cantidadcolumna;
        $this->encabezadocolumna = $this->txttitulo = $tabla->encabezadocolumna;
        $a = Registro::where('tabla_id',$id)
        ->orderby('fila')
        ->orderby('columna')
        ->get();

        
        // $aux = '';
        // dd($this->cantidadcolumna);
        $p = 0;
        $aux = '<table style="width:100%; border: 1px solid black;\">';
        for($i=1;$i<=$this->cantidadfila;$i++) {
            $aux = $aux . '<tr>';
            for($j=1;$j<=$this->cantidadcolumna;$j++) {
                if(substr($a[$p]['expresion'],0,1)=='*') {
                    $sql = substr($a[$p]['expresion'],1,strlen($a[$p]['expresion']));
                    $reg = db::select($sql);
                    $dato = $reg[0]->dato;
                } else {
                    $dato= $a[$p]['expresion'];
                }
                $aux = $aux . '<td style="border: 1px solid black; background-color:'.$a[$p]['colorfondocelda'].'; text-align: '.$a[$p]['alineacion'].';" wire:click="CargarDato('.$i.','.$j.','.$a[$p]['id'].')">' . $dato . '</td>';
                $p++;
            }
            $aux = $aux . '</tr>';
        }
        $this->mostrartabla = $aux . '</table>';
        // dd($this->mostrartabla);
    }

    public function CargarDato($i, $j, $dato_id) {
        $dato = Registro::where('tabla_id',$this->tabla_id)
        ->where('fila',$i)
        ->where('columna',$j)
        ->get();
        $this->registro_id = $dato_id;
        // dd($dato);
        $this->txtfila=$dato[0]['fila'];
        $this->txtcolumna=$dato[0]['columna'];
        $this->txtcolorfondo=$dato[0]['colorfondocelda'];
        $this->txtexpresion=$dato[0]['expresion'];
        $this->txtalineacion=$dato[0]['alineacion'];
        // dd($dato);
    }

    public function ActualizarDato() {
        $this->validate([
            'txtfila' => 'required|integer',
            'txtcolumna' => 'required|integer',
            'txtcolorfondo' => 'required',
            'txtexpresion' => 'required',
            'txtalineacion' => 'required',
        ]);
// dd( $this->registro_id);

        Registro::updateOrCreate(['id' => $this->registro_id], [
            'fila' => $this->txtfila,
            'columna' => $this->txtcolumna,
            'colorfondocelda' => $this->txtcolorfondo,
            'expresion' => $this->txtexpresion,
            'alineacion' => $this->txtalineacion,
        ]);
        $this->DibujarTabla($this->tabla_id );
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

    public function Visualizar($NombreInforme) {
        switch ($NombreInforme) {
            case 'Ventas':
                $BrutoComp = 0;
                $ExentoComp = 0;
                $ImpInternoComp = 0;
                $PercepcionIvaComp = 0;
                $RetencionIB = 0;
                $RetencionGan = 0;
                $NetoComp = 0;
                $Iva10 = 0;
                $Iva21 = 0;
                $Iva27 = 0;
                $SumIva = 0;
                
                $this->visualizar = '<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 10px; font-weight : 300;" border="1">
                <tbody>
                <tr style="font-weight:bold; border-top: 3px solid; font-size:14px">
                    <td colspan=9>Libros IVA Ventas 2024</td>
                </tr>
                <tr style="font-weight:bold; border-top: 3px solid;">
                    <td bgcolor="white" align="center">Meses</td>
                    <td bgcolor="white" align="center">Bruto</td>
                    <td bgcolor="white" align="center">Iva</td>
                    <td bgcolor="white" align="center">Exentos</td>
                    <td bgcolor="white" align="center">Imp.Int.</td>
                    <td bgcolor="white" align="center">Ret/Perc IVA</td>
                    <td bgcolor="white" align="center">Ret/Perc Ganancias</td>
                    <td bgcolor="white" align="center">Ret/Perc IB</td>
                    <td bgcolor="white" align="center">Neto</td>
                </tr>';
                
                for($i=1;$i<=12;$i++) {
                    $sql = Venta::selectraw('sum(BrutoComp) as BrutoComp, sum(ExentoComp) as ExentoComp, sum(ImpInternoComp) as ImpInternoComp, sum(PercepcionIvaComp) as PercepcionIvaComp, sum(RetencionIB) as RetencionIB, sum(RetencionGan) as RetencionGan, sum(NetoComp) as NetoComp, sum(ivas.monto*BrutoComp/100) as SumIva, count(ivas.monto) as CantReg')
                    ->join('ivas','ivas.id', '=', 'ventas.iva_id')
                    ->where('PasadoEnMes','=',$i)
                    ->where('ParticIva','=','Si')
                    ->where('Anio','=',2024)
                    ->where('empresa_id','=',session('empresa_id'))
                    ->get();
                    foreach($sql as $sql1) {
                        $this->visualizar = $this->visualizar .'
                    <tr>
                        <td bgcolor="white" align="left">'. $this->ConvierteMesEnTexto($i).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->BrutoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->SumIva,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->ExentoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->ImpInternoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->PercepcionIvaComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->RetencionIB,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->RetencionGan,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->NetoComp,2).'</td>
                    </tr>';
                    $BrutoComp = $BrutoComp + $sql1->BrutoComp;
                    $ExentoComp = $ExentoComp + $sql1->ExentoComp;
                    $ImpInternoComp = $ImpInternoComp + $sql1->ImpInternoComp;
                    $PercepcionIvaComp = $PercepcionIvaComp + $sql1->PercepcionIvaComp;
                    $RetencionIB = $RetencionIB + $sql1->RetencionIB;
                    $RetencionGan = $RetencionGan + $sql1->RetencionGan;
                    $NetoComp = $NetoComp + $sql1->NetoComp;
                    $SumIva = $SumIva + $sql1->SumIva;
                    }
                    
                }
                $this->visualizar = $this->visualizar .'
                <tr style="font-weight:bold; border-top: 3px solid;">
                    <td bgcolor="white" align="left">Totales</td>
                    <td bgcolor="white" align="right">'.number_format($BrutoComp,2).'</td>
                    <td bgcolor="white" align="right">'.number_format($SumIva,2).'</td>
                    <td bgcolor="white" align="right">'.number_format($ExentoComp,2).'</td>
                    <td bgcolor="white" align="right">'.number_format($ImpInternoComp,2).'</td>
                    <td bgcolor="white" align="right">'.number_format($PercepcionIvaComp,2).'</td>
                    <td bgcolor="white" align="right">'.number_format($RetencionIB,2).'</td>
                    <td bgcolor="white" align="right">'.number_format($RetencionGan,2).'</td>
                    <td bgcolor="white" align="right">'.number_format($NetoComp,2).'</td>
                </tr>';            
                break;
            case 'Compras':
                $BrutoComp = 0;
                $ExentoComp = 0;
                $ImpInternoComp = 0;
                $PercepcionIvaComp = 0;
                $RetencionIB = 0;
                $RetencionGan = 0;
                $NetoComp = 0;
                $Iva10 = 0;
                $Iva21 = 0;
                $Iva27 = 0;
                $SumIva = 0;

                //$this->visualizar = $this->AgregarEncabezado('Libros IVA Compras');
                $this->visualizar = '<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 10px; font-weight : 300;" border="1">
                <tbody>
                <tr style="font-weight:bold; border-top: 3px solid; font-size:14px">
                    <td colspan=9>Libros IVA Compras 2024</td>
                </tr>
                <tr style="font-weight:bold; border-top: 3px solid;">
                    <td bgcolor="white" align="center">Meses</td>
                    <td bgcolor="white" align="center">Bruto</td>
                    <td bgcolor="white" align="center">Iva</td>
                    <td bgcolor="white" align="center">Exentos</td>
                    <td bgcolor="white" align="center">Imp.Int.</td>
                    <td bgcolor="white" align="center">Ret/Perc IVA</td>
                    <td bgcolor="white" align="center">Ret/Perc Ganancias</td>
                    <td bgcolor="white" align="center">Ret/Perc IB</td>
                    <td bgcolor="white" align="center">Neto</td>
                </tr>';
                
                for($i=1;$i<=12;$i++) {
                    $sql = Comprobante::selectraw('sum(BrutoComp) as BrutoComp, sum(ExentoComp) as ExentoComp, sum(ImpInternoComp) as ImpInternoComp, sum(PercepcionIvaComp) as PercepcionIvaComp, sum(RetencionIB) as RetencionIB, sum(RetencionGan) as RetencionGan, sum(NetoComp) as NetoComp, sum(ivas.monto*BrutoComp/100) as SumIva, count(ivas.monto) as CantReg')
                    ->join('ivas','ivas.id', '=', 'comprobantes.iva_id')
                    ->where('PasadoEnMes','=',$i)
                    ->where('ParticIva','=','Si')
                    ->where('Anio','=',2024)
                    ->where('empresa_id','=',session('empresa_id'))
                    ->get();
                    foreach($sql as $sql1) {
                        $this->visualizar = $this->visualizar .'
                    <tr>
                        <td bgcolor="white" align="left">'. $this->ConvierteMesEnTexto($i).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->BrutoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->SumIva,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->ExentoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->ImpInternoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->PercepcionIvaComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->RetencionIB,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->RetencionGan,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($sql1->NetoComp,2).'</td>
                    </tr>';
                    $BrutoComp = $BrutoComp + $sql1->BrutoComp;
                    $ExentoComp = $ExentoComp + $sql1->ExentoComp;
                    $ImpInternoComp = $ImpInternoComp + $sql1->ImpInternoComp;
                    $PercepcionIvaComp = $PercepcionIvaComp + $sql1->PercepcionIvaComp;
                    $RetencionIB = $RetencionIB + $sql1->RetencionIB;
                    $RetencionGan = $RetencionGan + $sql1->RetencionGan;
                    $NetoComp = $NetoComp + $sql1->NetoComp;
                    $SumIva = $SumIva + $sql1->SumIva;
                    }
                    
                }
                    $this->visualizar = $this->visualizar .'
                    <tr style="font-weight:bold; border-top: 3px solid;">
                        <td bgcolor="white" align="left">Totales</td>
                        <td bgcolor="white" align="right">'.number_format($BrutoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($SumIva,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($ExentoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($ImpInternoComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($PercepcionIvaComp,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($RetencionIB,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($RetencionGan,2).'</td>
                        <td bgcolor="white" align="right">'.number_format($NetoComp,2).'</td>
                    </tr>';
            break;
            case "Paretto":
                    $a = $this->AgregarEncabezado('Areas-Compras');
                    $totalNetoCompra = Comprobante::where('Anio', 2021)
                        ->where('empresa_id', session('empresa_id'))
                        ->sum('NetoComp');

                        $sql = Comprobante::selectraw('sum(NetoComp) as NetoComp, area_id')    
                        ->join('areas', 'areas.id', '=', 'comprobantes.area_id')
                        ->whereraw('comprobantes.empresa_id=' . session('empresa_id'))
                        ->whereraw('Anio=2021')
                        ->groupby('comprobantes.area_id')
                        ->orderby('NetoComp', 'desc')
                        ->get();

                        // dd($sql);
                    // $sql = DB::table('comprobantes')
                    // ->join('areas', 'areas.id', '=', 'comprobantes.area_id')
                    // ->whereraw('comprobantes.empresa_id','=', session('empresa_id'))
                    // ->whereraw('Anio','=', 2021)
                    // ->selectraw('sum(NetoComp) as NetoComp, area_id')
                    // ->groupby('area_id')
                    // ->orderby('NetoComp', 'desc')
                    // ->get();
                
               
                    $a = $a .'<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 10px; font-weight : 300;" border="1">
                    <tbody>
                    <tr>
                        <td bgcolor="white" align="center">Areas</td>
                        <td bgcolor="white" align="center">Porcentaje</td>
                        <td bgcolor="white" align="center">Neto</td>
                    </tr>';
                    foreach($sql as $sql1) {
                        $area = Area::find($sql1->area_id);
                        $a = $a .'
                        <tr>
                            <td bgcolor="white" align="left">'.$area->name.'</td>
                            <td bgcolor="white" align="right">'.number_format($sql1->NetoComp*100/$totalNetoCompra,2).'%</td>
                            <td bgcolor="white" align="right">'.number_format($sql1->NetoComp,2).'</td>
                        </tr>';
                    }
                    $a = $a .'
                        <tr>
                            <td bgcolor="white" align="left">Total</td>
                            <td bgcolor="white" align="right">100%</td>
                            <td bgcolor="white" align="right">'.number_format($totalNetoCompra,2).'</td>
                        </tr>
                        </tbody>
                        </table>';

                        // Paretto Areas-Ventas

                        $b = $this->AgregarEncabezado('Areas-Ventas');
                        $totalNetoVenta = Venta::where('Anio', 2021)
                            ->where('empresa_id', session('empresa_id'))
                            ->sum('NetoComp');
    
                        $sql = DB::table('ventas')
                        ->join('areas', 'areas.id', '=', 'ventas.area_id')
                        ->whereraw("ventas.empresa_id", session('empresa_id'))
                        ->whereraw('Anio', 2021)
                        ->selectraw('sum(NetoComp) as NetoComp, area_id')
                        ->groupby('area_id')
                        ->orderby('NetoComp', 'desc')
                        ->get();
                    
                   
                        $b = $b .'<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 10px; font-weight : 300;" border="1">
                        <tbody>
                        <tr>
                            <td bgcolor="white" align="center">Areas</td>
                            <td bgcolor="white" align="center">Porcentaje</td>
                            <td bgcolor="white" align="center">Neto</td>
                        </tr>';
                        foreach($sql as $sql1) {
                            $area = Area::find($sql1->area_id);
                            $b = $b .'
                            <tr>
                                <td bgcolor="white" align="left">'.$area->name.'</td>
                                <td bgcolor="white" align="right">'.number_format($sql1->NetoComp*100/$totalNetoVenta,2).'%</td>
                                <td bgcolor="white" align="right">'.number_format($sql1->NetoComp,2).'</td>
                            </tr>';
                        }
                        $b = $b .'
                            <tr>
                                <td bgcolor="white" align="left">Total</td>
                                <td bgcolor="white" align="right">100%</td>
                                <td bgcolor="white" align="right">'.number_format($totalNetoVenta,2).'</td>
                            </tr>
                            </tbody>
                            </table>';
    
                            // Paretto Cuentas-Compras

                        $c = $this->AgregarEncabezado('Cuentas-Compras');
                        // $totalNetoCompra = Comprobante::where('Anio', 2021)
                        //     ->where('empresa_id', session('empresa_id'))
                        //     ->sum('NetoComp');
    
                        $sql = DB::table('comprobantes')
                        ->join('cuentas', 'cuentas.id', '=', 'comprobantes.cuenta_id')
                        ->whereraw("comprobantes.empresa_id", session('empresa_id'))
                        ->whereraw('Anio', 2021)
                        ->selectraw('sum(NetoComp) as NetoComp, cuenta_id')
                        ->groupby('cuenta_id')
                        ->orderby('NetoComp', 'desc')
                        ->get();
                    
                   
                        $c = $c .'<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 10px; font-weight : 300;" border="1">
                        <tbody>
                        <tr>
                            <td bgcolor="white" align="center">Cuentas</td>
                            <td bgcolor="white" align="center">Porcentaje</td>
                            <td bgcolor="white" align="center">Neto</td>
                        </tr>';
                        foreach($sql as $sql1) {
                            $cuenta = Cuenta::find($sql1->cuenta_id);
                            $c = $c .'
                            <tr>
                                <td bgcolor="white" align="left">'.$cuenta->name.'</td>
                                <td bgcolor="white" align="right">'.number_format($sql1->NetoComp*100/$totalNetoCompra,2).'%</td>
                                <td bgcolor="white" align="right">'.number_format($sql1->NetoComp,2).'</td>
                            </tr>';
                        }
                        $c = $c .'
                            <tr>
                                <td bgcolor="white" align="left">Total</td>
                                <td bgcolor="white" align="right">100%</td>
                                <td bgcolor="white" align="right">'.number_format($totalNetoCompra,2).'</td>
                            </tr>
                            </tbody>
                            </table>';
        
                                // Paretto Cuentas-Ventas

                        $d = $this->AgregarEncabezado('Cuentas-Ventas');
                        // $totalNetoCompra = Venta::where('Anio', 2021)
                        //     ->where('empresa_id', session('empresa_id'))
                        //     ->sum('NetoComp');
    
                        $sql = DB::table('ventas')
                        ->join('cuentas', 'cuentas.id', '=', 'ventas.cuenta_id')
                        ->whereraw("ventas.empresa_id", session('empresa_id'))
                        ->whereraw('Anio', 2021)
                        ->selectraw('sum(NetoComp) as NetoComp, cuenta_id')
                        ->groupby('cuenta_id')
                        ->orderby('NetoComp', 'desc')
                        ->get();
                    
                   
                        $d = $d .'<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 10px; font-weight : 300;" border="1">
                        <tbody style="width:100%;">
                            <tr>
                                <td bgcolor="white" align="center">Cuentas</td>
                                <td bgcolor="white" align="center">Porcentaje</td>
                                <td bgcolor="white" align="center">Neto</td>
                            </tr>';
                        foreach($sql as $sql1) {
                            $cuenta = Cuenta::find($sql1->cuenta_id);
                            $d = $d .'
                            <tr>
                                <td bgcolor="white" align="left">'.$cuenta->name.'</td>
                                <td bgcolor="white" align="right">'.number_format($sql1->NetoComp*100/$totalNetoVenta,2).'%</td>
                                <td bgcolor="white" align="right">'.number_format($sql1->NetoComp,2).'</td>
                            </tr>';
                        }
                        $d = $d .'
                            <tr>
                                <td bgcolor="white" align="left">Total</td>
                                <td bgcolor="white" align="right">100%</td>
                                <td bgcolor="white" align="right">'.number_format($totalNetoVenta,2).'</td>
                            </tr>
                        </tbody>
                        </table>';
                        //Resumen 

                        $r = $this->AgregarEncabezado('Paretto');
                        $r = '<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 10px; font-weight : 300;" border="1">
                        <tbody>
                        <tr>
                            <td bgcolor="white" align="center">Areas</td>
                            <td bgcolor="white" align="center">Cuentas</td>
                        </tr>';
                        $r = $r .'
                        <tr>
                            <td bgcolor="white" align="center">'.$a.'</td>
                            <td bgcolor="white" align="center">'.$c.'</td>
                        </tr>
                        <tr>
                            <td bgcolor="white" align="center">'.$b.'</td>
                            <td bgcolor="white" align="center">'.$d.'</td>
                        </tr>
                        </tbody>
                        </table>';

                        
                $this->visualizar = $r;
                break;
        }
    }
    
    
    public function AgregarEncabezado($Titulo) {
        $a = '<table class="table table-responsive table-hover" style="font-family : Verdana; font-size : 8px; font-weight : 300;" cellspacing="0" cellpadding="0" bgcolor="#5C92FF" border="0">
        <tbody>
        
        <tr>
            <td align="center"><font color="#444" face="verdana,arial,helvetica" size="2">
            <b>'.$Titulo.'</b>
        <tr>
        ';
        return $a;
        // <td><img src="images/pixeltrans.gif" width="1" height="1"></td>
        // <tr>
        //     <td width="11"><img src="images/sup-izq.gif" width="11" height="11"></td>
        //     <td width="278"><img src="images/pixeltrans.gif" width="278" height="1"></td>
        //     <td width="11" align="right"><img src="images/sup-der.gif" width="11" height="11"></td>
        // </tr>
    }
}

