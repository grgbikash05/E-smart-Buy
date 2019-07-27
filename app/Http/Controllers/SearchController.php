<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SearchController extends Controller
{
    public function search() {
        $product = Input::get('product');

        $product = trim($product);

        $product = preg_replace('/\s+/', ' ', $product);

        $product = str_replace(" ", '+', $product);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8080/". $product);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch); 

        curl_close($ch);

        echo $output;
    }
}
