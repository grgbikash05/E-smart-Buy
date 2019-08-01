<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $results = DB::select( DB::raw("SELECT DISTINCT search_id, count FROM Products"));

        return view('welcome', compact('results'));
    }

    public function homepage() {
        return view("home");
    }
}
