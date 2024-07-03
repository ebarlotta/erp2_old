<?php

namespace App\Http\Livewire\Haberes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Recibo;
use App\Http\Livewire\Haberes\clsRecibo as clsRecibo;
use App\Models\Categoriaprofesional;
use App\Models\Concepto;
use App\Models\ConceptoRecibo;
use App\Models\Empleado;
use App\Models\Empresa;
use Hamcrest\Type\IsNumeric;

class HaberesComponent extends Component
{
    public $anio = 2024;
    public $mes = '00';
    public $EmpleadosActivos;
    // public $EmpleadoActivo;

    //Datos propios del recibo seleccionado
    public $empleadoseleccionado = 0;
    public $IdRecibo;
    public $LugarPago;
    public $FechaPago;
    public $PerPago;
    public $IdEmpleado = 0;
    public $IdCatProfe = 0;
    public $TotHaberes = 0;
    public $Rem = 0;
    public $NoRem = 0;
    public $Descuentos = 0;
    public $PerUltLiq = 0;
    public $FechaUltLiq = 0;
    public $Conceptos;  //Recordset de Conceptos del recibo
    public $SumHaberes;
    public $SumUnidades;
    public $TotalRemu;
    public $RB; //Utilizado para sumarizar la Remuneración Bruta a la cual hacerle los descuentos
    public $AcumRem;
    public $AcumNoRem;
    public $AcumDescuento;
    public $NetoACobrar;
    public $NetoACobrarLetras;


    //Datos propios de la empresa
    public $NombreEmpresa;
    public $EmpresaId;
    public $Cuit;
    public $DireccionEmpresa;

    //Datos propios del Empleado
    public $NombreEmpleado;
    public $Cuil;
    public $FechaIngreso;
    public $Antiguedad;
    public $Legajo;
    public $Seccion;
    public $Banco;
    public $CategoriaProfesional_id;
    public $xMensualizado;
    public $xJornalizado;
    public $xHora;
    public $xUnidad;

    //Datos propios de la Categoría Profesional
    public $NombreCategoria;
    public $NombreSubCategoria;
    public $CCT;
    public $xPrecioMes;
    public $xPrecioDia;
    public $xPrecioHora;
    public $xPrecioUnidad;

    //Modales
    public $ModalAgregar=false;
    public $ModificarEscalaShow=false;
    public $ModificarConceptoShow=false;
    public $GestionarConceptos=false;
    public $EliminarConceptoReciboShow=false;

    // Propio de los items cargados para liquidar
    public $item_id;
    public $items;
    public $item;
    public $cantidad;
    public $cmbitem;

    public $name;
    public $orden;
    public $unidad;
    public $haberes=false;
    public $remunerativo=false;
    public $noremunerativo=false;
    public $descuento=false;
    public $activo=false;
    public $montofijo;
    public $calculo;
    public $montomaximo;

    public $chkTodos=false;
    public $chkActivos=true;  // Al cargar el formulario muestra sólo los items activos

    //Propio de Modificar Categoría
    public $cmbOpcionCatProf;

    // Recibos de Sueldo
    public $lmes,$lanio, $empleado;

    public function render()
    {
        $this->EmpresaId = session('empresa_id');
        return view('livewire.haberes.haberes-component');
    }

    public function CargarEmpleadosActivosEnEsePeriodo()
    {
        //dd(session('empresa_id'));
        $this->EmpleadosActivos = null;
        $this->IdRecibo = null;  // Borra el id de recibo para que se borre la pantalla con recibos
        if($this->mes == 13 or $this->mes == 14 or $this->mes == 15 ) {
            $this->EmpleadosActivos = DB::table('empleados')
            ->select('empleados.name', 'empleados.id', 'empleados.activo')
            ->leftjoin('empresas', 'empresas.id', 'empleados.empresa_id')
            ->where('activo',  1)
            ->where('empresas.id',  session('empresa_id'))
            // ->groupBy('Nombre')
            ->orderby('empleados.name')
            ->get();
            $this->EmpleadosActivos = json_decode(json_encode($this->EmpleadosActivos), true);

        } else {
        $this->EmpleadosActivos = DB::table('empleados')
            ->select('empleados.name', 'empleados.id', 'empleados.activo')
            ->leftjoin('recibos', 'empleado_id', 'empleados.id')
            ->leftjoin('empresas', 'empresas.id', 'empleados.empresa_id')
            ->where('activo',  1)
            ->where('recibos.perpago', $this->anio . $this->mes)
            ->where('empresas.id',  session('empresa_id'))
            // ->groupBy('Nombre')
            ->orderby('empleados.name')
            ->get();
        $this->EmpleadosActivos = json_decode(json_encode($this->EmpleadosActivos), true);
        }
        //dd($this->EmpleadosActivos);
    }

    public function cargaIdEmpleado($id) {
        $this->empleadoseleccionado = $id;
        //$this->CargarEmpleadosActivosEnEsePeriodo();
        // $this->CargarEmpleadosTipoYCategoria($id);
        $this->CargarDatosRecibo($this->anio . $this->mes, $this->empleadoseleccionado);
    }

