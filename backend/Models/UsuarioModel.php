<?php

class UsuarioModel extends Model
{

    //
    // Busca un usuario por su cedula. Devuelve el registro completo
    //(incluye passw_usuario hasheada) o null si no existe.
    //
    public function buscarPorCedula(string $cedula): ?array
    {
        $cedula = trim($cedula);

        if ($cedula === '' || !preg_match('/^\d{8}$/', $cedula)) {
            return null;
        }

        $stmt = $this->db->prepare(
            'SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario,
                    rol_Usuario, email_Usuario, passw_Usuario, estado_Usuario
             FROM USUARIO
             WHERE cedula_Usuario = ?
             LIMIT 1'
        );

        $stmt->bind_param('s', $cedula);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $resultado ?: null;
    }

    //
    // Actualiza la fecha de último login del usuario.
    //
    public function actualizarUltimoLogin(int $idUsuario): void
    {
        $stmt = $this->db->prepare(
            'UPDATE USUARIO SET lastlogin_Usuario = NOW() WHERE id_Usuario = ?'
        );

        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $stmt->close();
    }

    //
    // Lista Los Usuarios Registrados en la Base de Datos.
    //
    public function listadoUsuarios(string $busqueda = '', bool $checkinactivos = false): array
    {
        $resultado = [];
        $busqueda = trim($busqueda);
        $checkinactivos = (bool) $checkinactivos;

        if (empty($busqueda) && !$checkinactivos) {
            $stmt = $this->db->prepare(
            'SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario, email_Usuario
            FROM USUARIO
            WHERE estado_Usuario = "activo"'
        );
        } else if (!empty($busqueda) && !$checkinactivos) {
            $busqueda = "%$busqueda%";
            $stmt = $this->db->prepare(
                'SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario, email_Usuario
                FROM USUARIO
                WHERE ( cedula_Usuario LIKE ?
                OR nombre_Usuario LIKE ?
                OR apellido_Usuario LIKE ?
                OR CONCAT_WS(\' \', nombre_Usuario, apellido_Usuario) LIKE ? )
                AND estado_Usuario = "activo"');
            $stmt->bind_param('ssss', $busqueda, $busqueda, $busqueda, $busqueda);
        } else {
            $busqueda = "%$busqueda%";
            $stmt = $this->db->prepare(
                'SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario, email_Usuario
                FROM USUARIO
                WHERE ( cedula_Usuario LIKE ?
                OR nombre_Usuario LIKE ?
                OR apellido_Usuario LIKE ?
                OR CONCAT_WS(\' \', nombre_Usuario, apellido_Usuario) LIKE ? )
                AND estado_Usuario = "inactivo"');
            $stmt->bind_param('ssss', $busqueda, $busqueda, $busqueda, $busqueda);
        }
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $usuario;
    }

    //
    // Agrega un Nuevo Usuario a la Base de Datos
    //
    public function crearUsuarioNuevo(string $cedula, string $nombre, string $apellido, string $rol, string $email, string $passw): bool {
        $resultado = [];
        $stmt = $this->db->prepare(
            'INSERT INTO USUARIO (cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario, email_Usuario, passw_Usuario, lastlogin_Usuario, dateupdate_Usuario)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())'
        );

        $stmt->bind_param('ssssss', $cedula, $nombre, $apellido, $rol, $email, $passw);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    //
    // Función para modificar usuarios tomando en cuenta un objeto 
    // usuario almacenado en $usuarioObj y la cedula del usuario a modificar.
    //
    public function modificarUsuario(UsuarioObj $usuarioObj, string $cedulaOld): bool {
        $stmt = $this->db->prepare(
            'UPDATE USUARIO 
            SET cedula_Usuario = ?, nombre_Usuario = ?, apellido_Usuario = ?, rol_Usuario = ?, email_Usuario = ?, passw_Usuario = ?, dateupdate_Usuario = NOW()
            WHERE cedula_Usuario = ?'
        );

        $cedula   = $usuarioObj->getCedulaUsuario();
        $nombre   = $usuarioObj->getNombreUsuario();
        $apellido = $usuarioObj->getApellidoUsuario();
        $rol      = $usuarioObj->getRolUsuario();
        $email    = $usuarioObj->getEmailUsuario();
        $passw   = $usuarioObj->getPasswUsuario();
        $cedulaOldTrim = trim($cedulaOld);

        $stmt->bind_param('sssssss', $cedula, $nombre, $apellido, $rol, $email, $passw, $cedulaOldTrim);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function cambiarStatusUsuario(string $cedula, string $estado) {
        $stmt = $this->db->prepare(
            'UPDATE USUARIO
            SET estado_Usuario = ?
            WHERE cedula_Usuario = ?'
        );

        $stmt->bind_param('ss', $estado, $cedula);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function borrarUsuario(){
        
    }
}