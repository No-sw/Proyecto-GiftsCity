<?php

    session_start();
    $login = $_SESSION['username'];

    if(!isset($login)){
        header('location: vistas/login.html');
    }
    else{
        header('location: vistas/index.php');
    }
    
?>