    public function CargarDatosRecibo($Periodo, $IdEmpleado) {
        $ReciboRec = Recibo::where('empleado_id', $IdEmpleado)
            ->where('perpago', $Periodo)
            ->get();
        if (count($ReciboRec)) {
            $this->LugarPago = $ReciboRec[0]['lugarpago'];
            $fecha = $ReciboRec[0]['fechapago'];
            $this->FechaPago = SUBSTR($fecha, 8, 2) . "-" . SUBSTR($fecha, 5, 2) . "-" . SUBSTR($fecha, 0, 4);

            $this->PerPago = $ReciboRec[0]['perpago'];

            switch (substr($fecha, 4, 2)) {
                case 13:
                    $this->PerPago = '1er SAC ' . substr($fecha, 0, 4);
                    break;
                case 14:
                    $this->PerPago = '2do SAC ' . substr($fecha, 0, 4);
                    break;
                case 15:
                    $this->PerPago = 'Vacaciones ' . substr($fecha, 0, 4);
            }

            $this->IdRecibo = $ReciboRec[0]['id'];
            $this->IdEmpleado = $ReciboRec[0]['empleado_id'];
            $this->IdCatProfe = $ReciboRec[0]['categoriaprofesional_id']; //REVISAR QUE COINCIDAN LA CAT PROF DEL RECIBO CON LA DEL EMPLEADO
            
            $this->NoRem = $ReciboRec[0]['noremunerativo'];
            $this->Descuentos = $ReciboRec[0]['descuentos'];

            $this->PerUltLiq = $ReciboRec[0]['perultimaliq'];
            $this->FechaUltLiq = $ReciboRec[0]['fechaultliq'];

            //$this->Banco=$row['Banco'];
            $this->AcumRem = 0;
            $this->AcumNoRem = 0;
            $this->AcumDescuento = 0;

            $this->CargarDatosDeLaEmpresa();
            $this->CargarDatosDelEmpleado();
            $this->CargarDatosCategoriaProfesional($ReciboRec[0]['categoriaprofesional_id']);
            $this->DevolverConceptosRecibo($this->IdRecibo);

            $this->ActualizaReciboTotales();
        }
    }

    // public function CargarEmpleadosTipoYCategoria($id) {
    //     $Empleado = Empleado::find($id);
    //     $Categoria = Categoriaprofesional::find($Empleado->categoriaprofesional_id);
    //     if($Empleado->mensualizado) { $this->xPrecioMes    = $Categoria->preciomes;    $this->TotHaberes=$Categoria->preciomes;  }
    //     if($Empleado->jornalizado)  { $this->xPrecioDia    = $Categoria->preciodia;    $this->TotHaberes=$Categoria->preciodia;  }
    //     if($Empleado->hora)         { $this->xPrecioHora   = $Categoria->preciohora;   $this->TotHaberes=$Categoria->preciohora;}
    //     if($Empleado->unidad)       { $this->xPrecioUnidad = $Categoria->preciounidad; $this->TotHaberes=$Categoria->preciounidad;}
    // }

    public function AltaRecibo($a, $m) {
        //Carga los datos del recibo actual 
        $reciboActual = Recibo::where('perpago', $a . $m)
            ->where('empleado_id', $this->empleadoseleccionado)
            ->get();

        //Prepara el recibo para el siguiente periodo
        //dd($a. " " . $m);
        
        // $mx = $mx + 1;
        // if ($mx == 13) {
        //     $mx = '01';
        //     $a = $a + 1;
        // }  //Coloca mes en 1 y suma un año si es el recibo de diciembre
        
        switch ($m) {
            case 12: $mx = '01'; $a = $a + 1; break;  // si es DICIEMBRE, vuelve el mes a 1 e incrementa el año
            case 13: $mx = 13; break;  // Si es el primer aguinaldo, coloca el mes en 13 para diferenciarlo
            case 14: $mx = 14; break; // Si es el segundo aguinaldo, coloca el mes en 14 para diferenciarlo
            case 15: $mx = 15; break; // Si son vacaciones, coloca el mes en 15 para diferenciarlo
            default: $mx = $m + 1;
        }
        
        if (strlen($m) == 1) {
            $m = '0' . $m;
        }
        if (strlen($mx) == 1) {
            $mx = '0' . $mx;
        }
        $recibo = Recibo::where('perpago', $a . $mx)
            ->where('empleado_id', $this->empleadoseleccionado)
            ->get();
        //Si ya estaba generado el recibo, se le avisa al usuario, sino, se genera
        if (!count($recibo)) {
            $ReciboN = new Recibo;
            $ReciboN->fechapago = date('Y-m-d');
            $ReciboN->perpago = $a . $mx;
            $ReciboN->lugarpago = 'San MArtín';
            $ReciboN->totalhaberes = 0;
            $ReciboN->noremunetativo = 0;
            $ReciboN->descuentos = 0;
            $ReciboN->perultimaliq = $reciboActual->max('perultimaliq');
            $ReciboN->fechaultliq = $reciboActual->max('fechaultliq');
            $ReciboN->estado = 1;
            $ReciboN->empleado_id = $this->empleadoseleccionado;
            $ReciboN->categoriaprofesional_id = $reciboActual[0]['categoriaprofesional_id'];
            $ReciboN->save();
            session()->flash('messageOk', 'El recibo se agregó con éxito');
            //dd($reciboActual[0]['id']);
            $this->MigrarConceptosANuevoRecibo($reciboActual[0]['id'] , $ReciboN->id);
        } else {
            session()->flash('messageError', 'El recibo ya se encontraba generado');
        }
    }

