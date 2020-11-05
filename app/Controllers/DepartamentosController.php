<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Departamentos.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\Departamentos;
use App\Models\GeneralFunctions;


if (!empty($_GET['action'])) { //D.php?action=create
    DepartamentosController::main($_GET['action']);
}

class DepartamentosController
{

    static function main($action)
    {
        if ($action == "create") {
            DepartamentosController::create();
        } else if ($action == "edit") {
            DepartamentosController::edit();
        } else if ($action == "searchForID") {
            DepartamentosController::searchForID($_REQUEST['id']);
        } else if ($action == "searchAll") {
            DepartamentosController::getAll();
        }  else if ($action == "Activar") {
            DepartamentosController::activate();
        } else if ($action == "Inactivar") {
            DepartamentosController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayDepartamentos = array();
            $arrayDepartamentos['nombre'] = '';
            $arrayDepartamentos['region'] = '';
            $arrayDepartamentos['estado'] = 'Activo';
            $Departamento = new Departamentos($arrayDepartamentos);
            if ($Departamento->save()) {
                header("Location: ../../views/modules/departamentos/create.php?id=" . $Departamento->getId());
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            header("Location: ../../views/modules/departamentos/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayDepartamentos = array();
            $arrayDepartamentos['nombre'] = $_POST['nombre'];
            $arrayDepartamentos['region'] = $_POST['region'];
            $arrayDepartamentos['estado'] = $_POST['estado'];
            $arrayDepartamentos['id'] = $_POST['id'];

            $Departamento = new Departamentos($arrayDepartamentos);
            $Departamento->update();

            header("Location: ../../views/modules/departamentos/show.php?id=" . $Departamento->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            header("Location: ../../views/modules/departamentos/edit.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Departamentos::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/departamentos/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Departamentos::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/departamentos/manager.php?respuesta=error");
        }
    }

}
