<?php

class Centro_TutoresController extends Controller
{
    public function index()
    {
        Auth::requireLogin();
        $this->view('dashboard/centro-tutores',[],'app',['app-layout.css']);
    }
}
