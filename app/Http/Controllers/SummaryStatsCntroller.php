<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummaryStats;
use Illuminate\Support\Facades\DB;

class SummaryStatsCntroller extends Controller
{
    public function getSummaryStatsByDate()
    {
        $type = \Request::input('type');
        $date = date('Y-m-d', strtotime(\Request::input('date')));
        if ($type == 0) {
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_goals_over15','summarystats.ss_goals_over25','summarystats.ss_goals_over35','summarystats.ss_goals_overbtts','summarystats.ss_goals_over05fhg','summarystats.ss_goals_over05shg','summarystats.ss_goals_minute37','summarystats.ss_goals_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->get();
            return response()->json($queryObj);
            // print_r($queryObj);

        }
        elseif($type ==1){
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_corners_over25','summarystats.ss_corners_over35','summarystats.ss_corners_over45','summarystats.ss_corners_over85','summarystats.ss_corners_over95','summarystats.ss_corners_over105','summarystats.ss_corners_minute37','summarystats.ss_corners_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 2');
        }
        else{
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_home_cards_for_fh','summarystats.ss_home_cards_against_fh','summarystats.ss_home_cards_for_sh','summarystats.ss_home_cards_against_sh','summarystats.ss_home_cards_for_ft','summarystats.ss_goals_over05shg','summarystats.ss_home_cards_against_ft','summarystats.ss_away_cards_for_fh','summarystats.ss_away_cards_against_fh','summarystats.ss_away_cards_for_sh','summarystats.ss_away_cards_against_sh','summarystats.ss_away_cards_for_ft','summarystats.ss_away_cards_against_ft')
            ->whereDate('ss_fixture_date','=',$date)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 3');
        }
        
    }
    public function getSummaryStatsAdvaceFilter()
    {
        $type = \Request::input('type');
        $date = date('Y-m-d', strtotime(\Request::input('date')));
        $value = \Request::input('value');
        $condition = \Request::input('condition');
        $fieldName = \Request::input('fieldName');
        if ($type == 0) {
            if ($condition == '>') {
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_goals_over15','summarystats.ss_goals_over25','summarystats.ss_goals_over35','summarystats.ss_goals_overbtts','summarystats.ss_goals_over05fhg','summarystats.ss_goals_over05shg','summarystats.ss_goals_minute37','summarystats.ss_goals_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'>',$value)
            ->get();
             return response()->json($queryObj);
         }
             elseif($condition == '>='){
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_goals_over15','summarystats.ss_goals_over25','summarystats.ss_goals_over35','summarystats.ss_goals_overbtts','summarystats.ss_goals_over05fhg','summarystats.ss_goals_over05shg','summarystats.ss_goals_minute37','summarystats.ss_goals_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'>=',$value)
            ->get();
             return response()->json($queryObj);

             }
             elseif($condition == '<'){
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_goals_over15','summarystats.ss_goals_over25','summarystats.ss_goals_over35','summarystats.ss_goals_overbtts','summarystats.ss_goals_over05fhg','summarystats.ss_goals_over05shg','summarystats.ss_goals_minute37','summarystats.ss_goals_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'<',$value)
            ->get();
             return response()->json($queryObj);
             }
             elseif($condition == '<='){
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_goals_over15','summarystats.ss_goals_over25','summarystats.ss_goals_over35','summarystats.ss_goals_overbtts','summarystats.ss_goals_over05fhg','summarystats.ss_goals_over05shg','summarystats.ss_goals_minute37','summarystats.ss_goals_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'<=',$value)
            ->get();
             return response()->json($queryObj);
             }
             else{
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_goals_over15','summarystats.ss_goals_over25','summarystats.ss_goals_over35','summarystats.ss_goals_overbtts','summarystats.ss_goals_over05fhg','summarystats.ss_goals_over05shg','summarystats.ss_goals_minute37','summarystats.ss_goals_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'=',$value)
            ->get();
             return response()->json($queryObj);
             }

            // $queryObj =  DB::table('summarystats')
            // ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_goals_over15','summarystats.ss_goals_over25','summarystats.ss_goals_over35','summarystats.ss_goals_overbtts','summarystats.ss_goals_over05fhg','summarystats.ss_goals_over05shg','summarystats.ss_goals_minute37','summarystats.ss_goals_minute85')
            // ->whereDate('ss_fixture_date','=',$date)
            // ->get();
            // return response()->json($queryObj);
            // print_r($queryObj);

        }
        elseif($type ==1){
             if($condition == '>') {
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_corners_over25','summarystats.ss_corners_over35','summarystats.ss_corners_over45','summarystats.ss_corners_over85','summarystats.ss_corners_over95','summarystats.ss_corners_over105','summarystats.ss_corners_minute37','summarystats.ss_corners_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'>',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 2');
        }
        elseif($condition == '>=') {
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_corners_over25','summarystats.ss_corners_over35','summarystats.ss_corners_over45','summarystats.ss_corners_over85','summarystats.ss_corners_over95','summarystats.ss_corners_over105','summarystats.ss_corners_minute37','summarystats.ss_corners_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'>=',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 2');
        }
        elseif($condition == '<') {
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_corners_over25','summarystats.ss_corners_over35','summarystats.ss_corners_over45','summarystats.ss_corners_over85','summarystats.ss_corners_over95','summarystats.ss_corners_over105','summarystats.ss_corners_minute37','summarystats.ss_corners_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'<',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 2');
        }
        elseif($condition == '<=') {
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_corners_over25','summarystats.ss_corners_over35','summarystats.ss_corners_over45','summarystats.ss_corners_over85','summarystats.ss_corners_over95','summarystats.ss_corners_over105','summarystats.ss_corners_minute37','summarystats.ss_corners_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'<=',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 2');
        }
        else{
            $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_corners_over25','summarystats.ss_corners_over35','summarystats.ss_corners_over45','summarystats.ss_corners_over85','summarystats.ss_corners_over95','summarystats.ss_corners_over105','summarystats.ss_corners_minute37','summarystats.ss_corners_minute85')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'=',$value)
            ->get();
            return response()->json($queryObj);
        }
    }
        else{
            if($condition == '>') {
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_home_cards_for_fh','summarystats.ss_home_cards_against_fh','summarystats.ss_home_cards_for_sh','summarystats.ss_home_cards_against_sh','summarystats.ss_home_cards_for_ft','summarystats.ss_goals_over05shg','summarystats.ss_home_cards_against_ft','summarystats.ss_away_cards_for_fh','summarystats.ss_away_cards_against_fh','summarystats.ss_away_cards_for_sh','summarystats.ss_away_cards_against_sh','summarystats.ss_away_cards_for_ft','summarystats.ss_away_cards_against_ft')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'>',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 3');
            }
            elseif($condition == '>=') {
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_home_cards_for_fh','summarystats.ss_home_cards_against_fh','summarystats.ss_home_cards_for_sh','summarystats.ss_home_cards_against_sh','summarystats.ss_home_cards_for_ft','summarystats.ss_goals_over05shg','summarystats.ss_home_cards_against_ft','summarystats.ss_away_cards_for_fh','summarystats.ss_away_cards_against_fh','summarystats.ss_away_cards_for_sh','summarystats.ss_away_cards_against_sh','summarystats.ss_away_cards_for_ft','summarystats.ss_away_cards_against_ft')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'>=',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 3');
            }
            elseif($condition == '<') {
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_home_cards_for_fh','summarystats.ss_home_cards_against_fh','summarystats.ss_home_cards_for_sh','summarystats.ss_home_cards_against_sh','summarystats.ss_home_cards_for_ft','summarystats.ss_goals_over05shg','summarystats.ss_home_cards_against_ft','summarystats.ss_away_cards_for_fh','summarystats.ss_away_cards_against_fh','summarystats.ss_away_cards_for_sh','summarystats.ss_away_cards_against_sh','summarystats.ss_away_cards_for_ft','summarystats.ss_away_cards_against_ft')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'<',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 3');
            }
            elseif($condition == '<=') {
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_home_cards_for_fh','summarystats.ss_home_cards_against_fh','summarystats.ss_home_cards_for_sh','summarystats.ss_home_cards_against_sh','summarystats.ss_home_cards_for_ft','summarystats.ss_goals_over05shg','summarystats.ss_home_cards_against_ft','summarystats.ss_away_cards_for_fh','summarystats.ss_away_cards_against_fh','summarystats.ss_away_cards_for_sh','summarystats.ss_away_cards_against_sh','summarystats.ss_away_cards_for_ft','summarystats.ss_away_cards_against_ft')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'<=',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 3');
            }
            else{
                $queryObj =  DB::table('summarystats')
            ->select('summarystats.ss_fixture_status','summarystats.ss_fixture_date','summarystats.ss_league_name','summarystats.ss_hometeam_name','summarystats.ss_awayteam_name','summarystats.ss_home_cards_for_fh','summarystats.ss_home_cards_against_fh','summarystats.ss_home_cards_for_sh','summarystats.ss_home_cards_against_sh','summarystats.ss_home_cards_for_ft','summarystats.ss_goals_over05shg','summarystats.ss_home_cards_against_ft','summarystats.ss_away_cards_for_fh','summarystats.ss_away_cards_against_fh','summarystats.ss_away_cards_for_sh','summarystats.ss_away_cards_against_sh','summarystats.ss_away_cards_for_ft','summarystats.ss_away_cards_against_ft')
            ->whereDate('ss_fixture_date','=',$date)
            ->where($fieldName,'=',$value)
            ->get();
            return response()->json($queryObj);
            // print_r('hello from condition 3');
            }
            
        }
        
    }
}
