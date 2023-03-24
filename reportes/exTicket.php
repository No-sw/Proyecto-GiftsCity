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
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
</head>
<body onload="window.print();">
<?php
require_once "../modelos/ejecutarSQL.php";

$categoria=new ejecutarSQL();

//Incluímos la clase Venta
require_once "../modelos/Venta.php";

//Instanaciamos a la clase con el objeto venta
$venta = new Venta();
//En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
$rspta = $venta->ventacabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$reg = $rspta->fetch_object();

$categoria=new ejecutarSQL();
$empresax=$_SESSION["empresa"];
$rspta=$categoria->listar("select * from referencia");
$emp=$rspta->fetch_object();




$rspta=$categoria->listar("select * from factura f  where f.numero=".$_GET["id"]);

$fila=$rspta->fetch_object();

//Establecemos los datos de la empresa
$empresa = $emp->empresa;
$documento = $emp->matriz;
$direccion = $emp->direccion;
$telefono = $emp->telefono;
$email = $emp->correo;
$rtn = $emp->rtn;
?>
<div class="zona_impresion">
<!-- codigo imprimir -->
<br>
<table border="0" align="left" width="250px">
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        .::<strong> <?php echo "$empresa"; ?></strong>::.<br>
        <br>
            <?php echo "RTN:".$rtn; ?><br>
        <br>
        <?php echo "$documento"; ?><br><br>
        <?php echo "Dirección:".$direccion; ?><br>
        <br>
        <?php echo "Telefono:".$telefono; ?><br><br>
        <?php echo "Correo:".$email; ?><br>
        </td>
    </tr>
    <tr>
        <td align="center"><strong>FACTURA DE VENTA </strong></td>
        
    </tr>
    <tr>
        
        <td align="center"><?php echo $fila->fecha; ?></td>
    </tr>
    <tr>
      <td align="center"></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Cliente: <?php echo $fila->cliente; ?></td><br>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>rtn: <?php echo $fila->rtncliente; ?></td>
    </tr>
    <tr>
        <td> Cajero: <?php echo $fila->usuario; ?>
        </td>
    </tr>
    <tr>
        <td><?php echo "Tipo Fact.: ". $fila->tipofactura.": ".$fila->tipopago; ?></td>
    </tr>
    <tr>
        <td>Nº de venta: <?php echo $emp->prefijo."-".str_pad($fila->numero, 8, "0", STR_PAD_LEFT) ; ?></td>
    </tr>
<tr>
        <td>

<?PHP echo "<br>DATOS DEL ADQUIERENTE EXONERADO".
 "<br>No.de Orden de Compra Exenta:_____________".
"<br>No. Constancia de Registro Exonerado:_________".
"<br>Número Registro de SAG:___________________";  ?></td>
</tr>
<tr>
        <td> <?PHP echo "CAI:<BR>".$fila->cai; ?>
        </td>
       </tr> 

<tr>
        <td> <?PHP echo "Rango:<BR>".$fila->limite; ?>
        </td>
       </tr>

<tr>
        <td> <?PHP echo "Fecha limite: ".$fila->vencimiento; ?>
        </td>
       </tr>

<tr>
        <td> <?PHP echo ""; ?>
        </td>
       </tr>


</table>
<br>
<!-- Mostramos los detalles de la venta en el documento HTML -->
<table border="0"  width="300px">
    <tr>
        <td>PRODUCTO</td>
    </tr>
    <tr>
        <td>Cantidad.</td>
        <td>Precio</td>
        <td align="right">Sub-Total</td>
    </tr>
    <tr>
      <td colspan="3">==========================================</td>
    </tr>
    <?php
    $rsptad = $categoria->listar("select * from detallefactura where idfactura=".$_GET["id"]);
    $cantidad=0;
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>";
        echo "<td>".$regd->Producto."</td></tr>";
        echo "<tr>";
        echo "<td>".$regd->cantidad."</td>";
        echo "<td>".$regd->Precio."</td>";
        echo "<td>".$regd->subtotal."</td>";
        echo "</tr>";
        $cantidad+=$regd->cantidad;
    }
    ?>
    <!-- Mostramos los totales de la venta en el documento HTML -->
<tr>
      <td colspan="3">==========================================</td>
    </tr>

    <tr>
    
    <td  align="left"><b>Total Exonerado:</b></td>
    <td align="left"><b>L.<?php echo $fila->exonerado;  ?></b></td>
    </tr>
    <tr>

    <td align="left"><b>Total exento:</b></td>
    <td align="left"><b>L.<?php echo $fila->exento;  ?></b></td>
    </tr>
    <tr>

    <td align="left"><b>Total Gravado 15:</b></td>
    <td align="left"><b>L.  <?php echo $fila->gravado15;  ?></b></td>
    </tr>
    <tr>

    <td align="left"><b>Total Gravado 18:</b></td>
    <td align="left"><b>L.  <?php echo $fila->gravado18;  ?></b></td>
    </tr>
    <tr>

    <td align="left"><b>Impuesto 15%:</b></td>
    <td align="left"><b>L.  <?php echo $fila->isv15;  ?></b></td>
    </tr>
    <tr>

    <td align="left"><b>Impuesto 18%:</b></td>
    <td align="left"><b>L.  <?php echo $fila->isv18;  ?></b></td>
    </tr>


    <td align="left"><b>Total Impuesto:</b></td>
    <td align="left"><b>L.  <?php echo $fila->totalisv;  ?></b></td>
    </tr>


    <td align="left"><b>Desc. y rebajas:</b></td>
    <td align="left"><b>L.<?php echo $fila->descuento;  ?></b></td>
    </tr>



    <tr>

    <td align="left"><b>TOTAL:</b></td>
    <td align="left"><b>L.  <?php echo $fila->total_venta;  ?></b></td>
    </tr>




<tr>
      <td colspan="3"><?php echo $fila->letras;  ?></td>
</tr>










    <tr>
      <td colspan="3">Nº de artículos: <?php echo $cantidad; ?></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>      
    <tr>
      <td colspan="3" align="left">¡Gracias por su compra!</td>
    </tr>
    <tr>
      <td colspan="3" align="left">Dirreción:<br> <?php echo $fila->direccion;  ?></td>
    </tr>
    
    
</table>
<br>
</div>
<p>&nbsp;</p>

</body>
</html>
<?php 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
// PIN 202020
?>