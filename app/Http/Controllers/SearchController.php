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

        $searchquery = DB::table('search_queries')->where('search_query', $product_for_query)->first();

        if(!empty($searchquery)) {
            $today_search_query = DB::table('top_searches_todays')->where('search_id', $searchquery->id)->first();

            if(!empty($today_search_query)) {
                $search_id = $searchquery->id;
    
                $user_searches = DB::table('user_searches')->where('user_id', Auth::id())->where('search_id', $search_id)->first();
    
                if(!empty($user_searches)) {
                    DB::table('user_searches')->where('user_id', Auth::id())->where('search_id', $search_id)->increment('count');
                } else {
                    $values = ['user_id' => Auth::id(), 'search_id' => $search_id];
                    DB::table('user_searches')->insert($values);
                }
    
                $today_searches_today = DB::table('top_searches_todays')->where('search_id', $search_id)->first();
    
                if(!empty($today_searches_today)) {
                    DB::table('top_searches_todays')->where('search_id', $search_id)->increment('total_searches_today');
                } else {
                    $values = ['search_id' => $search_id];
                    DB::table('top_searches_todays')->insert($values);
                }
                
                $results = DB::table('products')->where('search_id', $search_id)->get();

                return view('results', compact('results'));

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
                    $searchquery = DB::table('search_queries')->where('search_query', $product_for_query)->first();

                    $values = ['search_id' => $searchquery->id, 'created_at' => Carbon::now()];

                    DB::table('top_searches_todays')->insert($values);

                    $user_searches = DB::table('user_searches')->where('search_id', $searchquery->id)->where('user_id', Auth::id())->first();

                    if(!empty($user_searches)) {
                        DB::table('user_searches')->where('search_id', $searchquery->id)->where('user_id', Auth::id())->increment('count');
                    } else {
                        $values = ['user_id' => Auth::id(), 'search_id' => $searchquery->id];
                        DB::table('user_searches')->insert($values);
                    }

                    foreach($results as $result) {
                        $values = ['search_id' => $searchquery->id, 'title' => $result->title, 'price' => $result->price, 'image' => $result->image, 'link' => $result->link, 'site' => $result->site, 'created_at' => Carbon::now()];
                        DB::table('products')->insert($values);
                    }
                }
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
                $searchquery = DB::table('search_queries')->where('search_query', $product_for_query)->first();
                
                if(!empty($searchquery)) {
                    $search_id = $searchquery->id;
                } else {
                    $values = ['search_query' => $product_for_query];

                    DB::table('search_queries')->insert($values);
                }

                $new_search_query = DB::table('search_queries')->where('search_query', $product_for_query)->first();

                $values2 = ['user_id' => Auth::id(), 'search_id' => $new_search_query->id];

                DB::table('user_searches')->insert($values2);

                $today_searches = DB::table('top_searches_todays')->where('search_id', $new_search_query->id)->first();

                if(!empty($today_searches)) {
                    DB::table('top_searches_todays')->where('search_id', $new_search_query->id)->increment('total_searches_today');
                } else {
                    $values = ['search_id' => $new_search_query->id, 'created_at' => Carbon::now()];
                    DB::table('top_searches_todays')->insert($values);
                }
                
                foreach($results as $result) {
                    $values = ['search_id' => $new_search_query->id, 'title' => $result->title, 'price' => $result->price, 'image' => $result->image, 'link' => $result->link, 'site' => $result->site, 'created_at' => Carbon::now()];
                    DB::table('products')->insert($values);
                }
            }

            return view('results', compact('results'));
        }
    }

    public function products($id) {
        $results = DB::table('products')->where('search_id', $id)->get();

        DB::table('top_searches_todays')->where('search_id', $id)->increment('total_searches_today');

        $alreadyUser = DB::table('user_searches')->where('search_id', $id)->where('user_id', Auth::id())->first();

        if(!empty($alreadyUser)) {
            DB::table('user_searches')->where('search_id', $id)->where('user_id', Auth::id())->increment('count');
        } else {
            $values = ['user_id' => Auth::id(), 'search_id' => $id];
            DB::table('user_searches')->insert($values);
        }

        return view('results', compact('results'));
    }
}
