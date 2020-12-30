<?php

namespace App\Models;

use App\Models\Interfaces\Model;
use Exception;
use JsonSerializable;

class Productos extends AbstractDBConnection implements Model, JsonSerializable
{
    //Propiedades
    protected ?int $id;
    protected string $nombre;
    protected string $marca;
    protected string $descripcion;
    protected float $precio;
    protected float $porcentaje_ganancia;
    private int $stock;
    protected int $categoria_id;
    protected string $estado;

    /* Relaciones */
    private ?Categorias $categoria;
    private ?array $fotosProducto;

    /**
     * Producto constructor. Recibe un array asociativo
     * @param array $categoria
     */
    public function __construct(array $producto = [])
    {
        {
            parent::__construct();
            //Propiedad recibida y asigna a una propiedad de la clase
            $this->setId($producto['id'] ?? NULL);
            $this->setNombre($producto['nombre'] ?? "");
            $this->setMarca($producto['marca'] ?? "");
            $this->setDescripcion($producto['descripcion'] ?? "");
            $this->setPrecio($producto['precio'] ?? 0.0);
            $this->setPorcentajeGanancia($producto['porcentaje_ganancia'] ?? 0.0);
            $this->setStock($producto['stock'] ?? 0);
            $this->setCategoriaId($producto['categoria_id'] ?? 0);
            $this->setEstado($producto['estado'] ?? "");

        }
    }

    function __destruct()
    {
        if($this->isConnected){
            $this->Disconnect();
        }
    }


    /**
     * @return int|mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed|string
     */
    public function getNombre() : string
    {
        return ucwords($this->nombre);
    }

    /**
     * @param mixed|string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = trim(mb_strtolower($nombre, 'UTF-8'));
    }

    /**
     * @return mixed|string
     */
    public function getMarca(): string
    {
        return ucwords($this->marca);
    }

    /**
     * @param mixed|string $marca
     */
    public function setMarca(string $marca): void
    {
        $this->marca = trim(mb_strtolower($marca, 'UTF-8'));
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
     * @return float|mixed
     */
    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * @param float|mixed $precio
     */
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }


    /**
     * @return float|mixed
     */
    public function getPorcentajeGanancia() : float
    {
        return $this->porcentaje_ganancia;
    }

    /**
     * @param float|mixed $porcentaje_ganancia
     */
    public function setPorcentajeGanancia(float $porcentaje_ganancia): void
    {
        $this->porcentaje_ganancia = $porcentaje_ganancia;
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
     * @return Categorias
     */
    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    /**
     * @param $categoria_id
     */
    public function setCategoriaId(int $categoria_id): void
    {
        $this->categoria_id = $categoria_id;
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


    /* Relaciones */
    /**
     * @return Categorias
     */
    public function getCategoria(): ?Categorias
    {
        if(!empty($this->categoria_id)){
            $this->categoria = Categorias::searchForId($this->categoria_id) ?? new Categorias();
            return $this->categoria;
        }
        return NULL;
    }


    /**
     * retorna un array de fotos que pertenecen al producto
     * @return array
     */
    public function getFotosProducto(): ?array
    {
        $this->fotosProducto = Fotos::search("SELECT * FROM `h&mcomputadores`.fotos WHERE producto_id = ".$this->id." and estado = 'Activo'");
        return $this->fotosProducto;
    }



    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':marca' =>  $this->getMarca(),
            ':descripcion' =>   $this->getDescripcion(),
            ':precio' =>   $this->getPrecio(),
            ':porcentaje_ganancia' =>   $this->getPorcentajeGanancia(),
            ':stock' =>   $this->getStock(),
            ':categoria_id' =>   $this->getCategoriaId(),
            ':estado' =>   $this->getEstado(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */
    public function insert(): ?bool
    {
        $query = "INSERT INTO `h&mcomputadores`.productos VALUES (
            :id,:nombre,:marca,:descripcion,:precio,:porcentaje_ganancia,:stock,:categoria_id,:estado)";
        return $this->save($query);
    }

    public function update(): ?bool
    {
        $query = "UPDATE `h&mcomputadores`.productos SET 
            nombre =  :nombre, marca = :marca, descripcion =:descripcion, precio = :precio, porcentaje_ganancia = :porcentaje_ganancia, stock = :stock, categoria_id = :categoria_id, estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Agotado"); //Cambia el estado del Usuario
        return $this->update();             //Guarda los cambios..
    }


    /**
     * @param $query
     * @return Productos|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrProductos = array();
            $tmp = new Productos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Producto = new Productos($valor);
                array_push($arrProductos, $Producto);
                unset($Producto);
            }
            return $arrProductos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @param $id
     * @return Productos
     * @throws Exception
     */
    public static function searchForId($id) : ?Productos
    {
        try {
            if ($id > 0) {
                $Producto = new Productos();
                $Producto->Connect();
                $getrow = $Producto->getRow("SELECT * FROM `h&mcomputadores`.productos WHERE id =?", array($id));
                $Producto->Disconnect();
                return ($getrow) ? new Productos($getrow) : null;
            }else{
                throw new Exception('Id de producto Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll(): array
    {
        return Productos::search("SELECT * FROM `h&mcomputadores`.productos");
    }


    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function productoRegistrado($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Productos::search("SELECT id FROM `h&mcomputadores`.productos where nombre = '" . $nombre. "'");
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    //esto es lo del porcentaje de ganancia
    /**
     * @return float|mixed
     */
    public function getPrecioVenta() : float
    {
        return $this->precio + ($this->precio * ($this->porcentaje_ganancia / 100));
    }


    /**
     * @return string
     */
    public function __toString() : string
    {
        return "nombre: $this->nombre, marca: $this->marca,  categoria_id: $this->categoria_id, porcentaje_ganancia: $this->porcentaje_ganancia, descripcion: $this->descripcion, stock: $this->stock, precio: $this->precio,  estado: $this->estado";
    }

    //funciones con el objetivo de calcular el stock
    public function substractStock(int $quantity)
    {
        $this->setStock( $this->getStock() - $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }

    //funciones con el objetivo de calcular el stock
    public function addStock(int $quantity)
    {
        $this->setStock( $this->getStock() + $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'marca' => $this->getMarca(),
            'descripcion' => $this->getDescripcion(),
            'precio' => $this->getPrecio(),
            'porcentaje_ganancia' => $this->getPorcentajeGanancia(),
            'stock' => $this->getStock(),
            'categoria_id' => $this->getCategoriaId(),
            'estado' => $this->getEstado(),
        ];
    }


}