    public function MigrarConceptosANuevoRecibo($idViejo,$idNuevo) {

        $Detalle = DB::table('concepto_recibos')
            ->join('conceptos', 'concepto_id', '=', 'conceptos.id')
            ->where('recibo_id', '=', $idViejo)->get();

        $Detalle = json_decode($Detalle, true);
        //dd($Detalle);
        
        foreach ($Detalle as $item) {
            $concepto_recibo = new ConceptoRecibo();
            //dd($item['concepto_id'] );
            $concepto_recibo->concepto_id = $item['concepto_id'];
            $concepto_recibo->recibo_id = $idNuevo;
            $concepto_recibo->cantidad = $item['cantidad'];
            $concepto_recibo->save();
            session()->flash('messageOk1', 'Conceptos migrados con éxito');
        }
    }

    public function CargarDatosDeLaEmpresa() {
        //Busca los datos de la empresa
        $Empresa = Empresa::where('id', session('empresa_id'))->get();
        $this->NombreEmpresa = $Empresa[0]['name'];
        $this->Cuit = $Empresa[0]['cuit'];
        $this->DireccionEmpresa = $Empresa[0]['direccion'];
    }

    public function CargarDatosDelEmpleado() {
        //Busca los datos del Empleado
        $Empleado = Empleado::where('empresa_id',  session('empresa_id'))
            ->where('id', $this->empleadoseleccionado)->get();
        $this->NombreEmpleado = $Empleado[0]['name'];
        $this->Cuil = $Empleado[0]['cuil'];
        $this->FechaIngreso = $Empleado[0]['ingreso'];
        //$this->Antiguedad = $this->calculaAntiguedad($this->FechaIngreso, $this->PerPago );
        $this->Antiguedad = $this->calculaedad($this->FechaIngreso);
        $this->Legajo = $Empleado[0]['legajo'];
        $this->Seccion = $Empleado[0]['seccion'];
        $this->Banco = $Empleado[0]['banco'];
        $this->CategoriaProfesional_id = $Empleado[0]['categoriaprofesional_id'];
        //dd($this->CategoriaProfesional_id);        
    }

    public function CargarDatosCategoriaProfesional($id) {

        $Empleado = Empleado::find($this->IdEmpleado);
        
        $Categoria = Categoriaprofesional::find($id);
    
        //Este detalle se tiene en cuenta para el cálculo de algunos items del recibo
        if($Empleado->mensualizado) { $this->TotHaberes=$Categoria->preciomes;  }
        if($Empleado->jornalizado)  { $this->TotHaberes=$Categoria->preciodia;  }
        if($Empleado->hora)         { $this->TotHaberes=$Categoria->preciohora;}
        if($Empleado->unidad)       { $this->TotHaberes=$Categoria->preciounidad;}

        $this->xPrecioMes    = $Categoria->preciomes;   
        $this->xPrecioDia    = $Categoria->preciodia;  
        $this->xPrecioHora   = $Categoria->preciohora;  
        $this->xPrecioUnidad = $Categoria->preciounidad;

        $this->NombreCategoria = $Categoria->name;
        $this->NombreSubCategoria = $Categoria->subcategoria;
        $this->CCT = $Categoria->cct;

    }

