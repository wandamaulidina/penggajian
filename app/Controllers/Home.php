<?php

namespace App\Controllers;

class Home Extends BaseController
{
    public function index()
    {
        $data =[
            'judul'    => 'Home',
        ];
        return view('v_login');
    }
}