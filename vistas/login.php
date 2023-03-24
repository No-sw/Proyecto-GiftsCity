<?php
    require '../config/Conexion.php';
    session_start();
    require_once "../modulo/ejecutarSQL.php";
    $categoria=new ejecutarSQL();

    $login = $_POST['logina'];
    $clave = $_POST['clavea'];
    echo '<script type="text/javascript"> alert($login.toString()+ " "+$clave.toString());</script>';
    $q = "SELECT COUNT(*) AS contar FROM usuario WHERE login = '".$login."' 
    AND clave ='".$clave."' AND `condicion`=1";
    $consulta = mysqli_query($conexion, $q);
    $array = mysqli_fetch_array($consulta);

    if ($array['contar']>0){
        $_SESSION['username']=$login;
        $inf=0;
        $_SESSION['bandera']="";
        $valores=array();
        $_SESSION['categoriap']=100;
        $rspta=$categoria->listar("SELECT * FROM usuario WHERE `login`='".$login."' 
        AND `clave`='".$clave."' AND `condicion`=1");
        while ($reg=$rspta->fetch_object()){
            $_SESSION['bandera']="1";
            $idx=$reg->idusuario;
            $_SESSION['login1']=$reg->login;
            $_SESSION['nombre1']=$reg->usuario;
            $_SESSION['imagen']=$reg->imagen;
            $inf=1;


        }
        $inf=$inf." ".$idx;
        $rspta=$categoria->listar("SELECT * FROM  `detalleusuario` WHERE idusuario=".$idx);
        while ($reg=$rspta->fetch_object()){
            array_push($valores,$reg->permiso);
        

        } 
        in_array(1,$valores) ? $_SESSION['categoriap']=1:$_SESSION['categoriap']=0;
        in_array(2,$valores) ? $_SESSION['prodinsert']=1:$_SESSION['prodinsert']=0;
        in_array(3,$valores) ? $_SESSION['prodedit']=1:$_SESSION['prodedit']=0;
        in_array(4,$valores) ? $_SESSION['prodanular']=1:$_SESSION['prodanular']=0;
        in_array(5,$valores) ? $_SESSION['userinsert']=1:$_SESSION['userinsert']=0;
        in_array(6,$valores) ? $_SESSION['useredit']=1:$_SESSION['useredit']=0;
        in_array(7,$valores) ? $_SESSION['useranular']=1:$_SESSION['useranular']=0;
        echo json_encode($inf);
        $rspta=$categoria->listar("SELECT * FROM  `empresa` WHERE `idempresa`=1");
        while ($reg=$rspta->fetch_object()){
            $_SESSION['empresa']=$reg->empresa;
        }
        header("location: index.php");
    }else{
        header("location: login.html");
    }


?>