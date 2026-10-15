<?php

class HospitalizacionController extends Controller
{
    public function index()
    {   
        Auth::requireLogin();
        $this->view('dashboard/hospitalizacion',[],'app',['app-layout.css']);
    }
}
