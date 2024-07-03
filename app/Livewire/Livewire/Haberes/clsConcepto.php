<?
 // Clase Concepto
// -------------------
//$pdo = new PDO('mysql:host=127.0.0.1;dbname=barl83_barlotta', $_SESSION['user'], $_SESSION['password']);
// include_once("../stringconexion.inc");

class clsConcepto {

	var $IdConcepto;
	var $Descripcion;
	var $Unidades;
	var $Haberes;
	var $R;
	var $NR;
	var $D;
	var $MontoFijo;	// En caso de ser un monto fijo
	var $MontoMaximo;
	var $Calculo;
	var $Orden;
	var $Activo;


function CargarConcepto($Id,$CantUnidades) {

	$sSql='SELECT * FROM tblConceptos WHERE IdConcepto='.$Id;
	$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute();
// 	$result = mysql_db_query($_SESSION['db'],$sSql);
// 	$row = mysql_fetch_array($result);
	$row=$stmt->fetch();
	$this->IdConcepto=$row['IdConcepto'];
	$this->Descripcion=utf8_decode($row['Descripcion']);
	$this->Unidades=$CantUnidades;
	if ($row['Haberes']) { $this->Haberes=1; } else { $this->Haberes=0; }
	if ($row['Rem']) { $this->R=1; $this->NR=0; $this->D=0; }
	if ($row['NoRem']) { $this->R=0; $this->NR=1; $this->D=0; }
	if ($row['Descuento']) { $this->R=0; $this->NR=0; $this->D=1; }
	$this->MontoFijo=$row['MontoFijo']; // En caso de ser un monto fijo
 	$this->Calculo=$row['Calculo'];
	$this->MontoMaximo=$row['MontoMaximo'];
	$this->Orden=$row['Orden'];
	if ($row['Activo']) { $this->Activo=1; } else { $this->Activo=0; }
}

function AgregarConcepto($Descripcion, $Unidades, $Haberes, $Rem, $NoRem, $Descuento, $MFijo, $Calculo, $MontoMaximo, $Orden, $Activo) {
	if ($Haberes=="on") { $Haberes=1; } else { $Haberes=0; }
	if ($Rem=="on") { $Rem=1; } else { $Rem=0; }
	if ($NoRem=="on") { $NoRem=1; } else { $NoRem=0; }
	if ($Descuento=="on") { $Descuento=1; } else { $Descuento=0; }
	if ($Activo=="on") { $Activo=1; } else { $Activo=0; }
	if ($Descripcion<>"" and $Calculo<>"" and $Orden<>"") {
		$sSql="INSERT INTO tblConceptos (Descripcion, Unidades, Haberes, Rem, NoRem, Descuento, MontoFijo, Calculo, MontoMaximo, Orden, Activo) VALUES ('$Descripcion', $Unidades, $Haberes, $Rem, $NoRem, $Descuento, $MFijo, '$Calculo', $MontoMaximo, $Orden, $Activo)";
		$sSql=utf8_encode($sSql);
		$stmt =  $GLOBALS['pdo']->prepare(utf8_encode($sSql)); $stmt->execute();
// 		$result = mysql_db_query($_SESSION['db'],$sSql);
		//$row = mysql_fetch_array($result);
		if (!$stmt->affected_rows) { return "Se dió de Alta correctamente"; } else { return "Ocurrió un error"; }
// 		if (mysql_affected_rows()) { return "Se dió de Alta correctamente"; } else { return "Ocurrió un error"; }
 	}
	else { return "Ocurrió un error y no se pudieron dar de Alta los datos"; }
}

function ModificarConcepto($Id, $Descripcion, $Unidades, $Haberes, $Rem, $NoRem, $Descuento, $MFijo, $Calculo, $MontoMaximo, $Orden, $Activo) {
	if ($Haberes=="on") { $Haberes=1; } else { $Haberes=0; }
	if ($Rem=="on") { $Rem=1; } else { $Rem=0; }
	if ($NoRem=="on") { $NoRem=1; } else { $NoRem=0; }
	if ($Descuento=="on") { $Descuento=1; } else { $Descuento=0; }
	if ($Activo=="on") { $Activo=1; } else { $Activo=0; }
	if ($Descripcion<>"" and $Calculo<>"" and $Orden<>"") {
		$sSql="UPDATE tblConceptos SET Descripcion='$Descripcion', Unidades=$Unidades, Haberes=$Haberes, Rem=$Rem, NoRem=$NoRem, Descuento=$Descuento, MontoFijo=$MFijo, Calculo='$Calculo', MontoMaximo=$MontoMaximo, Orden='$Orden', Activo=$Activo WHERE IdConcepto=$Id";
//   		return $sSql;
		$stmt =  $GLOBALS['pdo']->prepare(utf8_encode($sSql)); $stmt->execute();
//  		$result = mysql_db_query($_SESSION['db'],utf8_encode($sSql));
 		//$row = mysql_fetch_array($result);
		return "Se Modificó correctamente";
		if (!$stmt->affected_rows) { return "Se Modificó correctamente"; } else { return "Ocurrió un error"; }
// 		if (mysql_affected_rows()) { return "Se Modificó correctamente"; } else { return "Ocurrió un error"; }
	}
else { return "Ocurrió un error y no se pudieron dar de Modificar los datos"; }
}

function ActualizarConcepto($Id,$Descripcion,$Unidades,$Haberes,$Rem,$NoRem,$Descuento,$MFijo,$Calculo,$MontoMaximo,$Orden,$Activo) {



	if ($Haberes=="on") { $Haberes=1; } else { $Haberes=0; }
	if ($Rem=="on") { $Rem=1; } else { $Rem=0; }
	if ($NoRem=="on") { $NoRem=1; } else { $NoRem=0; }
	if ($Descuento=="on") { $Descuento=1; } else { $Descuento=0; }
	if ($Activo=="on") { $Activo=1; } else { $Activo=0; }
	if ($Descripcion<>"" and $Calculo<>"" and $Orden<>"") {
		$sSql1="INSERT INTO tblConceptos (Descripcion, Unidades, Haberes, Rem, NoRem, Descuento, MontoFijo, Calculo, MontoMaximo, Orden, Activo) VALUES ('$Descripcion', $Unidades, $Haberes, $Rem, $NoRem, $Descuento, $MFijo, '$Calculo', $MontoMaximo, '$Orden', 1)";
		$stmt =  $GLOBALS['pdo']->prepare(utf8_encode($sSql1)); $stmt->execute();
//  		$result = mysql_db_query($_SESSION['db'],$sSql1);
// 		$row = mysql_fetch_array($result);
		$sSql2="UPDATE tblConceptos SET Activo=0 WHERE IdConcepto=$Id";
		$stmt2 =  $GLOBALS['pdo']->prepare($sSql2); $stmt2->execute();
//  		$result = mysql_db_query($_SESSION['db'],$sSql2);
// 		$row = mysql_fetch_array($result);
		if (!$stmt->affected_rows) { return "Se Actualizó correctamente"; } else { return $sSql1."Ocurrió un error".$sSql2; }
// 		if (mysql_affected_rows()) { return $sSql1."Se Actualizó correctamente".$sSql2; } else { return $sSql1."Ocurrió un error".$sSql2; }
	}
	else { return "Ocurrió un error y no se pudieron dar de Actualizar los datos"; } 
// Return "PEPE";
}


}
?>