<?php


namespace App\Models;

use App\Models\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;


class Departamentos extends AbstractDBConnection implements Model, JsonSerializable
{
    //Propiedades
    protected int $id;
    protected string $nombre;
    protected string $region;
    protected string $estado;

    //Metodo constructor
    public function __construct(array $Departamento = [])
    {
        parent::__construct();
        //Propiedad recibida y asigna a una propiedad de la clase
        $this->setId($Departamento['id'] ?? 0);
        $this->setNombre($Departamento['nombre'] ?? "");
        $this->setRegion($Departamento['region'] ?? "");
        $this->setEstado($Departamento['estado'] ?? "");

    }


    function __destruct()
    {
        if($this->isConnected){
            $this->Disconnect();
        }
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
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
        $this->nombre = $nombre;
    }

    /**
     * @return mixed|string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param mixed|string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
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


    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':region' =>   $this->getRegion(),
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
        $query = "INSERT INTO `h&mcomputadores`.departamentos VALUES (
            :id,:nombre,:region,:estado
        )";
        return $this->save($query);
    }

    public function update(): ?bool
    {
        $query = "UPDATE `h&mcomputadores`.departamentos SET 
            nombre = :nombre, region = :region, estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();             //Guarda los cambios..
    }

    /**
     * @param $query
     * @return Departamentos|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrDepartamentos = array();
            $tmp = new Departamentos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Departamento = new Departamentos($valor);
                array_push($arrDepartamentos, $Departamento);
                unset($Departamento);
            }
            return $arrDepartamentos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @param $id
     * @return Departamentos
     * @throws Exception
     */
    public static function searchForId(int $id): ?Departamentos
    {
        try {
            if ($id > 0) {
                $tmpDepartamento = new Departamentos();
                $tmpDepartamento->Connect();
                $getrow = $tmpDepartamento->getRow("SELECT * FROM `h&mcomputadores`.departamentos WHERE id =?", array($id));
                $tmpDepartamento->Disconnect();
                return ($getrow) ? new Departamentos($getrow) : null;
            }else{
                throw new Exception('Id de Departamento Invalido');
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
        return Departamentos::search("SELECT * FROM `h&mcomputadores`.departamentos");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function DepartamentoRegistrado ($nombre): bool
    {
        $result = Departamentos::search("SELECT * FROM `h&mcomputadores`.departamentos where nombre = " . $nombre);
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
        return "Nombre: $this->nombre, Region: $this->region,  Estado: $this->estado";
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'region' => $this->getRegion(),
            'estado' => $this->getEstado(),
        ];
    }
}

