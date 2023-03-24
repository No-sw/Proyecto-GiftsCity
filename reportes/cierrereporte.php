<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['cargo']=="Administrador")
{

//Inlcuímos a la clase PDF_MC_Table
require('PDF_MC_Table.php');
 

require_once "../modelos/ejecutarSQL.php";

$categoria=new ejecutarSQL();
//Incluímos el archivo Factura.php
//$pdf=new FPDF('P', 'mm', '300, 200'); 
//$pdf=new PDF_MC_Table();
$pdf=new PDF_MC_Table('L','mm','letter');
$fecha_inicio=$_GET["fecha_inicio"];
$fecha_fin=$_GET["fecha_fin"];

$empresax=$_SESSION["empresa"];
$rspta=$categoria->listar("select * from referencia where empresa='". $_SESSION['empresa']."'");
$emp=$rspta->fetch_object();




/*
$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(100,6,$emp->empresa,1,0,'C'); 
$pdf->Ln(10);*/



//Instanciamos la clase para generar el documento pdf

 
//Agregamos la primera página al documento pdf
$pdf->AddPage();
 
//Seteamos el inicio del margen superior en 25 pixeles 
$y_axis_initial = 25;
 
//Seteamos el tipo de letra y creamos el título de la página. No es un encabezado no se repetirá
$pdf->SetFont('Arial','B',12);

$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(150,6,$emp->empresa,0,0,'C'); 
$pdf->Ln(5);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(150,6,"CIERRE DIARIO",0,0,'C'); 
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(150,6,"RTN:".$emp->rtn,0,0,'C'); 
$pdf->Ln(5);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(100,6,"Direccion:".$emp->direccion." Telefonos: ".$emp->telefono." Correo:".$emp->correo,0,0,'C'); 
$pdf->Ln(4);
$pdf->Cell(100,6,"Desde".$fecha_inicio." Hasta: ".$fecha_fin,0,0,'R'); 


$pdf->Ln(10);
//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(23,6,utf8_decode('Operacion'),1,0,'L',1);
$pdf->Cell(23,6,'Deposito',1,0,'L',1);
$pdf->Cell(23,6,'Retiro',1,0,'L',1);
$pdf->Cell(25,6,'Cantidad',1,0,'L',1);
$pdf->Cell(25,6,'Comision',1,0,'L',1);
$pdf->Cell(25,6,'Total Comision',1,0,'L',1);

 $pdf->Ln(6);

//Comenzamos a crear las filas de los registros según la consulta mysql


$rsptad = $categoria->listar("SELECT fecha,empresa,banco,operacion, sum(deposito) as depo, sum(retiro) as reti, COUNT(*) as cant FROM `movimientos`
    where DATE(fecha)>='$fecha_inicio' AND DATE(fecha)<='$fecha_fin' and empresa='$empresax' and banco='BANCO ATLANTIDA'
    GROUP by empresa,banco,operacion,fecha");
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,23,23,23,25,25,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;

while ($regd = $rsptad->fetch_object())
{  
    $exo=$exo+$regd->depo;
    $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;
    
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->operacion,$regd->depo,round($regd->reti,2),round($regd->cant,2),0,0));
}
$pdf->Row(array("","","Total:",$exo,round( $t15,2),round( $t18,2),0,0));
$pdf->Ln(4);

$rsptad = $categoria->listar("SELECT fecha,empresa,banco,operacion, sum(deposito) as depo, sum(retiro) as reti, COUNT(*) as cant FROM `movimientos`
    where DATE(fecha)>='$fecha_inicio' AND DATE(fecha)<='$fecha_fin' and empresa='$empresax' and banco='BANCO OCCIDENTE'
    GROUP by empresa,banco,operacion,fecha");
$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(23,6,utf8_decode('Operacion'),1,0,'L',1);
$pdf->Cell(23,6,'Deposito',1,0,'L',1);
$pdf->Cell(23,6,'Retiro',1,0,'L',1);
$pdf->Cell(25,6,'Cantidad',1,0,'L',1);
$pdf->Cell(25,6,'Comision',1,0,'L',1);
$pdf->Cell(25,6,'Total Comision',1,0,'L',1);
$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,23,23,23,25,25,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
    $exo=$exo+$regd->depo;
    $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;
  
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->operacion,$regd->depo,round($regd->reti,2),round($regd->cant,2),0,0));
}
$pdf->Row(array("","","Total:",$exo,round( $t15,2),round( $t18,2),0,0));
$pdf->Ln(4);


