<?php
class Servicio extends Model
{
    public function getArray_Servicios(): array
    {

        $stmt = $this->db->prepare(
            "SELECT
                nombre_servicio AS titulo,
                descripcion_servicio AS descripcion,
                imagenURL_servicio AS imagen,
                '/login' AS link
             FROM servicio"
        );

        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $resultado;
    }
}
?>