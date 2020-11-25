<?php

namespace App\Models;

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once('Productos.php');
require_once('Ventas.php');
require_once('BasicModel.php');

use App\Models\Productos;
use App\Models\Ventas;


class DetalleVentas extends BasicModel
{
    //Propiedades
    protected int $id;
    protected float $valor_unitario;
    protected int $cantidad;
    protected Productos $producto_id;
    protected Ventas $venta_id;
    protected string $estado;

    public function __construct($arrDetalleVentas = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->id = $arrDetalleVentas['id'] ?? 0;
        $this->valor_unitario = $arrDetalleVentas['valor_unitario'] ?? 0;
        $this->cantidad = $arrDetalleVentas['cantidad'] ?? 0;
        $this->producto_id = !empty($arrDetalleVentas['producto_id']) ? Productos::searchForId($arrDetalleVentas['producto_id']) : new Productos();
        $this->venta_id = !empty($arrDetalleVentas['venta_id']) ? Ventas::searchForId($arrDetalleVentas['venta_id']) : new Ventas();
        $this->estado = $arrDetalleVentas['estado'] ?? '';
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
     * @return float|int|mixed
     */
    public function getValorUnitario(): float
    {
        return $this->valor_unitario;
    }

    /**
     * @param float|int|mixed $valor_unitario
     */
    public function setValorUnitario(float $valor_unitario): void
    {
        $this->valor_unitario = $valor_unitario;
    }

    /**
     * @return int|mixed
     */
    public function getCantidad(): int
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
     * @return Productos|mixed
     */
    public function getProductoId(): Productos
    {
        return $this->producto_id;
    }

    /**
     * @param Productos|mixed $producto_id
     */
    public function setProductoId(Productos $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @return Ventas|mixed
     */
    public function getVentaId(): Ventas
    {
        return $this->venta_id;
    }

    /**
     * @param \App\Models\Ventas|mixed $venta_id
     */
    public function setVentaId(Ventas $venta_id): void
    {
        $this->venta_id = $venta_id;
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
        $this->estado =$estado;
    }


    /**
     * @return mixed
     */
    public function save(): DetalleVentas
    {
        $result = $this->insertRow("INSERT INTO `h&mcomputadores`.detalle_ventas VALUES (NULL, ?, ?, ?, ?, ?)", array(
                $this->valor_unitario,
                $this->cantidad,
                $this->producto_id->getId(),
                $this->venta_id->getId(),
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
        $result = $this->updateRow("UPDATE `h&mcomputadores`.detalle_ventas SET valor_unitario = ?, cantidad = ?, producto_id= ?, venta_id = ?, estado = ? WHERE id = ?", array(
                $this->valor_unitario,
                $this->cantidad,
                $this->producto_id->getId(),
                $this->venta_id->getId(),
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
        $DetalleVentas = DetalleVentas::searchForId($id);//Buscando un Municipio por el ID
        $DetalleVentas->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $DetalleVentas->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */

    public static function search($query): array
    {
        $arrDetalleVentas = array();
        $tmp = new DetalleVentas();
        $getrows = $tmp->getRows($query);


        foreach ($getrows as $deta) {
            $DetalleVentas = new DetalleVentas();
            $DetalleVentas->id = $deta['id'];
            $DetalleVentas->valor_unitario = $deta['valor_unitario'];
            $DetalleVentas->cantidad = $deta['cantidad'];
            $DetalleVentas->producto_id = Productos::searchForId($deta['producto_id']);
            $DetalleVentas->venta_id = Ventas::searchForId($deta['venta_id']);
            $DetalleVentas->estado = $deta['estado'];
            array_push($arrDetalleVentas, $DetalleVentas);
        }
        $tmp->Disconnect();
        return $arrDetalleVentas;
    }


    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $DetalleVentas = null;
        if ($id > 0) {
            $DetalleVentas = new DetalleVentas();
            $getrow = $DetalleVentas->getRow("SELECT * FROM `h&mcomputadores`.detalle_ventas WHERE id =?", array($id));
            $DetalleVentas->id = $getrow['id'];
            $DetalleVentas->valor_unitario = $getrow['valor_unitario'];
            $DetalleVentas->cantidad = $getrow['cantidad'];
            $DetalleVentas->producto_id = Productos::searchForId($getrow['producto_id']);
            $DetalleVentas->venta_id = Ventas::searchForId($getrow['venta_id']);
            $DetalleVentas->estado = $getrow['estado'];
        }
        $DetalleVentas->Disconnect();
        return $DetalleVentas;
    }

    static function DetalleVentaRegistrada(string $id){
        $result = DetalleVentas::search("SELECT * FROM `h&mcomputadores`.detalle_ventas where id = " .$id);
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
        return DetalleVentas::search("SELECT * FROM `h&mcomputadores`.detalle_ventas");
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "fecha:  " .$this->valor_unitario.
            "cantidad:  " .$this->cantidad.
            "producto:  " .$this->producto_id.
            "venta:  " .$this->venta_id.
            "Estado:  " .$this->getEstado(). $typeOutput;
    }

}