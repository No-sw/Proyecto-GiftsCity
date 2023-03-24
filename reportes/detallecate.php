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
if ($_SESSION['cargo']=="Administrador");
}

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
$pdf->Cell(150,6,"COMISION POR SERVICIOS",0,0,'C'); 
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(150,6,"RTN:".$emp->rtn,0,0,'C'); 
$pdf->Ln(5);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(100,6,"Direccion:".$emp->direccion." Telefonos: ".$emp->telefono." Correo:".$emp->correo,0,0,'C'); 
$pdf->Ln(6);
$pdf->Cell(100,6,"Desde".$fecha_inicio." Hasta: ".$fecha_fin,0,0,'R'); 

//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->Ln(10);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(50,6,utf8_decode('Servicios'),1,0,'L',1);
$pdf->Cell(23,6,'Cantidad',1,0,'L',1);
$pdf->Cell(23,6,'Precio',1,0,'L',1);
$pdf->Cell(25,6,'Total comision',1,0,'L',1);



 $rsptad = $categoria->listar("select m.fecha,c.nombre, m.banco,COUNT(*) as cant,d.atlantida as precio,'0' as Total from movimientos m inner join servicios s on m.operacion=s.servicio INNER join categoria c on c.idcategoria=s.idcategoria inner join detallecomision d on c.idcategoria=d.idcategoria
 where DATE(m.fecha)>='$fecha_inicio' AND DATE(m.fecha)<='$fecha_fin' and m.empresa='$empresax' and m.banco='BANCO ATLANTIDA'
 GROUP by m.fecha,c.nombre, m.banco,d.occidente");

$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,50,23,23,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
$exo=$exo+($regd->cant*$regd->precio);
/* $t15=$t15+$regd->reti;
 $t18=$t18+$regd->cant;*/

  $pdf->SetFont('Arial','',8);
 $pdf->Row(array($regd->fecha,$regd->banco,$regd->nombre,$regd->cant,round($regd->precio,2),round($regd->cant*$regd->precio,2)));
}
$pdf->Row(array("","","Total:","","",round($exo,2)));
$pdf->Ln(4);

$rsptad = $categoria->listar("select m.fecha,c.nombre, m.banco,COUNT(*) as cant,d.occidente as precio,'0' as Total from movimientos m inner join servicios s on m.operacion=s.servicio INNER join categoria c on c.idcategoria=s.idcategoria inner join detallecomision d on c.idcategoria=d.idcategoria
    where DATE(m.fecha)>='$fecha_inicio' AND DATE(m.fecha)<='$fecha_fin' and m.empresa='$empresax' and m.banco='BANCO OCCIDENTE'
    GROUP by m.fecha,c.nombre, m.banco,d.occidente");
$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(50,6,utf8_decode('Servicios'),1,0,'L',1);
$pdf->Cell(23,6,'Cantidad',1,0,'L',1);
$pdf->Cell(23,6,'Precio',1,0,'L',1);
$pdf->Cell(25,6,'Total comision',1,0,'L',1);

$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,50,23,23,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
   $exo=$exo+($regd->cant*$regd->precio);
   /* $t15=$t15+$regd->reti;
    $t18=$t18+$regd->cant;*/
  
 	$pdf->SetFont('Arial','',8);
    $pdf->Row(array($regd->fecha,$regd->banco,$regd->nombre,$regd->cant,round($regd->precio,2),round($regd->cant*$regd->precio,2)));
}
$pdf->Row(array("","","Total:","","",round($exo,2)));
$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(50,6,utf8_decode('Servicios'),1,0,'L',1);
$pdf->Cell(23,6,'Cantidad',1,0,'L',1);
$pdf->Cell(23,6,'Precio',1,0,'L',1);
$pdf->Cell(25,6,'Total comision',1,0,'L',1);



 $rsptad = $categoria->listar("select m.fecha,c.nombre, m.banco,COUNT(*) as cant,d.bac as precio,'0' as Total from movimientos m inner join servicios s on m.operacion=s.servicio INNER join categoria c on c.idcategoria=s.idcategoria inner join detallecomision d on c.idcategoria=d.idcategoria
 where DATE(m.fecha)>='$fecha_inicio' AND DATE(m.fecha)<='$fecha_fin' and m.empresa='$empresax' and m.banco='BAC CREDOMATIC'
 GROUP by m.fecha,c.nombre, m.banco,d.occidente");

$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,50,23,23,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
$exo=$exo+($regd->cant*$regd->precio);
/* $t15=$t15+$regd->reti;
 $t18=$t18+$regd->cant;*/

  $pdf->SetFont('Arial','',8);
 $pdf->Row(array($regd->fecha,$regd->banco,$regd->nombre,$regd->cant,round($regd->precio,2),round($regd->cant*$regd->precio,2)));
}
$pdf->Row(array("","","Total:","","",round($exo,2)));

$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(50,6,utf8_decode('Servicios'),1,0,'L',1);
$pdf->Cell(23,6,'Cantidad',1,0,'L',1);
$pdf->Cell(23,6,'Precio',1,0,'L',1);
$pdf->Cell(25,6,'Total comision',1,0,'L',1);



 $rsptad = $categoria->listar("select m.fecha,c.nombre, m.banco,COUNT(*) as cant,d.banrural as precio,'0' as Total from movimientos m inner join servicios s on m.operacion=s.servicio INNER join categoria c on c.idcategoria=s.idcategoria inner join detallecomision d on c.idcategoria=d.idcategoria
 where DATE(m.fecha)>='$fecha_inicio' AND DATE(m.fecha)<='$fecha_fin' and m.empresa='$empresax' and m.banco='Banrural'
 GROUP by m.fecha,c.nombre, m.banco,d.occidente");

$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,50,23,23,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
$exo=$exo+($regd->cant*$regd->precio);
/* $t15=$t15+$regd->reti;
 $t18=$t18+$regd->cant;*/

  $pdf->SetFont('Arial','',8);
 $pdf->Row(array($regd->fecha,$regd->banco,$regd->nombre,$regd->cant,round($regd->precio,2),round($regd->cant*$regd->precio,2)));
}
$pdf->Row(array("","","Total:","","",round($exo,2)));




