    <?php
    class Usuario
    {
        //
        // Atributos de la clase Usuario.
        //

        private int $id_Usuario;
        private int $cedula_Usuario;
        private string $nombre_Usuario;
        private string $apellido_Usuario;
        private string $rol_Usuario;
        private string $email_Usuario;
        private string $passw_usuario;
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
            return $this->id_Usuario;
        }

        public function setIdUsuario(?int $id_Usuario): void
        {
            $this->id_Usuario = $id_Usuario;
        }

        // --- CEDULA USUARIO ---
        public function getCedulaUsuario(): ?string
        {
            return $this->cedula_Usuario;
        }

        public function setCedulaUsuario(?string $cedula_Usuario): void
        {
            $this->cedula_Usuario = $cedula_Usuario;
        }

        // --- NOMBRE USUARIO ---
        public function getNombreUsuario(): ?string
        {
            return $this->nombre_Usuario;
        }

        public function setNombreUsuario(?string $nombre_Usuario): void
        {
            $this->nombre_Usuario = $nombre_Usuario;
        }

        // --- APELLIDO USUARIO ---
        public function getApellidoUsuario(): ?string
        {
            return $this->apellido_Usuario;
        }

        public function setApellidoUsuario(?string $apellido_Usuario): void
        {
            $this->apellido_Usuario = $apellido_Usuario;
        }

        // --- ROL USUARIO ---
        public function getRolUsuario(): ?string
        {
            return $this->rol_Usuario;
        }

        public function setRolUsuario(?string $rol_Usuario): void
        {
            $this->rol_Usuario = $rol_Usuario;
        }

        // --- EMAIL USUARIO ---
        public function getEmailUsuario(): ?string
        {
            return $this->email_Usuario;
        }

        public function setEmailUsuario(?string $email_Usuario): void
        {
            $this->email_Usuario = $email_Usuario;
        }

        // --- PASSWD USUARIO ---
        public function getPasswUsuario(): ?string
        {
            return $this->passw_usuario;
        }

        public function setPasswUsuario(?string $passw_usuario): void
        {
            $this->passw_usuario = $passw_usuario;
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
