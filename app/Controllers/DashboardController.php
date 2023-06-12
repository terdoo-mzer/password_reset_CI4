<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function profile()
    {
        //
        $data = [];
        if(session()->has('data')) {
            $data['user'] = session()->get('data');
        } 
  
        return view('templates/header')
        . view('dashboard', $data)
        . view('templates/footer');
    }
}
