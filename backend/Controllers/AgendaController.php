<?php

class AgendaController extends Controller
{
    public function index()
    {
        Auth::requireLogin();
        $this->view('dashboard/agenda',[],'app',['app-layout.css']);
    }
}
