<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\File;
// use File;

class MatchStatsController extends Controller
{
    function checkavail($key)
    {
        // print_r($key);
        // exit();
        if(isset($key))
        {
            return $key;
        }
        else
        {
            return 0;
        }
    }
    

    public function createHead2Head()
    {
        
        $teamA = $_GET['team1'];
        $teamB = $_GET['team2'];
        // $env = env('RAPID_API_KEY');
        // print_r($env);
        // exit;


        $head2headresponse = Http::withHeaders([
            'x-rapidapi-host' => env('RAPID_API_HOST'),
            'x-rapidapi-key' => env('RAPID_API_KEY')
        ])->get('https://soccer-football-info.p.rapidapi.com/custom/', [
            'i' => 'fqbqo7gzv33tjpb1e',
            'w' => 'all',
            'x' => $teamA,
            'y' => $teamB
        ]);
        // print_r($head2headresponse);
        // exit;

        $h2hdec = json_decode($head2headresponse, true);

        $array1['teamX'] = $this->checkavail($h2hdec['result'][0]['results']['teamX']);
        $array1['teamY'] = $this->checkavail($h2hdec['result'][0]['results']['teamY']);
        $array1['draw'] = $this->checkavail($h2hdec['result'][0]['results']['draw']);
        $array1['teamX_name'] = $this->checkavail($h2hdec['result'][0]['teamX']['name']);
        $array1['teamY_name'] = $this->checkavail($h2hdec['result'][0]['teamY']['name']);


        if(isset($h2hdec['result'][0]['matches']))
        {
            foreach ($h2hdec['result'][0]['matches'] as $res) 
            {
                $hometeamdata = array();
                $awayteamdata = array();
                $leaguedata = array();
                $fixturedata = array();
                $teamAhtgoals = 0;
                $teamAftgoals = 0;
                $teamAhtcorner = 0;
                $teamAftcorner = 0;
                $teamBhtgoals = 0;
                $teamBftgoals = 0;
                $teamBhtcorner = 0;
                $teamBftcorner = 0;
                

                $hometeamdata['id'] = $res['teamA']['id'];
                $hometeamdata['name'] = $res['teamA']['name'];

                $awayteamdata['id'] = $res['teamB']['id'];
                $awayteamdata['name'] = $res['teamB']['name'];

                $leaguedata['id'] = $res['championship']['id'];
                $leaguedata['name'] = $res['championship']['name'];

                $fixturedata['id'] = $res['id'];
                $fixturedata['time']  = $res['date'];
                
                foreach ($res['events'] as $e) 
                {
                    if($e['timer'] <= 45 )
                    {
                        if($e['type'] == "goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAhtgoals = $teamAhtgoals + 1;
                            }
                            else
                            {
                                $teamBhtgoals = $teamBhtgoals + 1;
                            }
                        }
                        elseif($e['type'] == "cancel_goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAhtgoals = $teamAhtgoals - 1;
                            }
                            else
                            {
                                $teamBhtgoals = $teamBhtgoals - 1;
                            }
                        }
                        elseif($e['type'] == "corner")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAhtcorner = $teamAhtcorner + 1;
                            }
                            else
                            {
                                $teamBhtcorner = $teamBhtcorner + 1;
                            }
                        }
                    }
                    elseif($e['timer'] > 45 )
                    {
                        if($e['type'] == "goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAftgoals = $teamAftgoals + 1;
                            }
                            else
                            {
                                $teamBftgoals = $teamBftgoals + 1;
                            }
                        }
                        elseif($e['type'] == "cancel_goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAftgoals = $teamAftgoals - 1;
                            }
                            else
                            {
                                $teamBftgoals = $teamBftgoals - 1;
                            }
                        }
                        elseif($e['type'] == "corner")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAftcorner = $teamAftcorner + 1;
                            }
                            else
                            {
                                $teamBftcorner = $teamBftcorner + 1;
                            }
                        }
                    }
                }

                $hometeamdata['ht_goals'] = $teamAhtgoals;
                $hometeamdata['ft_goals'] = $teamAhtgoals + $teamAftgoals;
                $hometeamdata['ht_corners'] = $teamAhtcorner;
                $hometeamdata['ft_corners'] = $teamAhtcorner + $teamAftcorner;


                $awayteamdata['ht_goals'] = $teamBhtgoals;
                $awayteamdata['ft_goals'] = $teamBhtgoals + $teamBftgoals;
                $awayteamdata['ht_corners'] = $teamBhtcorner;
                $awayteamdata['ft_corners'] = $teamBhtcorner + $teamBftcorner;

                //BINDING
                $array2['hometeam'] = $hometeamdata;
                $array2['awayteam'] = $awayteamdata;
                $array2['league'] = $leaguedata;
                $array2['fixture'] = $fixturedata;

                $array1['matches'][] = $array2;

            }
        }
        
        return $array1;
    }
    public function createTeamLastMatches()
    {

        $team = $_GET['teamid'];
        
        $teamresponse = Http::withHeaders([
            'x-rapidapi-host' => env('RAPID_API_HOST'),
            'x-rapidapi-key' => env('RAPID_API_KEY')
        ])->get('https://soccer-football-info.p.rapidapi.com/custom/', [
            'i' => 'pw21e890p287531lq',
            'team' => $team,
            'w' => 'all'
        ]);
        
        $teamresponsec = stripslashes(html_entity_decode($teamresponse));
        $teamresponse = str_replace('#1#3#4#2', '', $teamresponsec);
        $teamdec = json_decode($teamresponse, true);
        
        $array1['win'] = $this->checkavail($teamdec['result'][0]['results']['win']);
        $array1['loss'] = $this->checkavail($teamdec['result'][0]['results']['loss']);
        $array1['draw'] = $this->checkavail($teamdec['result'][0]['results']['draw']);
        

        if(isset($teamdec['result'][0]['matches']))
        {
            foreach ($teamdec['result'][0]['matches'] as $res) 
            {
                $hometeamdata = array();
                $awayteamdata = array();
                $leaguedata = array();
                $fixturedata = array();
                $teamAhtgoals = 0;
                $teamAftgoals = 0;
                $teamAhtcorner = 0;
                $teamAftcorner = 0;
                $teamBhtgoals = 0;
                $teamBftgoals = 0;
                $teamBhtcorner = 0;
                $teamBftcorner = 0;

                $hometeamdata['id'] = $res['teamA']['id'];
                $hometeamdata['name'] = $res['teamA']['name'];

                $awayteamdata['id'] = $res['teamB']['id'];
                $awayteamdata['name'] = $res['teamB']['name'];

                $leaguedata['id'] = $res['championship']['id'];
                $leaguedata['name'] = $res['championship']['name'];

                $fixturedata['id'] = $res['id'];
                $fixturedata['time']  = $res['date'];

                foreach ($res['events'] as $e) 
                {
                    if($e['timer'] <= 45 )
                    {
                        if($e['type'] == "goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAhtgoals = $teamAhtgoals + 1;
                            }
                            else
                            {
                                $teamBhtgoals = $teamBhtgoals + 1;
                            }
                        }
                        elseif($e['type'] == "cancel_goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAhtgoals = $teamAhtgoals - 1;
                            }
                            else
                            {
                                $teamBhtgoals = $teamBhtgoals - 1;
                            }
                        }
                        elseif($e['type'] == "corner")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAhtcorner = $teamAhtcorner + 1;
                            }
                            else
                            {
                                $teamBhtcorner = $teamBhtcorner + 1;
                            }
                        }
                    }
                    elseif($e['timer'] > 45 )
                    {
                        if($e['type'] == "goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAftgoals = $teamAftgoals + 1;
                            }
                            else
                            {
                                $teamBftgoals = $teamBftgoals + 1;
                            }
                        }
                        elseif($e['type'] == "cancel_goal")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAftgoals = $teamAftgoals - 1;
                            }
                            else
                            {
                                $teamBftgoals = $teamBftgoals - 1;
                            }
                        }
                        elseif($e['type'] == "corner")
                        {
                            if($e['team'] == "A")
                            {
                                $teamAftcorner = $teamAftcorner + 1;
                            }
                            else
                            {
                                $teamBftcorner = $teamBftcorner + 1;
                            }
                        }
                    }
                }

                $hometeamdata['ht_goals'] = $teamAhtgoals;
                $hometeamdata['ft_goals'] = $teamAhtgoals + $teamAftgoals;
                $hometeamdata['ht_corners'] = $teamAhtcorner;
                $hometeamdata['ft_corners'] = $teamAhtcorner + $teamAftcorner;


                $awayteamdata['ht_goals'] = $teamBhtgoals;
                $awayteamdata['ft_goals'] = $teamBhtgoals + $teamBftgoals;
                $awayteamdata['ht_corners'] = $teamBhtcorner;
                $awayteamdata['ft_corners'] = $teamBhtcorner + $teamBftcorner;


                //BINDING
                $array2['hometeam'] = $hometeamdata;
                $array2['awayteam'] = $awayteamdata;
                $array2['league'] = $leaguedata;
                $array2['fixture'] = $fixturedata;

                $array1['matches'][] = $array2;

            }
        }

        return $array1;
    }
}
