<?php

require_once('..\app\Models\Fotos.php');

use App\Models\Fotos;


$insert = new Fotos([ "id" => 1, "descripcion" => "Portatil", "ruta" => "https://www.muycomputerpro.com/wp-content/uploads/2019/06/huawei_portatiles.jpg" , "productos_id" => 1 , "estado" => "Activo" ]);
$insert->save();

$allFotos =Fotos::getAll();
var_dump($allFotos);