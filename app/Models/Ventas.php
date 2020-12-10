<?php

namespace App\Models;

use App\Models\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Ventas extends AbstractDBConnection implements Model, JsonSerializable
{
    private ?int $id;
    private Carbon $fecha;
    private int $administrador_id;
    private int $cliente_id;
    private float $valor_total;
    private string $forma_pago;
    private string $estado;

    /* Relaciones */
    private ?Personas $administrador;
    private ?Personas $cliente; //objeto del cliente
    private ?array $detalleVenta;

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
        $this->setClienteId($venta['cliente_id'] ?? 0);
        $this->setValorTotal($venta['valor_total'] ?? 0);
        $this->setFormaPago($venta['forma_pago'] ?? 'Efectivo');
        $this->setEstado($venta['estado'] ?? 'Pendiente');
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
        $this->valor_total = $valor_total;
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
    public function getClienteId(): int
    {
        return $this->cliente_id;
    }

    /**
     * @param int cliente_id
     */
    public function setClienteId(int $cliente_id): void
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * @return string
     */
    public function getFormaPago(): string
    {
        return $this->forma_pago;
    }

    /**
     * @param string $forma_pago
     */
    public function setFormaPago(string $forma_pago): void
    {
        $this->forma_pago = $forma_pago;
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
     * @return array|null
     */
    public function getDetalleVenta(): ?array
    {
        return $this->detalleVenta;
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
    public function getCliente(): ?Personas
    {
        if(!empty($this->cliente_id)){
            $this->empleado = Personas::searchForId($this->cliente_id) ?? new Personas();
            return $this->cliente;
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
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':fecha' =>  $this->getFecha()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':administrador_id' =>   $this->getAdministradorId(),
            ':cliente_id' =>   $this->getClienteId(),
            ':valor_total' =>   $this->getValorTotal(),
            ':forma_pago' =>   $this->getFormaPago(),
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
        $query = "INSERT INTO h&mcomputadores.ventas VALUES (:id,:fecha,:administrador_id,:cliente_id,:valor_total,:forma_pago,:estado)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update() : ?bool
    {
        $query = "UPDATE h&mcomputadores.ventas SET 
            fecha = :fecha,administrador_id = :administrador_id,
            cliente_id = :cliente_id, valor_total = :valor_total, 
            forma_pago = :forma_pago,estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrVentas = array();
            $tmp = new Compras();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Venta = new Compras($valor);
                array_push($arrVentas, $Venta);
                unset($Venta);
            }
            return $arrVentas;
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
                $Venta = new Compras();
                $Venta->Connect();
                $getrow = $Venta->getRow("SELECT * FROM h&mcomputadores.ventas WHERE id =?", array($id));
                $Venta->Disconnect();
                return ($getrow) ? new Compras($getrow) : null;
            }else{
                throw new Exception('Id de venta Invalido');
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
        return Compras::search("SELECT * FROM h&mcomputadores.ventas");
    }

    /**
     * @param $numeroSerie
     * @return bool
     * @throws Exception
     */
    public static function facturaRegistrada($fecha): bool
    {
        $fecha = trim(strtolower($fecha));
        $result = Categorias::search("SELECT id FROM h&mcomputadores.ventas where fecha = '" . $fecha. "'");
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
        return "Fecha : $this->fecha, Cliente: ".$this->getCliente()->nombresCompletos().", Administrador: ".$this->getAdministrador()->nombresCompletos().", Fecha: $this->fecha->toDateTimeString(), Estado: $this->estado";
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
            'cliente' => $this->getCliente()->jsonSerialize(),
            'valor_total' => $this->getValorTotal(),
            'forma_pago' => $this->getFormaPago(),
            'estado' => $this->getEstado()
        ];
    }
}