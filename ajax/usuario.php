<?php
if (strlen(session_id()) < 1) 
session_start();
require_once "../modulo/ejecutarSQL.php";

$categoria=new ejecutarSQL();


$idcategoria=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["usuario"])? limpiarCadena($_POST["usuario"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$correo=isset($_POST["correo"])? limpiarCadena($_POST["correo"]):"";
$clave=isset($_POST["password"])? limpiarCadena($_POST["password"]):"";

switch ($_GET["op"]){


    case 'permisos':
    
        	
		$rspta = $categoria->listar("select * from permisos ");
      
            
		//Obtener los permisos asignados al usuario
		$id=$_GET['id'];
		$marcados =$categoria->listar("SELECT * FROM `detalleusuario` where idusuario=".$id);

		//Declaramos el array para almacenar todos los permisos marcados
		$valores=array();

		//Almacenar los permisos asignados al usuario en el array
		while ($per = $marcados->fetch_object())
			{
				array_push($valores, $per->idpermiso);
			}
            echo '<li> Inicio </li>';
            
		//Mostramos la lista de permisos en la vista y si están o no marcados
		while ($reg = $rspta->fetch_object())
				{
					$sw=in_array($reg->idpermisos,$valores)?'checked':'';
					echo '<li> <input type="checkbox" '.$sw.'  name="permiso[]" value="'.$reg->idpermisos.'">'.$reg->permisos.'</li>';
				}
	
               
		break;

$sql1;    

    case 'guardaryeditar':
                $ms="Registro el Usuario";


        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$imagen=$_POST["imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			{
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
			}
		}


                if (empty($idcategoria))
                {
                    $sql=" INSERT INTO `usuario`( `login`, `usuario`, `clave`, `correo`, `imagen`, `condicion`) 
                    VALUES ('$login','$nombre','$clave','$correo','$imagen',1)";
                  
                    $sql1=" INSERT INTO `usuario`( `login`, `usuario`, `clave`, `correo`, `imagen`, `condicion`)
                     VALUES ('$login','$nombre','$clave','$correo','$imagen',1)";

                }else
                
                {
                $sql="UPDATE `usuario` SET `login`='$login', `usuario`='$nombre', clave='$clave',
                correo='$correo', imagen='$imagen' WHERE idusuario='$idcategoria'";
                $ms="Edito el Registro del usuario";
                  


                }
                $respuesta=$categoria->insertar($sql);

                $perx=$_POST["permiso"];    

                $i=0;

                while ($i < count($perx)){
                    if(empty($idcategoria)){
                        $sql="INSERT INTO `detalleusuario`( `idusuario`, `permiso`) VALUES ( (select max(idusuario) from usuario)  , '$perx[$i]' )";
                    }
                    else{
                        $sql="INSERT INTO `detalleusuario`( `idusuario`, `permiso`) VALUES ( '$idcategoria'  , '$perx[$i]' )";
                    }
                    $respuesta=$categoria->insertar($sql);
                    $i++;
                }


                 echo $respuesta ? $ms : $sql1."El usuario fue registrado";     

    break;
case 'mostrar':
    
$sql="SELECT * FROM `usuario` WHERE idusuario=".$idcategoria;
$respuesta=$categoria->mostrar($sql);
echo json_encode($respuesta);



break;
case 'activar':
    $sql="UPDATE `usuario` SET condicion=1 WHERE idusuario=".$idcategoria;
    $respuesta=$categoria->insertar($sql);
    echo $respuesta ? "Se activo  el usuario" : "El usuario no se pudo ingresar";     
              

    break;
case 'desactivar':
    $sql="UPDATE `usuario` SET condicion=0 WHERE idusuario=".$idcategoria;
    $respuesta=$categoria->insertar($sql);
    echo $respuesta ? "Se desactivo el usuario" : "El usuario no se pudo ingresar";     
              
    
    break;
        case 'listar':
            $rspta=$categoria->listar("select * from  usuario order by usuario desc");
            //Vamos a declarar un array
            $data= Array();
   
            while ($reg=$rspta->fetch_object()){

                $btx='<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="far fa-edit"></i></i>Editar</button>';
                $btn=  '<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil">Editar</i></button>';               
                if ($_SESSION['useredit']=="0"){
                            $btx="";
                            $btn="";

                }

                $btn1e= ' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="far fa-file-times"></i>Anular</button>';
                
                $btn1ee= ' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>';
                if ($_SESSION['useranular']=="0"){
                    $btn1e="";
                    $btn1ee="";

                }

                $data[]=array(
                    "0"=>($reg->condicion)?$btx.$btn1e:$btn.$btn1ee,
                        "1"=>$reg->login,
                        "2"=>$reg->usuario,
                        "3"=>$reg->clave, 
                        "4"=>$reg->correo,     
                    "5"=>'<img src=../files/usuarios/'.$reg->imagen.' width=36 height=36 >',
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