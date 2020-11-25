<?php
namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Compras.php');
require_once(__DIR__ . '/../Models/Personas.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Compras;
use Carbon\Carbon;

if (!empty($_GET['action'])) { //VentasController.php?action=create
    ComprasController::main($_GET['action']);
}

class ComprasController
{

    static function main($action)
    {
        if ($action == "create") {
            ComprasController::create();
        } else if ($action == "edit") {
            ComprasController::edit();
        } else if ($action == "searchForID") {
            ComprasController::searchForID($_REQUEST['idCompras']);
        } else if ($action == "searchAll") {
            ComprasController::getAll();
        } else if ($action == "Recibida") {
            ComprasController::activate();
        } else if ($action == "Pendiente") {
            ComprasController::inactivate();
        }
    }


    static public function create()
    {
        try {
            $arrayCompras = array();
            $arrayCompras['fecha'] = Carbon::parse($_POST['fecha']);
            $arrayCompras['valor_total'] = $_POST['valor_total'];
            $arrayCompras['persona_id'] = $_POST['persona_id'];
            $arrayCompras['estado'] = $_POST['estado'];

            if (!Compras::CompraRegistrada($arrayCompras['id'])) {
                $Compras = new Compras($arrayCompras);
                if ($Compras->save()) {
                    header("Location: ../../views/modules/compras/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/compras/create.php?respuesta=error&mensaje=Venta ya registrada");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }


    static public function edit()
    {
        try {
            $arrayCompras = array();
            $arrayCompras['fecha'] = Carbon::parse($_POST['fecha']);
            $arrayCompras['valor_total'] = $_POST['valor_total'];
            $arrayCompras['persona_id'] = $_POST['persona_id'];
            $arrayCompras['estado'] = $_POST['estado'];
            $arrayCompras['id'] = $_POST['id'];

            $Compras = new Compras($arrayCompras);
            $Compras->update();

            header("Location: ../../views/modules/compras/show.php?id=" . $Compras->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/compras/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Compras::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/compras/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Compras::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/compras/manager.php?respuesta=error");
        }
    }


    static public function selectCompras($isMultiple = false,
                                        $isRequired = true,
                                        $id = "compra_id",
                                        $nombre = "compra_id",
                                        $defaultValue = "",
                                        $class = "form-control",
                                        $where = "",
                                        $arrExcluir = array())
    {
        $arrCompras = array();
        if ($where != "") {
            $base = "SELECT * FROM `h&mcomputadores`.compras WHERE ";
            $arrCompras = Compras::search($base . ' ' . $where);
        } else {
            $arrCompras = Compras::getAll();
        }

        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrCompras) > 0) {
            /* @var $arrCompras Compras[] */
            foreach ($arrCompras as $compra)
                if (!ComprasController::compraIsInArray($compra->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($compra != "") ? (($defaultValue == $compra->getId()) ? "selected" : "") : "") . " value='" . $compra->getId() . "'>" .  $compra->getFecha() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function compraIsInArray($idCompra, $arrCompras)
    {
        if (count($arrCompras) > 0) {
            foreach ($arrCompras as $compra) {
                if ($compra->getId() == $idCompra) {
                    return true;
                }
            }
        }
        return false;
    }

    static public function activate()
    {
        try {
            $ObjCompra = Compras::searchForId($_GET['Id']);
            $ObjCompra->setEstado("Recibida");
            if ($ObjCompra->update()) {
                header("Location: ../../views/modules/compras/index.php");
            } else {
                header("Location: ../../views/modules/compras/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/compras/index.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }


    static public function inactivate()
    {
        try {
            $ObjCompra = Compras::searchForId($_GET['Id']);
            $ObjCompra->setEstado("Pendiente");
            if ($ObjCompra->update()) {
                header("Location: ../../views/modules/compras/index.php");
            } else {
                header("Location: ../../views/modules/compras/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/compras/index.php?respuesta=error");
        }
    }
}