    public function DevolverConceptosRecibo($IdRecibo) {
        // Restablece los contadores a cero
        $this->AcumRem=0;
        $this->AcumNoRem=0;
        $this->AcumDescuento=0;

        $Detalle = DB::table('concepto_recibos')
            ->join('conceptos', 'concepto_id', '=', 'conceptos.id')
            ->where('recibo_id', '=', $IdRecibo)
            ->orderBy('orden')
            ->get(['concepto_recibos.id','concepto_id','recibo_id','cantidad','orden','name','unidad','haber','rem','norem','descuento','montofijo','calculo','montomaximo']);

        $Detalle = json_decode($Detalle, true);
        //dd($IdRecibo);
        //dd($Detalle);
        //Toma el cógigo de la categoría profesional correspondiente al RECIBO
        $a = Categoriaprofesional::find($this->IdCatProfe);
        $e = Empleado::where('empresa_id', session('empresa_id'))->where('id', $this->empleadoseleccionado)->get();
        $precios = array($a->preciomes, $a->preciodia, $a->preciohora, $a->preciounidad, $a->basico, $a->basico1, $a->basico2);
        $precios = implode("#", $precios);
        $activo = array($e[0]['mensualizado'], $e[0]['jornalizado'], $e[0]['hora'], $e[0]['unidad']);
        $tipos = implode("#", $activo);

        $i = 0;
        unset($AA);
        unset($this->Conceptos);
        foreach ($Detalle as $Conceptohtml) {
            
            $AA[$i]['id'] = $Conceptohtml['id'];
            $AA[$i]['orden'] = $Conceptohtml['orden'];
            $AA[$i]['cantidad'] = $Conceptohtml['cantidad'];
            $AA[$i]['name'] = $Conceptohtml['name'];
            $AA[$i]['Rem'] = '0';
            $AA[$i]['NoRem'] = '0';
            $AA[$i]['Descuento'] = '0';
            if ($Conceptohtml['rem'] > 0) {
                $AA[$i]['Rem'] = $this->CalcularExpresion($precios, $tipos, $Conceptohtml['cantidad'], $Conceptohtml['montofijo'], $Conceptohtml['calculo']);
                $AA[$i]['NoRem'] = 0;
                $AA[$i]['Descuento'] = 0;
                $this->AcumRem = $this->AcumRem + $AA[$i]['Rem'];
            }
            if ($Conceptohtml['norem'] > 0) {
                $AA[$i]['Rem'] = 0;
                $AA[$i]['NoRem'] = $this->CalcularExpresion($precios, $tipos, $Conceptohtml['cantidad'], $Conceptohtml['montofijo'], $Conceptohtml['calculo']);
                $AA[$i]['Descuento'] = 0;
                $this->AcumNoRem = $this->AcumNoRem + $AA[$i]['NoRem'];
            }
            if ($Conceptohtml['descuento'] > 0) {
                $AA[$i]['Rem'] = 0;
                $AA[$i]['NoRem'] = 0;
                $AA[$i]['Descuento'] = $this->CalcularExpresion($precios, $tipos, $Conceptohtml['cantidad'], $Conceptohtml['montofijo'], $Conceptohtml['calculo']);
                $this->AcumDescuento = $this->AcumDescuento + $AA[$i]['Descuento'];
            }
            //if (trim($AA[$i]['name'])=="Antiguedad Salud.") { dd($Conceptohtml) ; }
            //dd($Conceptohtml) ;
            $i++;
        }
        if (isset($AA)) {
            $this->Conceptos = $AA;
        }

        $this->NetoACobrar = $this->AcumRem + $this->AcumNoRem - $this->AcumDescuento;
        $this->NetoACobrarLetras = $this->convertir((int)$this->NetoACobrar) .' con '.  ((int)(($this->NetoACobrar-(int)$this->NetoACobrar)*100)) . '/100 centavos';
        //dd($this->NetoACobrarLetras);
        // if ($this->Conceptos[0]['montofijo']>0) {

        //     $pp=floatval($this->CalcularExpresion($precios, $tipos, $this->Conceptos[0]['cantidad'], $this->Conceptos[0]['unidad'], $this->Conceptos[0]['montofijo'], $this->Conceptos[0]['calculo']));
        // $pp=$this->CalcularExpresion($precios,$tipos,$this->Conceptos->cantidad,$this->Conceptos->unidad,$this->Conceptos->montofijo,$this->Conceptos->calculo,$this->SumHaberes,$this->SumUnidades);
      

        // public function DevolverConceptosRecibo($IdRecibo) {
        //     $this->Conceptos = DB::table('concepto_recibos')
        //     ->join('conceptos','concepto_id','=','conceptos.id')
        //     ->where('recibo_id','=',$IdRecibo)->get();


        //     if ($this->Conceptos[0]['montofijo']>0) {

        //         $pp=floatval($this->CalcularExpresion($precios, $tipos, $this->Conceptos[0]['cantidad'], $this->Conceptos[0]['unidad'], $this->Conceptos[0]['montofijo'], $this->Conceptos[0]['calculo']));
        // $pp=$this->CalcularExpresion($precios,$tipos,$this->Conceptos->cantidad,$this->Conceptos->unidad,$this->Conceptos->montofijo,$this->Conceptos->calculo,$this->SumHaberes,$this->SumUnidades);
    }

    public function ActualizaReciboTotales() {
        $recibo = Recibo::find($this->IdRecibo);
        $recibo->totalhaberes = $this->AcumRem;
        $recibo->noremunetativo = $this->AcumNoRem;
        $recibo->descuentos = $this->AcumDescuento;
        
        $recibo->save();
    }

