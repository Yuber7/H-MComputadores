<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Personas.php');
require_once(__DIR__ . '/../Models/Municipios.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Personas as Personas;

if (!empty($_GET['action'])) { //PersonasController.php?action=create
    PersonasController::main($_GET['action']);
}

class PersonasController
{

    static function main($action)
    {
        if ($action == "create") {
            PersonasController::create();
        } else if ($action == "edit") {
            PersonasController::edit();
        } else if ($action == "searchForID") {
            PersonasController::searchForID($_REQUEST['idPersonas']);
        } else if ($action == "searchAll") {
            PersonasController::getAll();
        }  else if ($action == "Activo") {
            PersonasController::Activo();
        } else if ($action == "Inactivo") {
            PersonasController::Inactivo();
        }else if ($action == "login") {
            PersonasController::login();
        }else if($action == "cerrarSession"){
            PersonasController::cerrarSession();
        }
    }

    static public function create()
    {
        try {
            $arrayPersonas = array();
            $arrayPersonas['nombre'] = $_POST['nombre'];
            $arrayPersonas['apellido'] = $_POST['apellido'];
            $arrayPersonas['tipo_documento'] = $_POST['tipo_documento'];
            $arrayPersonas['documento'] = $_POST['documento'];
            $arrayPersonas['telefono'] = $_POST['telefono'];
            $arrayPersonas['rol'] = $_POST['rol'];
            $arrayPersonas['municipio_id'] = $_POST['municipio_id'];
            $arrayPersonas['direccion'] = $_POST['direccion'];
            $arrayPersonas['email'] = $_POST['email'];
            $arrayPersonas['user'] = $_POST['user'];
            $arrayPersonas['password'] = $_POST['password'];
            $arrayPersonas['estado'] = $_POST['estado'];

            if (!Personas::PersonaRegistrada($arrayPersonas['documento'])) {
                $Personas = new Personas ($arrayPersonas);
                if ($Personas->save()) {
                    header("Location: ../../views/modules/personas/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/personas/create.php?respuesta=error&mensaje=Persona ya registrada");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayPersonas = array();
            $arrayPersonas['nombre'] = $_POST['nombre'];
            $arrayPersonas['apellido'] = $_POST['apellido'];
            $arrayPersonas['tipo_documento'] = $_POST['tipo_documento'];
            $arrayPersonas['documento'] = $_POST['documento'];
            $arrayPersonas['telefono'] = $_POST['telefono'];
            $arrayPersonas['rol'] = $_POST['rol'];
            $arrayPersonas['municipio_id'] = $_POST['municipio_id'];
            $arrayPersonas['direccion'] = $_POST['direccion'];
            $arrayPersonas['email'] = $_POST['email'];
            $arrayPersonas['user'] = $_POST['user'];
            $arrayPersonas['password'] = $_POST['password'];
            $arrayPersonas['estado'] = $_POST['estado'];
            $arrayPersonas['id'] = $_POST['id'];

            $Personas = new Personas ($arrayPersonas);
            $Personas ->update();


            header("Location: ../../views/modules/personas/show.php?id=" . $Personas->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Personas::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/personas/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Personas::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/personas/manager.php?respuesta=error");
        }
    }
    static public function selectPersonas($isMultiple = false,
                                           $isRequired = true,
                                           $id = "persona_id",
                                           $nombre = "persona_id",
                                           $defaultValue = "",
                                           $class = "form-control",
                                           $where = "",
                                           $arrExcluir = array())
    {
        $arrPersonas = array();
        if ($where != "") {
            $base = "SELECT * FROM `h&mcomputadores`.personas WHERE ";
            $arrPersonas = Personas::search($base . ' ' . $where);
        } else {
            $arrPersonas = Personas::getAll();
        }

        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrPersonas) > 0) {
            /* @var $arrPersonas Personas[] */
            foreach ($arrPersonas as $persona)
                if (!PersonasController::personaIsInArray($persona->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($persona != "") ? (($defaultValue == $persona->getId()) ? "selected" : "") : "") . " value='" . $persona->getId() . "'>" .  $persona->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }


    private static function personaIsInArray($idPersona, $arrPersonas)
    {
        if (count($arrPersonas) > 0) {
            foreach ($arrPersonas as $persona) {
                if ($persona->getId() == $idPersona) {
                    return true;
                }
            }
        }
        return false;
    }

    static public function Activo()
    {
        try {
            $ObjPersona = Personas::searchForId($_GET['Id']);
            $ObjPersona->setEstado("Activo");
            if ($ObjPersona->update()) {
                header("Location: ../../views/modules/personas/index.php");
            } else {
                header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function Inactivo()
    {
        try {
            $ObjPersona = Personas::searchForId($_GET['Id']);
            $ObjPersona->setEstado("Inactivo");
            if ($ObjPersona->update()) {
                header("Location: ../../views/modules/personas/index.php");
            } else {
                header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/personas/index.php?respuesta=error");
        }
    }

    public static function login (){
        try {
            if(session_status() == PHP_SESSION_NONE){ //Si la session no ha iniciado
                session_start();
            }
            if(!empty($_POST['user']) && !empty($_POST['password'])){
                $tmpUser = new Personas();
                $respuesta = $tmpUser->Login($_POST['user'], $_POST['password']);
                if (is_a($respuesta,"App\Models\Personas")) {
                    $_SESSION['UserInSession'] = $respuesta->jsonSerialize();
                    header("Location: ../../views/index.php");
                }else{
                    header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=".$respuesta);
                }
            }else{
                header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=Datos Vacíos");
            }
        } catch (\Exception $e) {
            header("Location: ../../views/modules/site/login.php?respuesta=error".$e->getMessage());
        }
    }

    public static function cerrarSession (){
        session_unset();
        session_destroy();
        header("Location: ../../views/modules/site/login.php");
    }
}