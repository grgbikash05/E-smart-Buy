<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Searchlist;

class SearchController extends Controller
{
    public function search(Request $request) {
        $request->validate([
            'product' => "required",
        ]);

        $product = Input::get('product');

        $product = trim($product);

        $product = preg_replace('/\s+/', ' ', $product);

        $product = str_replace(" ", '+', $product);

        $search_item = DB::table('searchlists')->where('search_query', $product)->first();

        if(!empty($search_item)) {
            if($search_item->search_query === Input::get('product')) {
                DB::table('searchlists')->where('search_query', $product)->increment('count');
                $products = DB::table('products')->where('search_id', $search_item->id)->get();

                echo "<pre>";

                print_r($products);

                echo "</pre>";
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

            $search_item = new Searchlist([
                'search_query' => $request->get('product'),
            ]);

            $search_item->save();

            sleep(2);

            $search_item = DB::table('searchlists')->where('search_query', $product)->first();

            if(!empty($results)) {
                foreach($results as $result) {
                    $values = ['search_id' => $search_item->id, 'title' => $result->title, 'price' => $result->price, 'image' => $result->image, 'link' => $result->link, 'site' => $result->site];
                    DB::table('products')->insert($values);
                }
            }

            echo "From webscraping </br>";

            echo "<pre>";

            var_dump($results);

            echo "</pre>";
        }
    }
}
