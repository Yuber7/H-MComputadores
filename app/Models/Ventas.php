<?php
namespace App\Models;

require_once (__DIR__ .'/../../vendor/autoload.php');
require_once ('Personas.php');
require_once  ('BasicModel.php');

use App\Models\Personas;
use Carbon\Carbon;

class Ventas extends BasicModel
{
    //Propiedades
    public int $id;
    public Carbon $fecha;
    public float $valor_total;
    public Personas $persona_id;
    protected string $forma_pago;
    public string $estado;


    public function __construct($arrVentas = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->id = $arrVentas['id'] ?? 0;
        $this->fecha = $arrVentas['fecha'] ?? new Carbon();
        $this->valor_total = $arrVentas['valor_total'] ?? 0;
        $this->persona_id = !empty($arrVentas['persona_id']) ? Personas::searchForId($arrVentas['persona_id']) : new Personas();
        $this->forma_pago = $arrVentas['forma_pago'] ?? '';
        $this->estado = $arrVentas['estado'] ?? '';
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
     * @return Carbon
     */
    public function getFecha(): Carbon
    {
        return $this->fecha->locale('es');
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return float|int|mixed
     */
    public function getValorTotal(): float
    {
        return $this->valor_total;
    }

    /**
     * @param float|int|mixed $valor_total
     */
    public function setValorTotal(float $valor_total): void
    {
        $this->valor_total = $valor_total;
    }

    /**
     * @return Personas|mixed
     */
    public function getPersonaId() :Personas
    {
        return $this->persona_id;
    }

    /**
     * @param Personas|mixed $persona_id
     */
    public function setPersonaId(Personas $persona_id): void
    {
        $this->persona_id = $persona_id;
    }

    /**
     * @return mixed|string
     */
    public function getFormaPago(): string
    {
        return $this->forma_pago;
    }

    /**
     * @param mixed|string $forma_pago
     */
    public function setFormaPago(string $forma_pago): void
    {
        $this->forma_pago = $forma_pago;
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
    public function save() : Ventas
    {
        $result = $this->insertRow( "INSERT INTO `h&mcomputadores`.ventas VALUES (NULL, ?, ?, ?, ?, ?)", array(
                $this->fecha->toDateString(), //YYYY-MM-DD,
                $this->valor_total,
                $this->persona_id->getId(),
                $this->forma_pago,
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
        $result = $this->updateRow( "UPDATE `h&mcomputadores`.ventas SET fecha = ?, valor_total = ?, persona_id= ?, forma_pago = ?, estado = ? WHERE id = ?", array(
                $this->fecha->toDateString(),
                $this->valor_total,
                $this->persona_id->getId(),
                $this->forma_pago,
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
        $Ventas = Ventas::searchForId($id); //Buscando un Municipio por el ID
        $Ventas->setEstado("Pendiente"); //Cambia el estado del Usuario
        return $Ventas->update();                    //Guarda los cambios..
    }


    /**
     * @param $query
     * @return mixed
     */

    public static function search($query) : array
    {
        $arrVentas = array();
        $tmp = new Ventas();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $deta) {
            $Ventas = new Ventas();
            $Ventas->id = $deta['id'];
            $Ventas->fecha = Carbon::parse($deta['fecha']);
            $Ventas->valor_total = $deta['valor_total'];
            $Ventas->persona_id = Personas::searchForId($deta['persona_id']);
            $Ventas->forma_pago = $deta['forma_pago'];
            $Ventas->estado = $deta['estado'];
            array_push($arrVentas, $Ventas);
        }
        $tmp->Disconnect();
        return $arrVentas;
    }



    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Ventas = null;
        if ($id > 0) {
            $Ventas = new Ventas();
            $getrow = $Ventas->getRow("SELECT * FROM `h&mcomputadores`.ventas WHERE id =?", array($id));
            $Ventas->id = $getrow['id'];
            $Ventas->fecha = Carbon::parse($getrow['fecha']);
            $Ventas->valor_total = $getrow['valor_total'];
            $Ventas->persona_id = Personas::searchForId($getrow['persona_id']);
            $Ventas->forma_pago = $getrow['forma_pago'];
            $Ventas->estado = $getrow['estado'];
        }
        $Ventas->Disconnect();
        return $Ventas;
    }


    static function VentaRegistrada(int $id){
        $result = Ventas::search("SELECT * FROM `h&mcomputadores`.ventas where id = " .$id);
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
        return Ventas::search("SELECT * FROM `h&mcomputadores`.ventas");
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "fecha:  " .$this->fecha.
            "valor total:  " .$this->valor_total.
            "persona:  " .$this->persona_id.
            "forma de pago:  " .$this->forma_pago.
            "Estado:  " .$this->getEstado(). $typeOutput;
    }


}