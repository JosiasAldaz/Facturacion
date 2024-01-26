<?php

function Validacion_empty($usuario,$contraseña){
    if(empty($usuario) || empty($contraseña)){
        
        $alert = 'Usuario o clave incorrectos';   
    }else{
        $alert = 'VALIDANDO USUARIO Y CLAVE';   
    }
}
?>