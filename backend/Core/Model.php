<?php

/** Clase base que comparte la conexión de base de datos entre los modelos. */
class Model
{
    protected mysqli $db;

    public function __construct()
    {
        // Todos los modelos reutilizan la conexión única configurada por Database.
        $this->db = Database::getConnection();
    }
}