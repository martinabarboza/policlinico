<?php

class Usuario extends Model
{

    //
    // Busca un usuario por su cedula. Devuelve el registro completo
    //(incluye passw_usuario hasheada) o null si no existe.
    //

    public function buscarPorCedula(int $cedula): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario,
                    rol_Usuario, email_Usuario, passw_Usuario
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

    public function listadoUsuarios(): array 
    {
        $stmt = $this->db->prepare(
            'SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario
            FROM USUARIO'
        );
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $usuario;
    }

    //
    // Agrega un Nuevo Usuario a la Base de Datos
    //

    public function crearUsuarioNuevo(int $cedula, string $nombre, string $apellido, string $rol, string $email, string $passw)
    {
        password_hash($passw, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            'INSERT INTO USUARIO (cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario, email_Usuario, passw_Usuario, lastlogin_Usuario, dateupdate_Usuario)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW()'
        );

        $stmt->bind_param('ssssss', $cedula, $nombre, $apellido, $rol, $email, $passw);
        $stmt->execute();
        $stmt->close();
    }

    //
    // Función para modificar usuarios tomando en cuenta un objeto usuario almacenado en $usuarioObj.
    //

    public function modificarUsuario($usuarioObj, $cedulaOld)
    {
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
}
