<?php

/** Provee los servicios publicados que consume la página de inicio. */
class Servicio extends Model
{
    /** Devuelve los campos de servicio con los alias esperados por la vista landing. */
    public function getArray_Servicios(): array
    {

        $stmt = $this->db->prepare(
            "SELECT
                nombre_Servicio AS titulo,
                descripcion_Servicio AS descripcion,
                imagenURL_Servicio AS imagen,
                '/login' AS link
             FROM SERVICIO"
        );

        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $resultado;
    }
}
?>