<?php
 // Clase Empleado
// -------------------
//include_once("../stringconexion.inc");
//$pdo = new PDO('mysql:host=127.0.0.1;dbname=barl83_barlotta', $_SESSION['user'], $_SESSION['password']);
class clsEmpleado {
	var $IdEmpleado;
	var $CuitEmpresa;
	var $Nombre;
	var $CatProf;		// Obrero de Viña
	var $IdCatProfe;	// Código de la categoría profesional
	var $Cuil;
	var $CalifProf; 	//Obrero Común
	var $Seccion;		//Finca A
	var $Legajo;
	var $Convenio;
	var $FIngreso;
	var $RAsignada;
	var $Banco;
	var $FDeposito;
	var $MesPresentacion;
	var $NombreEmpresa;
	var $DireccionEmpresa;
	var $EstadoCivil;
	var $Domicilio;
	var $ModalidadContratacion;
	var $Regimen;

	var $DNI;
	var $NroCtaBanco;
	var $FechaNacimiento;
	var $Telefono;
	var $Activo;
	var $FechaBaja;
	var $Mensualizado;
	var $Jornalizado;
	var $PorHora;
	var $PorUnidad;

	var $Edad;
	var $Antiguedad;
	var $BasicoCategoria;
	var $BasicoCategoriaActual;
	var $Porcentaje;

// function CargarDatosEmpleados($IdEmpleado) {