$rsptad = $categoria->listar("SELECT fecha,empresa,banco,operacion, sum(deposito) as depo, sum(retiro) as reti, COUNT(*) as cant FROM `movimientos`
    where DATE(fecha)>='$fecha_inicio' AND DATE(fecha)<='$fecha_fin' and empresa='$empresax' and banco='BAC CREDOMATIC'
    GROUP by empresa,banco,operacion,fecha");
$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(23,6,utf8_decode('Operacion'),1,0,'L',1);
$pdf->Cell(23,6,'Deposito',1,0,'L',1);
$pdf->Cell(23,6,'Retiro',1,0,'L',1);
$pdf->Cell(25,6,'Cantidad',1,0,'L',1);
$pdf->Cell(25,6,'Comision',1,0,'L',1);
$pdf->Cell(25,6,'Total Comision',1,0,'L',1);
$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,23,23,23,25,25,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
    $exo=$exo+$regd->depo;
    $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;
  
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->operacion,$regd->depo,round($regd->reti,2),round($regd->cant,2),0,0));
}
$pdf->Row(array("","","Total:",$exo,round( $t15,2),round( $t18,2),0,0));
$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(23,6,utf8_decode('Operacion'),1,0,'L',1);
$pdf->Cell(23,6,'Deposito',1,0,'L',1);
$pdf->Cell(23,6,'Retiro',1,0,'L',1);
$pdf->Cell(25,6,'Cantidad',1,0,'L',1);
$pdf->Cell(25,6,'Comision',1,0,'L',1);
$pdf->Cell(25,6,'Total Comision',1,0,'L',1);
$pdf->Ln(6);

$rsptad = $categoria->listar("SELECT fecha,empresa,banco,operacion, sum(deposito) as depo, sum(retiro) as reti, COUNT(*) as cant FROM `movimientos`
    where DATE(fecha)>='$fecha_inicio' AND DATE(fecha)<='$fecha_fin' and empresa='$empresax' and banco='BANRURAL'
    GROUP by empresa,banco,operacion,fecha");
$pdf->Ln(4);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,23,23,23,25,25,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
    $exo=$exo+$regd->depo;
    $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;
  
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->operacion,$regd->depo,round($regd->reti,2),round($regd->cant,2),0,0));
}
$pdf->Row(array("","","Total:",$exo,round( $t15,2),round( $t18,2),0,0));
$pdf->Ln(4);




$rsptad = $categoria->listar("SELECT fecha,empresa,banco,operacion, sum(deposito) as depo, sum(retiro) as reti, COUNT(*) as cant FROM `movimientos`
    where DATE(fecha)>='$fecha_inicio' AND DATE(fecha)<='$fecha_fin' and empresa='$empresax' and banco='LOTO'
    GROUP by empresa,banco,operacion,fecha");
$pdf->Ln(4);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,23,23,23,25,25,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
    $exo=$exo+$regd->depo;
    $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;
  
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->operacion,$regd->depo,round($regd->reti,2),round($regd->cant,2),0,0));
}
$pdf->Row(array("","","Total:",$exo,round( $t15,2),round( $t18,2),0,0));
$pdf->Ln(4);

$rsptad = $categoria->listar("SELECT fecha,empresa,banco,operacion, sum(deposito) as depo, sum(retiro) as reti, COUNT(*) as cant FROM `movimientos`
    where DATE(fecha)>='$fecha_inicio' AND DATE(fecha)<='$fecha_fin' and empresa='$empresax' and banco='BANTRAB'
    GROUP by empresa,banco,operacion,fecha");
$pdf->Ln(4);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,23,23,23,25,25,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
    $exo=$exo+$regd->depo;
    $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;
  
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->operacion,$regd->depo,round($regd->reti,2),round($regd->cant,2),0,0));
}
$pdf->Row(array("","","Total:",$exo,round( $t15,2),round( $t18,2),0,0));
$pdf->Ln(4);

$rsptad = $categoria->listar("SELECT fecha,empresa,banco,operacion, sum(deposito) as depo, sum(retiro) as reti, COUNT(*) as cant FROM `movimientos`
    where DATE(fecha)>='$fecha_inicio' AND DATE(fecha)<='$fecha_fin' and empresa='$empresax' and banco='FINANCIERA'
    GROUP by empresa,banco,operacion,fecha");
$pdf->Ln(4);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,23,23,23,25,25,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
    $exo=$exo+$regd->depo;
    $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;
  
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->operacion,$regd->depo,round($regd->reti,2),round($regd->cant,2),0,0));
}
$pdf->Row(array("","","Total:",$exo,round( $t15,2),round( $t18,2),0,0));
//$pdf->Row(array("","","TOTALES:",$exo,$t15,$t18,$ti15,$ti18,$titi,$tot));
/*

$pdf->Ln(4);
$pdf->Cell(177,6,"Desc./Rebajas:L.".$fila->descuento,0,0,'R');
$pdf->SetFont('Arial','B',10);
$pdf->Ln(4);
$pdf->Cell(175,6,"Total a pagar:L.".$fila->total_venta,0,0,'R');
$pdf->SetFont('Arial','',9);
$pdf->Ln(1);*/
$pdf->Cell(10,6,"FINAL DE LA PAGINA",0,0,'L');
//Mostramos el documento pdf
$pdf->Output();

?>
<?php
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>