<?

namespace App\Http\Livewire\Haberes;

use Livewire\Component;
// session_start();
//require_once("clsRelReciboConcepto.php");
//require_once("clsConcepto.php");
// include_once("../stringconexion.inc");
//pdo = new PDO('mysql:host=127.0.0.1;dbname=barl83_barlotta', $_SESSION['user'], $_SESSION['password']);

 	// $Conc = new clsConcepto();
	// $Rela = new clsRelReciboConcepto();

class clsRecibo extends Component {

	public $IdRecibo;
	public $PerPago;
	public $Banco;
	public $LugarPago;
	public $FPago;
	public $IdEmpleado;
	public $IdCatProfe;
	
	public $TotHaberes;
	public $NoRem;
	public $Descuentos;
	
	public $PerUltLiq;
	public $FechaUltLiq;
	
//  	$Conc = new clsConcepto();
//  	$Rela = new Extends clsRelReciboConcepto();
// function __construct($Lugar) {

// // 	$this->IdRecibo=10;
// 	switch (substr(date("m"),0,2)) {
// 		case "02" : $this->PerPago="Enero"; break;
// 		case "03" : $this->PerPago="Febrero"; break;
// 		case "04" : $this->PerPago="Marzo"; break;
// 		case "05" : $this->PerPago="Abril"; break;
// 		case "06" : $this->PerPago="Mayo"; break;
// 		case "07" : $this->PerPago="Junio"; break;
// 		case "08" : $this->PerPago="Julio"; break;
// 		case "09" : $this->PerPago="Agosto"; break;
// 		case "10" : $this->PerPago="Setiembre"; break;
// 		case "11" : $this->PerPago="Octubre"; break;
// 		case "12" : $this->PerPago="Noviembre"; break;
// 		case "01" : $this->PerPago="Diciembre"; break;
// 	}
//  if (substr(date("m"),0,2)=="01") { $this->PerPago=$this->PerPago." de ". (date("Y")-1); } else { $this->PerPago=$this->PerPago." de ".date("Y"); }
// // 	$this->LugarPago="GRAL. SAN MARTIN";
//  	$this->LugarPago=$Lugar;
//  	$this->FPago=date("d-m-y");
// 	$this->IdEmpleado=0;
// }

function CargarDatosRecibo($PerPago,$IdEmpleado,$MP,$FI,$FD) {
	$sSql='SELECT * FROM tblRecibos WHERE IdEmpleado='.$IdEmpleado.' and PerPago='.$PerPago;
// echo $sSql;
	unset($result);
	$stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute();
//   	$result = mysql_db_query($_SESSION['db'],$sSql);
// 	if (!$result) {
   		$row=$stmt->fetch();
// 		$row = mysql_fetch_array($result);
	 	$this->LugarPago=$row['LugarPago'];
		$this->Banco=$row['Banco'];
		//$this->FPago=$FI;
 		$this->FPago=SUBSTR($row['FPago'],8,2)."-".SUBSTR($row['FPago'],5,2)."-".SUBSTR($row['FPago'],0,4) ;
		$this->PerPago=$row['PerPago'];
		//$this->PerPago=$MP;
// $this->PerPago=substr($row['PerPago'],4,2);
		switch (substr($row['PerPago'],4,2)) { 
		case 13 :  $this->PerPago='1er SAC '.substr($row['PerPago'],0,4); break;
		case 14 :  $this->PerPago='2do SAC '.substr($row['PerPago'],0,4); break;
		case 15 :  $this->PerPago='Vacaciones '.substr($row['PerPago'],0,4); }
// 		else case { $this->PerPago=$row['PerPago']; }
		$this->IdRecibo=$row['IdRecibo'];
		$this->IdEmpleado=$row['IdEmpleado'];
		$this->IdCatProfe=$row['IdCatProfe'];
		
		$this->TotHaberes=$row['TotHaberes'];
		$this->NoRem=$row['NoRem'];
		$this->Descuentos=$row['Descuentos'];
		
		$this->PerUltLiq=$row['PerUltLiq'];
		$this->FechaUltLiq=$row['FechaUltLiq'];
		
// 		} else
// 	{
// 	 	$this->LugarPago="-";
//  		$this->FPago="-" ;
// 		$this->PerPago="-";
// 		$this->IdRecibo=-1;
// 		$this->IdEmpleado=-1;
// 	}
}

function EliminarRecibo($IdRecibo) {
	$sSql="DELETE FROM tblRecibos WHERE IdRecibo=".$IdRecibo;
  	// $result = mysql_db_query($_SESSION['db'],$sSql);
	return "Recibo eliminado";
}

function ModificarEscala($IdRecibo,$IdCatProf) {
    $sSql="UPDATE tblRecibos SET IdCatProfe=$IdCatProf WHERE IdRecibo=".$IdRecibo;
    $stmt =  $GLOBALS['pdo']->prepare($sSql); $stmt->execute();
    //$result = mysql_db_query($_SESSION['db'],$sSql);
    return "Escala modificada!!! ";
}
}

























?>