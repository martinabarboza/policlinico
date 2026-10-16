<?php

class AgendaController extends Controller
{
    public function index()
    {
        Auth::requireLogin();
        $this->view('dashboard/agenda',[],'app',['app-layout.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
    }
}
