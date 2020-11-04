<?php


namespace App\Models;
require_once (__DIR__ .'/../../vendor/autoload.php');
require_once ('Productos.php');
require_once('BasicModel.php');


use App\Models\Productos;

class Fotos extends BasicModel
{
    //Propiedades
    protected int $id;
    protected string $descripcion;
    protected string $ruta;
    protected Productos $productos_id;
    protected bool $estado;

    //Metodo constructor
    public function __construct($arrFotos = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->setId($arrFotos['id'] ?? 0);
        $this->setDescripcion($arrFotos['descripcion'] ?? "");
        $this->setRuta($arrFotos['ruta'] ?? "");
        $this->productos_id = !empty($Fotos['productos_id']) ? Fotos::searchForId($Fotos['productos_id']) : new Productos();
        $this->setEstado($arrFotos['estado'] ?? "");

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
     * @return string
     */
    public function getRuta(): string
    {
        return $this->ruta;
    }

    /**
     * @param string $ruta
     */
    public function setRuta(string $ruta): void
    {
        $this->ruta = $ruta;
    }

    /**
     * @return Fotos|\App\Models\Productos|mixed
     */
    public function getProductoId()
    {
        return $this->productos_id;
    }

    /**
     * @param Fotos|\App\Models\Productos|mixed $productos_id
     */
    public function setProductoId($productos_id): void
    {
        $this->productos_id = $productos_id;
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
        $this->estado = trim($estado) == "Inactivo";
    }



    public function save(): Fotos
    {
        $result = $this->insertRow("INSERT INTO `h&mcomputadores`.fotos VALUES (NULL, ?, ?, ?,?)", array(
                $this->getDescripcion(),
                $this->getRuta(),
                $this->productos_id->getId(),
                $this->getEstado()
            )
        );
        $this->Disconnect();
        return $this;
    }

    public function update()
    {
        $result = $this->updateRow("UPDATE `h&mcomputadores`.fotos SET descripcion = ?, ruta = ?, estado = ? WHERE id = ?", array(
                $this->getDescripcion(),
                $this->getRuta(),
                $this->productos_id->getId(),
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
        $result = $this->updateRow("UPDATE `h&mcomputadores`.fotos SET estado = ? WHERE id = ?", array(
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
        $arrFotos = array();
        $tmp = new Fotos();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Fotos = new Fotos();
            $Fotos->setId($valor['id']);
            $Fotos->setDescripcion($valor['descripcion']);
            $Fotos->setRuta($valor['ruta']);
            $Fotos->productos_id = Fotos::searchForId($valor['productos_id']);
            $Fotos->setEstado($valor['estado']);
            $Fotos->Disconnect();
            array_push($arrFotos, $Fotos);
        }
        $tmp->Disconnect();
        return $arrFotos;

    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Fotos::search("SELECT * FROM `h&mcomputadores`.fotos");
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Fotos = null;
        if ($id > 0) {
            $Fotos = new Fotos();
            $getrow = $Fotos->getRow("SELECT * FROM `h&mcomputadores`.fotos WHERE id =?", array($id));
            $Fotos->setId($getrow['id']);
            $Fotos->setDescripcion($getrow['descripcion']);
            $Fotos->setRuta($getrow['ruta']);
            $Fotos->productos_id = Fotos::searchForId($getrow['productos_id']);
            $Fotos->setEstado($getrow['estado']);
        }
        $Fotos->Disconnect();
        return $Fotos;
    }


    static function FotoRegistrada(string $ruta ){
        $result = Fotos::search("SELECT * FROM `h&mcomputadores`.fotos where ruta = " .$ruta);
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
            "descripcion:  " .$this->getDescripcion(). $typeOutput.
            "ruta:  " .$this->getRuta(). $typeOutput.
            "estado:  " .$this->getEstado(). $typeOutput;
    }

}

