<?php

require_once('..\app\Models\Categorias.php');

use App\Models\Categorias;


$insert = new Categorias([ "id" => 79, "nombre" => "Teclados", "descripcion" => "", "estado" => "Activo" ]);
$insert->save();

$arrCategorias = Categorias::search("SELECT * FROM h&mcomputadores.categorias WHERE nombre='Teclados'");

$allCategorias =Categorias::getAll();
var_dump($allCategorias);
