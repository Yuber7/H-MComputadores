<?php

namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php'); //Requerido para convertir un objeto en Array
require_once(__DIR__ . '/../Models/DetalleVentas.php');
require_once(__DIR__ . '/../Models/Ventas.php');
require_once(__DIR__ . '/../Models/Productos.php');
require_once(__DIR__ . '/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Ventas;

if (!empty($_GET['action'])) { //VentasController.php?action=create
    DetalleVentasController::main($_GET['action']);
}

class DetalleVentasController
{

    static function main($action)
    {
        if ($action == "create") {
            DetalleVentasController::create();
        } else if ($action == "edit") {
            DetalleVentasController::edit();
        } else if ($action == "searchForID") {
            DetalleVentasController::searchForID($_REQUEST['idDetalleVentas']);
        } else if ($action == "searchAll") {
            DetalleVentasController::getAll();
        } else if ($action == "Disponible") {
            DetalleVentasController::activate();
        } else if ($action == "Agotado") {
            DetalleVentasController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayDetalleVentas = array();
            $arrayDetalleVentas['valor_unitario'] = $_POST['valor_unitario'];
            $arrayDetalleVentas['cantidad'] = $_POST['cantidad'];
            $arrayDetalleVentas['producto_id'] = $_POST['producto_id'];
            $arrayDetalleVentas['venta_id'] = $_POST['venta_id'];
            $arrayDetalleVentas['estado'] = $_POST['estado'];

            if (!DetalleVentasController::DetalleVentaRegistrada($arrayDetalleVentas['id'])) {
                $DetalleVentas = new DetalleVentasController($arrayDetalleVentas);
                if ($DetalleVentas->save()) {
                    header("Location: ../../views/modules/Detalleventas/index.php?accion=create&respuesta=correcto");
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
            $arrayDetalleVentas = array();
            $arrayDetalleVentas['valor_unitario'] = $_POST['valor_unitario'];
            $arrayDetalleVentas['cantidad'] = $_POST['cantidad'];
            $arrayDetalleVentas['producto_id'] = $_POST['producto_id'];
            $arrayDetalleVentas['venta_id'] = $_POST['venta_id'];
            $arrayDetalleVentas['estado'] = $_POST['estado'];

            $DetalleVentas = new Ventas($arrayDetalleVentas);
            $DetalleVentas->update();

            header("Location: ../../views/modules/Detalleventas/show.php?id=" . $DetalleVentas->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/ventas/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return DetalleVentasController::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/Detalleventas/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return DetalleVentasController::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/ventas/manager.php?respuesta=error");
        }
    }
}
