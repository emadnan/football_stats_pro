<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SummaryStats;

class LiveStatsController extends Controller
{
    public function getLiveStats()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/live/full/';

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_PROXY => null,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: soccer-football-info.p.rapidapi.com",
                "X-RapidAPI-Key: 2e7a3d50b5msh5f8a22afdf8cdcdp137f55jsn65f872c9d5c5"
            ],
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
          $result = json_decode($response);
          return response()->json($result);
        } else {
            echo "cURL Error:" . $err;
        }
    }
    function getSummaryStats($value='')
    {
        $take = !empty(\Request::input('take')) ? \Request::input('take') : null;
        $skip = !empty(\Request::input('skip')) ? \Request::input('skip') : 0;

        $queryObj = SummaryStats::select('summarystats.*');

        if(!empty($take)){
            $queryObj->take($take);
        }
        if(!empty($skip)){
            $queryObj->skip($skip);
        }

        $SummaryStats = $queryObj->get();
        return response()->json(['SummaryStats' => $SummaryStats]);
    }
}
