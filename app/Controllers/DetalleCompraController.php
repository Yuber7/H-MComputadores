<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/DetalleCompras.php');
require_once(__DIR__ . '/../Models/Compras.php');
require_once(__DIR__ . '/../Models/Productos.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\Compras;
use App\Models\GeneralFunctions;
use App\Models\Compras;

if (!empty($_GET['action'])) { //VentasController.php?action=create
    DetalleComprasController::main($_GET['action']);
}

class DetalleCompraController
{

    static function main($action)
    {
        if ($action == "create") {
            DetalleCompraController::create();
        } else if ($action == "edit") {
            DetalleCompraController::edit();
        } else if ($action == "searchForID") {
            DetalleCompraController::searchForID($_REQUEST['idDetalleCompras']);
        } else if ($action == "searchAll") {
            DetalleCompraController::getAll();
        } else if ($action == "Disponible") {
            DetalleCompraController::activate();
        } else if ($action == "Agotado") {
            DetalleCompraController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayDetalleCompras = array();
            $arrayDetalleCompras['valor_unitario'] = $_POST['valor_unitario'];
            $arrayDetalleCompras['cantidad'] = $_POST['cantidad'];
            $arrayDetalleCompras['producto_id'] = $_POST['producto_id'];
            $arrayDetalleCompras['Compras_id'] = $_POST['Compras_id'];
            $arrayDetalleCompras['estado'] = $_POST['estado'];

            if (!DetalleCompraController::DetalleCompraRegistrada($arrayDetalleCompras['id'])) {
                $DetalleCompras = new DetalleComprasController($arrayDetalleCompras);
                if ($DetalleCompras->save()) {
                    header("Location: ../../views/modules/Detallecompras/index.php?accion=create&respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/Detallecompras/create.php?respuesta=error&mensaje=Compra ya registrada");
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
            $arrayDetalleCompras['Compras_id'] = $_POST['Compras_id'];
            $arrayDetalleCompras['estado'] = $_POST['estado'];

            $DetalleCompras = new Compras($arrayDetalleCompras);
            $DetalleCompras->update();

            header("Location: ../../views/modules/Detallecompras/show.php?id=" . $DetalleCompras->getId() . "&respuesta=correcto");
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
            //header("Location: ../../views/modules/DetalleCompras/manager.php?respuesta=error");
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
