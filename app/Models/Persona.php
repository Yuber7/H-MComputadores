<?php


namespace App\Models;
use Carbon\Carbon;
require_once  ('BasicModel.php');


class Persona extends BasicModel
{
    //Propiedades
    protected int $id;
    protected string $nombre;
    protected string $apellido;
    protected string $tipoDocumento;
    protected int $documento;
    protected string $correo;
    protected string $telefono;
    protected string $rol;
    protected string $direccion;
    protected bool $estado;

    //Metodo constructor
    public function __construct($arrPersona = array())
    {
        //Propiedad recibida y asigna a una propiedad de la clase
        parent::__construct();
        $this->setId($arrPersona['id'] ?? 0);
        $this->setNombre($arrPersona['nombre'] ?? "");
        $this->setApellido($arrPersona['apellido'] ?? "");
        $this->setTipoDocumento($arrPersona['tipoDocumento'] ?? "");
        $this->setDocumento($arrPersona['documento'] ?? 0);
        $this->setCorreo($arrPersona['correo'] ?? "");
        $this->setTelefono($arrPersona['telefono'] ?? "");
        $this->setRol($arrPersona['rol'] ?? "") ;
        $this->setDireccion($arrPersona['direccion'] ?? "");
        $this->setEstado($arrPersona['estado'] ?? "");
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
    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento;
    }

    /**
     * @param  $tipoDocumento
     */
    public function setTipoDocumento(string $tipoDocumento): void
    {
        $this->tipoDocumento = $tipoDocumento;
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
    public function getCorreo(): string
    {
        return $this->correo;
    }

    /**
     * @param mixed|string $correo
     */
    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }


    /**
     * @return mixed|string
     */
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @param mixed|string $telefono
     */
    public function setTelefono(string $telefono): void
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
        return ($this->estado) ? "activo" : "inactivo";
    }

    /**
     * @param mixed|bool $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = strtolower(trim($estado)) == "activo";
    }


    /**
     * @return mixed
     */
    public function save() : Persona
    {
        $result = $this->insertRow( "INSERT INTO `h&mcomputadores`.persona VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array(
                $this->getNombre(),
                $this->getApellido(),
                $this->getTipoDocumento(),
                $this->getDocumento(),
                $this->getCorreo(),
                $this->getTelefono(),
                $this->getRol(),
                $this->getDireccion(),
                $this->getEstado()
            )
        );
        $this->Disconnect();
        return $this;
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $result = $this->updateRow( "UPDATE `h&mcomputadores`.persona SET nombre = ?, apellido = ?, tipoDocumento = ?, documento = ?, correo = ?, telefono = ?, rol = ?, direccion = ?, estado = ? WHERE id = ?", array(
                $this->getNombre(),
                $this->getApellido(),
                $this->getTipoDocumento(),
                $this->getDocumento(),
                $this->getCorreo(),
                $this->getTelefono(),
                $this->getRol(),
                $this->getDireccion(),
                $this->getEstado(),
                $this->getId()
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
        $result = $this->updateRow("UPDATE `h&mcomputadores`.persona SET estado = ? WHERE id = ?", array(
                'inactivo',
                $this->getId()
            )
        );
        $this->Disconnect();
        return $this;
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query)
    {
        $arrPersonas = array();
        $tmp = new Persona();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Persona = new Persona();
            $Persona->setId($valor['id']);
            $Persona->setNombre($valor['nombre']);
            $Persona->setApellido($valor['apellido']);
            $Persona->setTipoDocumento($valor['tipoDocumento']);
            $Persona->setDocumento($valor['documento']);
            $Persona->setCorreo($valor['correo']);
            $Persona->setTelefono($valor['telefono']);
            $Persona->setRol($valor['rol']);
            $Persona->setDireccion($valor['direccion']);
            $Persona->setEstado($valor['estado']);
            $Persona->Disconnect();
            array_push($arrPersonas, $Persona);
        }
        $tmp->Disconnect();
        return $arrPersonas;    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Persona::search("SELECT * FROM `h&mcomputadores`.persona");
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Persona = null;
        if ($id > 0) {
            $Persona = new Persona();
            $getrow = $Persona->getRow("SELECT * FROM `h&mcomputadores`.persona WHERE id =?", array($id));
            $Persona->setId($getrow['id']);
            $Persona->setNombre($getrow['nombre']);
            $Persona->setApellido($getrow['apellido']);
            $Persona->setTipoDocumento($getrow['tipoDocumento']);
            $Persona->setDocumento($getrow['documento']);
            $Persona->setCorreo($getrow['correo']);
            $Persona->setTelefono($getrow['telefono']);
            $Persona->setRol($getrow['rol']);
            $Persona->setDireccion($getrow['direccion']);
            $Persona->setEstado($getrow['estado']);
        }
        $Persona->Disconnect();
        return $Persona;
    }

    //Metodo
    public function saludar (?string $nombre = "Sujeto", ?string $apellido = "Anonimo") : string
    { //Visibilidad, function, nombre metodo(parametros), retorno
        return "Hola ".$this->nombre." ".$this->apellido.", Bienvenido a H&M Computadores<br/>";
    }


    static function PersonaRegistrada(int $documento){
        $result = Persona::search("SELECT * FROM `h&mcomputadores`.persona where documento = " .$documento);
        if ( count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function __toString() : string
    {
        $typeOutput = "\n";
        return
            "Nombre:  " .$this->getNombre(). $typeOutput.
            "Apellido:  " .$this->getApellido(). $typeOutput.
            "Tipo de documento:  " .$this->getTipoDocumento(). $typeOutput.
            "Documento:  " .$this->getDocumento(). $typeOutput.
            "Correo:  " .$this->getCorreo(). $typeOutput.
            "Teléfono:  " .$this->getTelefono(). $typeOutput.
            "Rol:  " .$this->getRol(). $typeOutput.
            "Direcciòn:  " .$this->getDireccion(). $typeOutput.
            "Estado:  " .$this->getEstado(). $typeOutput;
    }


}

