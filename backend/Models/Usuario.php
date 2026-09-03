<?php

class Usuario extends Model
{
    
      // Busca un usuario por su cedula. Devuelve el registro completo
      //(incluye passwd_usuario hasheada) o null si no existe
     
    public function buscarPorCedula(int $cedula): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id_usuario, cedula_usuario, nombre_usuario, apellido_usuario,
                    rol_usuario, email_usuario, passwd_usuario
             FROM usuarios
             WHERE cedula_usuario = ?
             LIMIT 1'
        );

        $stmt->bind_param('i', $cedula);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $resultado ?: null;
    }

    
     // Actualiza la fecha de último login del usuario.
     
    public function actualizarUltimoLogin(int $idUsuario): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios SET lastlogin_usuario = NOW() WHERE id_usuario = ?'
        );

        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $stmt->close();
    }
}