$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(50,6,utf8_decode('Servicios'),1,0,'L',1);
$pdf->Cell(23,6,'Cantidad',1,0,'L',1);
$pdf->Cell(23,6,'Precio',1,0,'L',1);
$pdf->Cell(25,6,'Total comision',1,0,'L',1);



 $rsptad = $categoria->listar("select m.fecha,c.nombre, m.banco,COUNT(*) as cant,d.banrural as precio,'0' as Total from movimientos m inner join servicios s on m.operacion=s.servicio INNER join categoria c on c.idcategoria=s.idcategoria inner join detallecomision d on c.idcategoria=d.idcategoria
 where DATE(m.fecha)>='$fecha_inicio' AND DATE(m.fecha)<='$fecha_fin' and m.empresa='$empresax' and m.banco='loto'
 GROUP by m.fecha,c.nombre, m.banco,d.occidente");

$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,50,23,23,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
$exo=$exo+($regd->cant*$regd->precio);
/* $t15=$t15+$regd->reti;
 $t18=$t18+$regd->cant;*/

  $pdf->SetFont('Arial','',8);
 $pdf->Row(array($regd->fecha,$regd->banco,$regd->nombre,$regd->cant,round($regd->precio,2),round($regd->cant*$regd->precio,2)));
}
$pdf->Row(array("","","Total:","","",round($exo,2)));


$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(50,6,utf8_decode('Servicios'),1,0,'L',1);
$pdf->Cell(23,6,'Cantidad',1,0,'L',1);
$pdf->Cell(23,6,'Precio',1,0,'L',1);
$pdf->Cell(25,6,'Total comision',1,0,'L',1);



 $rsptad = $categoria->listar("select m.fecha,c.nombre, m.banco,COUNT(*) as cant,d.banrural as precio,'0' as Total from movimientos m inner join servicios s on m.operacion=s.servicio INNER join categoria c on c.idcategoria=s.idcategoria inner join detallecomision d on c.idcategoria=d.idcategoria
 where DATE(m.fecha)>='$fecha_inicio' AND DATE(m.fecha)<='$fecha_fin' and m.empresa='$empresax' and m.banco='BANTRAB'
 GROUP by m.fecha,c.nombre, m.banco,d.occidente");

$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,50,23,23,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
$exo=$exo+($regd->cant*$regd->precio);
/* $t15=$t15+$regd->reti;
 $t18=$t18+$regd->cant;*/

  $pdf->SetFont('Arial','',8);
 $pdf->Row(array($regd->fecha,$regd->banco,$regd->nombre,$regd->cant,round($regd->precio,2),round($regd->cant*$regd->precio,2)));
}
$pdf->Row(array("","","Total:","","",round($exo,2)));

$pdf->Ln(4);
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,utf8_decode('Id movimiento'),1,0,'L',1); 
$pdf->Cell(35,6,utf8_decode('Banco'),1,0,'L',1);
$pdf->Cell(50,6,utf8_decode('Servicios'),1,0,'L',1);
$pdf->Cell(23,6,'Cantidad',1,0,'L',1);
$pdf->Cell(23,6,'Precio',1,0,'L',1);
$pdf->Cell(25,6,'Total comision',1,0,'L',1);



 $rsptad = $categoria->listar("select m.fecha,c.nombre, m.banco,COUNT(*) as cant,d.banrural as precio,'0' as Total from movimientos m inner join servicios s on m.operacion=s.servicio INNER join categoria c on c.idcategoria=s.idcategoria inner join detallecomision d on c.idcategoria=d.idcategoria
 where DATE(m.fecha)>='$fecha_inicio' AND DATE(m.fecha)<='$fecha_fin' and m.empresa='$empresax' and m.banco='FINANCIERA'
 GROUP by m.fecha,c.nombre, m.banco,d.occidente");

$pdf->Ln(6);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(35,35,50,23,23,25));
$exo=0;
$t15=0;
$t18=0;
$ti15=0;
$ti18=0;
$titi=0;
$tot=0;
while ($regd = $rsptad->fetch_object())
{  
$exo=$exo+($regd->cant*$regd->precio);
/* $t15=$t15+$regd->reti;
 $t18=$t18+$regd->cant;*/

  $pdf->SetFont('Arial','',8);
 $pdf->Row(array($regd->fecha,$regd->banco,$regd->nombre,$regd->cant,round($regd->precio,2),round($regd->cant*$regd->precio,2)));
}
$pdf->Row(array("","","Total:","","",round($exo,2)));

$pdf->Ln(4);
//$pdf->Row(array("","","TOTALES:",$exo,$t15,$t18,$ti15,$ti18,$titi,$tot));
/*

$pdf->Ln(4);
$pdf->Cell(177,6,"Desc./Rebajas:L.".$fila->descuento,0,0,'R');
$pdf->SetFont('Arial','B',10);
$pdf->Ln(4);
$pdf->Cell(175,6,"Total a pagar:L.".$fila->total_venta,0,0,'R');
$pdf->SetFont('Arial','',9);
$pdf->Ln(1);*/
$pdf->Cell(10,6,"ORIGINAL CLIENTE-COPIA EMISOR",0,0,'L');
//Mostramos el documento pdf
$pdf->Output();

?>
<?php


ob_end_flush();
?>