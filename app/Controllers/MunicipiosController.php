<?php

namespace App\Controllers;
require_once(__DIR__.'/../Models/Municipios.php');
require_once(__DIR__.'/../Models/Departamentos.php');

use App\Models\GeneralFunctions;
use App\Models\Categorias;
use App\Models\Municipios;

if(!empty($_GET['action'])){
    MunicipiosController::main($_GET['action']);
}

class MunicipiosController
{

    static function main($action)
    {
        if ($action == "create") {
            MunicipiosController::create();
        } else if ($action == "edit") {
            MunicipiosController::edit();
        } else if ($action == "searchForID") {
            MunicipiosController::searchForID($_REQUEST['idMunicipio']);
        } else if ($action == "searchAll") {
            MunicipiosController::getAll();
        } else if ($action == "activate") {
            MunicipiosController::activate();
        } else if ($action == "inactivate") {
            MunicipiosController::inactivate();
        }
    }

    static public function create()
    {
        try {
            $arrayMunicipios = array();
            $arrayMunicipios['nombre'] = '';
            $arrayMunicipios['departamento_id'] = Categorias::searchForId($_POST['departamento_id']);
            $arrayMunicipios['acortado'] = '';
            $arrayMunicipios['estado'] = 'Activo';
            $Municipio = new Municipios($arrayMunicipios);
            if ($Municipio->save()) {
                header("Location: ../../views/modules/municipios/create.php?id=" . $Municipio->getId());
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            header("Location: ../../views/modules/municipio/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayMunicipios = array();
            $arrayMunicipios['nombre'] = $_POST['nombre'];
            $arrayMunicipios['departamento_id'] = Categorias::searchForId($_POST['departamento_id']);
            $arrayMunicipios['acortado'] = $_POST['acortado'];
            $arrayMunicipios['estado'] = $_POST['estado'];
            $arrayMunicipios['id'] = $_POST['id'];

            $Municipio = new Municipios($arrayMunicipios);
            $Municipio->update();

            header("Location: ../../views/modules/municipios/show.php?id=" . $Municipio->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            header("Location: ../../views/modules/municipios/edit.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function activate()
    {
        try {
            $ObjMunicipio = Municipios::searchForId($_GET['Id']);
            $ObjMunicipio->setEstado("Activo");
            if ($ObjMunicipio->update()) {
                header("Location: ../../views/modules/municipios/index.php");
            } else {
                header("Location: ../../views/modules/municipios/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            header("Location: ../../views/modules/municipios/index.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function inactivate()
    {
        try {
            $ObjMunicipio = Municipios::searchForId($_GET['Id']);
            $ObjMunicipio->setEstado("Inactivo");
            if ($ObjMunicipio->update()) {
                header("Location: ../../views/modules/municipios/index.php");
            } else {
                header("Location: ../../views/modules/municipios/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            header("Location: ../../views/modules/municipios/index.php?respuesta=error");
        }
    }

    static public function searchForID($id)
    {
        try {
            return Municipios::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/municipios/manager.php?respuesta=error");
        }
    }

    static public function getAll()
    {
        try {
            return Municipios::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            header("Location: ../Vista/modules/municipios/index.php?respuesta=error");
        }
    }
}