 function __construct($Periodo, $IdEmp) {
	$this->IdEmpleado=$IdEmp;
	if(substr($Periodo,5,2)<10) { $mes = '0'.substr($Periodo,5,1); } else { $mes=substr($Periodo,5,2); }
	//if(substr($Periodo,5,1)=='0') { $mes = '0'.substr($Periodo,6,1); } else { $mes=substr($Periodo,5,2); }
	//$Hasta=substr($Periodo,0,4)."-".$mes."-30";   //Formatea la fecha Hasta para poder calcular Edad/Antiguedad
	$Hasta=substr($Periodo,0,4)."-".substr($Periodo,4,2)."-30";   //Formatea la fecha Hasta para poder calcular Edad/Antiguedad
	//$Hasta=substr(date("Y-m-d"),0,4)."-12-31";   //Formatea la fecha Hasta para poder calcular Edad/Antiguedad
	//$Hasta="2019-12-31";
	$this->Domicilio=$Hasta;
	if ($this->IdEmpleado>0) {
	//Controla si hay un recibo creado anteriormente, si es así toma los datos del recibo, sino toma los datos del empleado
		$sSql="SELECT count(IdRecibo) as cant FROM tblRecibos WHERE IdEmpleado=$IdEmp and PerPago=$Periodo";
		$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute(); // $result = mysql_db_query($_SESSION['db'],$sSql);
		$row=$stmt->fetch();  //$row = mysql_fetch_array($result);
		if ($row[cant]>0) { $Usado=true; }
// 		$stmt->close();
		//Ejecuta la instruccion 
		$sSql='SELECT * FROM ViewEmpleados WHERE IdEmpleado='.$IdEmp . " and PerPago='" . $Periodo ."'";
		$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute(); // $result = mysql_db_query($_SESSION['db'],$sSql);
		$row=$stmt->fetch();  //$row = mysql_fetch_array($result);
		$this->CuitEmpresa=SUBSTR($row[Cuit],0,2)."-".SUBSTR($row[Cuit],2,8)."-".SUBSTR($row[Cuit],10,1);
		$this->Nombre=$row[Nombre];
		$this->Cuil=$row[CUIL];
		$this->CalifProf=$row[Subcategoria]; 	//Obrero Común
		//$this->Seccion=$row[DescripcionAreas];	//Finca A
		$this->Seccion=$row[Seccion];	//Finca A
		$this->Legajo=$row[Legajo];
		$this->FIngreso=$row[FechaIngreso];
		$this->Banco=$row[Banco];
		$this->NroCtaBanco=$row[NroCtaBanco];
		$this->Seccion=$row[Seccion];
		$this->NombreEmpresa=$row[NombreEmpresa];
		$this->DireccionEmpresa=$row[Direccion];
		$this->DNI=$row[DNI];
		$this->FechaNacimiento=$row[FechaNacimiento];
		$this->Telefono=$row[Telefono];
		$this->Mensualizado=$row[Mensualizado];
		$this->Jornalizado=$row[Jornalizado];
		$this->PorHora=$row[PorHora];
		$this->PorUnidad=$row[PorUnidad1];
		$this->Activo=$row[Activo];
		$this->FechaBaja=$row[FechaBaja];
		$this->Edad=CalcularEdadAntiguedad($row[FechaNacimiento],date("Y-m-d"));
		$this->Antiguedad=CalcularEdadAntiguedad($row[FechaIngreso],$Hasta);
		$this->AntiguedadLarga=CalcularEdadAntiguedadLarga($row[FechaIngreso],$Hasta);
		$this->Porcentaje=$row[PorcentajeCat];
		$this->EstadoCivil=$row[EstadoCivil];
		//$this->Domicilio=$row[Domicilio];
		$this->ModalidadContratacion=$row[ModalidadContratacion];
		$this->Regimen=$row[Regimen];

//		$stmt->close();
		if ($Usado) {

$sSql="SELECT * FROM tblEmpleados, tblRecibos, tblCatProfe
WHERE tblEmpleados.idempleado=$IdEmp
and tblRecibos.PerPago = $Periodo
and tblRecibos.IdEmpleado=$IdEmp
and tblRecibos.IdCatProfe=tblCatProfe.IdCatProf";
// 			$sSql="SELECT * FROM tblEmpleados, tblRecibos, tblCatProfe WHERE tblRecibos.IdCatProfe=tblCatProfe.IdCatProf and tblRecibos.PerPago=$Periodo and tblRecibos.IdEmpleado=$IdEmp";
			$AUX = $GLOBALS['pdo']->prepare($sSql); $AUX->execute(); //$AUX = mysql_db_query($_SESSION['db'],$sSql);
			$row1=$AUX->fetch(); // $row1 = mysql_fetch_array($AUX);
			$this->CatProf=$row1[DescripcionCatProf];
			$this->IdCatProfe=$row1[IdCatProfe];
			$this->BasicoCategoria=$row1[BasicoCategoria];
			$this->BasicoCategoriaActual=$row1[BasicoCategoria1];
			$this->Convenio=$row1[CCT];
			if ($row1[PorHora]>0)      { $this->RAsignada=$row1[PrecioHora]; }
			if ($row1[Jornalizado]>0)  { $this->RAsignada=$row1[PrecioDia];  }
			if ($row1[Mensualizado]>0) { $this->RAsignada=$row1[PrecioMes];  }
			if ($row1[PorUnidad]>0)  { $this->RAsignada=$row1[PorUnidad];  }
/*			if ($row1[PrecioHora]>0) { $this->RAsignada=$row1[PrecioHora]; }
			if ($row1[PrecioDia]>0)  { $this->RAsignada=$row1[PrecioDia];  }
			if ($row1[PrecioMes]>0)  { $this->RAsignada=$row1[PrecioMes];  }
			if ($row1[PorUnidad]>0)  { $this->RAsignada=$row1[PorUnidad];  }*/
		} else {
			$this->CatProf=$row[DescripcionCatProf];
			$this->IdCatProfe=$sSql;
			$this->BasicoCategoria=$row[BasicoCategoria];
			$this->BasicoCategoriaActual=$row1[BasicoCategoria1];
			if ($row[PrecioHora]>0) { $this->RAsignada=$row[PrecioHora]; }
			if ($row[PrecioDia]>0)  { $this->RAsignada=$row[PrecioDia];  }
			if ($row[PrecioMes]>0)  { $this->RAsignada=$row[PrecioMes];  }
			if ($row[PorUnidad]>0)  { $this->RAsignada=$row[PorUnidad];  }
		}
// 		$stmt->close();
 	}
}

function getDatosEmpleados($IdEmp) {
	$sSql="SELECT * FROM tblEmpleados WHERE IdEmpleado=". $IdEmp;
	$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute(); // $result = mysql_db_query($_SESSION['db'],$sSql);
	$row=$stmt->fetch();  //$row = mysql_fetch_array($result);
		$this->CuitEmpresa=SUBSTR($row[Cuit],0,2)."-".SUBSTR($row[Cuit],2,8)."-".SUBSTR($row[Cuit],10,1);
		$this->Nombre=$row[Nombre];
		$this->Cuil=$row[CUIL];
		$this->CalifProf=$row[Subcategoria]; 	//Obrero Común
// 		$this->Seccion=$row[DescripcionAreas];	//Finca A
		$this->Seccion=$row[Seccion];	//Finca A
		$this->Legajo=$row[Legajo];
		$this->FIngreso=$row[FechaIngreso];
		$this->Banco=$row[Banco];
		$this->NroCtaBanco=$row[NroCtaBanco];
		$this->Seccion=$row[Seccion];
		$this->NombreEmpresa=$row[NombreEmpresa];
		$this->DireccionEmpresa=$row[Direccion];
		$this->DNI=$row[DNI];
		$this->FechaNacimiento=$row[FechaNacimiento];
		$this->Telefono=$row[Telefono];
		$this->Mensualizado=$row[Mensualizado];
		$this->Jornalizado=$row[Jornalizado];
		$this->PorHora=$row[PorHora];
		$this->PorUnidad=$row[PorUnidad];
		$this->Activo=$row[Activo];
		$this->FechaBaja=$row[FechaBaja];
		$this->Edad=CalcularEdadAntiguedad($row[FechaNacimiento],date("Y-m-d"));
		$this->Antiguedad=CalcularEdadAntiguedad($row[FechaIngreso],$Hasta);
		$this->Porcentaje=$row[PorcentajeCat];
		$this->EstadoCivil=$row[EstadoCivil];
		$this->Domicilio=$row[Domicilio];
		$this->ModalidadContratacion=$row[ModalidadContratacion];
		$this->Regimen=$row[Regimen];

}
    function ModificarEscala($EscalaNueva) {
        $this->IdCatProfe=$EscalaNueva;
        $sSql="UPDATE tblEmpleados SET IdCatProfe=". $EscalaNueva . " WHERE IdEmpleado=" . $this->IdEmpleado;
        $stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute();
        //return $sSql;
    }

}
?>