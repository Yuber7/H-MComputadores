<?php


namespace App\Controllers;
require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/DetalleCompra.php');
require_once(__DIR__ . '/../Models/Compras.php');
require_once(__DIR__ . '/../Models/Productos.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Ventas;

if (!empty($_GET['action'])) { //ComprasController.php?action=create
    DetalleComprasController::main($_GET['action']);
}

class DetalleCompraController
{

    static function main($action)
    {
        if ($action == "create") {
            DetalleComprasController::create();
        } else if ($action == "edit") {
            DetalleComprasController::edit();
        } else if ($action == "searchForID") {
            DetalleComprasController::searchForID($_REQUEST['idDetalleVentas']);
        } else if ($action == "searchAll") {
            DetalleComprasController::getAll();
        } else if ($action == "Disponible") {
            DetalleComprasController::activate();
        } else if ($action == "Agotado") {
            DetalleComprasController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayDetalleCompras = array();
            $arrayDetalleCompras['valor_unitario'] = $_POST['valor_unitario'];
            $arrayDetalleCompras['cantidad'] = $_POST['cantidad'];
            $arrayDetalleCompras['producto_id'] = $_POST['producto_id'];
            $arrayDetalleCompras['venta_id'] = $_POST['venta_id'];
            $arrayDetalleCompras['estado'] = $_POST['estado'];

            if (!DetalleComprasController::DetalleCompraRegistrada($arrayDetalleCompra['id'])) {
                $DetalleCompra = new DetalleComprasController($arrayDetalleCompras);
                if ($DetalleCompras->save()) {
                    header("Location: ../../views/modules/Detallecompras/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/Detalleventas/create.php?respuesta=error&mensaje=Venta ya registrada");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayDetalleCompras = array();
            $arrayDetalleCompras['valor_unitario'] = $_POST['valor_unitario'];
            $arrayDetalleCompras['cantidad'] = $_POST['cantidad'];
            $arrayDetalleCompras['producto_id'] = $_POST['producto_id'];
            $arrayDetalleCompras['compra_id'] = $_POST['compra_id'];
            $arrayDetalleCompras['estado'] = $_POST['estado'];

            $DetalleCompras = new Ventas($arrayDetalleCompras);
            $DetalleCompras->update();

            header("Location: ../../views/modules/Detalleventas/show.php?id=" . $DetalleCompras->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/ventas/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return DetalleComprasController::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/Detallecompras/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return DetalleComprasController::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/compras/manager.php?respuesta=error");
        }
    }
}





{

}