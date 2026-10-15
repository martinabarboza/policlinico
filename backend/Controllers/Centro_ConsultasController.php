<?php

class Centro_ConsultasController extends Controller
{
    public function index()
    {
        $this->view('dashboard/centro-consultas',[],'app',['app-layout.css']);
    }
}
