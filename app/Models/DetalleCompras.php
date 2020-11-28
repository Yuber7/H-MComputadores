<?php

namespace App\Models;

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once('Productos.php');
require_once('Compras.php');
require_once('BasicModel.php');

use App\Models\Productos;
use App\Models\Ventas;


class DetalleCompras extends BasicModel
{
    //Propiedades
    protected int $id;
    protected float $valor_unitario;
    protected int $cantidad;
    protected Productos $producto_id;
    protected Compras $Compras_id;
    protected string $estado;

    public function __construct($arrDetalleCompras = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->id = $arrDetalleCompras['id'] ?? 0;
        $this->valor_unitario = $arrDetalleCompras['valor_unitario'] ?? 0;
        $this->cantidad = $arrDetalleCompras['cantidad'] ?? 0;
        $this->producto_id = !empty($arrDetalleCompras['producto_id']) ? Productos::searchForId($arrDetalleCompras['producto_id']) : new Productos();
        $this->Compras_id = !empty($arrDetalleCompras['compras_id']) ? Compras::searchForId($arrDetalleCompras['compras_id']) : new Compras();
        $this->estado = $arrDetalleCompras['estado'] ?? '';
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return float|int|mixed
     */
    public function getValorUnitario()
    {
        return $this->valor_unitario;
    }

    /**
     * @param float|int|mixed $valor_unitario
     */
    public function setValorUnitario($valor_unitario): void
    {
        $this->valor_unitario = $valor_unitario;
    }

    /**
     * @return int|mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param int|mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return \App\Models\Productos|mixed
     */
    public function getProductoId()
    {
        return $this->producto_id;
    }

    /**
     * @param \App\Models\Productos|mixed $producto_id
     */
    public function setProductoId($producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @return Compras|\App\Models\Ventas|mixed
     */
    public function getComprasId()
    {
        return $this->Compras_id;
    }

    /**
     * @param Compras|\App\Models\Ventas|mixed $Compras_id
     */
    public function setComprasId($Compras_id): void
    {
        $this->Compras_id = $Compras_id;
    }

    /**
     * @return mixed|string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed|string $estado
     */
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }


    public function save(): DetalleCompras
    {
        $result = $this->insertRow("INSERT INTO `h&mcomputadores`.detalle_Compras VALUES (NULL, ?, ?, ?, ?, ?)", array(
                $this->valor_unitario,
                $this->cantidad,
                $this->producto_id->getId(),
                $this->compras_id->getId(),
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
        $result = $this->updateRow("UPDATE `h&mcomputadores`.detalle_compras SET valor_unitario = ?, cantidad = ?, producto_id= ?, compras_id = ?, estado = ? WHERE id = ?", array(
                $this->valor_unitario,
                $this->cantidad,
                $this->producto_id->getId(),
                $this->compras_id->getId(),
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
        $DetalleCompras = DetalleCompras::searchForId($id);//Buscando un Municipio por el ID
        $DetalleCompras->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $DetalleCompras->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */

    public static function search($query): array
    {
        $arrDetalleCompras = array();
        $tmp = new DetalleCompras();
        $getrows = $tmp->getRows($query);


        foreach ($getrows as $deta) {
            $DetalleCompras = new DetalleCompras();
            $DetalleCompras->id = $deta['id'];
            $DetalleCompras->valor_unitario = $deta['valor_unitario'];
            $DetalleCompras->cantidad = $deta['cantidad'];
            $DetalleCompras->producto_id = Productos::searchForId($deta['producto_id']);
            $DetalleCompras->Compras_id = Compras::searchForId($deta['Compras_id']);
            $DetalleCompras->estado = $deta['estado'];
            array_push($arrDetalleCompras, $DetalleCompras);
        }
        $tmp->Disconnect();
        return $arrDetalleCompras;
    }


    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $DetalleCompras = null;
        if ($id > 0) {
            $DetalleCompras = new DetalleCompras();
            $getrow = $DetalleCompras->getRow("SELECT * FROM `h&mcomputadores`.detalle_compras WHERE id =?", array($id));
            $DetalleCompras->id = $getrow['id'];
            $DetalleCompras->valor_unitario = $getrow['valor_unitario'];
            $DetalleCompras->cantidad = $getrow['cantidad'];
            $DetalleCompras->producto_id = Productos::searchForId($getrow['producto_id']);
            $DetalleCompras->Compras_id = Compras::searchForId($getrow['Compras_id']);
            $DetalleCompras->estado = $getrow['estado'];
        }
        $DetalleCompras->Disconnect();
        return $DetalleCompras;
    }

    static function DetalleComprasRegistrada(string $id){
        $result = DetalleCompras::search("SELECT * FROM `h&mcomputadores`.detalle_compras where id = " .$id);
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
        return DetalleCompras::search("SELECT * FROM `h&mcomputadores`.detalle_Compras");
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "fecha:  " .$this->valor_unitario.
            "cantidad:  " .$this->cantidad.
            "producto:  " .$this->producto_id.
            "compras:  " .$this->Compras_id.
            "Estado:  " .$this->getEstado(). $typeOutput;
    }

}