    public function CalcularExpresion($precio, $tipo, $CA, $MF, $expre) {
        // dd($MF);
        unset($A);
        unset($pieces);

        $A = array();

        $precios = explode("#", $precio);
        $PM = (float)$precios[0]; $PD = (float)$precios[1]; $PH = (float)$precios[2]; $PU = (float)$precios[3]; $BC = (float)$precios[4]; $B1 = (float)$precios[5]; $B2 = (float)$precios[6];

        $tipos   = explode("#", $tipo);
        $TM = (float)$tipos[0]; $TD = (float)$tipos[1]; $TH = (float)$tipos[2]; $TU = (float)$tipos[3];
        
        // PD: Precio Mes   PD: Precio Dia    PH: Precio Hora    PU: Precio Unidad
        // TM: Tipo Mes     TD: Tipo Dia      TH: Tipo Hora      TU: Tipo Unidad
        //dd(trim($expre));
        $pieces = explode("*",trim($expre));
        /*
        RA	    Remuneración básica
        RB	    Remuneración básica
        AAOS	Aporte Adicional Obra Social
        AASS	Aporte Adicional Seg. Social
        ANR	    Asig. No Remunerat
        BC	    Básico Categoría al inicio
        B1	    Básico Categoría Uno
        B2	    Básico Categoría Dos
        ANT	    Antiguedad
        CA	    Cantidad
        MF	    Monto Fijo
        -- UN	    Unidades --
        DE	    Total Descuentos
        
        */
        //dd($this->PerPago);
        for ($c = 0; $c < count($pieces); $c++) {
            switch ($pieces[$c]) {
                case is_numeric($pieces[$c]): { $A[$c]=(float)$pieces[$c] ; break;}
                case "RA": { $A[$c] = $PM * $TM + $PD * $TD + $PH * $TH + $PU * $TU; break; } // 'R'emuneracion 'A'signada
                case "RB": { $A[$c] = ($PM * $TM + $PD * $TD + $PH * $TH + $PU * $TU); $this->RB = $A[$c];break; } // 'R'emuneracion 'A'signada
                    //case "TH":{ $A[$c]=($PD*$TD+$PH*$TH+$PM*$TM+$PU*$TU)*$C;	break;}// 'T'otal 'H'aberes
                    // case "U": {	 $A[$c]=$U;  break;}				// 'U'nidades
                
                case "DE": { $A[$c] = $this->RB; break; }            // 'D'escuentos 
                case "MF": { $A[$c] = $MF; break; }                    // 'M'onto 'F'ijo
                case "CA": { $A[$c] = $CA; break; }                // 'C'antidad
                case "C":  { $A[$c] = $CA; break; }                // 'C'antidad
                case "BC": { $A[$c] = $BC; break; }                // 'B'ásico 'C'ategoria
                case "B1": { $A[$c] = $B1; break; }                // 'B'ásico 'C'ategoria 1
                case "B2": { $A[$c] = $B2; break; }                // 'B'ásico 'C'ategoria 2
                case "REM":{ $A[$c] = $this->AcumRem; break; }
                case "PM": { $A[$c] = $this->xPrecioMes; break; }
                case "PD": { $A[$c] = $this->xPrecioDia;break; break;}
                case "PH": { $A[$c] = $this->xPrecioHora; break;}
                case "PU": { $A[$c] = $this->xPrecioUnidad; break;}
                //case "ANT": { $A[$c] = $this->Antiguedad; break;}
                
                case "ANT": { $A[$c] = $this->calculaAntiguedad($this->FechaIngreso,$this->PerPago); break;}


                    //             case "AAOS": { if (($SH*0.03)<$C) { $A[$c]=$C-($SH*0.03); } else { $A[$c]=0;} break;}	//
                    //             case "AASS": { if (($SH*0.14)<$C) { $A[$c]=$C-($SH*0.14); } else { $A[$c]=0;} break;}	//
                    //             case "ANR": {	 if ($SumUnidades<=$C) { $A[$c]=$U*$SumUnidades/200;} else { $A[$c]=$MF*$SumUnidades; } break;}	// Asignacion No Remunerativa
                    //             case "ANT": {	$Empl = new clsEmpleado($Periodo,$IdEmp); $A[$c]=$Empl->Antiguedad; unset($Empl); break;} // Devuelve la cantidad de a&ntilde;os
                    //               // case "ANTFCalc": {	$Empl = new clsEmpleado($Periodo,$IdEmp); $A[$c]=$Empl->AntiguedadFC($Periodo); unset($Empl); break;} // Devuelve la cantidad de a&ntilde;os
                    // // 			case "ANR": {	 if ($SumUnidades<=200) { $A[$c]=239*$SumUnidades/200;} else { $A[$c]=239; } break;}	// Asignacion No Remunerativa
                case "2SAC":{ 
                    //$Anio=substr($this->anio . $this->mes,0,4);
                    $Anio = $this->anio;
                    // $sSql="SELECT sum(TotHaberes)/12 as Tot FROM tblRecibos WHERE PerPago<=".$Anio."12 and PerPago >=".$Anio."07 and IdEmpleado=$IdEmp";
                    //$sSql="SELECT sum(totalhaberes)/12 as Tot FROM recibos WHERE perpago<=".$Anio."12 and PerPago >=".$Anio."07 and empleado_id=".$this->IdEmpleado;
                    //$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute()
                    $aux = Recibo::selectRaw('sum(totalhaberes)/12 as Tot')
                    ->where('perpago','<=',$Anio.'12')
                    ->where('perpago','>=',$Anio.'07')
                    ->where('empleado_id','=',$this->IdEmpleado)
                    ->get();
                    $A[$c]= $aux[0]['Tot'];
                    break;
                    }// 2do SAC
                case "1SAC":{ 
                    $Anio = $this->anio;
                    //$Anio=substr($Periodo,0,4);
                    //$sSql="SELECT sum(TotHaberes)/12 as Tot FROM tblRecibos WHERE PerPago<=".$Anio."06 and PerPago >=".$Anio."01 and IdEmpleado=$IdEmp";
                    $aux = Recibo::selectRaw('sum(totalhaberes)/12 as Tot')
                    ->where('perpago','<=',$Anio.'06')
                    ->where('perpago','>=',$Anio.'01')
                    ->where('empleado_id','=',$this->IdEmpleado)
                    ->get();
                    //dd($aux);
                    $A[$c]= $aux[0]['Tot'];   
                    break;}// 1do SAC
            default: $A[$c] = 1;
            }
        }
        $res = reset($A);
        //dd($res*$A[1]);
        
        for ($d = 1; $d < count($A); $d++) {
            if (is_numeric($A[$d])) 
            { $res = $res * (float)($A[$d]); } 
            else { $res = $res * (float)$A[$d]; }
            //if($d==3) { dd($A[0]."*".$A[1]."*".$A[2]."*".$A[3]); }
            // if ($d==1) { dd($A[$d]); }
        }

        return $res;
        //Controla que si hay un tope maximo, este no sea superado por el calculo obtenido
        // if ($res>$MM && $MM<>0) { $res=$MM; }
        // return $res;
    }

