<?php
namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/Ventas.php');
require_once(__DIR__ . '/../Models/Personas.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Ventas;
use Carbon\Carbon;

if (!empty($_GET['action'])) { //VentasController.php?action=create
    VentasController::main($_GET['action']);
}

class VentasController
{

    static function main($action)
    {
        if ($action == "create") {
            VentasController::create();
        } else if ($action == "edit") {
            VentasController::edit();
        } else if ($action == "searchForID") {
            VentasController::searchForID($_REQUEST['idVentas']);
        } else if ($action == "searchAll") {
            VentasController::getAll();
        } else if ($action == "Procesada") {
            VentasController::activate();
        } else if ($action == "Pendiente") {
            VentasController::inactivate();
        }
    }


    static public function create()
    {
        try {
            $arrayVentas = array();
            $arrayVentas['fecha'] = Carbon::parse($_POST['fecha']);
            $arrayVentas['valor_total'] = $_POST['valor_total'];
            $arrayVentas['persona_id'] = $_POST['persona_id'];
            $arrayVentas['forma_pago'] = $_POST['forma_pago'];
            $arrayVentas['estado'] = $_POST['estado'];

            //esta linea aun no funciona debemos dejar lo de registrar por otro tipo de dato no se puede por id
            //if (!Ventas::VentaRegistrada($arrayVentas['id'])) {
                $Ventas = new Ventas($arrayVentas);
                if ($Ventas->save()) {
                    header("Location: ../../views/modules/ventas/index.php?accion=create&respuesta=correcto");
                }
            /*} else {
                header("Location: ../../views/modules/ventas/create.php?respuesta=error&mensaje=Venta ya registrada");
            }*/
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }


    static public function edit()
    {
        try {
            $arrayVentas = array();
            $arrayVentas['fecha'] = Carbon::parse($_POST['fecha']);
            $arrayVentas['valor_total'] = $_POST['valor_total'];
            $arrayVentas['persona_id'] = $_POST['persona_id'];
            $arrayVentas['forma_pago'] = $_POST['forma_pago'];
            $arrayVentas['estado'] = $_POST['estado'];
            $arrayVentas['id'] = $_POST['id'];

            $Ventas = new Ventas($arrayVentas);
            $Ventas->update();

            header("Location: ../../views/modules/ventas/show.php?id=" . $Ventas->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/ventas/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Ventas::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/ventas/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Ventas::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/ventas/manager.php?respuesta=error");
        }
    }


    static public function selectVentas($isMultiple = false,
                                           $isRequired = true,
                                           $id = "venta_id",
                                           $nombre = "venta_id",
                                           $defaultValue = "",
                                           $class = "form-control",
                                           $where = "",
                                           $arrExcluir = array())
    {
        $arrVentas = array();
        if ($where != "") {
            $base = "SELECT * FROM `h&mcomputadores`.ventas WHERE ";
            $arrVentas = Ventas::search($base . ' ' . $where);
        } else {
            $arrVentas = Ventas::getAll();
        }

        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrVentas) > 0) {
            /* @var $arrVentas Ventas[] */
            foreach ($arrVentas as $venta)
                if (!VentasController::ventaIsInArray($venta->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($venta != "") ? (($defaultValue == $venta->getId()) ? "selected" : "") : "") . " value='" . $venta->getId() . "'>" .  $venta->getFecha() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function ventaIsInArray($idVenta, $arrVentas)
    {
        if (count($arrVentas) > 0) {
            foreach ($arrVentas as $venta) {
                if ($venta->getId() == $idVenta) {
                    return true;
                }
            }
        }
        return false;
    }

    static public function activate()
    {
        try {
            $ObjVenta = Ventas::searchForId($_GET['Id']);
            $ObjVenta->setEstado("Procesada");
            if ($ObjVenta->update()) {
                header("Location: ../../views/modules/ventas/index.php");
            } else {
                header("Location: ../../views/modules/ventas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/ventas/index.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }


    static public function inactivate()
    {
        try {
            $ObjVenta = Ventas::searchForId($_GET['Id']);
            $ObjVenta->setEstado("Pendiente");
            if ($ObjVenta->update()) {
                header("Location: ../../views/modules/ventas/index.php");
            } else {
                header("Location: ../../views/modules/ventas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/ventas/index.php?respuesta=error");
        }
    }
}