<?php

require_once('..\app\Models\Productos.php');

use App\Models\Productos;


$insert = new Productos([ "id" => 1, "nombre" => "Teclado Cmdas", "marca" => "asus", "categoria_id" => 1,  "referencia_fabrica" => "12343", "descripcion" => "mansa", "stock" => 12, "precio" => 20.00000,  "estado" => "Disponible"     ]);
$insert->save();

$allProductos = Productos::search("SELECT * FROM `h&mcomputadores`.productos WHERE nombre='Teclado'");

$allProductos =Productos::getAll();
var_dump($allProductos);
