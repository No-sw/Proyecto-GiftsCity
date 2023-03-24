<?php

require_once "../modulo/ejecutarSQL.php";

$categoria=new ejecutarSQL();


$idinventario=isset($_POST["idinventario"])? limpiarCadena($_POST["idinventario"]):"";
$evento=isset($_POST["evento"])? limpiarCadena($_POST["evento"]):"";
$inicio=isset($_POST["inicio"])? limpiarCadena($_POST["inicio"]):"";
$final=isset($_POST["final"])? limpiarCadena($_POST["final"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";



switch ($_GET["op"]){

    case 'guardaryeditar':
                $ms="Registro el evento";

                if (empty($idinventario))
                {
                    $sql="INSERT INTO `inventario`(`Evento`, `fechaInicio`, `fechafinal`, `descripcion`, `condicion`) 
                    VALUES ('$evento', '$inicio', '$final','$descripcion',1)";
                  


                }else
                
                {
                    $sql="update `inventario` set `Evento`='$evento', `fechaInicio`='$inicio', 
                    `fechafinal`='$final', descripcion='$descripcion' where idinventario='$idinventario'";
                $ms="Edito el Registro de el evento";
                  


                }
                $respuesta=$categoria->insertar($sql);
                 echo $respuesta ? $ms : "El evento no se pudo ingresar";     
              


    break;
case 'mostrar':
    
$sql="SELECT * FROM `inventario` WHERE idinventario=".$idinventario;
$respuesta=$categoria->mostrar($sql);
echo json_encode($respuesta);



break;
case 'activar':
    $sql="update `inventario` set condicion=1 WHERE idinventario=".$idinventario;
    $respuesta=$categoria->insertar($sql);
    echo $respuesta ? "Se activo  el evento" : "El evento no se pudo ingresar";     
              

    break;
case 'desactivar':
    $sql="update `inventario` set condicion=0 WHERE idinventario=".$idinventario;
    $respuesta=$categoria->insertar($sql);
    echo $respuesta ? "Se desactivo el evento" : "El evento no se pudo ingresar";     
               

    break;
        case 'listar':
            $rspta=$categoria->listar("select * from  inventario");
            //Vamos a declarar un array
            $data= Array();
            $url="../reportes/rptinventario.php?id=";
   
            while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>($reg->condicion)?'<a class="btn btn-primary" href='.$url.$reg->idinventario .' target="_blank">Reporte</a>'.'<button class="btn btn-warning" onclick="mostrar('.$reg->idinventario.')">Editar</button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idinventario.')">Anular</button>':
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idinventario.')"><i class="fa fa-pencil">Editar</i></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idinventario.')"><i class="fa fa-check"></i></button>',
                        "1"=>$reg->Evento,
                        "2"=>$reg->fechaInicio,
                        "3"=>$reg->fechafinal,
                    "4"=>$reg->Descripcion,
                    "5"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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