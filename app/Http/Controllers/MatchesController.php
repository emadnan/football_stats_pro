<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
}