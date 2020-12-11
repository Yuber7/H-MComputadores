<?php


namespace App\Models;

use App\Models\Interfaces\Model;
use Exception;
use JsonSerializable;

    class Municipios extends AbstractDBConnection implements Model, JsonSerializable
{
    protected ?int $id;
    protected string $nombre;
    protected int $departamento_id;
    protected string $acortado;
    protected string $estado;

        /* Relaciones */
        private ?Departamentos $departamento;


        //Metodo constructor
        public function __construct(array $municipio = [])
        {
            parent::__construct();
            //Propiedad recibida y asigna a una propiedad de la clase
            $this->setId($municipio['id'] ?? NULL);
            $this->setNombre($municipio['nombre'] ?? '');
            $this->setDepartamentoId($municipio['departamento_id'] ?? 0);
            $this->setAcortado($municipio['acortado'] ?? '');
            $this->setEstado($municipio['estado'] ?? '');

        }


        function __destruct()
        {
            if($this->isConnected){
                $this->Disconnect();
            }
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
         * @return Municipios
         */
        public function setId(?int $id): Municipios
        {
            $this->id = $id;
            return $this;
        }

        /**
         * @return string
         */
        public function getNombre(): string
        {
            return $this->nombre;
        }

        /**
         * @param string $nombre
         * @return Municipios
         */
        public function setNombre(string $nombre): Municipios
        {
            $this->nombre = $nombre;
            return $this;
        }

    /**
     * @return int
     */
    public function getDepartamentoId(): int
    {
        return $this->departamento_id;
    }

    /**
     * @param int $departamento_id
     */
    public function setDepartamentoId(int $departamento_id): void
    {
        $this->departamento_id = $departamento_id;
    }

    /**
     * @return mixed|string
     */
    public function getAcortado()
    {
        return $this->acortado;
    }

    /**
     * @param mixed|string $acortado
     */
    public function setAcortado($acortado): void
    {
        $this->acortado = $acortado;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }


        /**
         * Relacion con departamento
         *
         * @return Departamentos
         */
        public function getDepartamento(): ?Departamentos
        {
            if(!empty($this->departamento_id)){
                $this->departamento = Departamentos::searchForId($this->departamento_id) ?? new Departamentos();
                return $this->departamento;
            }
            return null;
        }


    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }


        static function search($query): ?array
        {
            try {
                $arrMunicipios = array();
                $tmp = new Municipios();
                $tmp->Connect();
                $getrows = $tmp->getRows($query);
                $tmp->Disconnect();

                foreach ($getrows as $valor) {
                    $Municipio = new Municipios($valor);
                    array_push($arrMunicipios, $Municipio);
                    unset($Municipio);
                }
                return $arrMunicipios;
            } catch (Exception $e) {
                GeneralFunctions::logFile('Exception',$e, 'error');
            }
            return null;
        }

        static function getAll(): array
        {
            return Municipios::search("SELECT * FROM `h&mcomputadores`.municipios");
        }

        static function searchForId(int $id): ?object
        {
            try {
                if ($id > 0) {
                    $tmpMun = new Municipios();
                    $tmpMun->Connect();
                    $getrow = $tmpMun->getRow("SELECT * FROM `h&mcomputadores`.municipios WHERE id =?", array($id));
                    $tmpMun->Disconnect();
                    return ($getrow) ? new Municipios($getrow) : null;
                }else{
                    throw new Exception('Id de municipio Invalido');
                }
            } catch (Exception $e) {
                GeneralFunctions::logFile('Exception',$e, 'error');
            }
            return null;
        }

        public function __toString() : string
        {
            return "Nombre: $this->nombre, Estado: $this->estado";
        }

        public function jsonSerialize()
        {
            return [
                'id' => $this->getId(),
                'nombre' => $this->getNombre(),
                'departamento_id' => $this->getDepartamento()->jsonSerialize(),
                'acortado' => $this->getAcortado(),
                'estado' => $this->getEstado(),
            ];
        }

        protected function save(string $query): ?bool { return null; }
        function insert(){ }
        function update() { }
        function deleted() { }

    }

