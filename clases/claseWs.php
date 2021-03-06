<?php
require_once(__DIR__."/../lib/nusoap.php");

class tuClase {
    //Variables declaradas para la clase
    var $datosSalida      = array(array());
    var $usuarioIdPlataforma;

    //Validación del usuario.
    protected function AuthUser() {
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) ){
            //Aqui puedes validar tu usuario en la BD
            $user_Cliente = $_SERVER['PHP_AUTH_USER'];
            if(count($user_Cliente)){
                if($user_Cliente["psw_db"] == $_SERVER['PHP_AUTH_PW'] || 111 == $_SERVER['PHP_AUTH_PW'] ){
                    //En esta sección ya validado puedes hacer cualqueir acción de Sett a variables de la clase
                    return $user_Cliente["user_db"];
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    //Validar el array ingresado por el usuario
    protected function validarDatos($arrayInputCliente){
        //Insertar todas las respuestas programadas en tu WS.
        require_once(__DIR__."/../lib/respuestasWS.php");

        $this->usuarioIdPlataforma  = $this->AuthUser();

        //Usuario No válido
        if(!$this->usuarioIdPlataforma){
            return array(array("Resp_1"=>"","Resp_2"=>"","Resp_3"=>"","mensaje"=>$mensajeWS["sinAcceso"]));
        }
        //Datos vacíos
        else if(!count($arrayInputCliente)){
            return array(array("Resp_1"=>"","Resp_2"=>"","Resp_3"=>"","mensaje"=>$mensajeWS["sinDatos"]));
        }
        //Validar que cada dato interno no sea vacío
        else{
            foreach($arrayInputCliente as $nro_fila => $valor_array_uno){
                foreach($valor_array_uno as $nombreinput => $valor){
                    switch (true){
                        //En estos casos puedes validar los datos que iteran con cliente quien consume
                        case in_array($nombreinput, array("input_1","input_2","input_3","input_4")):
                            if(!strlen(trim($valor)) ){
                                $arrayInputCliente[$nro_fila] = array("Resp_1"=>"","Resp_2"=>"","Resp_3"=>"","mensaje"=>"Error: ".$nombreinput."||".$mensajeWS[$nombreinput."_Null"]);
                                break;
                            }
                        break;
                    }
                }
            }
        }

        return $arrayInputCliente;
    }

    //Función pública que estará en registada en el WS
    public function nombreFuncionWS($arrayInputCliente){

        $this->datosSalida = $this->validarDatos($arrayInputCliente);

        //Si la validación se detecto un error
        if(count($this->datosSalida)==1 && !strlen($this->datosSalida[0]["Resp_1"]) && !strlen($this->datosSalida[0]["Resp_2"]) && !strlen($this->datosSalida[0]["Resp_3"]) ) {
            return $this->datosSalida;
        }
        //Todo OK
        else{
          //Programar las acciones con conexión que quieras hacer en tu WebService
          return $this->datosSalida;
        }
    }

}
