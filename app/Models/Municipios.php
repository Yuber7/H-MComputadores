<?php


namespace App\Models;

require_once (__DIR__ .'/../../vendor/autoload.php');
require_once ('Departamentos.php');
require_once('BasicModel.php');

use Carbon\Carbon;
use App\Models\Categorias;

class Municipios extends BasicModel
{
    protected int $id;
    protected string $nombre;
    protected Categorias $departamento_id;
    protected string $acortado;
    protected bool $estado;


        public function __construct($Municipios = array())
    {
        parent::__construct();
        $this->id = $Municipio['id'] ?? 0;
        $this->nombre = $Municipios['nombre'] ?? '';
        $this->departamento_id = !empty($Municipios['departamento_id']) ? Categorias::searchForId($Municipios['departamento_id']) : new Categorias();
        $this->acortado = $Municipios['acortado'] ?? '';
        $this->estado = $Municipios['estado'] ?? '';
    }


    public function __destruct()
    {
        parent::__destruct();
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed|string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed|string $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return int|mixed|null
     */
    public function getDepartamentoId() : Categorias
    {
        return $this->departamento_id;
    }

    /**
     * @param int|mixed|null $departamento_id
     */
    public function setDepartamentoId(Categorias $departamento_id): void
    {
        $this->departamento_id = $departamento_id;
    }

    /**
     * @return mixed|string
     */
    public function getAcortado()
    {
        return $this->acortado;
    }

    /**
     * @param mixed|string $acortado
     */
    public function setAcortado($acortado): void
    {
        $this->acortado = $acortado;
    }

    /**
     * @return bool|mixed|string
     */
    public function getEstado(): string
    {
        return ($this->estado) ? "Activo" : "Inactivo";
    }

    /**
     * @param bool|mixed|string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = trim($estado) == "Activo";
    }

    public function save() : bool
    {
        $result = $this->insertRow("INSERT INTO `h&mcomputadores`.municipios VALUES (NULL, ?, ?, ?, ?)", array(
                $this->nombre,
                $this->departamento_id->getId(),
                $this->acortado,
                $this->estado
            )
        );
        $this->setId(($result) ? $this->getLastId() : null);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $result = $this->updateRow("UPDATE `h&mcomputadores`.municipios SET nombre = ?, departamento_id = ?, acortado = ?, estado = ? WHERE id = ?", array(
                $this->nombre,
                $this->departamento_id->getId(),
                $this->acortado,
                $this->estado,
                $this->id
            )
        );
        $this->Disconnect();
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleted($id) : bool
    {
        $Municipios = Municipios::searchForId($id); //Buscando un Municipio por el ID
        $Municipios->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $Municipios->update();                    //Guarda los cambios..
    }


    public static function search($query) : array
    {
        $arrMunicipios = array();
        $tmp = new Municipios();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $ubicacion) {
            $Municipios = new Municipios();
            $Municipios->id = $ubicacion['id'];
            $Municipios->nombre = $ubicacion['nombre'];
            $Municipios->departamento_id = Categorias::searchForId($ubicacion['departamento_id']);
            $Municipios->acortado = $ubicacion['acortado'];
            $Municipios->estado = $ubicacion['estado'];
            $Municipios->Disconnect();
            array_push($arrMunicipios, $Municipios);
        }

        $tmp->Disconnect();
        return $arrMunicipios;
    }

    public static function searchForId($id) : Municipios
    {
        $Municipios = null;
        if ($id > 0) {
            $Municipios = new Municipios();
            $getrow = $Municipios->getRow("SELECT * FROM `h&mcomputadores`.municipios WHERE id =?", array($id));
            $Municipios->id = $getrow['id'];
            $Municipios->nombre = $getrow['nombre'];
            $Municipios->departamento_id = Categorias::searchForId($getrow['departamento_id']);
            $Municipios->acortado = $getrow['acortado'];
            $Municipios->estado = $getrow['estado'];
        }
        $Municipios->Disconnect();
        return $Municipios;
    }

    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return Municipios::search("SELECT * FROM `h&mcomputadores`.municipios");
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "nombre: $this->nombre, departamento_id: $this->departamento_id->nombre(),  acortado: $this->acortado, Estado: $this->estado";
    }


}