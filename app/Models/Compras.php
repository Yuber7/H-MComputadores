<?php

namespace App\Models;

use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Compras extends AbstractDBConnection implements Model, JsonSerializable
{
    private ?int $id;
    private Carbon $fecha;
    private int $administrador_id;
    private int $proveedor_id;
    private float $valor_total;
    private string $estado;

    /* Relaciones */
    private ?Personas $administrador;
    private ?Personas $proveedor;
    private ?array $detalleCompra;


    /**
     * Venta constructor. Recibe un array asociativo
     * @param array $venta
     */
    public function __construct(array $venta = [])
    {
        parent::__construct();
        $this->setId($venta['id'] ?? NULL);
        $this->setFecha(!empty($venta['fecha']) ? Carbon::parse($venta['fecha']) : new Carbon());
        $this->setAdministradorId($venta['administrador_id'] ?? 0);
        $this->setProveedorId($venta['proveedor_id'] ?? 0);
        $this->setValorTotal();
        $this->setEstado($venta['estado'] ?? 'En Progreso');
    }

    /**
     *
     */
    function __destruct()
    {
        $this->Disconnect();
    }

    /**
     * @return int|mixed
     * @return int|mixed
     */
    public function getId() : ?int
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
     * @return int
     */
    public function getProveedorId() : int
    {
        return $this->proveedor_id;
    }

    /**
     * @param int $proveedor_id
     */
    public function setProveedorId(int $proveedor_id): void
    {
        $this->proveedor_id = $proveedor_id;
    }

    /**
     * @return int
     */
    public function getAdministradorId() : int
    {
        return $this->administrador_id;
    }

    /**
     * @param int $administrador_id
     */
    public function setAdministradorId(int $administrador_id): void
    {
        $this->administrador_id = $administrador_id;
    }

    /**
     * @return Carbon|mixed
     */
    public function getFecha() : Carbon
    {
        return $this->fecha->locale('es');
    }

    /**
     * @param Carbon|mixed $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return float|mixed
     */
    public function getValorTotal() : float
    {
        return $this->valor_total;
    }

    /**
     * @param float|mixed $valorTotal
     */
    public function setValorTotal(): void
    {
        $total = 0;
        if($this->getId() != null){
            $arrDetallesCompra = $this->getDetalleCompra();
            if(!empty($arrDetallesCompra)){
                /* @var $arrDetallesCompra DetalleCompras[] */
                foreach ($arrDetallesCompra as $DetalleCompra){
                    $total += $DetalleCompra->getTotalProducto();
                }
            }
        }
        $this->valor_total = $total;
    }

    /**
     * @return mixed|string
     */
    public function getEstado() : string
    {
        return $this->estado;
    }

    /**
     * @param mixed|string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /* Relaciones */
    /**
     * Retorna el objeto usuario del empleado correspondiente a la venta
     * @return Personas|null
     */
    public function getAdministrador(): ?Personas
    {
        if(!empty($this->administrador_id)){
            $this->administrador = Personas::searchForId($this->administrador_id) ?? new Personas();
            return $this->administrador;
        }
        return NULL;
    }

    /**
     * Retorna el objeto usuario del cliente correspondiente a la venta
     * @return Personas|null
     */
    public function getProveedor(): ?Personas
    {
        if(!empty($this->proveedor_id)){
            $this->proveedor = Personas::searchForId($this->proveedor_id) ?? new Personas();
            return $this->proveedor;
        }
        return NULL;
    }

    /**
     * retorna un array de detalles compra que perteneces a una venta
     * @return array
     */
    public function getDetalleCompra(): ?array
    {

        $this->detalleCompra = DetalleCompras::search('SELECT * FROM `h&mcomputadores`.detalle_compras where compra_id = '.$this->id);
        return $this->detalleCompra;
    }

    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':fecha' =>  $this->getFecha()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':administrador_id' =>   $this->getAdministradorId(),
            ':proveedor_id' =>   $this->getProveedorId(),
            ':valor_total' =>   $this->getValorTotal(),
            ':estado' =>   $this->getEstado()
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */
    function insert(): ?bool
    {
        $query = "INSERT INTO `h&mcomputadores`.compras VALUES (:id,:fecha,:administrador_id,:proveedor_id,:valor_total,:estado)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update() : ?bool
    {
        $query = "UPDATE `h&mcomputadores`.compras SET 
            fecha = :fecha, administrador_id = :administrador_id, 
            proveedor_id = :proveedor_id, valor_total = :valor_total, estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $this->setEstado("Finalizada"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrCompras = array();
            $tmp = new Compras();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Compra = new Compras($valor);
                array_push($arrCompras, $Compra);
                unset($Compra);
            }
            return $arrCompras;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return Ventas
     * @throws Exception
     */
    public static function searchForId($id) : ?Compras
    {
        try {
            if ($id > 0) {
                $Compra = new Compras();
                $Compra->Connect();
                $getrow = $Compra->getRow("SELECT * FROM `h&mcomputadores`.compras WHERE id =?", array($id));
                $Compra->Disconnect();
                return ($getrow) ? new Compras($getrow) : null;
            }else{
                throw new Exception('Id de compra Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll() : array
    {
        return Compras::search("SELECT * FROM `h&mcomputadores`.compras");
    }

    /**
     * @param $numeroSerie
     * @return bool
     * @throws Exception
     */
    public static function facturaRegistrada($id): bool
    {
        $id = trim(strtolower($id));
        $result = Compras::search("SELECT id FROM `h&mcomputadores`.compras where id = '" . $id. "'");
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
        return "Fecha: $this->fecha->toDateTimeString(), Administrador: ".$this->getAdministrador()->nombresCompletos().", Proveedor: ".$this->getProveedor()->nombresCompletos().",  Valor Total: $this->valor_total, Estado: $this->estado";
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
            'fecha' => $this->getFecha()->toDateTimeString(),
            'administrador' => $this->getAdministrador()->jsonSerialize(),
            'proveedor' => $this->getProveedor()->jsonSerialize(),
            'valor_total' => $this->getValorTotal(),
            'estado' => $this->getEstado()
        ];
    }
}