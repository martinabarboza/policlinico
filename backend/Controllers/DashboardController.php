<?php

class DashboardController extends Controller
{
    public function index()
    {   
        Auth::requireLogin();
        $this->view('dashboard/index');
    }
}

