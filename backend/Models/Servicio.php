<?php
class Servicio extends Model
{
    function getArray_Servicios()
    {
        $stmt = $this->db->prepare(
            'SELECT *
             FROM servicio'
        );

        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $resultado;
    }
}
?>