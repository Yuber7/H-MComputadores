<?php


namespace App\Models;
require_once  ('BasicModel.php');

class Personas extends BasicModel
{
    //Propiedades
    protected int $id;
    protected string $nombre;
    protected string $apellido;
    protected string $tipo_documento;
    protected int $documento;
    protected int $telefono;
    protected string $rol;
    protected Municipios $municipio_id;
    protected string $direccion;
    protected string $email;
    protected string $password;
    protected bool $estado;

    //Metodo constructor
    public function __construct($arrPersonas = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->id = $arrPersonas['id'] ?? 0;
        $this->nombre = $arrPersonas['nombre'] ?? '';
        $this->apellido = $arrPersonas['apellido'] ?? '';
        $this->tipo_documento = $arrPersonas['tipo_documento'] ?? '';
        $this->documento = $arrPersonas['documento'] ?? 0;
        $this->telefono = $arrPersonas['telefono'] ?? 0;
        $this->rol = $arrPersonas['rol'] ?? '';
        $this->municipio_id = !empty($arrPersonas['municipio_id']) ? Municipios::searchForId($arrPersonas['municipio_id']) : new Municipios();
        $this->direccion = $arrPersonas['direccion'] ?? '';
        $this->email = $arrPersonas['email'] ?? '';
        $this->password = $arrPersonas['password'] ?? '';
        $this->estado = $arrPersonas['estado'] ?? '';
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
     * @return mixed|int
     */

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed|int $id
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
    public function getApellido(): string
    {
        return $this->apellido;
    }

    /**
     * @param mixed|string $apellido
     */
    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    /**
     * @return mixed|string
     */
    public function getTipo_documento(): string
    {
        return $this->tipo_documento;
    }

    /**
     * @param  $tipo_documento
     */
    public function setTipo_documento(string $tipo_documento): void
    {
        $this->tipo_documento = $tipo_documento;
    }

    /**
     * @return Municipios
     */
    public function getMunicipioId(): Municipios
    {
        return $this->municipio_id;
    }

    /**
     * @param Municipios $municipio_id
     */
    public function setMunicipioId(Municipios $municipio_id): void
    {
        $this->municipio_id = $municipio_id;
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
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
     * @return mixed|string
     */
    public function getTelefono(): int
    {
        return $this->telefono;
    }

    /**
     * @param mixed|string $telefono
     */
    public function setTelefono(int $telefono): void
    {
        $this->telefono = $telefono;
    }



    /**
     * @return mixed|string
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * @param mixed|string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * @return mixed|string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @param mixed|string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
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


    /**
     * @return mixed
     */
    public function save() : Personas
    {
        $result = $this->insertRow( "INSERT INTO `h&mcomputadores`.personas VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array(
                $this->nombre,
                $this->apellido,
                $this->tipo_documento,
                $this->documento,
                $this->telefono,
                $this->rol,
                $this->municipio_id->getId(),
                $this->direccion,
                $this->email,
                $this->password,
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
        $result = $this->updateRow( "UPDATE `h&mcomputadores`.personas SET nombre = ?, apellido = ?, tipo_documento = ?, documento = ?, email = ?, telefono = ?, rol = ?, direccion = ?, estado = ? WHERE id = ?", array(
                $this->nombre,
                $this->apellido,
                $this->tipo_documento,
                $this->documento,
                $this->telefono,
                $this->rol,
                $this->municipio_id->getId(),
                $this->direccion,
                $this->email,
                $this->password,
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
        $Personas = Personas::searchForId($id); //Buscando un Municipio por el ID
        $Personas->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $Personas->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : array
    {
        $arrPersonas = array();
        $tmp = new Personas();
        $getrows = $tmp->getRows($query);



        foreach ($getrows as $datos) {
            $Personas = new Personas();
            $Personas->id = $datos['id'];
            $Personas->nombre = $datos['nombre'];
            $Personas->estado = $datos['estado'];
            $Personas->apellido = $datos['apellido'];
            $Personas->tipo_documento = $datos['tipo_documento'];
            $Personas->documento = $datos['documento'];
            $Personas->telefono = $datos['telefono'];
            $Personas->rol = $datos['rol'];
            $Personas->municipio_id = Municipios::searchForId($datos['municipio_id']);
            $Personas->direccion = $datos['direccion'];
            $Personas->email = $datos['email'];
            $Personas->password = $datos['password'];
            $Personas->estado = $datos['estado'];
            array_push($arrPersonas, $Personas);
        }
        $tmp->Disconnect();
        return $arrPersonas;
    }


    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Personas = null;
        if ($id > 0) {
            $Personas = new Personas();
            $getrow = $Personas->getRow("SELECT * FROM `h&mcomputadores`.personas WHERE id =?", array($id));
            $Personas->id = $getrow['id'];
            $Personas->nombre = $getrow['nombre'];
            $Personas->apellido = $getrow['apellido'];
            $Personas->tipo_documento = $getrow['tipo_documento'];
            $Personas->documento = $getrow['documento'];
            $Personas->telefono = $getrow['telefono'];
            $Personas->rol = $getrow['rol'];
            $Personas->municipio_id = Municipios::searchForId($getrow['municipio_id']);
            $Personas->direccion = $getrow['direccion'];
            $Personas->email = $getrow['email'];
            $Personas->password = $getrow['password'];
            $Personas->estado = $getrow['estado'];
        }
        $Personas->Disconnect();
        return $Personas;
    }

    static function PersonaRegistrada(int $documento){
        $result = Personas::search("SELECT * FROM `h&mcomputadores`.personas where documento = " .$documento);
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
        return Personas::search("SELECT * FROM `h&mcomputadores`.personas");
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "Nombre:  " .$this->nombre.
            "Apellido:  " .$this->apellido.
            "Tipo de documento:  " .$this->tipo_documento.
            "Documento:  " .$this->documento.
            "Teléfono:  " .$this->telefono.
            "Rol:  " .$this->rol.
            "Municipio:  " .$this->municipio_id.
            "Direcciòn:  " .$this->direccion.
            "Email:  " .$this->email.
            "Estado:  " .$this->getEstado(). $typeOutput;
    }


}

