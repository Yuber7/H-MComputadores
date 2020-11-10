<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Productos.php');
require_once(__DIR__ . '/../Models/Categorias.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Productos;

if (!empty($_GET['action'])) { //ProductosController.php?action=create
    ProductosController::main($_GET['action']);
}

class ProductosController
{

    static function main($action)
    {
        if ($action == "create") {
            ProductosController::create();
        } else if ($action == "edit") {
            ProductosController::edit();
        } else if ($action == "searchForID") {
            ProductosController::searchForID($_REQUEST['idProductos']);
        } else if ($action == "searchAll") {
            ProductosController::getAll();
        } else if ($action == "Disponible") {
            ProductosController::activate();
        } else if ($action == "Agotado") {
            ProductosController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayProductos = array();
            $arrayProductos['nombre'] = $_POST['nombre'];
            $arrayProductos['marca'] = $_POST['marca'];
            $arrayProductos['categoria_id'] = $_POST['categoria_id'];
            $arrayProductos['referencia_fabrica'] = $_POST['referencia_fabrica'];
            $arrayProductos['descripcion'] = $_POST['descripcion'];
            $arrayProductos['stock'] = $_POST['stock'];
            $arrayProductos['precio'] = $_POST['precio'];
            $arrayProductos['estado'] = $_POST['estado'];

            if (!Productos::ProductoRegistrado($arrayProductos['nombre'])) {
                $Productos = new Productos($arrayProductos);
                if ($Productos->save()) {
                    header("Location: ../../views/modules/productos/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/productos/create.php?respuesta=error&mensaje=Producto ya registrada");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayProductos = array();
            $arrayProductos['nombre'] = $_POST['nombre'];
            $arrayProductos['marca'] = $_POST['marca'];
            $arrayProductos['categoria_id'] = $_POST['categoria_id'];
            $arrayProductos['referencia_fabrica'] = $_POST['referencia_fabrica'];
            $arrayProductos['descripcion'] = $_POST['descripcion'];
            $arrayProductos['stock'] = $_POST['stock'];
            $arrayProductos['precio'] = $_POST['precio'];
            $arrayProductos['estado'] = $_POST['estado'];
            $arrayProductos['id'] = $_POST['id'];

            $Productos = new Productos($arrayProductos);
            $Productos->update();

            header("Location: ../../views/modules/productos/show.php?id=" . $Productos->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/productos/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Productos::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/productos/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Productos::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/productos/manager.php?respuesta=error");
        }
    }

    static public function selectProductos($isMultiple = false,
                                            $isRequired = true,
                                            $id = "productos_id",
                                            $nombre = "productos_id",
                                            $defaultValue = "",
                                            $class = "form-control",
                                            $where = "",
                                            $arrExcluir = array())
    {
        $arrProductos = array();
        if ($where != "") {
            $base = "SELECT * FROM `h&mcomputadores`.productos WHERE ";
            $arrProductos = Productos::search($base . ' ' . $where);
        } else {
            $arrProductos = Productos::getAll();
        }

        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrProductos) > 0) {
            /* @var $arrProductos \App\Models\Productos[] */
            foreach ($arrProductos as $producto)
                if (!ProductosController::productoIsInArray($producto->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($producto != "") ? (($defaultValue == $producto->getId()) ? "selected" : "") : "") . " value='" . $producto->getId() . "'>" .  $producto->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function productoIsInArray($idProducto, $arrProductos)
    {
        if (count($arrProductos) > 0) {
            foreach ($arrProductos as $producto) {
                if ($producto->getId() == $idProducto) {
                    return true;
                }
            }
        }
        return false;
    }


    static public function activate()
    {
        try {
            $ObjProducto = Productos::searchForId($_GET['Id']);
            $ObjProducto->setEstado("Disponible");
            if ($ObjProducto->update()) {
                header("Location: ../../views/modules/productos/index.php");
            } else {
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function inactivate()
    {
        try {
            $ObjProducto = Productos::searchForId($_GET['Id']);
            $ObjProducto->setEstado("Agotado");
            if ($ObjProducto->update()) {
                header("Location: ../../views/modules/productos/index.php");
            } else {
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/productos/index.php?respuesta=error");
        }
    }

}

