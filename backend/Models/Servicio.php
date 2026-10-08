<?php
class Servicio extends Model
{
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