//        /*protected function save(string $query): ?bool
//        {
//            $arrData = [
//                ':id' =>    $this->getId(),
//                ':nombre' =>   $this->getNombre(),
//                ':departamento_id' =>  $this->getDepartamentoId(),
//                ':acortado' =>   $this->getAcortado(),
//                ':estado' =>   $this->getEstado(),
//            ];
//            $this->Connect();
//            $result = $this->insertRow($query, $arrData);
//            $this->Disconnect();
//            return $result;
//        }
//
//
//        /**
//         * @return bool|null
//         */
//        public function insert(): ?bool
//        {
//            $query = "INSERT INTO `h&mcomputadores`.municipios VALUES (
//            :id,:nombre,:departamento_id,:acortado,:estado
//        )";
//            return $this->save($query);
//        }
//
//        public function update(): ?bool
//        {
//            $query = "UPDATE `h&mcomputadores`.municipios SET
//            nombre = :nombre, departamento_id = :departamento_id ,acortado = :acortado, estado = :estado WHERE id = :id";
//            return $this->save($query);
//        }
//
//        /**
//         * @param $id
//         * @return bool
//         * @throws Exception
//         */
//        public function deleted(): bool
//        {
//            $this->setEstado("Inactivo"); //Cambia el estado del Usuario
//            return $this->update();             //Guarda los cambios..
//        }
//
//
//        /**
//         * @param $query
//         * @return Municipios|array
//         * @throws Exception
//         */
//        public static function search($query) : ?array
//        {
//            try {
//                $arrMunicipios = array();
//                $tmp = new Municipios();
//                $tmp->Connect();
//                $getrows = $tmp->getRows($query);
//                $tmp->Disconnect();
//
//                foreach ($getrows as $valor) {
//                    $Municipio = new Municipios($valor);
//                    array_push($arrMunicipios, $Municipio);
//                    unset($Municipio);
//                }
//                return $arrMunicipios;
//            } catch (Exception $e) {
//                GeneralFunctions::logFile('Exception',$e, 'error');
//            }
//            return null;
//        }
//
//
//        /**
//         * @param $id
//         * @return Municipios
//         * @throws Exception
//         */
//        public static function searchForId(int $id): ?Municipios
//        {
//            try {
//                if ($id > 0) {
//                    $tmpMunicipio = new Municipios();
//                    $tmpMunicipio->Connect();
//                    $getrow = $tmpMunicipio->getRow("SELECT * FROM `h&mcomputadores`.municipios WHERE id =?", array($id));
//                    $tmpMunicipio->Disconnect();
//                    return ($getrow) ? new Municipios($getrow) : null;
//                }else{
//                    throw new Exception('Id de Municipio Invalido');
//                }
//            } catch (Exception $e) {
//                GeneralFunctions::logFile('Exception',$e, 'error');
//            }
//            return null;
//        }
//
//        /**
//         * @return array
//         * @throws Exception
//         */
//        public static function getAll(): array
//        {
//            return Municipios::search("SELECT * FROM `h&mcomputadores`.municipios");
//        }
//
//        /**
//         * @param $acortado
//         * @return bool
//         * @throws Exception
//         */
//        public static function MunicipioRegistrado ($acortado): bool
//        {
//            $result = Municipios::search("SELECT * FROM `h&mcomputadores`.municipios where acortado = " . $acortado);
//            if ( !empty($result) && count ($result) > 0 ) {
//                return true;
//            } else {
//                return false;
//            }
//
//        }
//
//        /**
//         * @return string
//         */
//        public function __toString() : string
//        {
//            return "Nombre: $this->nombre, departamento_id: $this->departamento_id, acortado: $this->acortado  Estado: $this->estado";
//        }
//
//        public function jsonSerialize()
//        {
//            return [
//                'id' => $this->getId(),
//                'nombre' => $this->getNombre(),
//                'departamento_id' => $this->getDepartamentoId(),
//                'acortado' => $this->getAcortado(),
//                'estado' => $this->getEstado(),
//            ];
//        }*/
