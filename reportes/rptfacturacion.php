<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

require('PDF_MC_Table.php');
 
require_once "../modulo/ejecutarSQL.php";

$categoria=new ejecutarSQL();

$rspta=$categoria->listar("SELECT * FROM empresa WHERE condicion=1");
$emp=$rspta->fetch_object();

//Inlcuímos a la clase PDF_MC_Table
 
//Instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table();
 
//Agregamos la primera página al documento pdf
$pdf->AddPage();
 
//Seteamos el inicio del margen superior en 25 pixeles 
$y_axis_initial = 25;
 
//Seteamos el tipo de letra y creamos el título de la página. No es un encabezado no se repetirá

$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(232,232,232);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(100,7,$emp->empresa,1,0,'C');
$pdf->SetDrawColor(131,131,131);

$pdf->Ln(20);
$pdf->Image('../files/empresa/logo.jpg',6,19,40);
 
$pdf->Ln(20);
 
//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetDrawColor(0,0,0);
 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,6,'Producto',1,0,'C',1);
$pdf->Cell(50,6,'Cliente',1,0,'C',1);
$pdf->Cell(30,6,'Venta',1,0,'C',1);
$pdf->Cell(30,6,'Impuesto',1,0,'C',1);
$pdf->Cell(30,6,'Total',1,0,'C',1);
 
$pdf->Ln(6);
//Comenzamos a crear las filas de los registros según la consulta mysql

if(isset($_GET["id"])){
    $idd=$_GET["id"];
    $rspta= $categoria->listar("SELECT * FROM `facturacion` WHERE `idfacturacion`=".$idd." AND `condicion`=1");
}else{
    $rspta = $categoria-> listar("SELECT * FROM `facturacion` WHERE `condicion`=1");
}




$pdf->SetWidths(array(30,50,30,30,30));
$ti=0;
$tit=0;
date_default_timezone_set('America/Tegucigalpa');
 
$fecha = date("d-m-Y");
while($reg= $rspta->fetch_object())
{  
    $nombre = $reg->producto;
    $ti=$reg->venta+($reg->venta*($reg->impuesto/100));
    $tit+=$ti;
 	$pdf->SetFont('Arial','',7); 
    $pdf->Row(array(utf8_decode($nombre) ,$reg->Cliente,$reg->venta,
    $reg->impuesto,$ti));
}
$pdf->SetFont('Arial','B',10);
$pdf->Cell(18,6,'RTN:',1,0,'R',1);
$pdf->Cell(30,6,$emp->rtn,1,0,'R',1);
$pdf->Cell(30,6,'Telefonos:',1,0,'R',1);
$pdf->Cell(30,6,$emp->telefono,1,0,'R',1);
$pdf->Cell(20,6,'direccion:',1,0,'R',1);
$pdf->Cell(42,6,$emp->direccion,1,0,'R',1);
$pdf->Ln(6);
$pdf->Cell(80,6,'fecha en que se genero el reporte:',1,0,'R',1);
$pdf->Cell(40,6,$fecha,1,0,'R',1);

//Mostramos el documento pdf
$pdf->Output();

?>
<?php



ob_end_flush();
?>