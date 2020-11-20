<?php

namespace App\Models;

require_once (__DIR__ .'/../../vendor/autoload.php');
require_once ('Categorias.php');
require_once  ('BasicModel.php');

use App\Models\Categorias;

class Productos extends BasicModel
{
    //Propiedades
    protected int $id;
    protected string $nombre;
    protected string $marca;
    protected Categorias $categoria_id;
    protected string $referencia_fabrica;
    protected string $descripcion;
    protected int $stock;
    protected float $precio;
    protected string $estado;


    public function __construct($arrProductos = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->id = $arrProductos['id'] ?? 0;
        $this->nombre = $arrProductos['nombre'] ?? '';
        $this->marca = $arrProductos['marca'] ?? '';
        $this->categoria_id = !empty($arrProductos['categoria_id']) ? Categorias::searchForId($arrProductos['categoria_id']) : new Categorias();
        $this->referencia_fabrica = $arrProductos['referencia_fabrica'] ?? '';
        $this->descripcion = $arrProductos['descripcion'] ?? '';
        $this->stock = $arrProductos['stock'] ?? 0;
        $this->precio = $arrProductos['precio'] ?? 0;
        $this->estado = $arrProductos['estado'] ?? '';
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
     * @return int|mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
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
        $this->nombre = trim(strtoupper($nombre));
    }

    /**
     * @return mixed|string
     */
    public function getMarca(): string
    {
        return $this->marca;
    }

    /**
     * @param mixed|string $marca
     */
    public function setMarca(string $marca): void
    {
        $this->marca = trim(strtoupper($marca));
    }

    /**
     * @return Categorias|mixed
     */
    public function getCategoriaId(): Categorias
    {
        return $this->categoria_id;
    }

    /**
     * @param Categorias|mixed $categoria_id
     */
    public function setCategoriaId(Categorias $categoria_id): void
    {
        $this->categoria_id = $categoria_id;
    }

    /**
     * @return mixed|string
     */
    public function getReferenciaFabrica(): string
    {
        return $this->referencia_fabrica;
    }

    /**
     * @param mixed|string $referencia_fabrica
     */
    public function setReferenciaFabrica(string $referencia_fabrica): void
    {
        $this->referencia_fabrica = $referencia_fabrica;
    }

    /**
     * @return mixed|string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param mixed|string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return int|mixed
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int|mixed $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return float|int|mixed
     */
    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * @param float|int|mixed $precio
     */
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    /**
     * @return bool|mixed|string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param bool|mixed|string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function save() : Productos
    {
        $result = $this->insertRow( "INSERT INTO `h&mcomputadores`.productos VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)", array(
                $this->nombre,
                $this->marca,
                $this->categoria_id->getId(),
                $this->referencia_fabrica,
                $this->descripcion,
                $this->stock,
                $this->precio,
                $this->estado
            )
        );
        $this->setId(($result) ? $this->getLastId() : null);
        $this->Disconnect();
        return $this;
    }


    /**
     * @return mixed
     */
    public function update()
    {
        $result = $this->updateRow( "UPDATE `h&mcomputadores`.productos SET nombre = ?, marca = ?, categoria_id = ?, referencia_fabrica = ?, descripcion = ?, stock = ?, precio = ?, estado = ? WHERE id = ?", array(
                $this->nombre,
                $this->marca,
                $this->categoria_id->getId(),
                $this->referencia_fabrica,
                $this->descripcion,
                $this->stock,
                $this->precio,
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
        $Productos = Categorias::searchForId($id); //Buscando un Municipio por el ID
        $Productos->setEstado("Agotado"); //Cambia el estado del Usuario
        return $Productos->update();                    //Guarda los cambios..
    }


    /**
     * @param $query
     * @return mixed
     */

    public static function search($query) : array
    {
        $arrProductos = array();
        $tmp = new Productos();
        $getrows = $tmp->getRows($query);



        foreach ($getrows as $info) {
            $Productos = new Productos();
            $Productos->id = $info['id'];
            $Productos->nombre = $info['nombre'];
            $Productos->marca = $info['marca'];
            $Productos->categoria_id = Categorias::searchForId($info['categoria_id']);
            $Productos->referencia_fabrica = $info['referencia_fabrica'];
            $Productos->descripcion = $info['descripcion'];
            $Productos->stock = $info['stock'];
            $Productos->precio = $info['precio'];
            $Productos->estado = $info['estado'];
            array_push($arrProductos, $Productos);
        }
        $tmp->Disconnect();
        return $arrProductos;
    }



    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Productos = null;
        if ($id > 0) {
            $Productos = new Productos();
            $getrow = $Productos->getRow("SELECT * FROM `h&mcomputadores`.productos WHERE id =?", array($id));
            $Productos->id = $getrow['id'];
            $Productos->nombre = $getrow['nombre'];
            $Productos->marca = $getrow['marca'];
            $Productos->categoria_id = Categorias::searchForId($getrow['categoria_id']);
            $Productos->referencia_fabrica = $getrow['referencia_fabrica'];
            $Productos->descripcion = $getrow['descripcion'];
            $Productos->stock = $getrow['stock'];
            $Productos->precio = $getrow['precio'];
            $Productos->estado = $getrow['estado'];
        }
        $Productos->Disconnect();
        return $Productos;
    }

    static function ProductoRegistrado(string $nombre){
        $nombre = trim(strtoupper($nombre));
        $result = Productos::search("SELECT * FROM `h&mcomputadores`.productos where nombre = '".$nombre."'");
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
        return Productos::search("SELECT * FROM `h&mcomputadores`.productos");
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "Nombre:  " .$this->nombre.
            "Marca:  " .$this->marca.
            "categoria:  " .$this->categoria_id.
            "referencia_fabrica:  " .$this->referencia_fabrica.
            "descripcion:  " .$this->descripcion.
            "stock:  " .$this->stock.
            "precio:  " .$this->precio.
            "Estado:  " .$this->getEstado(). $typeOutput;
    }


}