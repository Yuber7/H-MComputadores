<?php

require_once('..\app\Models\Compras.php');

use App\Models\Compras;
use Carbon\Carbon;


$insert = new Compras([ "id" => 2, "fecha" => new Carbon ("2020-11-01 00:00:00"), "valor_total" => 30000, "persona_id" => 5,  "estado" => "Pendiente"]);
$insert->save();

$allCompras =Compras::getAll();
var_dump($allCompras);