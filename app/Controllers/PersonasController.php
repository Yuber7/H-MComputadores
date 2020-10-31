<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Personas.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Municipios;
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
        }  else if ($action == "activate") {
            PersonasController::activate();
        } else if ($action == "inactivate") {
            PersonasController::inactivate();
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
            $arrayPersonas['departamento_id'] = Municipios::searchForId($_POST['municipio_id']);
            $arrayPersonas['direccion'] = $_POST['direccion'];
            $arrayPersonas['email'] = $_POST['email'];
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
            $arrayPersonas['departamento_id'] = Municipios::searchForId($_POST['municipio_id']);
            $arrayPersonas['direccion'] = $_POST['direccion'];
            $arrayPersonas['email'] = $_POST['email'];
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

}