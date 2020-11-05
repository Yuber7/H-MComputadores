<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Categorias.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Categorias;

if (!empty($_GET['action'])) { //PersonasController.php?action=create
    CategoriasController::main($_GET['action']);
}

class CategoriasController
{

    static function main($action)
    {
        if ($action == "create") {
            CategoriasController::save();
        } else if ($action == "edit") {
            CategoriasController::edit();
        } else if ($action == "searchForID") {
            CategoriasController::searchForID($_REQUEST['idCategorias']);
        } else if ($action == "searchAll") {
            CategoriasController::getAll();
        }  else if ($action == "activate") {
            CategoriasController::activate();
        } else if ($action == "inactivate") {
            CategoriasController::inactivate();
        }
    }

    static public function save()
    {
        try {
            $arrayCategorias = array();
            $arrayCategorias['nombre'] = $_POST['nombre'];
            $arrayCategorias['descripcion'] = $_POST['descripcion'];
            $arrayCategorias['estado'] = $_POST['estado'];

            if (!Categorias::CategoriaRegistrada($arrayCategorias['nombre'])) {
                $Categoria = new Categorias ($arrayCategorias);
                if ($Categoria->save()) {
                    header("Location: ../../views/modules/categorias/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/categorias/create.php?respuesta=error&mensaje=Categoria ya registrada");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayCategorias = array();
            $arrayCategorias['nombre'] = $_POST['nombre'];
            $arrayCategorias['descripcion'] = $_POST['descripcion'];
            $arrayCategorias['estado'] = $_POST['estado'];
            $arrayCategorias['id'] = $_POST['id'];

            $Categoria = new Categorias ($arrayCategorias);
            $Categoria ->update();

            header("Location: ../../views/modules/categorias/show.php?id=" . $Categoria->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/categorias/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Categorias::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/categorias/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Categorias::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/categorias/manager.php?respuesta=error");
        }
    }

    static public function activate()
    {
        try {
            $ObjCategoria = Categorias::searchForId($_GET['Id']);
            $ObjCategoria->setEstado("Activo");
            if ($ObjCategoria->update()) {
                header("Location: ../../views/modules/categorias/index.php");
            } else {
                header("Location: ../../views/modules/categorias/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function inactivate()
    {
        try {
            $ObjCategoria = Categorias::searchForId($_GET['Id']);
            $ObjCategoria->setEstado("Inactivo");
            if ($ObjCategoria->update()) {
                header("Location: ../../views/modules/categorias/index.php");
            } else {
                header("Location: ../../views/modules/categorias/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/personas/index.php?respuesta=error");
        }
    }

}