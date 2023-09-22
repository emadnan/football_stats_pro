<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Match;
use Illuminate\Support\Facades\DB;


class MatchesController extends Controller
{
    private $rapidApiHost;
    private $rapidApiKey;

    public function __construct()
    {
        $this->rapidApiHost = env('RAPID_API_HOST');
        $this->rapidApiKey = env('RAPID_API_KEY');
    }

    private function callApi($url)
    {
        $response = Http::withHeaders([
            "X-RapidAPI-Host" => $this->rapidApiHost,
            "X-RapidAPI-Key" => $this->rapidApiKey,
        ])->get($url);

        return $response->json();
    }

    public function getDayBasic()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/matches/day/basic/?d=DATE';
        return $this->callApi($url);
    }

    public function getDayFull()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/matches/day/full/?d=DATE';
        return $this->callApi($url);
    }

    public function getViewBasic()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/matches/view/basic/?i=ID';
        return $this->callApi($url);
    }

    public function getViewFull()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/matches/view/full/?i=ID';
        return $this->callApi($url);
    }

    public function getByBasic()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/v1/matches/by/basic/?c=CHAMPIONSHIP_ID';
        return $this->callApi($url);
    }

    public function getByFull()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/v1/matches/by/full/?c=CHAMPIONSHIP_ID';
        return $this->callApi($url);
    }

    public function getOdds()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/matches/odds/?i=ID';
        return $this->callApi($url);
    }

    public function getViewProgressive()
    {
        $url = 'https://soccer-football-info.p.rapidapi.com/matches/view/progressive/?i=ID';
        return $this->callApi($url);
    }
    public function getMatchsByDate($date)
    {
        $newDate = date('Y-m-d', strtotime($date));
        $take = !empty(\Request::input('take')) ? \Request::input('take') : null;
        $skip = !empty(\Request::input('skip')) ? \Request::input('skip') : 0;
        $queryObj = DB::table('matches')->whereDate('m_fixture_date',$newDate);
        if(!empty($take)){
            $queryObj->take($take);
        }
        if(!empty($skip)){
            $queryObj->skip($skip);
        }

        $match = $queryObj->get();
        return response()->json(['matches' => $match]);
    }
    public function searchLeague()
    {
        $name = \Request::input('name');
        $date = \Request::input('date');
         $mDate = date('Y-m-d', strtotime($date));
        $match = DB::table('matches')
        ->where('m_league_name', 'LIKE', '%'.$name.'%')
        ->whereDate('m_fixture_date', $mDate)
        ->get();
        return response()->json(['league_name' => $match]);
    }
    public function getMatchesListByDate()
    {
        $date = date('Y-m-d', strtotime(\Request::input('date')));
        $queryObj = DB::table('matches')->select('matches.m_fixture_status','matches.m_fixture_date','matches.m_fixture_timer','matches.m_league_name','matches.m_hometeam_name','matches.m_hometeam_score','matches.m_awayteam_name','matches.m_awayteam_score')
        ->whereDate('m_fixture_date', $date)
        ->get();
        return response()->json($queryObj);
    }
}
