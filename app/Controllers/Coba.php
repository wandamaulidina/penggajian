<?php

namespace App\Controllers;
use App\Libraries\Flip;

class Coba extends BaseController
{
	public function index()
	{
		$flip = new Flip(); // membuat atau meng-inisialisasi object Flip dari Class / Libary Flip
        $flip->generate_private_key();
	}
}
