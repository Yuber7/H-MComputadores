<?php

namespace App\Models;

use App\Models\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class DetalleVentas extends AbstractDBConnection implements Model, JsonSerializable
{
    private ?int $id;
    private float $valor_unitario;
    private int $cantidad;
    private int $producto_id;
    private int $venta_id;
    private string $estado;

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
        $this->setValorUnitario($detalle_venta['valor_unitario'] ?? 0.0);
        $this->setCantidad($detalle_venta['cantidad'] ?? 0);
        $this->setProductoId($detalle_venta['producto_id'] ?? 0);
        $this->setVentaId($detalle_venta['venta_id'] ?? 0);
        $this->setEstado($venta['estado'] ?? 'Inactivo');
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
     * @return float
     */
    public function getValorUnitario(): float
    {
        return $this->valor_unitario;
    }

    /**
     * @param float $valor_unitario
     */
    public function setValorUnitario(float $valor_unitario): void
    {
        $this->valor_unitario = $valor_unitario;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
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
     * @return int
     */
    public function getVentaId(): int
    {
        return $this->venta_id;
    }

    /**
     * @param int $venta_id
     */
    public function setVentaId(int $venta_id): void
    {
        $this->venta_id = $venta_id;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return Ventas|null
     */
    public function getVenta(): ?Ventas
    {
        return $this->venta;
    }

    /**
     * @param Ventas|null $venta
     */
    public function setVenta(?Ventas $venta): void
    {
        $this->venta = $venta;
    }

    /**
     * @return Productos|null
     */
    public function getProducto(): ?Productos
    {
        return $this->producto;
    }

    /**
     * @param Productos|null $producto
     */
    public function setProducto(?Productos $producto): void
    {
        $this->producto = $producto;
    }

    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':id' =>   $this->getId() ];
        }else{
            $arrData = [
                ':id' =>   $this->getId(),
                ':valor_unitario' =>   $this->getValorUnitario(),
                ':cantidad' =>   $this->getCantidad(),
                ':producto_id' =>  $this->getProductoId(),
                ':venta_id' =>   $this->getVentaId(),
                ':estado' =>   $this->getEstado()
            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert()
    {
        $query = "INSERT INTO h&mcomputadores.detalle_ventas VALUES (:id,:valor_unitario,:cantidad,:producto_id,:venta_id,:estado)";
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
        $query = "UPDATE h&mcomputadores.detalle_ventas SET 
            valor_unitario = :valor_unitario, cantidad = :cantidad, producto_id = :producto_id, 
            venta_id = :venta_id, estado = :estado WHERE id = :id";
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
                $getrow = $DetalleVenta->getRow("SELECT * FROM h&mcomputadores.detalle_ventas WHERE id = ?", array($id));
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
        return DetalleVentas::search("SELECT * FROM h&mcomputadores.detalle_ventas");
    }

    /**
     * @param $venta_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($venta_id,$producto_id): bool
    {
        $result = DetalleVentas::search("SELECT id FROM h&mcomputadores.detalle_ventas where venta_id = '" . $venta_id. "' and producto_id = '" . $producto_id. "'");
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
        return "Venta: ".$this->venta->getId().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Venta: $this->valor_unitario";
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
            'valor_unitario' => $this->getValorUnitario(),
            'cantidad' => $this->getCantidad(),
            'producto_id' => $this->getProducto()->jsonSerialize(),
            'venta_id' => $this->getVenta()->jsonSerialize(),
            'estado' => $this->getEstado()
        ];
    }
}