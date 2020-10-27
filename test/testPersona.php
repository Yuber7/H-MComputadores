<?php

require_once ('..\app\Models\Persona.php');
require_once  ('..\app\Models\Administrador.php');

use App\Models\Persona;
use App\Models\Administrador;

/*
$Juan = new Persona(
    'Juan Esteban',
    'Chaparron Silva',
    'C.C',
    '1007751545',
    'yuber634hotmail.com',
    '3123123121',
    'Cliente',
    'Carrera 11 #12-43',
    'activo');


$Juan->create();

//echo $Juan;

//echo $Juan->saludar();
*/

$carlos = new Persona();
$manuel = new persona("Samuel", "Naranjo", "C.C", 1234567842, "mnuel123@correo.com", "3211232345", "Cliente", "Kr10b-43-93","activo");
$manuel->save();

$arrPersonas = Persona::search("SELECT * FROM `h&mcomputadores`.persona WHERE rol='Cliente'");

$allPersonas =Persona::getAll();
var_dump($allPersonas);
