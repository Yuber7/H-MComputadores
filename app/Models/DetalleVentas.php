<?php

namespace App\Models;

use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class DetalleVentas extends AbstractDBConnection implements Model, JsonSerializable
{
    private ?int $id;
    private int $venta_id;
    private int $producto_id;
    private float $precio_venta;
    private int $cantidad;

    /* Relaciones */
    private ?Ventas $venta;
    private ?Productos $producto;

    /**
     * Detalle Venta constructor. Recibe un array asociativo
     * @param array $detalle_venta
     */
    public function __construct(array $detalle_venta = [])
    {
        parent::__construct();
        $this->setId($detalle_venta['id'] ?? NULL);
        $this->setVentaId($detalle_venta['venta_id'] ?? 0);
        $this->setProductoId($detalle_venta['producto_id'] ?? 0);
        $this->setPrecioVenta($detalle_venta['precio_venta'] ?? 0.0);
        $this->setCantidad($detalle_venta['cantidad'] ?? 0);
    }

    /**
     *
     */
    function __destruct()
    {
        $this->Disconnect();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|mixed
     */
    public function getVentaId() : int
    {
        return $this->venta_id;
    }

    /**
     * @param int|mixed $venta_id
     */
    public function setVentaId(int $venta_id): void
    {
        $this->venta_id = $venta_id;
    }

    /**
     * @return int
     */
    public function getProductoId(): int
    {
        return $this->producto_id;
    }

    /**
     * @param int $producto_id
     */
    public function setProductoId(int $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @return int|mixed
     */
    public function getCantidad() : int
    {
        return $this->cantidad;
    }

    /**
     * @param int|mixed $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float|mixed
     */
    public function getPrecioVenta() : float
    {
        return $this->precio_venta;
    }

    /**
     * @param float|mixed $precio_venta
     */
    public function setPrecioVenta(float $precio_venta): void
    {
        $this->precio_venta = $precio_venta;
    }

    public function getTotalProducto() : float
    {
        return $this->getPrecioVenta() * $this->getCantidad();
    }

    /* Relaciones */
    /**
     * Retorna el objeto venta correspondiente al detalle venta
     * @return Ventas|null
     */
    public function getVenta(): ?Ventas
    {
        if(!empty($this->ventas_id)){
            $this->venta = Ventas::searchForId($this->ventas_id) ?? new Ventas();
            return $this->venta;
        }
        return NULL;
    }

    /**
     * Retorna el objeto producto correspondiente al detalle venta
     * @return Productos|null
     */
    public function getProducto(): ?Productos
    {
        if(!empty($this->producto_id)){
            $this->producto = Productos::searchForId($this->producto_id) ?? new Productos();
            return $this->producto;
        }
        return NULL;
    }

    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':id' =>   $this->getId() ];
        }else{
            $arrData = [
                ':id' =>   $this->getId(),
                ':venta_id' =>   $this->getVentaId(),
                ':producto_id' =>  $this->getProductoId(),
                ':precio_venta' =>   $this->getPrecioVenta(),
                ':cantidad' =>   $this->getCantidad()
            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert()
    {
        $query = "INSERT INTO `h&mcomputadores`.detalle_ventas VALUES (:id,:venta_id,:producto_id,:precio_venta,:cantidad)";
        if($this->save($query)){
            return $this->getProducto()->substractStock($this->getCantidad());
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $query = "UPDATE `h&mcomputadores`.detalle_ventas SET 
            venta_id = :venta_id, producto_id = :producto_id, 
            precio_venta = :precio_venta, cantidad = :cantidad WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM detalle_ventas WHERE id = :id";
        return $this->save($query, 'deleted');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrDetalleVenta = array();
            $tmp = new DetalleVentas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetalleVenta = new DetalleVentas($valor);
                array_push($arrDetalleVenta, $DetalleVenta);
                unset($DetalleVenta);
            }
            return $arrDetalleVenta;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : ?DetalleVentas
    {
        try {
            if ($id > 0) {
                $DetalleVenta = new DetalleVentas();
                $DetalleVenta->Connect();
                $getrow = $DetalleVenta->getRow("SELECT * FROM `h&mcomputadores`.detalle_ventas WHERE id = ?", array($id));
                $DetalleVenta->Disconnect();
                return ($getrow) ? new DetalleVentas($getrow) : null;
            }else{
                throw new Exception('Id de detalle venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return DetalleVentas::search("SELECT * FROM `h&mcomputadores`.detalle_ventas");
    }

    /**
     * @param $venta_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($venta_id,$producto_id): bool
    {
        $result = DetalleVentas::search("SELECT id FROM `h&mcomputadores`.detalle_ventas where venta_id = '" . $venta_id. "' and producto_id = '" . $producto_id. "'");
        if (count($result) > 0) {
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
        return "Venta: ".$this->venta->getId().", Producto: ".$this->producto->getNombre().", Precio Venta: $this->precio_venta, Cantidad: $this->cantidad";
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
            'venta_id' => $this->getVenta()->jsonSerialize(),
            'producto_id' => $this->getProducto()->jsonSerialize(),
            'precio_venta' => $this->getPrecioVenta(),
            'cantidad' => $this->getCantidad()
        ];
    }
}