    public function calculaedad($fechanacimiento) {      
        list($ano,$mes,$dia) = explode("-",$fechanacimiento);
        $dia = substr($dia,0,2);
        $this->ano_diferencia  = date("Y") - $ano;
        $this->mes_diferencia = date("m") - $mes;
        $this->dia_diferencia   = date("d") - $dia;
        if ($this->dia_diferencia < 0 || $this->mes_diferencia < 0){ $this->ano_diferencia--; }
        // $this->Antiguedad = $this->ano_diferencia.'a'.$this->mes_diferencia.'m'; 
        return $this->ano_diferencia.'a'.$this->mes_diferencia.'m'; 
        //dd($this->Antiguedad);
        
        //return $this->ano_diferencia;
      }

      public function calculaAntiguedad($fechainicial,$fechadecalculo) {      
        // list($ano,$mes,$dia) = explode("-",$fechainicial);
        list($ano,$mes,$dia) = explode("-",$fechainicial);
        $dia = substr($dia,0,2);
        $fechadecalculo = substr($fechadecalculo,0,4) . "-" . substr($fechadecalculo,4,2) . "-01";
        //$fechadecalculo = strtotime('-1 day', strtotime($fechadecalculo));
        //$fechadecalculo = strtotime('-1 day', strtotime($fechadecalculo));
        $fechadecalculo = strtotime($fechadecalculo);
        $fechadecalculo = date('Y-m-d',$fechadecalculo);
        list($anocalculo, $mescalculo, $diacalculo) = explode("-",$fechadecalculo);
        
        //$dia = substr($dia,0,2);
        $this->ano_diferencia  = $anocalculo - $ano;
        $this->mes_diferencia = $mescalculo - $mes;
        $this->dia_diferencia   = $diacalculo - $dia;
       //dd($fechadecalculo);
        if ($this->dia_diferencia < 0 || $this->mes_diferencia < 0){ $this->ano_diferencia--; }
        // $this->Antiguedad = $this->ano_diferencia.'a'.$this->mes_diferencia.'m'; 
        
        //dd($this->ano_diferencia);
        return $this->ano_diferencia; 
        
        //return $this->ano_diferencia;
      }

      public function MostrarOcultarModalAgregar() {
        //$this->items = Concepto::all();
        $this->items = Concepto::where('empresa_id',session('empresa_id'))->where('activo',1)->ORDERBY('name','asc')->get();
        $this->ModalAgregar = true;
    }

    public function closeModalPopover() {
        $this->ModalAgregar=false;
    }

    public function GrabarItem($item,$cantidad) {
        $concepto_recibo = new ConceptoRecibo();
        $concepto_recibo->concepto_id = $item;
        $concepto_recibo->recibo_id = $this->IdRecibo;
        $concepto_recibo->cantidad = $cantidad;
        $concepto_recibo->save();
        session()->flash('messageOk', 'Concepto agregado con éxito');
        $this->DevolverConceptosRecibo($this->IdRecibo);
        $this->closeModalPopover();
    }

    public function ModificarEscalaShow() {
        $this->CategoriasProf = Categoriaprofesional::where('empresa_id',session('empresa_id'))->where('activo',1)->orderby('name')->get();
        $this->ModificarEscalaShow=true;
    }

    public function ModificarEscalaHide() {
        $this->ModificarEscalaShow=false;
    }

    public function ModificarEscala($IdCatProf, $Opcion) {
        $ReciboRec = Recibo::find($this->IdRecibo);
        $Empleado = Empleado::find($ReciboRec->empleado_id);
        //$Empleado = Empleado::find($ReciboRec->DatosEmpleado->id);
        // dd($Empleado);
        switch ($Opcion) {
            case 1: $ReciboRec->categoriaprofesional_id = $IdCatProf; $ReciboRec->save(); break;    // Solo cambia la categoría de este recibo
            case 2: $Empleado->categoriaprofesional_id = $IdCatProf; $Empleado->save(); break;  // Sólo cambia la categoría del Empleado
            case 3: $ReciboRec->categoriaprofesional_id = $IdCatProf; $ReciboRec->save(); 
                    $Empleado->categoriaprofesional_id = $IdCatProf; $Empleado->save(); break;  // CAmbia del recibo y del empleado
        }        
        $this->ModificarEscalaHide();
        session()->flash('messageOk', 'Escala Profesional modificada con éxito');
    }

    public function EliminarRecibo() {
        // dd($this->IdRecibo);
        $ReciboRec = Recibo::find($this->IdRecibo);
        $ReciboRec->destroy($this->IdRecibo);
        $this->CargarEmpleadosActivosEnEsePeriodo();
        session()->flash('messageOk', 'El recibo se eliminó con éxito');
    }

    public function ModificarConceptoShow($item_id,$item_name,$item_cantidad) {
        $this->ModificarConceptoShow=true;
        $this->item_id  = $item_id;
        $this->item  = $item_name;
        $this->cantidad = $item_cantidad;
    }

    public function ModificarConceptoHide() {
        $this->ModificarConceptoShow=false;
    }

    public function EliminarConceptoReciboShow($item_id,$item_name,$item_cantidad) {
        $this->EliminarConceptoReciboShow=true;
        $this->item_id  = $item_id;
        $this->item  = $item_name;
        $this->cantidad = $item_cantidad;
    }

    public function EliminarConceptoReciboHide() {
        $this->EliminarConceptoReciboShow=false;
    }
    
