<?php
class Usuario
{
    //
    // Atributos de la clase Usuario.
    //

    private int $id_usuario;
    private int $cedula_usuario;
    private string $nombre_usuario;
    private string $apellido_usuario;
    private string $rol_usuario;
    private string $email_usuario;
    private string $passwd_usuario;
    private datetime $lastlogin_usuario;
    private datetime $dateupdate_usuario;

    //
    // Constructor de un objeto para esta clase.
    //

    public function __CONSTRUCT() {

    }

    // --- ID USUARIO ---
    public function getIdUsuario(): ?int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    // --- CEDULA USUARIO ---
    public function getCedulaUsuario(): ?string
    {
        return $this->cedula_usuario;
    }

    public function setCedulaUsuario(?string $cedula_usuario): void
    {
        $this->cedula_usuario = $cedula_usuario;
    }

    // --- NOMBRE USUARIO ---
    public function getNombreUsuario(): ?string
    {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario(?string $nombre_usuario): void
    {
        $this->nombre_usuario = $nombre_usuario;
    }

    // --- APELLIDO USUARIO ---
    public function getApellidoUsuario(): ?string
    {
        return $this->apellido_usuario;
    }

    public function setApellidoUsuario(?string $apellido_usuario): void
    {
        $this->apellido_usuario = $apellido_usuario;
    }

    // --- ROL USUARIO ---
    public function getRolUsuario(): ?string
    {
        return $this->rol_usuario;
    }

    public function setRolUsuario(?string $rol_usuario): void
    {
        $this->rol_usuario = $rol_usuario;
    }

    // --- EMAIL USUARIO ---
    public function getEmailUsuario(): ?string
    {
        return $this->email_usuario;
    }

    public function setEmailUsuario(?string $email_usuario): void
    {
        $this->email_usuario = $email_usuario;
    }

    // --- PASSWD USUARIO ---
    public function getPasswdUsuario(): ?string
    {
        return $this->passwd_usuario;
    }

    public function setPasswdUsuario(?string $passwd_usuario): void
    {
        $this->passwd_usuario = $passwd_usuario;
    }

    // --- LASTLOGIN USUARIO ---
    public function getLastloginUsuario(): ?datetime
    {
        return $this->lastlogin_usuario;
    }

    public function setLastloginUsuario(?datetime $lastlogin_usuario): void
    {
        $this->lastlogin_usuario = $lastlogin_usuario;
    }

    // --- DATEUPDATE USUARIO ---
    public function getDateupdateUsuario(): ?datetime
    {
        return $this->dateupdate_usuario;
    }

    public function setDateupdateUsuario(?datetime $dateupdate_usuario): void
    {
        $this->dateupdate_usuario = $dateupdate_usuario;
    }

}
