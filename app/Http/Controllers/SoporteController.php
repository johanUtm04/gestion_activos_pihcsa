<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoporteController extends Controller
{
    public function manual()
    {
        return view('soporte.manual');
    }

    public function contacto()
    {
        return view('soporte.contacto');
    }
}
