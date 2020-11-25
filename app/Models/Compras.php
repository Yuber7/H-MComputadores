<?php
namespace App\Models;

require_once (__DIR__ .'/../../vendor/autoload.php');
require_once ('Personas.php');
require_once  ('BasicModel.php');

use App\Models\Personas;
use Carbon\Carbon;

class Compras extends BasicModel
{
    //Propiedades
    protected int $id;
    protected Carbon $fecha;
    protected float $valor_total;
    protected Personas $persona_id;
    protected string $estado;


    public function __construct($arrCompras = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->id = $arrCompras['id'] ?? 0;
        $this->fecha = $arrCompras['fecha'] ?? new Carbon();
        $this->valor_total = $arrCompras['valor_total'] ?? 0;
        $this->persona_id = !empty($arrCompras['persona_id']) ? Personas::searchForId($arrCompras['persona_id']) : new Personas();
        $this->estado = $arrCompras['estado'] ?? '';
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
    public function save() : Compras
    {
        $result = $this->insertRow( "INSERT INTO `h&mcomputadores`.compras VALUES (NULL, ?, ?, ?, ?)", array(
                $this->fecha->toDateString(), //YYYY-MM-DD,
                $this->valor_total,
                $this->persona_id->getId(),
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
        $result = $this->updateRow( "UPDATE `h&mcomputadores`.compras SET fecha = ?, valor_total = ?, persona_id= ?, estado = ? WHERE id = ?", array(
                $this->fecha->toDateString(),
                $this->valor_total,
                $this->persona_id->getId(),
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
        $Compras = Compras::searchForId($id); //Buscando un Municipio por el ID
        $Compras->setEstado("Pendiente"); //Cambia el estado del Usuario
        return $Compras->update();                    //Guarda los cambios..
    }


    /**
     * @param $query
     * @return mixed
     */

    public static function search($query) : array
    {
        $arrCompras = array();
        $tmp = new Compras();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $comp) {
            $Compras = new Compras();
            $Compras->id = $comp['id'];
            $Compras->fecha = Carbon::parse($comp['fecha']);
            $Compras->valor_total = $comp['valor_total'];
            $Compras->persona_id = Personas::searchForId($comp['persona_id']);
            $Compras->estado = $comp['estado'];
            array_push($arrCompras, $Compras);
        }
        $tmp->Disconnect();
        return $arrCompras;
    }



    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Compras = null;
        if ($id > 0) {
            $Compras = new Compras();
            $getrow = $Compras->getRow("SELECT * FROM `h&mcomputadores`.compras WHERE id =?", array($id));
            $Compras->id = $getrow['id'];
            $Compras->fecha = Carbon::parse($getrow['fecha']);
            $Compras->valor_total = $getrow['valor_total'];
            $Compras->persona_id = Personas::searchForId($getrow['persona_id']);
            $Compras->estado = $getrow['estado'];
        }
        $Compras->Disconnect();
        return $Compras;
    }


    static function CompraRegistrada(int $id){
        $result = Compras::search("SELECT * FROM `h&mcomputadores`.compras where id = " .$id);
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
        return Compras::search("SELECT * FROM `h&mcomputadores`.compras");
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "fecha:  " .$this->fecha.
            "valor total:  " .$this->valor_total.
            "persona:  " .$this->persona_id.
            "Estado:  " .$this->getEstado(). $typeOutput;
    }


}