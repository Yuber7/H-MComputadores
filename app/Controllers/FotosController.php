<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Fotos.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Fotos;

if (!empty($_GET['action'])) { //PersonasController.php?action=create
    FotosController::main($_GET['action']);
}

class FotosController
{

    static function main($action)
    {
        if ($action == "create") {
            FotosController::create();
        } else if ($action == "edit") {
            FotosController::edit();
        } else if ($action == "searchForID") {
            FotosController::searchForID($_REQUEST['idFotos']);
        } else if ($action == "searchAll") {
            FotosController::getAll();
        }  else if ($action == "activate") {
            FotosController::activate();
        } else if ($action == "inactivate") {
            FotosController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayFotos = array();
            $arrayFotos['descripcion'] = $_POST['descripcion'];
            $arrayFotos['ruta'] = $_POST['ruta'];
            $arrayFotos['productos_id'] = Fotos::searchForId($_POST['productos_id']);
            $arrayFotos['estado'] = $_POST['estado'];

            if (!Fotos::FotoRegistrada($arrayFotos['ruta'])) {
                $Fotos = new Fotos ($arrayFotos);
                if ($Fotos->save()) {
                    header("Location: ../../views/modules/fotos/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/fotos/create.php?respuesta=error&mensaje=Persona ya registrada");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayFotos = array();
            $arrayFotos['descripcion'] = $_POST['descripcion'];
            $arrayFotos['ruta'] = $_POST['ruta'];
            $arrayFotos['productos_id'] = Fotos::searchForId($_POST['productos_id']);
            $arrayFotos['estado'] = $_POST['estado'];
            $arrayFotos['id'] = $_POST['id'];

            $Fotos = new Fotos ($arrayFotos);
            $Fotos ->update();

            header("Location: ../../views/modules/fotos/show.php?id=" . $Fotos->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/fotos/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Fotos::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/fotos/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Fotos::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/personas/manager.php?respuesta=error");
        }
    }

}