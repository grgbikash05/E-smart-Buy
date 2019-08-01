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

        $search_item = DB::table('searchlists')->where('search_query', $product_for_query)->first();

        if(!empty($search_item)) {
            if($search_item->search_query === $product_for_query) {
                $results = DB::table('products')->where('search_id', $search_item->id)->get();

                DB::table('products')->where('search_id', $search_item->id)->increment('count');

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
                $values = ['user_id' => Auth::id(), 'search_query' => $product_for_query, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];

                DB::table('searchlists')->insert($values);

                $new_searchlist = DB::table('searchlists')->where('search_query', $product_for_query)->first();
                
                foreach($results as $result) {
                    $values = ['search_id' => $new_searchlist->id, 'title' => $result->title, 'price' => $result->price, 'image' => $result->image, 'link' => $result->link, 'site' => $result->site, 'created_at' => Carbon::now()];
                    DB::table('products')->insert($values);
                }
            }

            return view('results', compact('results'));
        }
    }

    public function products($id) {
        $results = DB::table('products')->where('search_id', $id)->get();

        DB::table('products')->where('search_id', $id)->increment('count');

        return view('results', compact('results'));
    } 
}
