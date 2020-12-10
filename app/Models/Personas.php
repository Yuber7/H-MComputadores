<?php


namespace App\Models;

use App\Models\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Personas extends AbstractDBConnection implements Model, JsonSerializable
{
    /* Tipos de Datos => bool, int, float,  */
    protected ?int $id;
    protected string $nombre;
    protected string $apellido;
    protected string $tipo_documento;
    protected int $documento;
    protected int $telefono;
    protected string $rol;
    protected int $municipio_id;
    protected string $direccion;
    protected string $email;
    protected ?string $user;
    protected ?string $password;
    protected string $estado;

    /* Relaciones */
    private ?Municipios $municipio;
    private ?array $ventasCliente;
    private ?array $ventasAdministrador;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $persona
     */
    public function __construct(array $persona = [])
    {
        parent::__construct();
        $this->setId($persona['id'] ?? NULL);
        $this->setNombre($persona['nombre'] ?? '');
        $this->setApellido($persona['apellido'] ?? '');
        $this->setTipoDocumento($persona['tipo_documento'] ?? '');
        $this->setDocumento($persona['documento'] ?? 0);
        $this->setTelefono($persona['telefono'] ?? 0);
        $this->setRol($persona['rol'] ?? '');
        $this->setMunicipioId($persona['municipio_id'] ?? 0);
        $this->setDireccion($persona['direccion'] ?? '');
        $this->setEmail($persona['email'] ?? null);
        $this->setUser($persona['user'] ?? null);
        $this->setPassword($persona['password'] ?? null);
        $this->setEstado($persona['estado'] ?? '');
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
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getApellido(): string
    {
        return $this->apellido;
    }

    /**
     * @param string $apellido
     */
    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    /**
     * @return string
     */
    public function getTipoDocumento(): string
    {
        return $this->tipo_documento;
    }

    /**
     * @param string $tipo_documento
     */
    public function setTipoDocumento(string $tipo_documento): void
    {
        $this->tipo_documento = $tipo_documento;
    }

    /**
     * @return int
     */
    public function getDocumento(): int
    {
        return $this->documento;
    }

    /**
     * @param int $documento
     */
    public function setDocumento(int $documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return int
     */
    public function getTelefono(): int
    {
        return $this->telefono;
    }

    /**
     * @param int $telefono
     */
    public function setTelefono(int $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * @return int
     */
    public function getMunicipioId(): int
    {
        return $this->municipio_id;
    }

    /**
     * @param int $municipio_id
     */
    public function setMunicipioId(int $municipio_id): void
    {
        $this->municipio_id = $municipio_id;
    }

    /**
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string|null $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
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
     * @return Municipios|null
     */
    public function getMunicipio(): ?Municipios
    {
        if(!empty($this->municipio_id)){
            $this->municipio = Municipios::searchForId($this->municipio_id) ?? new Municipios();
            return $this->municipio;
        }
        return NULL;
    }

    /**
     * @return array
     */
    public function getVentasCliente(): ?array
    {
        if(!empty($this->getId())){
            $this->ventasCliente = Compras::search('SELECT * FROM ventas WHERE cliente_id = '.$this->getId());
            return $this->ventasCliente;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getVentasAdministrador(): ?array
    {
        if(!empty($this->getId())){
            $this->ventasAdministrador = Compras::search('SELECT * FROM ventas WHERE administrador_id = '.$this->getId());
            return $this->ventasAdministrador;
        }
        return null;
    }

    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombres' =>   $this->getNombre(),
            ':apellidos' =>   $this->getApellido(),
            ':tipo_documento' =>  $this->getTipoDocumento(),
            ':documento' =>   $this->getDocumento(),
            ':telefono' =>   $this->getTelefono(),
            ':rol' =>   $this->getRol(),
            ':municipio_id' =>   $this->getMunicipioId(),
            ':direccion' =>   $this->getDireccion(),
            ':email' =>   $this->getEmail(),
            ':user' =>  $this->getUser(),
            ':password' =>   $this->getPassword(),
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
        $query = "INSERT INTO h&mcomputadores.personas VALUES (
            :id,:nombres,:apellidos,:tipo_documento,:documento,
            :telefono,:direccion,:municipio_id,:fecha_nacimiento,:user,
            :password,:foto,:rol,:estado,:created_at,:updated_at
        )";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE h&mcomputadores.personas SET 
            nombre = :nombre, apellido = :apellido, tipo_documento = :tipo_documento, 
            documento = :documento, telefono = :telefono, rol = :rol, municipio_id = :municipio_id,
            direccion = :direccion, email = :email, user = :user,  
            password = :password, estado = :estado WHERE id = :id";
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
     * @return Personas|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrUsuarios = array();
            $tmp = new Personas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Usuario = new Personas($valor);
                array_push($arrUsuarios, $Usuario);
                unset($Usuario);
            }
            return $arrUsuarios;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }


    /**
     * @param $id
     * @return Personas
     * @throws Exception
     */
    public static function searchForId(int $id): ?Personas
    {
        try {
            if ($id > 0) {
                $tmpUsuario = new Personas();
                $tmpUsuario->Connect();
                $getrow = $tmpUsuario->getRow("SELECT * FROM h&mcomputadores.personas WHERE id =?", array($id));
                $tmpUsuario->Disconnect();
                return ($getrow) ? new Personas($getrow) : null;
            }else{
                throw new Exception('Id de persona Invalido');
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
        return Personas::search("SELECT * FROM h&mcomputadores.personas");
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function personaRegistrada($documento): bool
    {
        $result = Personas::search("SELECT * FROM h&mcomputadores.personas where documento = " . $documento);
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function nombresCompletos() : string
    {
        return $this->nombre . " " . $this->apellido;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Nombre: $this->nombre, Apellido: $this->apellido, Tipo Documento: $this->tipo_documento, Documento: $this->documento, Telefono: $this->telefono, Direccion: $this->direccion, Email: $this->email";
    }

    public function Login($User, $Password){
        try {
            $resultPersonas = Personas::search("SELECT * FROM `h&mcomputadores`.personas WHERE user = '$User'");
            if(count($resultPersonas) >= 1){
                if($resultPersonas[0]->password == $Password){
                    if($resultPersonas[0]->estado == 'Activo'){
                        return $resultPersonas[0];
                    }else{
                        return "Persona Inactiva";
                    }
                }else{
                    return "Contraseña Incorrecta";
                }
            }else{
                return "Persona Incorrecta";
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            return "Error en Servidor";
        }
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'apellido' => $this->getApellido(),
            'tipo_documento' => $this->getTipoDocumento(),
            'documento' => $this->getDocumento(),
            'telefono' => $this->getTelefono(),
            'rol' => $this->getRol(),
            'municipio_id' => $this->getMunicipioId(),
            'direccion' => $this->getDireccion(),
            'email' => $this->getEmail(),
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'estado' => $this->getEstado(),
        ];
    }


}

