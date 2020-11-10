<?php


require_once('..\app\Models\Ventas.php');

use App\Models\Ventas;


$insert = new Ventas([ "id" => 2, "fecha" => "2020-11-01 00:00:00", "valor_total" => 30000, "persona_id" => 5, "forma_pago" => "Efectivo", "estado" => "Pendiente"]);
$insert->save();

$allVentas =Ventas::getAll();
var_dump($allVentas);