<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request) {
        $request->validate([
            'product' => "required",
        ]);

        $product = Input::get('product');

        $product = trim($product);

        $product_for_query = trim($product);

        $product = preg_replace('/\s+/', ' ', $product);

        $product = str_replace(" ", '+', $product);

        $searchquery = DB::table('searchqueries')->where('search_query', $product_for_query)->first();

        if(!empty($searchquery)) {
            if($searchquery->search_query === $product_for_query) {
                $results = DB::table('products')->where('search_query_id', $searchquery->id)->get();

                $alreadyUsers = DB::table('searchlists')->where('user_id', Auth::id())->where('search_query_id', $searchquery->id)->first();

                if(empty($alreadyUsers)) {
                    $values = ['user_id' => Auth::id(), 'search_query_id' => $searchquery->id, 'created_at' => $searchquery->created_at];

                    DB::table('searchlists')->insert($values);
                } else {
                    DB::table('searchlists')->where('user_id', Auth::id())->where('search_query_id', $searchquery->id)->increment('no_of_clicks_by_user');
                }

                DB::table('searchqueries')->where('id', $searchquery->id)->increment('count');

                return view('results', compact('results'));
            }
        } else {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8080/". $product);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($ch); 

            curl_close($ch);

            $results = json_decode($output);
            
            $names=[];
            if(!empty($results)) {
                foreach ($results as $my_object) {
                    $names[] =  $my_object->price;
                }
                array_multisort($names, SORT_ASC, $results);
            }

            if(!is_null($results)) {
                $values = ['search_query' => $product_for_query, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];

                DB::table('searchqueries')->insert($values);

                $new_search_query = DB::table('searchqueries')->where('search_query', $product_for_query)->first();

                $values2 = ['user_id' => Auth::id(), 'search_query_id' => $new_search_query->id, 'created_at' => $new_search_query->created_at];

                DB::table('searchlists')->insert($values2);
                
                foreach($results as $result) {
                    $values = ['search_query_id' => $new_search_query->id, 'title' => $result->title, 'price' => $result->price, 'image' => $result->image, 'link' => $result->link, 'site' => $result->site, 'created_at' => Carbon::now()];
                    DB::table('products')->insert($values);
                }
            }

            return view('results', compact('results'));
        }
    }

    public function products($id) {
        $results = DB::table('products')->where('search_query_id', $id)->get();

        $searchqueries = DB::table('searchqueries')->where('id', $id)->first();

        $alreadyUsers = DB::table('searchlists')->where('user_id', Auth::id())->where('search_query_id', $id)->first();

        if(empty($alreadyUsers)) {
            $values = ['user_id' => Auth::id(), 'search_query_id' => $id, 'created_at' => $searchqueries->created_at];

            DB::table('searchlists')->insert($values);
        } else {
            DB::table('searchlists')->where('user_id', Auth::id())->where('search_query_id', $id)->increment('no_of_clicks_by_user');
        }

        DB::table('searchqueries')->where('id', $id)->increment('count');

        return view('results', compact('results'));
    } 
}
