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
    protected string $estado;

    //Metodo constructor
    public function __construct($arrFotos = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->id = $arrFotos['id'] ?? 0;
        $this->descripcion = $arrFotos['descripcion'] ?? '';
        $this->ruta = $arrFotos['ruta'] ?? '';
        $this->productos_id = !empty($arrFotos['productos_id']) ? Productos::searchForId($arrFotos['productos_id']) : new Productos();
        $this->estado = $arrFotos['estado'] ?? '';

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
     * @return mixed|string
     */
    public function getEstado(): string
    {
        return ($this->estado);
    }

    /**
     * @param mixed|string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }



    public function save(): Fotos
    {
        $result = $this->insertRow("INSERT INTO `h&mcomputadores`.fotos VALUES (NULL, ?, ?, ?, ?)", array(
                $this->descripcion,
                $this->ruta,
                $this->productos_id->getId(),
                $this->estado
            )
        );
        $this->setId(($result) ? $this->getLastId() : null);
        $this->Disconnect();
        return $this;
    }


    public function update()
    {
        $result = $this->updateRow("UPDATE `h&mcomputadores`.fotos SET descripcion = ?, ruta = ?, productos_id = ?, estado = ? WHERE id = ?", array(
                $this->descripcion,
                $this->ruta,
                $this->productos_id->getId(),
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
    public function deleted($id)
    {
        $Fotos = Categorias::searchForId($id); //Buscando un Municipio por el ID
        $Fotos->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $Fotos->update();
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
            $Fotos->id = $valor['id'];
            $Fotos->descripcion = $valor['descripcion'];
            $Fotos->ruta = $valor['ruta'];
            $Fotos->productos_id = Productos::searchForId($valor['productos_id']);
            $Fotos->estado = $valor['estado'];
            array_push($arrFotos, $Fotos);
        }
        $tmp->Disconnect();
        return $arrFotos;

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
            $Fotos->id = $getrow['id'];
            $Fotos->descripcion = $getrow['descripcion'];
            $Fotos->ruta = $getrow['ruta'];
            $Fotos->productos_id = Productos::searchForId($getrow['productos_id']);
            $Fotos->estado = $getrow['estado'];
        }
        $Fotos->Disconnect();
        return $Fotos;
    }


    static function FotoRegistrada(string $ruta ){
        $result = Fotos::search("SELECT * FROM `h&mcomputadores`.fotos where ruta = '" .$ruta."'");
        if ( count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Fotos::search("SELECT * FROM `h&mcomputadores`.fotos");
    }



    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "descripcion:  " .$this->descripcion. $typeOutput.
            "ruta:  " .$this->ruta. $typeOutput.
            "estado:  " .$this->estado. $typeOutput;
    }

}

