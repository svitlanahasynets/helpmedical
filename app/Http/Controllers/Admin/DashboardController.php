<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller as Controller;

use App\Models\User;
use App\Models\Permission;
use App\Models\Competition;
use App\Models\Schedule;
use App\Models\Result;
use App\Models\Event;
use App\Models\EventMeter;
use App\Models\Team; 

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $competition_id = null)
    {
        $user = Auth::User();

        if ( $competition_id == null ) {
            $recent_schedule = Schedule::where('progress', 1)->get()->last();
            $current_competition_id = $recent_schedule->competition_id;
        } else {
            $current_competition_id = $competition_id;
        }

        $current_competition = Competition::find($current_competition_id);

        if (!$current_competition)
            abort(404);

        $competition_name = $current_competition->competition_name;
        $start_date_timestamp = strtotime($current_competition->start_date);
        $start_date = date('Y.n.j', $start_date_timestamp);
        $end_date_timestamp = strtotime($current_competition->end_date);
        $end_date = date('Y.n.j', $end_date_timestamp);

        $end = 0;
        
        if ( date('Y-m-d') > $current_competition->end_date ) {
            $end = 1;
        }

        $events = explode(",", $current_competition->event_ids);
        $event_count = count($events);

        $teams = Team::where('competition_id', $current_competition->id)->get()->all();
        $team_count = count($teams);

        $event_game_counts = $team_count * ($team_count-1) / 2;
        $total_game_counts = $event_game_counts * $event_count;

        $progressed_schedules = Schedule::where('competition_id', $current_competition->id)
                                        ->where('progress', 1)
                                        ->get()->all();

        $progressed_schedules_count = count($progressed_schedules);

        $progress_percent = round(($progressed_schedules_count / $total_game_counts) * 100);


        // 지난 경기대회리력
        $competitions = Competition::whereRaw('true')->get()->all();
        $competitions_array = [];

        foreach ($competitions as $key => $competition) {
            $events_individual = explode(",", $competition->event_ids);
            $event_count_individual = count($events_individual);

            $teams_individual = Team::where('competition_id', $competition->id)->get()->all();
            $team_count_individual = count($teams_individual);

            $event_game_counts_individual = $team_count_individual * ($team_count_individual-1) / 2;
            $total_game_counts_individual = $event_game_counts_individual * $event_count_individual;

            $progressed_schedules_individual = Schedule::where('competition_id', $competition->id)
                                            ->where('progress', 1)
                                            ->get()->all();

            $progressed_schedules_individual_count = count($progressed_schedules_individual);

            // last competition information making start

            $competitions_array[$competition->id]['competition_name'] = $competition->competition_name;
            $competitions_array[$competition->id]['start_date'] = date('Y.n.j', strtotime($competition->start_date));
            $competitions_array[$competition->id]['end_date'] = date('Y.n.j', strtotime($competition->end_date));
            $competitions_array[$competition->id]['team_count'] = $team_count_individual;
            $competitions_array[$competition->id]['event_count'] = $event_count_individual;
            $competitions_array[$competition->id]['total_game_count'] = $total_game_counts_individual;

            $competitions_array[$competition->id]['winner_team_name'] = '-';

            if ( count($teams_individual) != 0 ) {
                $competitions_array[$competition->id]['winner_team_name'] = $competition->winner();
            }

            // last competition information making end
        }

        return view('pages.admin.dashboard', [
            'page' => 'dashboard',
            'competitions' => $competitions,
            'competition_name' => $competition_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'end' => $end,
            'events' => $events,
            'event_count' => $event_count,
            'teams' => $teams,
            'team_count' => $team_count,
            'total_game_counts' => $total_game_counts,
            'progressed_schedules_count' => $progressed_schedules_count,
            'progress_percent' => $progress_percent,
            'competitions_array' => $competitions_array,
            'user' => $user,

            'stats'         => [
                                    'bar' => $this->userGraphBarData($current_competition, $team_count),
                                    'pie' => $this->userGraphPieData($current_competition)
                                ]
        ]);
    }

    private function userGraphBarData($current_competition, $team_count) {
        
        $graphDataList = [];

        $teams = Team::where('competition_id', $current_competition->id)->get()->all();

        foreach ($teams as $key => $team) {
            $graphDataList[$team->id]['team_name']  = $team->team_name;
            $graphDataList[$team->id]['score'] = 0;
        }

        $progressed_schedules = Schedule::where('competition_id', $current_competition->id)
                                        ->where('progress', 1)
                                        ->get()->all();

        if ( !$progressed_schedules ) {
            return $graphDataList;
        }

        $event_game_counts = $team_count * ($team_count-1) / 2;

        foreach ($progressed_schedules as $key => $progressed_schedule) {
            $result = Result::where('schedule_id', $progressed_schedule->id)->first();
            $event_id = $progressed_schedule->event_id;
            $event_weight = EventMeter::where('competition_id', $current_competition->id)
                                        ->where('event_id', $event_id)
                                        ->value('event_weight');

            
            $weight_per_game = round($event_weight / $event_game_counts);
            $graphDataList[$result->winner_team_id]['score'] += $weight_per_game;
            
        }
        
        return array_values($graphDataList);
    }

    private function userGraphPieData($current_competition) {
        $graphDataList = [];

        $event_meters = EventMeter::where('competition_id', $current_competition->id)->get()->all();

        foreach ($event_meters as $key => $event_meter) {
            $graphDataList[$key]['type']  = Event::where('id', $event_meter->event_id)->value('event_name');
            $graphDataList[$key]['value'] = $event_meter->event_weight;
        }
        
        return array_values($graphDataList);
    }
}
