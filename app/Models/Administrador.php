<?php


namespace App\Models;


class Administrador extends Persona
{
    private string $contraseña;

    public function __construct($id = 0, $nombre = "", $apellido = "", $tipoDocumento = "", $documento = 0, $correo = "", $telefono = "", $rol = "", $direccion = "", $estado = "", $contraseña="")
    {
        parent::__construct($id, $nombre, $apellido, $tipoDocumento, $documento, $correo, $telefono, $rol, $direccion, $estado, $contraseña);
        $this->setContraseña($contraseña);
        $this->setRol('Administrador');
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @return string
     */
    public function getContraseña(): string
    {
        return $this->contraseña;
    }

    /**
     * @param string $contraseña
     */
    public function setContraseña(string $contraseña): void
    {
        $this->contraseña = $contraseña;
    }


}