<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PapeleraController extends Controller
{


public function index(Request $request)
{
        return view('papelera.index', [
            'user' => $request->user(),
        ]);
    }
    
}