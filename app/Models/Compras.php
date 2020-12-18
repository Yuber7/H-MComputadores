<?php

namespace App\Models;

use App\Models\Interfaces\Model;
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
    private ?Personas $proveedor; //objeto del cliente
    private ?array $detalleCompra;

    /**
     * Compra constructor. Recibe un array asociativo
     * @param array $compra
     */
    public function __construct(array $compra = [])
    {
        parent::__construct();
        $this->setId($compra['id'] ?? NULL);
        $this->setFecha(!empty($compra['fecha']) ? Carbon::parse($compra['fecha']) : new Carbon());
        $this->setAdministradorId($compra['administrador_id'] ?? 0);
        $this->setProveedorId($compra['proveedor_id'] ?? 0);
        $this->setValorTotal($compra['valor_total'] ?? 0);
        $this->setEstado($compra['estado'] ?? 'Pendiente');
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
     * @return Carbon
     */
    public function getFecha(): Carbon
    {
        return $this->fecha;
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return float
     */
    public function getValorTotal(): float
    {
        return $this->valor_total;
    }

    /**
     * @param float $valor_total
     */
    public function setValorTotal(float $valor_total): void
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
     * @return int
     */
    public function getAdministradorId(): int
    {
        return $this->administrador_id;
    }

    /**
     * @param int $persona_id
     */
    public function setAdministradorId(int $administrador_id): void
    {
        $this->$administrador_id = $administrador_id;
    }

    /**
     * @return int
     */
    public function getProveedorId(): int
    {
        return $this->proveedor_id;
    }

    /**
     * @param int cliente_id
     */
    public function setProveedorId(int $proveedor_id): void
    {
        $this->proveedor_id = $proveedor_id;
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
     * @param Personas|null $cliente
     */
    public function setCliente(?Personas $cliente): void
    {
        $this->cliente = $cliente;
    }

    /**
     * retorna un array de detalles compra que perteneces a una compra
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
            proveedor_id = :proveedor_id, valor_total = :valor_total,
            estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $this->setEstado("Recibido"); //Cambia el estado del Usuario
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
     * @return Compras
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
    public static function facturaRegistrada($fecha): bool
    {
        $fecha = trim(strtolower($fecha));
        $result = Categorias::search("SELECT id FROM `h&mcomputadores`.compras where fecha = '" . $fecha. "'");
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
        return "Fecha : $this->fecha, Administrador: ".$this->getAdministrador()->nombresCompletos().", Proveedor: ".$this->getProveedor()->nombresCompletos().", Estado: $this->estado";
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