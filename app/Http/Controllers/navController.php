<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class navController extends Controller
{
    function index(){
        return view('home');
    }
    function sobre(){
        return view('sobre');
    }
}
