<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Permission;

class CompetitionController extends Controller
{
    public function schedule()
    {


        // 4개까지의 팀개수에 적용되는 알고리듬
        $array = array();

        $event_counts = 5;
        $team_counts = 4;  //  일반적으로 팀개수는 4개까지이다!
        $event_game_counts = $team_counts * ($team_counts-1) / 2;
        $total_game_counts = $event_game_counts * $event_counts;

        $game_date_counts = 5;

        // for ($k=1; $k <= $event_counts ; $k++) { 
        //     $index = 0;
        //     for ($i=1; $i < $team_counts ; $i++) { 
        //         for ($j=$i+1; $j <= $team_counts; $j++) { 
        //             $array[$k][$index] = $k . ':' . $i . $j;
        //             $index ++;
        //         }
        //     }
        // }

        // $result_array = array();
        // while ( count($result_array) < $total_game_counts ) {
        //     foreach ($array as $k => $value) {
        //         $index = rand(0, 5);
        //         if (!in_array($value[$index], $result_array)) {
        //             $result_array[] = $value[$index];
        //         }
        //     }
        // }




        // 팀개수에 무관계한 알고리듬

        // 가능한 경기대전표작성

        $index = 1;
        for ($k=1; $k <= $event_counts ; $k++) { 
            for ($i=1; $i < $team_counts ; $i++) { 
                for ($j=$i+1; $j <= $team_counts; $j++) { 
                    $array[$index] = $k . ':' . $i . $j;
                    $index ++;
                }
            }
        }

        // 경기순서 random배치

        $result_array = array();
        $current_event_value = 0;
        $prev_event_value = 0;

        while ( count($result_array) < $total_game_counts ) {

            $index = rand(1, $total_game_counts);

            $current_event_value = intval(substr($array[$index], 0, 1));

            if (!in_array($array[$index], $result_array) && ($current_event_value != $prev_event_value)) {
                $result_array[] = $array[$index];
                $prev_event_value = $current_event_value;
                if ($prev_event_value > $event_counts) {
                    $prev_event_value = 1;
                }
            }
        }


        // 경기날자별로 묶기


        $divide = floor($total_game_counts / $game_date_counts);
        $mod = $total_game_counts % $game_date_counts;
        
        $result_dates = array();
        $count = 0;

        for ($date=1; $date <= $game_date_counts ; $date++) { 
            
            $first = $count;
            $end = ($date <= $mod) ? $count+$divide+1 : $count+$divide;

            for ($key=$first; $key <$end ; $key++) { 
                
                
                $result_dates[$date][] = $result_array[$key];
                $count ++;
            }
            
        }

        
        


        dd($result_dates);
        

        return view('pages.admin.schedule', 
            [
                'page' => 'schedule',
            ]
        );
        
    }

    public function result()
    {
        

        return view('pages.admin.result', 
            [
                'page' => 'result',
            ]
        );
        
    }

}