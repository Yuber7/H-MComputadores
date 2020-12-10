<?php


namespace App\Models;

use App\Models\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Fotos extends AbstractDBConnection implements Model, JsonSerializable
{
    //Propiedades
    protected int $id;
    protected string $descripcion;
    protected string $ruta;
    protected int $productos_id;
    protected string $estado;

    /* Relaciones */
    private Productos $producto;


    /**
     * Fotos constructor.
     * @param array $foto
     */
    public function __construct(array $foto = [])
    {
        parent::__construct();
        $this->setId($foto['id'] ?? 0);
        $this->setDescripcion($foto['descripcion'] ?? '');
        $this->setRuta($foto['ruta'] ?? '');
        $this->setProductosId($foto['producto_id'] ?? 0);
        $this->setEstado($foto['estado'] ?? '');
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
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getRuta(): string
    {
        return $this->ruta;
    }

    /**
     * @param string $ruta
     */
    public function setRuta(string $ruta): void
    {
        $this->ruta = $ruta;
    }

    /**
     * @return Fotos|\App\Models\Productos|mixed
     */
    public function getProductosId()
    {
        return $this->productos_id;
    }

    /**
     * @param Fotos|\App\Models\Productos|mixed $productos_id
     */
    public function setProductosId($productos_id): void
    {
        $this->productos_id = $productos_id;
    }


    /**
     * @return mixed|string
     */
    public function getEstado(): string
    {
        return ($this->estado);
    }

    /**
     * @param mixed|string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getProducto(): ?Productos
    {
        if(!empty($this->producto_id)){
            $this->producto = Productos::searchForId($this->producto_id) ?? new Productos();
            return $this->producto;
        }
        return null;
    }

    /**
     * @param Productos $producto
     */
    public function setProducto(Productos $producto): void
    {
        $this->producto = $producto;
    }


    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':descripcion' =>   $this->getDescripcion(),
            ':ruta' =>  $this->getRuta(),
            ':productos_id' =>   $this->getProductosId(),
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
        $query = "INSERT INTO `h&mcomputadores`.fotos VALUES (:id, :descripcion, :ruta, :productos_id, :estado)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    function update(): ?bool
    {
        $query = "UPDATE `h&mcomputadores`.fotos SET 
            descripcion = :descripcion, ruta = :ruta, productos_id = :productos_id, 
             estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    function deleted() : bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return Fotos|array
     * @throws Exception
     */
    static function search($query): ?array
    {
        try {
            $arrFotos = array();
            $tmp = new Fotos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Foto = new Fotos($valor);
                array_push($arrFotos, $Foto);
                unset($Foto);
            }
            return $arrFotos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }


    /**
     * @return array
     * @throws Exception
     */
    static function getAll(): ?array
    {
        return Fotos::search("SELECT * FROM `h&mcomputadores`.fotos");
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $Foto = new Fotos();
                $Foto->Connect();
                $getrow = $Foto->getRow("SELECT * FROM `h&mcomputadores`.fotos WHERE id =?", array($id));
                $Foto->Disconnect();
                return ($getrow) ? new Fotos($getrow) : null;
            }else{
                throw new Exception('Id de foto Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return " Descripcion: $this->descripcion, Ruta: $this->ruta, productos_id: $this->productos_id, Estado: $this->estado";
    }

    public function jsonSerialize()
    {
        return [
            'descripcion' => $this->getDescripcion(),
            'ruta' => $this->getRuta(),
            'productos_id' => $this->getProductosId(),
            'estado' => $this->getEstado(),
        ];
    }

}

