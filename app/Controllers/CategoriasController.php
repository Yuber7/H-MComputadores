<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Categorias.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Categorias;

if (!empty($_GET['action'])) { //CategoriasController.php?action=create
    CategoriasController::main($_GET['action']);
}

class CategoriasController
{

    static function main($action)
    {
        if ($action == "create") {
            CategoriasController::create();
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

    static public function create()
    {
        try {
            $arrayCategorias = array();
            $arrayCategorias['nombre'] = $_POST['nombre'];
            $arrayCategorias['descripcion'] = $_POST['descripcion'];
            $arrayCategorias['estado'] = $_POST['estado'];

            if (!Categorias::CategoriaRegistrada($arrayCategorias['nombre'])) {
                $Categorias = new Categorias ($arrayCategorias);
                if ($Categorias->save()) {
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

            $Categorias = new Categorias($arrayCategorias);
            $Categorias ->update();

            header("Location: ../../views/modules/categorias/show.php?id=" . $Categorias->getId() . "&respuesta=correcto");
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

    static public function selectCategorias($isMultiple = false,
                                            $isRequired = true,
                                            $id = "categoria_id",
                                            $nombre = "categoria_id",
                                            $defaultValue = "",
                                            $class = "form-control",
                                            $where = "",
                                            $arrExcluir = array())
    {
        $arrCategorias = array();
        if ($where != "") {
            $base = "SELECT * FROM `h&mcomputadores`.categorias WHERE ";
            $arrCategorias = Categorias::search($base . ' ' . $where);
        } else {
            $arrCategorias = Categorias::getAll();
        }

        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrCategorias) > 0) {
            /* @var $arrCategorias \App\Models\Municipios[] */
            foreach ($arrCategorias as $categoria)
                if (!CategoriasController::categoriaIsInArray($categoria->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($categoria != "") ? (($defaultValue == $categoria->getId()) ? "selected" : "") : "") . " value='" . $categoria->getId() . "'>" .  $categoria->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function categoriaIsInArray($idCategoria, $ArrCategorias)
    {
        if (count($ArrCategorias) > 0) {
            foreach ($ArrCategorias as $Categoria) {
                if ($Categoria->getId() == $idCategoria) {
                    return true;
                }
            }
        }
        return false;
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