    public function EliminarConceptoRecibo() {
        $ConceptoAEliminar = ConceptoRecibo::find($this->item_id);
        //dd($ConceptoAEditar);
        $ConceptoAEliminar->destroy($this->item_id);
        $this->DevolverConceptosRecibo($this->IdRecibo);
        session()->flash('messageOk', 'El ítem se eliminó');
        $this->EliminarConceptoReciboHide();
    }

    public function ModificarConcepto() {
        $ConceptoAEditar = ConceptoRecibo::find($this->item_id);
        // $ConceptoAEditar = ConceptoRecibo::where('recibo_id',$this->IdRecibo)->where('concepto_id',$this->item_id)->get();
        $ConceptoAEditar->cantidad = $this->cantidad;
        // dd($ConceptoAEditar);
        $ConceptoAEditar->save();
        $this->DevolverConceptosRecibo($this->IdRecibo);
        session()->flash('messageOk', 'El Concepto se actualizó con éxito');
        $this->ModificarConceptoHide();
    }

    public function GestionarConceptosShow() {
        $this->CargarListaDeConceptos();
        // dd($this->items);
        $this->GestionarConceptos=true;
    }

    public function GestionarConceptoHide() {
        $this->GestionarConceptos=false;
    }

    public function CargarListaDeConceptos() {
        if ($this->chkTodos=='Todos') {
            //Si el check está en todos
            $this->items = Concepto::where('empresa_id',session('empresa_id'))->ORDERBY('name','asc')->get();
            //Si el check está en Activos
        } else {
            $this->items = Concepto::where('empresa_id',session('empresa_id'))->where('activo',1)->ORDERBY('name','asc')->get();
        }

        //$this->items = Concepto::where('empresa_id',session('empresa_id'))->where('activo',$this->chkTodos=="Todos" ? 1 : 0)->ORDERBY('name','asc')->get();
        //dd($this->items);
    }

    public function CargarItemAModificar() {
        $item = Concepto::where('empresa_id',session('empresa_id'))->where('id',$this->cmbitem)->ORDERBY('name','asc')->get();
        //$item = utf8_encode(Concepto::where('empresa_id',session('empresa_id'))->where('id',$this->cmbitem)->get());
        //dd($item);
        //dd($item[0]['orden']);// $this->name = $this->item->name;
        $this->orden = $item[0]['orden'];
        $this->name = $item[0]['name'];
        $this->unidad = $item[0]['unidad'];
        //dd((bool)$item[0]['haber']==true);
        (bool)$item[0]['haber']==true ? $this->haberes = true : $this->haberes = false;
        (bool)$item[0]['rem']==true ? $this->remunerativo = true : $this->remunerativo = false;
        (bool)$item[0]['norem']==true ? $this->noremunerativo = true : $this->noremunerativo = false;
        (bool)$item[0]['descuento']==true ? $this->descuento = true : $this->descuento = false;
        (bool)$item[0]['activo']==true ? $this->activo=true : $this->activo=false;
        $this->montofijo = $item[0]['montofijo'];
        $this->calculo = $item[0]['calculo'];
        $this->montomaximo = $item[0]['montomaximo'];
        //dd($item);
    }

    public function EliminarConcepto(){ 
        $Concepto = Concepto::find($this->cmbitem);
        $Concepto_Recibo = ConceptoRecibo::where('concepto_id',$this->cmbitem)->get();
        if(count($Concepto_Recibo)) {
            $Concepto->activo = false;
            $Concepto->save();
            session()->flash('messageModalOk', 'El Concepto se marcó como no activo.');
        } else {
            $Concepto->destroy($this->cmbitem);
            session()->flash('messageModalOk', 'El Concepto se eliminó.');
        }
        $this->CargarListaDeConceptos();
    }

    public function ActualizaConcepto(){ 
        //Genera nuevo Concepto con los datos del anterior
        $ConceptoNuevo = new Concepto();
        $ConceptoNuevo->orden = $this->orden;
        $ConceptoNuevo->name = $this->name;
        $ConceptoNuevo->unidad = $this->unidad;
        //dd($this->haberes);
        $ConceptoNuevo->haber = $this->haberes;
        $ConceptoNuevo->rem = $this->remunerativo;
        $ConceptoNuevo->norem = $this->noremunerativo;
        $ConceptoNuevo->descuento = $this->descuento;
        $ConceptoNuevo->montofijo = $this->montofijo;
        $ConceptoNuevo->calculo = $this->calculo;
        $ConceptoNuevo->montomaximo = $this->montomaximo;
        $ConceptoNuevo->empresa_id = session('empresa_id');
        $ConceptoNuevo->activo=true;
        $ConceptoNuevo->save();
        // Desactiva el Concepto anterior
        $Concepto = Concepto::find($this->cmbitem);
        $Concepto->activo = false;
        $Concepto->save();
        $this->CargarListaDeConceptos();
        session()->flash('messageModalOk', 'Se Actualizó el Concepto con los nuevos valores');
    }

