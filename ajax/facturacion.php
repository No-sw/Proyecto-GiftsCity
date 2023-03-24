<?php
if (strlen(session_id()) < 1) 
session_start();

require_once "../modulo/ejecutarSQL.php";

$facturacion=new ejecutarSQL();


$idproducto=isset($_POST["idfacturacion"])? limpiarCadena($_POST["idfacturacion"]):"";
$producto=isset($_POST["producto"])? limpiarCadena($_POST["producto"]):"";
$cliente=isset($_POST["cliente"])? limpiarCadena($_POST["cliente"]):"";
$venta=isset($_POST["venta"])? limpiarCadena($_POST["venta"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$evento=isset($_POST["evento"])? limpiarCadena($_POST["evento"]):"";



switch ($_GET["op"]){

    case 'guardaryeditar':
                $ms="Registro la factura";

                if (empty($idproducto)) 
                {
                    $sql="INSERT INTO `facturacion`(`producto`, `Cliente`, `idEvento`, `venta`, `impuesto`,`condicion`) 
                    VALUES ('$producto','$cliente','$evento','$venta','$impuesto',1)";
                  


                }else
                
                {
                $sql="UPDATE `facturacion` SET `producto`='$producto',`Cliente`='$cliente',`idEvento`='$evento',
                `venta`='$venta',`impuesto`='$impuesto'
                WHERE `idfacturacion`='$idproducto'";
                $ms="Edito el Registro de la factura"; 
                  


                }
                $respuesta=$facturacion->insertar($sql);
                 echo $respuesta ? $ms : "La factura no se pudo ingresar";     
              


    break;
case 'mostrar':
    
$sql="SELECT * FROM `facturacion` WHERE idfacturacion=".$idproducto;
$respuesta=$facturacion->mostrar($sql);
echo json_encode($respuesta);



break;
case 'activar':
    $sql="UPDATE `facturacion` SET condicion=1 WHERE idfacturacion=".$idproducto;
    $respuesta=$facturacion->insertar($sql);
    echo $respuesta ? "Se activo  la factura" : "La factura no se pudo ingresar";     
              

    break;
case 'desactivar':
    $sql="UPDATE `facturacion` SET condicion=0 WHERE idfacturacion=".$idproducto;
    $respuesta=$facturacion->insertar($sql);
    echo $respuesta ? "Se desactivo la factura" : "La factura no se pudo desactivar";     
              

    break;
    case 'selectevento':
        
        $rspta=$facturacion->listar("SELECT * FROM  inventario WHERE condicion=1");
        //Vamos a declarar un array
        
        while ($reg=$rspta->fetch_object()){
            echo '<option value="'.$reg->idinventario.'">'.$reg->Evento.'</option>';

        
        }
        echo '<option value=0 selected>Seleccionar</option>';

    
    break;

        case 'listar':
            $rspta=$facturacion->listar("select * from  facturacion");
            //Vamos a declarar un array
            $data= Array();
            $url="../reportes/rptfacturacion.php?id=";
   
            while ($reg=$rspta->fetch_object()){

                $btx='<a class="btn btn-primary" href='.$url.$reg->idfacturacion.' target="_blank">Reporte </a>'.'<button class="btn btn-warning" onclick="mostrar('.$reg->idfacturacion.')"><i class="far fa-edit"></i></i>Editar</button>';
                $btn=  '<button class="btn btn-warning" onclick="mostrar('.$reg->idfacturacion.')"><i class="fa fa-pencil">Editar</i></button>';               
                if ($_SESSION['prodedit']=="0"){
                            $btx="";
                            $btn="";

                }

                $btn1e= ' <button class="btn btn-danger" onclick="desactivar('.$reg->idfacturacion.')"><i class="far fa-file-times"></i>Anular</button>';
                
                $btn1ee= ' <button class="btn btn-primary" onclick="activar('.$reg->idfacturacion.')"><i class="fa fa-check"></i></button>';
                if ($_SESSION['prodanular']=="0"){
                    $btn1e="";
                    $btn1ee="";

                }
                $data[]=array(
                    "0"=>($reg->condicion)?$btx.$btn1e:$btn.$btn1ee,
                        "1"=>$reg->producto,
                        "2"=>$reg->Cliente,
                    "3"=>$reg->idEvento,
                    "4"=>$reg->venta,
                    "5"=>$reg->impuesto,
                    "6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                    '<span class="label bg-red">Desactivado</span>'
                    );
            }
            $results = array(
                "sEcho"=>1, //Información para el datatables
                "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                "aaData"=>$data);
            echo json_encode($results);
   
       break;
        break;
}
?>