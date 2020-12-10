<?php

namespace App\Models;

use App\Models\Interfaces\Model;
use Exception;
use JsonSerializable;

class Productos extends AbstractDBConnection implements Model, JsonSerializable
{
    //Propiedades
    protected int $id;
    protected string $nombre;
    protected string $marca;
    protected int $categoria_id;
    protected string $referencia_fabrica;
    protected string $descripcion;
    protected int $stock;
    protected float $precio;
    protected string $estado;

    private ?Categorias $categoria;


    public function __construct(array $producto = [])
    {
        {
            parent::__construct();
            //Propiedad recibida y asigna a una propiedad de la clase
            $this->setId($producto['id'] ?? 0);
            $this->setNombre($producto['nombre'] ?? "");
            $this->setMarca($producto['marca'] ?? "");
            $this->setCategoriaId($producto['categoria_id'] ?? 0);
            $this->setReferenciaFabrica($producto['referencia_fabrica'] ?? "");
            $this->setDescripcion($producto['descripcion'] ?? "");
            $this->setStock($producto['stock'] ?? 0);
            $this->setPrecio($producto['precio'] ?? 0);
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


    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':departamento_id' =>  $this->getMarca(),
            ':categoria_id' =>   $this->getCategoriaId(),
            ':referencia_fabrica' =>   $this->getReferenciaFabrica(),
            ':descripcion' =>   $this->getDescripcion(),
            ':stock' =>   $this->getStock(),
            ':precio' =>   $this->getPrecio(),
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
            :id, :nombre, :marca, :categoria_id, :referencia_id, :descripcion, :stock, :precio, :estado
        )";
        return $this->save($query);
    }

    public function update(): ?bool
    {
        $query = "UPDATE `h&mcomputadores`.productos SET 
            nombre = :nombre, marca = :marca ,categoria_id = :categoria_id, referencia_fabrica = :referencia_fabrica, descripcion = :descripcion, stock = :stock, precio = :precio, estado = :estado WHERE id = :id";
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
                $producto = new Productos($valor);
                array_push($arrProductos, $producto);
                unset($producto);
            }
            return $arrProductos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @param $id
     * @return Municipios
     * @throws Exception
     */
    public static function searchForId(int $id): ?Productos
    {
        try {
            if ($id > 0) {
                $tmpProducto = new Productos();
                $tmpProducto->Connect();
                $getrow = $tmpProducto->getRow("SELECT * FROM `h&mcomputadores`.productos WHERE id =?", array($id));
                $tmpProducto->Disconnect();
                return ($getrow) ? new Productos($getrow) : null;
            }else{
                throw new Exception('Id de Producto Invalido');
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
     * @param $referencia_fabrica
     * @return bool
     * @throws Exception
     */
    public static function ProductoRegistrado ( $referencia_fabrica): bool
    {
        $result = Productos::search("SELECT * FROM `h&mcomputadores`.productos where referencia_fabrica = " .  $referencia_fabrica);
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "nombre: $this->nombre, marca: $this->marca,  categoria_id: $this->categoria_id, referencia_fabrica: $this->referencia_fabrica, descripcion: $this->descripcion, stock: $this->stock, precio: $this->precio,  estado: $this->estado";
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'marca' => $this->getMarca(),
            'categoria_id' => $this->getCategoriaId(),
            'referencia_fabrica' => $this->getReferenciaFabrica(),
            'descripcion' => $this->getDescripcion(),
            'stock' => $this->getStock(),
            'precio' => $this->getPrecio(),
            'estado' => $this->getEstado(),
        ];
    }


}