    public function ModificarValoresDeConcepto() {
        $Concepto = Concepto::find($this->cmbitem);
        //dd($Concepto);
        $Concepto->orden = $this->orden;
        $Concepto->name = $this->name;
        $Concepto->unidad = $this->unidad;
        is_null($this->haberes) ? (bool)$Concepto->haber = false : $Concepto->haber = $this->haberes;
        is_null($this->remunerativo) ? (bool)$Concepto->rem = false : $Concepto->rem = $this->remunerativo;
        is_null($this->noremunerativo) ? (bool)$Concepto->norem = false : $Concepto->norem = $this->noremunerativo;
        is_null($this->descuento) ? (bool)$Concepto->descuento = false : $Concepto->descuento = $this->descuento;

        // $Concepto->norem = $this->noremunerativo;
        // $Concepto->descuento = $this->descuento;
        //dd($Concepto->haber . ' ' . $Concepto->rem . ' ' . $Concepto->norem . ' ' . $Concepto->descuento);
        $Concepto->montofijo = $this->montofijo;
        $Concepto->calculo = $this->calculo;
        $Concepto->montomaximo = $this->montomaximo;
        //$Concepto->empresa_id = session('empresa_id');
        is_null($this->activo) ? $Concepto->activo = 0 : $Concepto->activo = $this->activo;
        $Concepto->save();
        $this->CargarListaDeConceptos();
        session()->flash('messageModalOk', 'Se Modificó el valor del Concepto para todos los valores.');
    }

    public function NuevoConcepto(){ 
        $ConceptoNuevo = new Concepto();
        $ConceptoNuevo->orden = $this->orden;
        $ConceptoNuevo->name = $this->name;
        $ConceptoNuevo->unidad = $this->unidad;
        $ConceptoNuevo->haber = $this->haberes;
        $ConceptoNuevo->rem = $this->remunerativo;
        $ConceptoNuevo->norem = $this->noremunerativo;
        $ConceptoNuevo->descuento = $this->descuento;
        $ConceptoNuevo->montofijo = $this->montofijo;
        $ConceptoNuevo->calculo = $this->calculo;
        $ConceptoNuevo->montomaximo = $this->montomaximo;
        $ConceptoNuevo->empresa_id = session('empresa_id');
        $ConceptoNuevo->activo=true;
        $ConceptoNuevo->save();
        session()->flash('messageModalOk', 'Se Actualizó el Concepto con los nuevos valores');
        $this->CargarListaDeConceptos();
    }

    public function setTodos() {
        if ($this->chkTodos=='Todos') {
            $this->chkActivos = false;
        } else {
            $this->chkActivos = true;
        }
        $this->CargarListaDeConceptos();
        //dd($this->chkActivos);
    }
    // public function setValue($elemento){
    //     switch ($elemento) {
    //         case 'haberes': $this->haberes=!$this->haberes; break;
    //         case 'rem': $this->remunerativo=!$this->remunerativo; break;
    //         case 'norem': $this->noremunerativo=!$this->noremunerativo; break;
    //         case 'descuento': $this->descuento=!$this->descuento; break;
    //         case 'activo': $this->activo=!$this->activo; break;
    //     }
    //     //dd("Elemento elegido:".$elemento.": Haberes:".(bool)$this->haberes ." Remunerativo:$this->remunerativo NoRemunerativo:$this->noremunerativo Descuento:$this->descuento Activo:$this->activo");
    // }
        public function basico($numero) {
            $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve','veinte','veintiuno ','vientidos ','veintitrés ', 'veinticuatro','veinticinco','veintiséis','veintisiete','veintiocho','veintinueve');
            return $valor[$numero - 1];
        }
        
        public function decenas($n) {
            $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta', 70=>'setenta',80=>'ochenta',90=>'noventa');
            if( $n <= 29) return $this->basico($n);
                $x = $n % 10;
            if ( $x == 0 ) {
                return $decenas[$n];
            } else {
                return $decenas[$n - $x].' y '. $this->basico($x);
            }
        }
        
        public function centenas($n) {
            $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos', 400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos', 700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
            if( $n >= 100) {
            if ( $n % 100 == 0 ) {
                return $cientos[$n];
            } else {
                $u = (int) substr($n,0,1);
                $d = (int) substr($n,1,2);
                return (($u == 1)?'ciento':$cientos[$u*100]).' '.$this->decenas($d);
            }
            } else {
                return $this->decenas($n);
            }
        }
        
        public function miles($n) {
            if($n > 999) {
                if( $n == 1000) { return 'mil'; }
                else {
                    $l = strlen($n); // 8
                    $c = (int)substr($n,0,$l-3);  // 46172
                    $x = (int)substr($n,-3); //0
                    if($c == 1) { $cadena = 'mil '.$this->centenas($x); }
                    else if($x != 0) { $cadena = $this->centenas($c).' mil '.$this->centenas($x); }
                    else $cadena = $this->centenas($c). ' mil';
                    return $cadena;
                }
            } else return $this->centenas($n);
        }
        
        public function millones($n) {
            if($n == 1000000) {return 'un millón';}
            else {
                $l = strlen($n);
                $c = (int)substr($n,0,$l-6);
                $x = (int)substr($n,-6);
                if($c == 1) {
                    $cadena = ' millón ';
                } else {
                    $cadena = ' millones ';
                }
                return $this->miles($c).$cadena.(($x > 0) ? $this->miles($x):'');
            }
        }

        public function convertir($n) {
            switch (true) {
                case ( $n >= 1 && $n <= 29) : return $this->basico($n); break;
                case ( $n >= 30 && $n < 100) : return $this->decenas($n); break;
                case ( $n >= 100 && $n < 1000) : return $this->centenas($n); break;
                case ($n >= 1000 && $n <= 999999): return $this->miles($n); break;
                case ($n >= 1000000): return $this->millones($n);
            }
        }
}
