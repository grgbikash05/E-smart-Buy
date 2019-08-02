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
        $results = DB::select( DB::raw("SELECT DISTINCT id, search_query, count FROM searchqueries"));

        $names = [];

        if(!empty($results)) {
            foreach($results as $myobject) {
                $names[] =  $myobject->count;
            }
            array_multisort($names, SORT_DESC, $results);
        }

        $search_history = [];

        $all_users = DB::table('users')->get();

        $user_searched_items = [];

        foreach($all_users as $users) {
            $object[] = DB::table('searchlists')->where('user_id', $users->id)->get();

            $final_items = array_merge($user_searched_items, $object);
        }

        $output = [];

        foreach($final_items as $items) {
            foreach($items as $item) {
                $search_query = DB::table('searchqueries')->where('id', $item->search_query_id)->first();
            }
        }

        return view('welcome', compact('results'));
    }

    public function homepage() {
        return view("home");
    }
}
