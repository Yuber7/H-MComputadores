<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Personas;

class PersonasController
{
    private array $dataPersona;

    public function __construct(array $_FORM)
    {
        $this->dataPersona = array();
        $this->dataPersona['id'] = $_FORM['id'] ?? NULL;
        $this->dataPersona['nombre'] = $_FORM['nombre'] ?? NULL;
        $this->dataPersona['apellido'] = $_FORM['apellido'] ?? null;
        $this->dataPersona['tipo_documento'] = $_FORM['tipo_documento'] ?? NULL;
        $this->dataPersona['documento'] = $_FORM['documento'] ?? NULL;
        $this->dataPersona['telefono'] = $_FORM['telefono'] ?? NULL;
        $this->dataPersona['rol'] = $_FORM['rol'] ?? 'Cliente';
        $this->dataPersona['municipio_id'] = $_FORM['municipio_id'] ?? NULL;
        $this->dataPersona['direccion'] = $_FORM['direccion'] ?? NULL;
        $this->dataPersona['email'] = !empty($_FORM['email']) ?? NULL;
        $this->dataPersona['user'] = $_FORM['user'] ?? NULL;
        $this->dataPersona['password'] = $_FORM['password'] ?? NULL;
        $this->dataPersona['estado'] = $_FORM['estado'] ?? 'Activo';
    }

    public function create($withFiles = null) {
        try {
            if (!empty($this->dataUsuario['documento']) && !Personas::personaRegistrada($this->dataPersona['documento'])) {

                /*if(!empty($withFiles)){
                    $fotoUsuario = $withFiles['foto'];
                    $resultUpload = GeneralFunctions::subirArchivo($fotoUsuario, "views/public/uploadFiles/photos/");
                    $this->dataUsuario['foto'] = ($resultUpload != false) ? $resultUpload : NULL;
                }*/

                $Persona = new Personas ($this->dataPersona);
                if ($Persona->insert()) {
                    unset($_SESSION['frmPersona']);
                    header("Location: ../../views/modules/personas/index.php?respuesta=success&mensaje=Persona Registrada");
                }
            } else {
                header("Location: ../../views/modules/personas/create.php?respuesta=error&mensaje=Persona ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit($withFiles = null)
    {
        try {
            /*if(!empty($withFiles)){
                $fotoUsuario = $withFiles['foto'];
                if($fotoUsuario['error'] == 0){ //Si la foto se selecciono correctamente
                    $resultUpload = GeneralFunctions::subirArchivo($fotoUsuario, "views/public/uploadFiles/photos/");
                    if($resultUpload != false){
                        GeneralFunctions::eliminarArchivo("views/public/uploadFiles/photos/".$this->dataUsuario['foto']);
                        $this->dataUsuario['foto'] = $resultUpload;
                    }
                }
            }*/

            $user = new Personas($this->dataPersona);
            if($user->update()){
                unset($_SESSION['frmPersonas']);
            }

            header("Location: ../../views/modules/personas/show.php?id=" . $user->getId() . "&respuesta=success&mensaje=Persona Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = Personas::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function getAll(array $data = null)
    {
        try {
            $result = Personas::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function activate(int $id)
    {
        try {
            $ObjPersona = Personas::searchForId($id);
            $ObjPersona->setEstado("Activo");
            if ($ObjPersona->update()) {
                header("Location: ../../views/modules/personas/index.php");
            } else {
                header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate(int $id)
    {
        try {
            $ObjPersona = Personas::searchForId($id);
            $ObjPersona->setEstado("Inactivo");
            if ($ObjPersona->update()) {
                header("Location: ../../views/modules/personas/index.php");
            } else {
                header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectPersona(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "persona_id";
        $params['name'] = $params['name'] ?? "persona_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrPersonas = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM personas WHERE ";
            $arrPersonas = Personas::search($base . ' ' . $params['where']);
        } else {
            $arrPersonas = Personas::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrPersonas) > 0) {
            /* @var $arrPersonas Personas[] */
            foreach ($arrPersonas as $persona)
                if (!PersonasController::personaIsInArray($persona->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($persona != "") ? (($params['defaultValue'] == $persona->getId()) ? "selected" : "") : "") . " value='" . $persona->getId() . "'>" . $persona->getDocumento() . " - " . $persona->getNombre() . " " . $persona->getApellido() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function personaIsInArray($idPersona, $ArrPersonas)
    {
        if (count($ArrPersonas) > 0) {
            foreach ($ArrPersonas as $Persona) {
                if ($Persona->getId() == $idPersona) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function login (){
        try {
            if(!empty($_POST['user']) && !empty($_POST['password'])){
                $tmpUser = new Personas();
                $respuesta = $tmpUser->Login($_POST['user'], $_POST['password']);
                if (is_a($respuesta,"App\Models\Personas")) {
                    $_SESSION['UserInSession'] = $respuesta->jsonSerialize();
                    header("Location: ../../views/index.php");
                }else{
                    header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=".$respuesta);
                }
            }else{
                header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=Datos Vacíos");
            }
        } catch (\Exception $e) {
            header("Location: ../../views/modules/site/login.php?respuesta=error".$e->getMessage());
        }
    }

    public static function cerrarSession (){
        session_unset();
        session_destroy();
        header("Location: ../../views/modules/site/login.php");
    }

}