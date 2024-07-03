<?
 // Clase RelReciboConcepto
// -------------------
require_once("EnLetras.php");
require_once("clsEmpleados.php");
// include_once("../stringconexion.inc");

try {
		//$pdo = new PDO('mysql:host=127.0.0.1;dbname=barl83_barlotta', $_SESSION['user'], $_SESSION['password']);
		//$link = mysql_connect ("localhost", $_SESSION['user'], $_SESSION['password']) or die ("No se puede conectar a la Base de Datos");
	} catch(PDOException $e) {
	    echo "SESION VENCIDA";
    	echo '<script language="javascript">window.location="../sistema/index.php"</script>';
}

class clsRelReciboConcepto {

	var $IdRecibo = array();
	var $Cantidad;
	var $IdConcepto;
	var $Descripcion;
	var $Unidades;
	var $Rem;
	var $NoRem;
	var $Descuento;
	var $MontoFijo;
	var $Calculo;
	var $MontoMaximo;
	var $Orden;
	var $Activo;

function CargarRelaciones($IdRecibo) {
	$sSql='SELECT tblRelRecibosConceptos.IdRecibo, tblRelRecibosConceptos.Cantidad, tblConceptos.* FROM tblRelRecibosConceptos,tblConceptos WHERE tblRelRecibosConceptos.IdConcepto=tblConceptos.IdConcepto and IdRecibo='.$IdRecibo.' ORDER BY Orden, Haberes';
/*	// $result = mysql_db_query($_SESSION['db'],$sSql);
	while($row = mysql_fetch_array($result)) {
		$this->IdRecibo[$c;0]=$row[IdRecibo];
		$this->Cantidad[$c,1]	=$row[Cantidad];
		$this->IdConcepto[$c,2]	=$row[IdConcepto];
		$this->Descripcion[$c,3]=$row[Descripcion];
		$this->Unidades[$c,4]	=$row[Unidades];
		$this->Rem[$c,5]			=$row[Rem];
		$this->NoRem[$c,6]		=$row[NoRem];
		$this->Descuento[$c,7]	=$row[Descuento];
		$this->MontoFijo[$c,8]	=$row[MontoFijo];
		$this->Calculo[$c,9]		=$row[Calculo];
		$this->Orden[$c,10]		=$row[Orden];
		$this->Activo[$c,11]		=$row[Activo];
		$c++;
} */
// 	$this->IdConcepto=$row[IdConcepto];
// 	$this->IdRecibo=$row[IdRecibo];
// 	$this->Cantidad=$row[Cantidad];
// return $A;
}

function CalcularExpresion($precio,$tipo,$C,$U,$MF,$expre,$SH,$SumUnidades,$IdEmp,$MM,$Periodo) {

unset($A);
unset($A);
unset($pieces);
$precios = explode("#", $precio); $PD=$precios[0]; $PH=$precios[1]; $PM=$precios[2]; $PU=$precios[3]; $BC1=$precios[4];
$tipos   = explode("#", $tipo);   $TD=$tipos[0];   $TH=$tipos[1];   $TM=$tipos[2];   $TU=$tipos[3];
// PD: Precio Dia   PH: Precio Hora    PM: Precio Mes    PU: Precio Unidad
// TD: Tipo Dia     TH: Tipo Hora      TM: Tipo Mes      TU: Tipo Unidad
$pieces = explode("*", $expre);



	for ($c=0; $c<count($pieces); $c++) {
// 	while ($pieces[$c]) {
// 		$c++;
		switch ($pieces[$c]) {
			case "RA":{ $A[$c]=$PD*$TD+$PH*$TH+$PM*$TM+$PU*$TU  ;   break;}// 'R'emuneracion 'A'signada
			case "TH":{ $A[$c]=($PD*$TD+$PH*$TH+$PM*$TM+$PU*$TU)*$C;	break;}// 'T'otal 'H'aberes
	/*$A[$c]=$PD;
	$A[$c]=$TD;
		$A[$c]=$PH;
		$A[$c]=$TH;
		$A[$c]=$PM;
	echo $TM;
	echo $PU;
	echo $TU;*/

			case "U": {	 $A[$c]=$U;  break;}				// 'U'nidades
			case "D": {	 $A[$c]=$SH*$C;  break;}			// 'D'escuentos 

			case "MF": { $A[$c]=$MF;	break;}				// 'M'onto 'F'ijo
			case "C":  {	 $A[$c]=$C; break;}				// 'C'antidad
			case "BC1": {	 $A[$c]=$BC1; break;}				// 'C'antidad
			case "AAOS": { if (($SH*0.03)<$C) { $A[$c]=$C-($SH*0.03); } else { $A[$c]=0;} break;}	//
			case "AASS": { if (($SH*0.14)<$C) { $A[$c]=$C-($SH*0.14); } else { $A[$c]=0;} break;}	//
			case "ANR": {	 if ($SumUnidades<=$C) { $A[$c]=$U*$SumUnidades/200;} else { $A[$c]=$MF*$SumUnidades; } break;}	// Asignacion No Remunerativa
  			case "BC": { $Empl = new clsEmpleado($Periodo,$IdEmp); $A[$c]=$Empl->BasicoCategoria; unset($Empl); break;}// Devuelve el basico de la categoría al inicio
 			case "B":  { $Empl = new clsEmpleado($Periodo,$IdEmp); $A[$c]=$Empl->BasicoCategoriaActual; unset($Empl); break;}// Devuelve el basico de la categoría actual
  			case "ANT": {	$Empl = new clsEmpleado($Periodo,$IdEmp); $A[$c]=$Empl->Antiguedad; unset($Empl); break;} // Devuelve la cantidad de a&ntilde;os
  			// case "ANTFCalc": {	$Empl = new clsEmpleado($Periodo,$IdEmp); $A[$c]=$Empl->AntiguedadFC($Periodo); unset($Empl); break;} // Devuelve la cantidad de a&ntilde;os
// 			case "ANR": {	 if ($SumUnidades<=200) { $A[$c]=239*$SumUnidades/200;} else { $A[$c]=239; } break;}	// Asignacion No Remunerativa
			case "2SAC":{ $Anio=substr($Periodo,0,4);
				$sSql="SELECT sum(TotHaberes)/12 as Tot FROM tblRecibos WHERE PerPago<=".$Anio."12 and PerPago >=".$Anio."07 and IdEmpleado=$IdEmp";
				$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute();
				$row=$stmt->fetch();
				$A[$c]=$row['Tot'];   break;}// 2do SAC
			case "1SAC":{ $Anio=substr($Periodo,0,4);
				$sSql="SELECT sum(TotHaberes)/12 as Tot FROM tblRecibos WHERE PerPago<=".$Anio."06 and PerPago >=".$Anio."01 and IdEmpleado=$IdEmp";
				$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute();
				$row=$stmt->fetch();
				$A[$c]=$row['Tot'];   break;}// 2do SAC

			case is_numeric($pieces[$c]) : { $A[$c]=$pieces[$c]; break;} // Si es un numero
		}
	}
	$res=$A[0];
	for ($d=1; $d<count($pieces); $d++) {
		$res=$res*$A[$d];
	}
//Controla que si hay un tope maximo, este no sea superado por el calculo obtenido
	if ($res>$MM && $MM<>0) { $res=$MM; }
	return $res;
}


function ImprimirRelaciones($IdRecibo,$IdEmp) {
	$SumHaberes=0;
$pepepe=0;
$SumUnidades=0;
$R=0;
$precios=0;
$tipos=0;
$TotalRemu=0;
$Periodo=0;
$NR=0;
$c=0;
$D=0;

$sSql='SELECT tblRecibos.PerPago, tblRecibos.LugarPago, tblRecibos.FPago, tblRecibos.IdEmpleado, tblRelRecibosConceptos.IdRecibo, tblRelRecibosConceptos.Cantidad, tblConceptos.* FROM tblRelRecibosConceptos,tblConceptos, tblRecibos WHERE tblRelRecibosConceptos.IdConcepto=tblConceptos.IdConcepto and tblRelRecibosConceptos.IdRecibo='.$IdRecibo.' and tblRecibos.IdRecibo=tblRelRecibosConceptos.IdRecibo ORDER BY Orden';
//echo $sSql;

	// // $result = mysql_db_query($_SESSION['db'],$sSql); 
	$A="";
	while($row = $pepepe) {

		$A=$A.'<tr onclick="var a='.$row['IdConcepto'].'; b='.$row['Cantidad'].'; var  xxx=\'../Empleados/ModificarDetalle.php?Detalle='.$row['Descripcion'].'&IdConcepto='.$row['IdConcepto'].'&Cantidad='.$row['Cantidad'].'&Recibo='.$row['IdRecibo'].'\'; window.open(xxx,\'nuevaVentana\',\'width=500, height=200\'); "><td align="center" >'.$row['Orden'].'</td><td>'.utf8_decode($row['Descripcion']).'</td>';
        $A=$A.'<td align="center">'.number_format($row['Cantidad'], 2, ',', '.').'</td>';
	    $MM=$row['MontoMaximo']; // Asigna el monto máximo al que se puede llegar para poder calcular la expresión
        if ($row['Haberes']>0) {
        $SQL="select `tblEmpleados`.`IdEmpleado` AS `IdEmpleado`,`tblEmpleados`.`Legajo` AS `Legajo`,`tblEmpleados`.`Nombre` AS `Nombre`,`tblEmpleados`.`FechaNacimiento` AS `FechaNacimiento`,`tblEmpleados`.`FechaIngreso` AS `FechaIngreso`,`tblRecibos`.`IdCatProfe` AS `IdCatProfe`,`tblEmpleados`.`DNI` AS `DNI`,`tblEmpleados`.`CUIL` AS `CUIL`,`tblEmpleados`.`Seccion` AS `Seccion`,`tblEmpleados`.`NroCtaBanco` AS `NroCtaBanco`,`tblEmpleados`.`Banco` AS `Banco`,`tblEmpleados`.`Telefono` AS `Telefono`,`tblEmpleados`.`Mensualizado` AS `Mensualizado`,`tblEmpleados`.`Jornalizado` AS `Jornalizado`,`tblEmpleados`.`PorHora` AS `PorHora`,`tblEmpleados`.`PorUnidad` AS `PorUnidad1`,`tblEmpleados`.`Activo` AS `Activo`,`tblEmpleados`.`FechaBaja` AS `FechaBaja`,`tblEmpleados`.`IdArea` AS `IdArea`,`tblEmpleados`.`IdEmpresa` AS `IdEmpresa`,`tblAreas`.`DescripcionAreas` AS `DescripcionAreas`,`tblCatProfe`.`CCT` AS `CCT`,`tblCatProfe`.`DescripcionCatProf` AS `DescripcionCatProf`,`tblCatProfe`.`Subcategoria` AS `Subcategoria`,`tblCatProfe`.`PrecioDia` AS `PrecioDia`,`tblCatProfe`.`PrecioMes` AS `PrecioMes`,`tblCatProfe`.`PrecioHora` AS `PrecioHora`,`tblCatProfe`.`PorUnidad` AS `PorUnidad`,`tblCatProfe`.`BasicoCategoria` AS `BasicoCategoria`,`tblCatProfe`.`BasicoCategoria1` AS `BasicoCategoria1`,`tblCatProfe`.`PorcentajeCat` AS `PorcentajeCat`,`tblEmpresas`.`Nombre` AS `NombreEmpresa`,`tblEmpresas`.`Direccion` AS `Direccion`,`tblEmpresas`.`Cuit` AS `Cuit`,`tblEmpresas`.`IB` AS `IB`,`tblEmpresas`.`NroEstablecimiento` AS `NroEstablecimiento`,`tblEmpresas`.`CondIVA` AS `CondIva` ,`tblRecibos`.`IdRecibo` AS `IdRecibo` from ((((`tblEmpleados` join `tblEmpresas`) join `tblAreas`) join `tblCatProfe`) join `tblRecibos`) where ((`tblEmpleados`.`IdEmpresa` = `tblEmpresas`.`IdEmpresa`) and (`tblEmpleados`.`IdArea` = `tblAreas`.`IdArea`) and (`tblEmpresas`.`Cuit` = `tblAreas`.`Empresa`) and (`tblRecibos`.`IdCatProfe` = `tblCatProfe`.`IdCatProf`) and ( `tblEmpleados`.`IdEmpleado`=".$IdEmp.") and (`tblRecibos`.`IdRecibo`=".$IdRecibo."));";
//  	echo $SQL;
		$Periodo=$row['PerPago'];
//  		$SQL="SELECT * FROM ViewEmpleados WHERE IdEmpleado=".$IdEmp;
 		$result2 = mysql_db_query($_SESSION['db'],$SQL);
		$row2 = mysql_fetch_array($result2);
// 		return $sSql;

		$array = array($row2['PrecioDia'],$row2['PrecioMes'],$row2['PrecioHora'],$row2['PorUnidad'],$row2['BasicoCategoria1']);
		$precios= implode("#", $array);
// 		$activo = array($row2['Mensualizado'],$row2['Jornalizado'],$row2['PorHora'],$row2['PorUnidad1]); //Según el tipo de contrato
		$activo = array($row2['Jornalizado'],$row2['Mensualizado'],$row2['PorHora'],$row2['PorUnidad1']); //Según el tipo de contrato
		$tipos= implode("#", $activo);
//   		$MM=$row['MontoMaximo']; // Asigna el monto máximo al que se puede llegar para poder calcular la expresión
		$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
		$SumHaberes=$SumHaberes+$pp;
		$SumUnidades=$SumUnidades+$row['Cantidad'];
		$A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td><td align="right">0,00</td><td align="right">0,00</td>';
		$R=$R+$pp;
	    } 
else {
    //echo "Entrando<br>";
	if ($row['MontoFijo']>0) {
		$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
		if ($row['Rem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>'; $R=$R+$row['MontoFijo'];} else { $A=$A.'<td align="right">0,00</td>';}
		if ($row['NoRem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>'; $NR=$NR+$pp; } else { $A=$A.'<td align="right">0,00</td>';}
 		if ($row['Descuento']>0) { $A=$A.'<td align="right">'.number_format($row['MontoFijo'], 2, ',', '.').'</td>'; $D=$D+$row['MontoFijo'];} else { $A=$A.'<td align="right">0,00</td></tr>';}
	} 
	else {
		$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
		if ($row['Rem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>';  $R=$R+$pp;} else { $A=$A.'<td align="right">0,00</td>';}
		$TotalRemu=$TotalRemu+$pp;
	 	if ($row['NoRem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>'; $NR=$NR+$pp;} else { $A=$A.'<td align="right">0,00</td>';}
		if ($row['Descuento']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>';  $D=$D+$pp; } else { $A=$A.'<td align="right">0,00</td></tr>';}
	}
}
		$c++;
	}
	
 	$sSql="UPDATE tblRecibos SET TotHaberes=".$R.", NoRem='".$NR."', Descuentos=".$D." WHERE IdRecibo=".$IdRecibo;
 	//echo $sSql;
	// $result = mysql_db_query($_SESSION['db'],$sSql);

$A=$A.'<tr onclick="var  xxx=\'../Empleados/ModificarDetalle.php?Detalle=0&IdConcepto=0&Cantidad=0&Recibo=0\'; window.open(xxx,\'nuevaVentana\',\'width=300, height=400\'); "><td>-</td><td>-</td><td align=center>-</td><td align=right>-</td><td align=right>-</td><td align=right>-</td></tr>';
 $V=new EnLetras();
 $monto=strtoupper($V->ValorEnLetras($R+$NR-$D,"pesos"));
	$A=$A.'<tr>
      <td align="center"><strong></strong></td>
      <td align="center"><strong></strong></td>
      <td align="center"><strong></strong></td>
      <td align="center"><strong>'.number_format($R, 2, ',', '.').'</strong></td>
      <td align="center"><strong>'.number_format($NR, 2, ',', '.').'</strong></td>
      <td align="center"><strong>'.number_format($D, 2, ',', '.').'</strong></td>
    </tr>
	<tr>
      <td align="center" colspan="2"></td>
      <td align="center"></td>
      <td colspan="2" align="center"><strong>NETO A COBRAR</strong></td>
      <td align="right" bgcolor="lightGray"><strong>'.number_format($R+$NR-$D, 2, ',', '.').'</strong></td>
    </tr>
	 <tr>
      <td colspan="6" bgcolor="lightGray" style="font-size : 10px;"><strong>Son pesos: '.$monto.'</strong></td>
    </tr>';
    
return $A;
//return $sSql." ".$c;
}

function ImprimirRelacionesPDF($IdRecibo,$IdEmp,$FI) {
	$SumHaberes=0;
	$pepepe=0;
	$SumUnidades=0;
	$R=0;
	$precios=0;
	$tipos=0;
	$Periodo=0;
	$NR=0;
	$TotalRemu=0;
	$D=0;
	$c=0;

$sSql='SELECT tblRecibos.PerPago, tblRecibos.LugarPago, tblRecibos.FPago, tblRecibos.IdEmpleado, tblRecibos.FechaUltLiq, tblRelRecibosConceptos.IdRecibo, tblRelRecibosConceptos.Cantidad, tblConceptos.* FROM tblRelRecibosConceptos,tblConceptos, tblRecibos WHERE tblRelRecibosConceptos.IdConcepto=tblConceptos.IdConcepto and tblRelRecibosConceptos.IdRecibo='.$IdRecibo.' and tblRecibos.IdRecibo=tblRelRecibosConceptos.IdRecibo ORDER BY Orden';
//echo $sSql;

	// // $result = mysql_db_query($_SESSION['db'],$sSql); 
	$A="";
	while($row = $pepepe) {
				
		//$A=$A.'<tr onclick="var a='.$row['IdConcepto].'; b='.$row['Cantidad].'; var  xxx=\'../Empleados/ModificarDetalle.php?Detalle='.$row['Descripcion].'&IdConcepto='.$row['IdConcepto].'&Cantidad='.$row['Cantidad].'&Recibo='.$row['IdRecibo].'\'; window.open(xxx,\'nuevaVentana\',\'width=300, height=400\'); "><td align="center" >'.$row['Orden].'</td><td>'.utf8_decode($row['Descripcion]).'</td>';
		//$A=$A.'<td align="center">'.number_format($row['Cantidad'], 2, ',', '.').'</td>';
 		$MM=$row['MontoMaximo']; // Asigna el monto máximo al que se puede llegar para poder calcular la expresión
 	    if ($row['Haberes']>0) {
        $SQL="select `tblEmpleados`.`IdEmpleado` AS `IdEmpleado`,`tblEmpleados`.`Legajo` AS `Legajo`,`tblEmpleados`.`Nombre` AS `Nombre`,`tblEmpleados`.`FechaNacimiento` AS `FechaNacimiento`,`tblEmpleados`.`FechaIngreso` AS `FechaIngreso`,`tblRecibos`.`IdCatProfe` AS `IdCatProfe`,`tblEmpleados`.`DNI` AS `DNI`,`tblEmpleados`.`CUIL` AS `CUIL`,`tblEmpleados`.`Seccion` AS `Seccion`,`tblEmpleados`.`NroCtaBanco` AS `NroCtaBanco`,`tblEmpleados`.`Banco` AS `Banco`,`tblEmpleados`.`Telefono` AS `Telefono`,`tblEmpleados`.`Mensualizado` AS `Mensualizado`,`tblEmpleados`.`Jornalizado` AS `Jornalizado`,`tblEmpleados`.`PorHora` AS `PorHora`,`tblEmpleados`.`PorUnidad` AS `PorUnidad1`,`tblEmpleados`.`Activo` AS `Activo`,`tblEmpleados`.`FechaBaja` AS `FechaBaja`,`tblEmpleados`.`IdArea` AS `IdArea`,`tblEmpleados`.`IdEmpresa` AS `IdEmpresa`,`tblAreas`.`DescripcionAreas` AS `DescripcionAreas`,`tblCatProfe`.`CCT` AS `CCT`,`tblCatProfe`.`DescripcionCatProf` AS `DescripcionCatProf`,`tblCatProfe`.`Subcategoria` AS `Subcategoria`,`tblCatProfe`.`PrecioDia` AS `PrecioDia`,`tblCatProfe`.`PrecioMes` AS `PrecioMes`,`tblCatProfe`.`PrecioHora` AS `PrecioHora`,`tblCatProfe`.`PorUnidad` AS `PorUnidad`,`tblCatProfe`.`BasicoCategoria` AS `BasicoCategoria`,`tblCatProfe`.`BasicoCategoria1` AS `BasicoCategoria1`,`tblCatProfe`.`PorcentajeCat` AS `PorcentajeCat`,`tblEmpresas`.`Nombre` AS `NombreEmpresa`,`tblEmpresas`.`Direccion` AS `Direccion`,`tblEmpresas`.`Cuit` AS `Cuit`,`tblEmpresas`.`IB` AS `IB`,`tblEmpresas`.`NroEstablecimiento` AS `NroEstablecimiento`,`tblEmpresas`.`CondIVA` AS `CondIva` ,`tblRecibos`.`IdRecibo` AS `IdRecibo` from ((((`tblEmpleados` join `tblEmpresas`) join `tblAreas`) join `tblCatProfe`) join `tblRecibos`) where ((`tblEmpleados`.`IdEmpresa` = `tblEmpresas`.`IdEmpresa`) and (`tblEmpleados`.`IdArea` = `tblAreas`.`IdArea`) and (`tblEmpresas`.`Cuit` = `tblAreas`.`Empresa`) and (`tblRecibos`.`IdCatProfe` = `tblCatProfe`.`IdCatProf`) and ( `tblEmpleados`.`IdEmpleado`=".$IdEmp.") and (`tblRecibos`.`IdRecibo`=".$IdRecibo."));";
        //$SQL="select `tblEmpleados`.`IdEmpleado` AS `IdEmpleado`,`tblEmpleados`.`Legajo` AS `Legajo`,`tblEmpleados`.`Nombre` AS `Nombre`,`tblEmpleados`.`FechaNacimiento` AS `FechaNacimiento`,`tblEmpleados`.`FechaIngreso` AS `FechaIngreso`,`tblRecibos`.`IdCatProfe` AS `IdCatProfe`,`tblEmpleados`.`DNI` AS `DNI`,`tblEmpleados`.`CUIL` AS `CUIL`,`tblEmpleados`.`Seccion` AS `Seccion`,`tblEmpleados`.`NroCtaBanco` AS `NroCtaBanco`,`tblEmpleados`.`Banco` AS `Banco`,`tblEmpleados`.`Telefono` AS `Telefono`,`tblEmpleados`.`Mensualizado` AS `Mensualizado`,`tblEmpleados`.`Jornalizado` AS `Jornalizado`,`tblEmpleados`.`PorHora` AS `PorHora`,`tblEmpleados`.`PorUnidad` AS `PorUnidad1`,`tblEmpleados`.`Activo` AS `Activo`,`tblEmpleados`.`FechaBaja` AS `FechaBaja`,`tblEmpleados`.`IdArea` AS `IdArea`,`tblEmpleados`.`IdEmpresa` AS `IdEmpresa`,`tblAreas`.`DescripcionAreas` AS `DescripcionAreas`,`tblCatProfe`.`CCT` AS `CCT`,`tblCatProfe`.`DescripcionCatProf` AS `DescripcionCatProf`,`tblCatProfe`.`Subcategoria` AS `Subcategoria`,`tblCatProfe`.`PrecioDia` AS `PrecioDia`,`tblCatProfe`.`PrecioMes` AS `PrecioMes`,`tblCatProfe`.`PrecioHora` AS `PrecioHora`,`tblCatProfe`.`PorUnidad` AS `PorUnidad`,`tblCatProfe`.`BasicoCategoria` AS `BasicoCategoria`,`tblCatProfe`.`PorcentajeCat` AS `PorcentajeCat`,`tblEmpresas`.`Nombre` AS `NombreEmpresa`,`tblEmpresas`.`Direccion` AS `Direccion`,`tblEmpresas`.`Cuit` AS `Cuit`,`tblEmpresas`.`IB` AS `IB`,`tblEmpresas`.`NroEstablecimiento` AS `NroEstablecimiento`,`tblEmpresas`.`CondIVA` AS `CondIva` ,`tblRecibos`.`IdRecibo` AS `IdRecibo` from ((((`tblEmpleados` join `tblEmpresas`) join `tblAreas`) join `tblCatProfe`) join `tblRecibos`) where ((`tblEmpleados`.`IdEmpresa` = `tblEmpresas`.`IdEmpresa`) and (`tblEmpleados`.`IdArea` = `tblAreas`.`IdArea`) and (`tblEmpresas`.`Cuit` = `tblAreas`.`Empresa`) and (`tblRecibos`.`IdCatProfe` = `tblCatProfe`.`IdCatProf`) and ( `tblEmpleados`.`IdEmpleado`=".$IdEmp.") and (`tblRecibos`.`IdRecibo`=".$IdRecibo."));";
//  	echo $SQL;
		$Periodo=$row['PerPago'];
		if($row['FechaUltLiq']=='0000-00-00') { 
		    $sSql="UPDATE tblRecibos SET FechaUltLiq='". Date("Y-m-d") ."' WHERE tblRecibos.IdEmpleado=".$IdEmp. " and IdRecibo=" . $IdRecibo;
		    $resultMaxFecha = mysql_db_query($_SESSION['db'],$sSql);
		    $FechaUltLiq=Date("d-m-Y"); 
		} 
		else { $sSql='SELECT max(FechaUltLiq) as FechaUltLiq FROM tblRecibos WHERE tblRecibos.IdEmpleado='.$IdEmp;
		      $resultMaxFecha = mysql_db_query($_SESSION['db'],$sSql);
		      $rowMaxFecha = mysql_fetch_array($resultMaxFecha);
		      $FechaUltLiq= $rowMaxFecha['FechaUltLiq']; 
		}
		  //Fecha en la que se imprime el Recibo
//  		$SQL="SELECT * FROM ViewEmpleados WHERE IdEmpleado=".$IdEmp;
 		$result2 = mysql_db_query($_SESSION['db'],$SQL);
		$row2 = mysql_fetch_array($result2);
// 		return $sSql;
		//if ($row['Descripcion]=="Antiguedad Bodega") { echo "ESte es el resultado:".$row2; }
		$array = array($row2['PrecioDia'],$row2['PrecioMes'],$row2['PrecioHora'],$row2['PorUnidad'],$row2['BasicoCategoria1']);
		$precios= implode("#", $array);// 		
        //$activo = array($row2['Mensualizado'],$row2['Jornalizado'],$row2['PorHora'],$row2['PorUnidad1]); //Según el tipo de contrato
		$activo = array($row2['Jornalizado'],$row2['Mensualizado'],$row2['PorHora'],$row2['PorUnidad1']); //Según el tipo de contrato
		$tipos= implode("#", $activo);
//   		$MM=$row['MontoMaximo']; // Asigna el monto máximo al que se puede llegar para poder calcular la expresión
		$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
		$col4=$pp;
		$SumHaberes=$SumHaberes+$pp;
		$SumUnidades=$SumUnidades+$row['Cantidad'];
		$A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td><td align="right">0,00</td><td align="right">0,00</td>';

		$data[] = array('col1'=>$row['IdConcepto'],
		                'col2'=>utf8_decode($row['Descripcion']),
						'col3'=>number_format($row['Cantidad'], 2, ',', '.'),
						'col4'=>number_format($col4, 2, ',', '.'),
						'col5'=>'0.00',
						'col6'=>'0.00'
					);
				
		
		$R=$R+$pp;
	} 
	else {
		if ($row['MontoFijo']>0) {
 			$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
			//$col5=$pp;
 			
			$col4='0.00';
			$col5='0.00';
			$col6='0.00';
			
			if ($row['Rem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>'; $R=$R+$row['MontoFijo']; $col4=$pp; } else { $A=$A.'<td align="right">0,00</td>';}
 			if ($row['NoRem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>'; $NR=$NR+$pp;  $col5=$pp;} else { $A=$A.'<td align="right">0,00</td>';}
	 		if ($row['Descuento']>0) { $A=$A.'<td align="right">'.number_format($row['MontoFijo'], 2, ',', '.').'</td>'; $D=$D+$row['MontoFijo'];  $col6=$pp;} else { $A=$A.'<td align="right">0,00</td></tr>';}
			$data[] = array('col1'=>$row['IdConcepto'],
			            'col2'=>utf8_decode($row['Descripcion']),
						'col3'=>number_format($row['Cantidad'], 2, ',', '.'),
						'col4'=>number_format($col4, 2, ',', '.'),
						'col5'=>number_format($col5, 2, ',', '.'),
						'col6'=>"0.00",
					);
		} 
		else {
 			$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
			//$col6=$pp;

 			$col4='0.00';
			$col5='0.00';
			$col6='0.00';
			
 			if ($row['Rem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>';  $R=$R+$pp; $col4=$pp; } else { 
 					$A=$A.'<td align="right">0,00</td>'; $col4=$pp; }
			$TotalRemu=$TotalRemu+$pp;
		 	if ($row['NoRem']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>'; $NR=$NR+$pp; $col5=$pp;} else { $A=$A.'<td align="right">0,00</td>';}
 			if ($row['Descuento']>0) { $A=$A.'<td align="right">'.number_format($pp, 2, ',', '.').'</td>';  $D=$D+$pp; $col6=$pp;} else { $A=$A.'<td align="right">0,00</td></tr>';}

			$data[] = array('col1'=>$row['IdConcepto'],
			            'col2'=>utf8_decode($row['Descripcion']),
						'col3'=>number_format($row['Cantidad'], 2, ',', '.'),
						'col4'=>'0.00',
						'col5'=>'0.00',
						'col6'=>number_format($col6, 2, ',', '.'),
					);
		}
	}
		$c++;
	}

 	$sSql="UPDATE tblRecibos SET TotHaberes=".$R.", NoRem='".$NR."', Descuentos=".$D." WHERE IdRecibo=".$IdRecibo;
 	//echo $sSql;
	// $result = mysql_db_query($_SESSION['db'],$sSql);

//$A=$A.'<tr onclick="var  xxx=\'../Empleados/ModificarDetalle.php?Detalle=0&IdConcepto=0&Cantidad=0&Recibo=0\'; window.open(xxx,\'nuevaVentana\',\'width=300, height=400\'); "><td>-</td><td>-</td><td align=center>-</td><td align=right>-</td><td align=right>-</td><td align=right>-</td></tr>';
 $V=new EnLetras();
 $monto=strtoupper($V->ValorEnLetras($R+$NR-$D,"pesos"));
	/*$A=$A.'<tr>
      <td align="center"><strong></strong></td>
      <td align="center"><strong></strong></td>
      <td align="center"><strong></strong></td>
      <td align="center"><strong>'.number_format($R, 2, ',', '.').'</strong></td>
      <td align="center"><strong>'.number_format($NR, 2, ',', '.').'</strong></td>
      <td align="center"><strong>'.number_format($D, 2, ',', '.').'</strong></td>
    </tr>
	<tr>
      <td align="center" colspan="2"></td>
      <td align="center"></td>
      <td colspan="2" align="center"><strong>NETO A COBRAR</strong></td>
      <td align="right" bgcolor="lightGray"><strong>'.number_format($R+$NR-$D, 2, ',', '.').'</strong></td>
    </tr>
	 <tr>
      <td colspan="6" bgcolor="lightGray" style="font-size : 10px;"><strong>Son pesos: '.$monto.'</strong></td>
    </tr>';*/
	$data[] = array('col1'=>' ',
						'col2'=>'LUGAR DE PAGO',
						'col3'=>'Fecha Pago',
						'col4'=>'<b>'.number_format($R, 2, ',', '.').'</b>',
						'col5'=>'<b>'.number_format($NR, 2, ',', '.').'</b>',
						'col6'=>'<b>'.number_format($D, 2, ',', '.').'</b>',
					);
	$data[] = array('col1'=>' ',
						'col2'=>'San Martín - Mendoza',
	                    'col3'=>substr($FI,8,2) . "/" . substr($FI,5,2) . "/" . substr($FI,0,4) ,    //Fecha de Pago
						'col4'=>'',
						'col5'=>'Neto a Cobrar',
						'col6'=>'<b>'.number_format(($R+$NR-$D), 2, ',', '.').'</b>'
					);
	
	//'col3'=>Date("d/m/Y"),   //Fecha de pago
	
	
	$data[] = array('col1'=>' ',
						'col2'=>'ULTIMA LIQUIDACIÓN',
						'col3'=>'BANCO',
						'col4'=>'FECHA ULT.DEPÓS',
						'col5'=>'',
						'col6'=>''
					);
	
	$sSql="SELECT * FROM tblRecibos WHERE PerPago=".$Periodo." and IdEmpleado=".$IdEmp;
	$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute(); $row=$stmt->fetch(); 
	
	$data[] = array('col1'=>' ',
	                    'col2'=> $row['PerUltLiq'], //'01/2020', //substr($Periodo,4,2)-1 . " / " . substr($Periodo,0,4),     //Ultima Liquidación
						'col3'=>'Galicia',
                        'col4'=> $row['FechaUltLiq'], // 10/02/2020', //$FechaUltLiq,                             // Fecha de ultimo depósito
						'col5'=>'ART 12 LEY 17250',
						'col6'=>''
					);
	
	
	return $data;
}

function ImprimirRelacionesEnLibro($IdRecibo,$IdEmp) {
	$SumHaberes=0;
	$NR=0;

	$pepepe=0;
	$SumUnidades=0;
	$R=0;
	$precios=0;
	$tipos=0;
	$Periodo=0;
	$NR=0;
	$TotalRemu=0;
	$D=0;
	$c=0;
	$MM=0;
	$A=0;


$sSql='SELECT tblRecibos.PerPago, tblRecibos.LugarPago, tblRecibos.FPago, tblRecibos.IdEmpleado, tblRelRecibosConceptos.IdRecibo, tblRelRecibosConceptos.Cantidad, tblConceptos.* FROM tblRelRecibosConceptos,tblConceptos, tblRecibos WHERE tblRelRecibosConceptos.IdConcepto=tblConceptos.IdConcepto and tblRelRecibosConceptos.IdRecibo='.$IdRecibo.' and tblRecibos.IdRecibo=tblRelRecibosConceptos.IdRecibo ORDER BY Orden';


	// $result = mysql_db_query($_SESSION['db'],$sSql); $A="";
	while($row = $pepepe) {

		$A=$A.$row['"IdConcepto"'].'\n'.$row["'Cantidad'"].'\n'.$row['"Descripcion"'].'\n';
// 		$A=$A.'<td align="center">'.number_format($row['Cantidad'], 2, ',', '.').'</td>';
//  		$MM=$row['MontoMaximo]; // Asigna el monto máximo al que se puede llegar para poder calcular la expresión

	if ($row['Haberes']>0) {
$SQL="select `tblEmpleados`.`IdEmpleado` AS `IdEmpleado`,`tblEmpleados`.`Legajo` AS `Legajo`,`tblEmpleados`.`Nombre` AS `Nombre`,`tblEmpleados`.`FechaNacimiento` AS `FechaNacimiento`,`tblEmpleados`.`FechaIngreso` AS `FechaIngreso`,`tblRecibos`.`IdCatProfe` AS `IdCatProfe`,`tblEmpleados`.`DNI` AS `DNI`,`tblEmpleados`.`CUIL` AS `CUIL`,`tblEmpleados`.`Seccion` AS `Seccion`,`tblEmpleados`.`NroCtaBanco` AS `NroCtaBanco`,`tblEmpleados`.`Banco` AS `Banco`,`tblEmpleados`.`Telefono` AS `Telefono`,`tblEmpleados`.`Mensualizado` AS `Mensualizado`,`tblEmpleados`.`Jornalizado` AS `Jornalizado`,`tblEmpleados`.`PorHora` AS `PorHora`,`tblEmpleados`.`PorUnidad` AS `PorUnidad1`,`tblEmpleados`.`Activo` AS `Activo`,`tblEmpleados`.`FechaBaja` AS `FechaBaja`,`tblEmpleados`.`IdArea` AS `IdArea`,`tblEmpleados`.`IdEmpresa` AS `IdEmpresa`,`tblAreas`.`DescripcionAreas` AS `DescripcionAreas`,`tblCatProfe`.`CCT` AS `CCT`,`tblCatProfe`.`DescripcionCatProf` AS `DescripcionCatProf`,`tblCatProfe`.`Subcategoria` AS `Subcategoria`,`tblCatProfe`.`PrecioDia` AS `PrecioDia`,`tblCatProfe`.`PrecioMes` AS `PrecioMes`,`tblCatProfe`.`PrecioHora` AS `PrecioHora`,`tblCatProfe`.`PorUnidad` AS `PorUnidad`,`tblCatProfe`.`BasicoCategoria` AS `BasicoCategoria`,`tblCatProfe`.`PorcentajeCat` AS `PorcentajeCat`,`tblEmpresas`.`Nombre` AS `NombreEmpresa`,`tblEmpresas`.`Direccion` AS `Direccion`,`tblEmpresas`.`Cuit` AS `Cuit`,`tblEmpresas`.`IB` AS `IB`,`tblEmpresas`.`NroEstablecimiento` AS `NroEstablecimiento`,`tblEmpresas`.`CondIVA` AS `CondIva` ,`tblRecibos`.`IdRecibo` AS `IdRecibo` from ((((`tblEmpleados` join `tblEmpresas`) join `tblAreas`) join `tblCatProfe`) join `tblRecibos`) where ((`tblEmpleados`.`IdEmpresa` = `tblEmpresas`.`IdEmpresa`) and (`tblEmpleados`.`IdArea` = `tblAreas`.`IdArea`) and (`tblEmpresas`.`Cuit` = `tblAreas`.`Empresa`) and (`tblRecibos`.`IdCatProfe` = `tblCatProfe`.`IdCatProf`) and ( `tblEmpleados`.`IdEmpleado`=".$IdEmp.") and (`tblRecibos`.`IdRecibo`=".$IdRecibo."));";
//  	echo $SQL;
		$Periodo=$row['PerPago'];
//  		$SQL="SELECT * FROM ViewEmpleados WHERE IdEmpleado=".$IdEmp;
 		$result2 = mysql_db_query($_SESSION['db'],$SQL);
		$row2 = mysql_fetch_array($result2);
// 		return $sSql;
		$array = array($row2['PrecioDia'],$row2['PrecioMes'],$row2['PrecioHora'],$row2['PorUnidad']);
		$precios= implode("#", $array);
// 		$activo = array($row2['Mensualizado'],$row2['Jornalizado'],$row2['PorHora'],$row2['PorUnidad1']); //Según el tipo de contrato
		$activo = array($row2['Jornalizado'],$row2['Mensualizado'],$row2['PorHora'],$row2['PorUnidad1']); //Según el tipo de contrato
		$tipos= implode("#", $activo);
//   		$MM=$row['MontoMaximo']; // Asigna el monto máximo al que se puede llegar para poder calcular la expresión
		$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
		$SumHaberes=$SumHaberes+$pp;
		$SumUnidades=$SumUnidades+$row['Cantidad'];
		$A=$A.number_format($pp, 2, ',', '.');
		$R=$R+$pp;
	} 
	else {
		if ($row['MontoFijo']>0) {
 			$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
			if ($row['Rem']>0) { 
				$A=$A.number_format($pp, 2, ',', '.'); 
				$R=$R+$row['MontoFijo'];} else { $A=$A."0,00";
			}
 			if ($row['NoRem']>0) { $A=$A.number_format($pp, 2, ',', '.'); $NR=$NR+$pp; } else { $A=$A."0,00";}
	 		if ($row['Descuento']>0) { $A=$A.number_format($row['MontoFijo'], 2, ',', '.'); $D=$D+$row['MontoFijo'];} 
			else { $A=$A.'0,00'; }
		} 
		else {
 			$pp=$this->CalcularExpresion($precios,$tipos,$row['Cantidad'],$row['Unidades'],$row['MontoFijo'],$row['Calculo'],$SumHaberes,$SumUnidades,$IdEmp,$MM,$Periodo);
			if ($row['Rem']>0) { $A=$A.number_format($pp, 2, ',', '.');  $R=$R+$pp;} else { $A=$A."0,00";}
			$TotalRemu=$TotalRemu+$pp;
		 	if ($row['NoRem']>0) { $A=$A.number_format($pp, 2, ',', '.'); $NR=$NR+$pp;} else { $A=$A."0,00";}
 			if ($row['Descuento']>0) { $A=$A.number_format($pp, 2, ',', '.');  $D=$D+$pp; } else { $A=$A."0,00";}
		}
	}
		$c++;
	}

 $V=new EnLetras();
 $monto=strtoupper($V->ValorEnLetras($R+$NR-$D,"pesos"));
	$A=$A.number_format($R, 2, ',', '.').'</strong><strong>'.number_format($NR, 2, ',', '.').'</strong>
      <strong>'.number_format($D, 2, ',', '.').'</strong><strong>NETO A COBRAR</strong>
      <strong>'.number_format($R+$NR-$D, 2, ',', '.').'</strong><strong>Son pesos: '.$monto.'</strong>';
return $A;
}




function AgregarRelacion($IdRecibo, $IdConcepto, $Cantidad) {
//  	$objResponse = new xajaxResponse();
 	$sSql="INSERT INTO tblRelRecibosConceptos (IdRecibo, IdConcepto, Cantidad) VALUES ($IdRecibo,$IdConcepto,$Cantidad)";
	// $result = mysql_db_query($_SESSION['db'],$sSql);
// 	$objResponse->alert($sSql);
/*	if (mysql_affected_rows($result)) { $objResponse->alert("Registro guardado"); }
	else { $objResponse->alert("No se pudo guardar"); }*/
	return "Se dio de alta";
}

function ModificarRelacion($IdRecibo,$IdConcepto,$Cantidad) {
    //if ($Cantidad=="") { $Cantidad=0;}
	$sSql="UPDATE tblRelRecibosConceptos SET IdRecibo=".$IdRecibo.", IdConcepto=".$IdConcepto.", Cantidad=".$Cantidad." WHERE IdRecibo=".$IdRecibo." and IdConcepto=".$IdConcepto;
 	// $result = mysql_db_query($_SESSION['db'],$sSql);
	return "Registro Modificado";
}

function EliminarRelacion($IdRecibo,$IdConcepto) {
	$sSql="DELETE FROM tblRelRecibosConceptos WHERE IdRecibo=".$IdRecibo." and IdConcepto=".$IdConcepto;
  	// $result = mysql_db_query($_SESSION['db'],$sSql);
	return "Registro eliminado";
}
function EliminarTodasLasRelaciones($IdRecibo) {
	$sSql="DELETE FROM tblRelRecibosConceptos WHERE IdRecibo=".$IdRecibo;
  	// $result = mysql_db_query($_SESSION['db'],$sSql);
	return "Relaciones eliminadas";
}

}