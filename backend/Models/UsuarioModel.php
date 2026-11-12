<?php

/** Consultas y operaciones de persistencia para cuentas de acceso. */
class UsuarioModel extends Model
{
    /** Busca por cédula validada y devuelve el hash para que AuthController verifique la contraseña. */
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

    /** Registra el último acceso en hora local de la conexión de base de datos. */
    public function actualizarUltimoLogin(int $idUsuario): void
    {
        $stmt = $this->db->prepare(
            'UPDATE USUARIO SET lastlogin_Usuario = NOW() WHERE id_Usuario = ?'
        );

        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $stmt->close();
    }

    /** Busca usuarios activos o inactivos; la opción controla cuál conjunto se devuelve. */
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

    /** Inserta una cuenta; la contraseña recibida debe venir ya hasheada. */
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

    /** Actualiza los campos de la cuenta identificada por la cédula anterior. */
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

    /** Cambia el estado de una cuenta sin eliminar su historial ni asociaciones. */
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

    /** Método legado sin implementación; las cuentas se desactivan con cambiarStatusUsuario(). */
    public function borrarUsuario(){
        
    }
}