<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Personas.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Persona;

if (!empty($_GET['action'])) { //PersonaController.php?action=create
    PersonaController::main($_GET['action']);
}

class PersonaController
{

    static function main($action)
    {
        if ($action == "create") {
            PersonaController::create();
        } else if ($action == "edit") {
            PersonaController::edit();
        } else if ($action == "searchForID") {
            PersonaController::searchForID($_REQUEST['idPersona']);
        } else if ($action == "searchAll") {
            PersonaController::getAll();
        }  else if ($action == "activate") {
            PersonaController::activate();
        } else if ($action == "inactivate") {
            PersonaController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayPersona = array();
            $arrayPersona['nombre'] = $_POST['nombre'];
            $arrayPersona['apellido'] = $_POST['apellido'];
            $arrayPersona['tipoDocumento'] = $_POST['tipoDocumento'];
            $arrayPersona['documento'] = $_POST['documento'];
            $arrayPersona['correo'] = $_POST['correo'];
            $arrayPersona['telefono'] = $_POST['telefono'];
            $arrayPersona['rol'] = $_POST['rol'];
            $arrayPersona['direccion'] = $_POST['direccion'];
            $arrayPersona['estado'] = $_POST['estado'];

            if (!Persona::PersonaRegistrada($arrayPersona['documento'])) {
                $Persona = new Persona ($arrayPersona);
                if ($Persona->save()) {
                    header("Location: ../../views/modules/persona/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/persona/create.php?respuesta=error&mensaje=Persona ya registrada");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayPersona = array();
            $arrayPersona['nombre'] = $_POST['nombre'];
            $arrayPersona['apellido'] = $_POST['apellido'];
            $arrayPersona['tipoDocumento'] = $_POST['tipoDocumento'];
            $arrayPersona['documento'] = $_POST['documento'];
            $arrayPersona['correo'] = $_POST['correo'];
            $arrayPersona['telefono'] = $_POST['telefono'];
            $arrayPersona['rol'] = $_POST['rol'];
            $arrayPersona['direccion'] = $_POST['direccion'];
            $arrayPersona['estado'] = $_POST['estado'];
            $arrayPersona['id'] = $_POST['id'];

            $Persona = new Persona($arrayPersona);
            $Persona->update();

            header("Location: ../../views/modules/persona/show.php?id=" . $Persona->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Persona::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/personas/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Persona::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/persona/manager.php?respuesta=error");
        }
    }

}