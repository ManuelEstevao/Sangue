<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campanha;
use Carbon\Carbon; 

class navController extends Controller
{
    

public function index()
{
    $campanhas = Campanha::with('centro')
        ->where('data_fim', '>=', now())
        ->orderBy('data_inicio', 'asc')
        ->paginate(3);

    return view('home', compact('campanhas'));
}
    function sobre(){
        return view('sobre');
    }
}
