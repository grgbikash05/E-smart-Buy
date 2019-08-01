<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function search(Request $request) {
        $request->validate([
            'product' => "required",
        ]);

        $product = Input::get('product');

        $product = trim($product);

        $product_for_query = trim($product);

        $product = preg_replace('/\s+/', ' ', $product);

        $product = str_replace(" ", '+', $product);

        $search_item = DB::table('products')->where('search_query', $product_for_query)->first();

        if(!empty($search_item)) {
            if($search_item->search_query === $product_for_query) {
                $products = DB::table('products')->where('search_query', $product_for_query)->get();

                DB::table('products')->where('search_query', $product_for_query)->increment('count');

                echo "From database";

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

            if(!is_null($results)) {
                foreach($results as $result) {
                    $values = ['search_query' => $product_for_query, 'title' => $result->title, 'price' => $result->price, 'image' => $result->image, 'link' => $result->link, 'site' => $result->site, 'created_at' => Carbon::now()];
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
