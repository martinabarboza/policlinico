<?php

class Model
{
    protected mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}