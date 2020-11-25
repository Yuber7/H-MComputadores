<?php

require_once('..\app\Models\DetalleVentas.php');

use App\Models\DetalleVentas;


$insert = new DetalleVentas([ "id" => 1, "valor_unitario" => 20000, "cantidad" => 3, "producto_id" => 6, "venta_id" => 1, "estado" => "Activo"]);
$insert->save();

$allDetalle =DetalleVentas::getAll();
var_dump($allDetalle);