<?php


namespace App\Models;
require_once  ('BasicModel.php');


class Departamentos extends BasicModel
{
    //Propiedades
    protected int $id;
    protected string $nombre;
    protected string $region;
    protected bool $estado;

    //Metodo constructor
    public function __construct($arrDepartamentos = array())
    {
        parent::__construct();
        //Propiedad recibida y asigna a una propiedad de la clase
        $this->setId($arrDepartamentos['id'] ?? 0);
        $this->setNombre($arrDepartamentos['nombre'] ?? "");
        $this->setRegion($arrDepartamentos['region'] ?? "");
        $this->setEstado($arrDepartamentos['estado'] ?? "");

    }


    public function __destruct() // Cierro Conexiones
    {
        /*
        echo "<span style='color: #8b0000'>";
        echo $this->getNombre()." se ha eliminado<br/>";
        echo "</span>";
         */
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
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param mixed|string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed|string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param mixed|string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return mixed|bool
     */
    public function getEstado(): string
    {
        return ($this->estado) ? "Activo" : "Inactivo";
    }

    /**
     * @param mixed|bool $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = trim($estado) == "Activo";
    }


    public function save(): Departamentos
    {
        $result = $this->insertRow("INSERT INTO `h&mcomputadores`.departamentos VALUES (NULL, ?, ?, ?)", array(
                $this->getNombre(),
                $this->getRegion(),
                $this->getEstado()
            )
        );
        $this->Disconnect();
        return $this;
    }

    public function update()
    {
        $result = $this->updateRow("UPDATE `h&mcomputadores`.departamentos SET nombre = ?, region = ?, estado = ? WHERE id = ?", array(
                $this->getNombre(),
                $this->getRegion(),
                $this->getEstado(),
                $this->getId()
            )
        );
        $this->Disconnect();
        return $result;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function deleted($id)
    {
        $result = $this->updateRow("UPDATE `h&mcomputadores`.departamentos SET estado = ? WHERE id = ?", array(
                'Inactivo',
                $this->getId()
            )
        );
        $this->Disconnect();
        return $this;
    }


    /**
     * @param $query
     * @return mixed
     */
    public static function search($query)
    {
        $arrDepartamentos = array();
        $tmp = new Departamentos();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Departamentos = new Departamentos();
            $Departamentos->setId($valor['id']);
            $Departamentos->setNombre($valor['nombre']);
            $Departamentos->setRegion($valor['region']);
            $Departamentos->setEstado($valor['estado']);
            $Departamentos->Disconnect();
            array_push($arrDepartamentos, $Departamentos);
        }
        $tmp->Disconnect();
        return $arrDepartamentos;

    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Departamentos::search("SELECT * FROM `h&mcomputadores`.departamentos");
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Departamentos = null;
        if ($id > 0) {
            $Departamentos = new Departamentos();
            $getrow = $Departamentos->getRow("SELECT * FROM `h&mcomputadores`.departamentos WHERE id =?", array($id));
            $Departamentos->setId($getrow['id']);
            $Departamentos->setNombre($getrow['nombre']);
            $Departamentos->setRegion($getrow['region']);
            $Departamentos->setEstado($getrow['estado']);
        }
        $Departamentos->Disconnect();
        return $Departamentos;
    }


    static function DepartamentosRegistrado(string $nombre ){
        $result = Departamentos::search("SELECT * FROM `h&mcomputadores`.departamentos where nombre = " .$nombre);
        if ( count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "nombre:  " .$this->getNombre(). $typeOutput.
            "region:  " .$this->getRegion(). $typeOutput.
            "estado:  " .$this->getEstado(). $typeOutput;
    }

}

