<?php

function Validacion_empty($usuario,$contraseña){
    if(empty($usuario) || empty($contraseña)){
        $alert = 'Usuario o clave incorrectos';   
    }else{
        $alert = 'VALIDANDO USUARIO Y CLAVE';   
    }
}

function cedula($cedula):bool{
    if(strlen($cedula)!=10){
        $alert='<p class="msg_error">El número de cédula debe ser 10</p>';
        return false;
    }else{
        return true;
    }
}

function camposCliente($cedula,$nombre,$telefono,$direccion):string{
    $retorno = "";
    if((empty($cedula))){
        $retorno = "cedula";
    }else if((empty($nombre))){
        $retorno = "nombre";
    }elseif ((empty($telefono))) {
        $retorno = "telefono";
    }else if((empty($direccion))) {
        $retorno = "direccion";
    }else{
        $retorno = "correcto";
    }
    return $retorno;
    echo ($retorno);
}
?>