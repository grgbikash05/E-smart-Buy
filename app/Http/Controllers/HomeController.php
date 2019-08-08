<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_users = DB::table('users')->get();

        $users_id = [];

        foreach($all_users as $user) {
            $users_id[] = $user->id;
        }

       
        $allData = [];
        foreach($users_id as $user) {
            $users_search_history = [];
            $results = DB::table('user_searches')->where('user_id', $user)->get();

            foreach($results as $result) {
                
                $users_search_history[$result->search_id] = $result->count;
            }
            $allData[$user] = $users_search_history;
        }

        $recommendation = $this->getRecommendations($allData, Auth::id());

        $recommended_search_query = [];

        foreach($recommendation as $key => $value) {
            $recommended_search_query[] = DB::table('search_queries')->where('id', $key)->pluck('search_query');
        }

        $todays_seach_query = DB::table('top_searches_todays')->get();

        $top_searches_today = [];

        foreach($todays_seach_query as $today) {
            $top = DB::table('search_queries')->where('id', $today->search_id)->first();

            $top_searches_today[] = (object) array(['id' => $top->id, 'search_query' => $top->search_query, 'count' => $today->total_searches_today]);
        }

        $names = [];

        if(!empty($top_searches_today)) {
            foreach($top_searches_today as $today) {
                foreach($today as $to) {
                    $names[] = $to['count'];
                }
            }

            array_multisort($names, SORT_DESC, $top_searches_today);
        }

        return view('welcome', compact(['recommended_search_query', 'top_searches_today']));
    }

    public function similarityDistance($preferences, $person1, $person2)
    {
        $similar = array();
        $sum = 0;
    
        foreach($preferences[$person1] as $key=>$value)
        {
            if(array_key_exists($key, $preferences[$person2]))
                $similar[$key] = 1;
        }
        
        if(count($similar) == 0)
            return 0;
        
        foreach($preferences[$person1] as $key=>$value)
        {
            if(array_key_exists($key, $preferences[$person2]))
                $sum = $sum + pow($value - $preferences[$person2][$key], 2);
        }
        
        return 1/(1 + sqrt($sum));     
    }
    
    public function getRecommendations($preferences, $person)
    {
        $total = array();
        $simSums = array();
        $ranks = array();
        $sim = 0;
        
        foreach($preferences as $otherPerson=>$values)
        {
            if($otherPerson != $person)
            {
                $sim = $this->similarityDistance($preferences, $person, $otherPerson);
            }
            
            if($sim > 0)
            {
                foreach($preferences[$otherPerson] as $key=>$value)
                {
                    if(!array_key_exists($key, $preferences[$person]))
                    {
                        if(!array_key_exists($key, $total)) {
                            $total[$key] = 0;
                        }
                        $total[$key] += $preferences[$otherPerson][$key] * $sim;
                        
                        if(!array_key_exists($key, $simSums)) {
                            $simSums[$key] = 0;
                        }
                        $simSums[$key] += $sim;
                    }
                }
                
            }
        }

        foreach($total as $key=>$value)
        {
            $ranks[$key] = $value / $simSums[$key];
        }

        arsort($ranks);

        return $ranks;
        
    }

    public function homepage() {
        return view("home");
    }
}
