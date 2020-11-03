<?php


namespace App\Models;
require_once  ('BasicModel.php');


class Categorias extends BasicModel
{
    //Propiedades
    protected int $id;
    protected string $nombre;
    protected string $descripcion;
    protected bool $estado;

    //Metodo constructor
    public function __construct($arrCategorias = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->setId($arrCategorias['id'] ?? 0);
        $this->setNombre($arrCategorias['nombre'] ?? "");
        $this->setDescripcion($arrCategorias['descripcion'] ?? "");
        $this->setEstado($arrCategorias['estado'] ?? "");
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
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = trim(mb_strtoupper($nombre));
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
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

    public function save(): Categorias
    {
        $result = $this->insertRow("INSERT INTO `h&mcomputadores`.categorias VALUES (NULL, ?, ?, ?)", array(
                $this->getNombre(),
                $this->getDescripcion(),
                $this->getEstado()
            )
        );
        $this->Disconnect();
        return $this;
    }

    public function update()
    {
        $result = $this->updateRow("UPDATE `h&mcomputadores`.categorias SET nombre = ?, descripcion = ?, estado = ? WHERE id = ?", array(
                $this->getNombre(),
                $this->getDescripcion(),
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
        $result = $this->updateRow("UPDATE `h&mcomputadores`.categorias SET estado = ? WHERE id = ?", array(
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
        $arrCategorias = array();
        $tmp = new Categorias();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Categorias = new Categorias();
            $Categorias->setId($valor['id']);
            $Categorias->setNombre($valor['nombre']);
            $Categorias->setDescripcion($valor['descripcion']);
            $Categorias->setEstado($valor['estado']);
            $Categorias->Disconnect();
            array_push($arrCategorias, $Categorias);
        }
        $tmp->Disconnect();
        return $arrCategorias;

    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Categorias::search("SELECT * FROM `h&mcomputadores`.categorias");
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Categorias = null;
        if ($id > 0) {
            $Categorias = new Categorias();
            $getrow = $Categorias->getRow("SELECT * FROM `h&mcomputadores`.categorias WHERE id =?", array($id));
            $Categorias->setId($getrow['id']);
            $Categorias->setNombre($getrow['nombre']);
            $Categorias->setDescripcion($getrow['descripcion']);
            $Categorias->setEstado($getrow['estado']);
        }
        $Categorias->Disconnect();
        return $Categorias;
    }


    static function CategoriaRegistrada(string $nombre ){
        $result = Categorias::search("SELECT * FROM `h&mcomputadores`.categorias where nombre = " .$nombre);
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
            "descripcion:  " .$this->getDescripcion(). $typeOutput.
            "estado:  " .$this->getEstado(). $typeOutput;
    }
}