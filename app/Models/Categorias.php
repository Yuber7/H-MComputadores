<?php

namespace App\Models;

use App\Models\Interfaces\Model;
use Exception;
use JsonSerializable;

class Categorias extends AbstractDBConnection implements Model, JsonSerializable
{
    //Propiedades
    protected ?int $id;
    protected string $nombre;
    protected string $descripcion;
    protected string $estado;

    /* Relaciones */
    protected ?array $productosCategoria;

    /**
     * Categorias constructor. Recibe un array asociativo
     * @param array $categoria
     */
    public function __construct(array $categoria = [])
    {
        parent::__construct();
        $this->setId($categoria['id'] ?? NULL);
        $this->setNombre($categoria['nombre'] ?? '');
        $this->setDescripcion($categoria['descripcion'] ?? '');
        $this->setEstado($categoria['estado'] ?? '');
    }

    function __destruct()
    {
        if($this->isConnected){
            $this->Disconnect();
        }
    }

    /**
     * @return mixed|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param  mixed|int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return  mixed|string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param  mixed|string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = trim(mb_strtoupper($nombre));
    }

    /**
     * @return mixed|string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param mixed|string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed|bool
     */
    public function getEstado(): string
    {
        return ($this->estado) ? "Activo" : "Inactivo";
    }

    /**
     * @param mixed|bool $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = trim($estado) == "Activo";
    }

    /* Relaciones */
    /**
     * retorna un array de productos que pertenecen a una categoria
     * @return array
     */
    public function getProductosCategoria(): ?array
    {
        $this->productosCategoria = Productos::search("SELECT * FROM `h&mcomputadores`.productos WHERE categoria_id = ".$this->id." and estado = 'Disponible'");
        return $this->productosCategoria;
    }

    /**
     * @param string $query
     * @return bool|null
     */
    public function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':descripcion' =>   $this->getDescripcion(),
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
        $query = "INSERT INTO `h&mcomputadores`.categorias VALUES (:id,:nombre,:descripcion,:estado)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE `h&mcomputadores`.categorias SET 
            nombre = :nombre, descripcion = :descripcion,
            estado = :estado WHERE id = :id";
        return $this->save($query);
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }


    /**
     * @param $query
     * @return Categorias|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrCategorias = array();
            $tmp = new Categorias();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Categoria = new Categorias($valor);
                array_push($arrCategorias, $Categoria);
                unset($Categoria);
            }
            return $arrCategorias;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @param $id
     * @return Categorias
     * @throws Exception
     */
    public static function searchForId($id) : ?Categorias
    {
        try {
            if ($id > 0) {
                $Categoria = new Categorias();
                $Categoria->Connect();
                $getrow = $Categoria->getRow("SELECT * FROM `h&mcomputadores`.categorias WHERE id =?", array($id));
                $Categoria->Disconnect();
                return ($getrow) ? new Categorias($getrow) : null;
            }else{
                throw new Exception('Id de categoria Invalido');
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
    public static function getAll() : ?array
    {
        return Categorias::search("SELECT * FROM `h&mcomputadores`.categorias");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function categoriaRegistrada($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Categorias::search("SELECT id FROM `h&mcomputadores`.categorias where nombre = '" . $nombre. "'");
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
        return "Nombre: $this->nombre, Descripción: $this->descripcion, Estado: $this->estado";
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
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'estado' => $this->getEstado()
        ];
    }
}