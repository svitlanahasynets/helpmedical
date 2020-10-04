<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Permission;
use App\Models\Event;
use App\Models\Competition;
use App\Models\EventMeter;
use App\Models\TeamMode;
use App\Models\Team;
use App\Models\Room;
use App\Models\Cell;
use App\Models\Zone;
use App\Models\Floor;
use App\Models\Schedule;
use App\Models\Result;
use App\Models\File;

class HomepageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comp_flag = 1;
        $events = Event::whereRaw(true)->get()->all();
        $competitions = Competition::whereRaw(true);
        $schedules = Schedule::whereRaw(true);

        $sort = $request->input('sort') ? $request->input('sort') : 'newest';

        // -- Search by query start -- //

        $results_page = $request->input('page') ? $request->input('page') : 1;
        $category = $request->input('category') ? $request->input('category') : 'competition_view';
        $start_date = $request->input('start_date') ? $request->input('start_date') : '';
        $end_date = $request->input('end_date') ? $request->input('end_date') : '';
        $date_range = $request->input('date_range') ? $request->input('date_range') : '';
        $event_ids_str = $request->input('event_ids') ? $request->input('event_ids') : '';

        $event_ids = [];
        $event_ids = explode(',', $event_ids_str);

        $searchOtherParams = [
            'page', 'category', 'start_date', 'end_date', 'event_ids'
        ];

        $defaultSearched = true;
        foreach ( $searchOtherParams as $p ) {
            if ( isset($_REQUEST[$p]) ) {
                $defaultSearched = false;
            }
        }

        if ( $category == 'schedule_view' ) {
            $comp_flag = 0;
        }

        if ( $comp_flag == 1 ) {
            if ( $request->input('q') ) {
                $competitions = $competitions->where('competition_name', 'like', '%' . $request->input('q') . '%');
            }

            if ( $start_date != '' && $end_date != '' ) {
                $competitions = $competitions->where('start_date', '>=', $start_date)
                                            ->where('end_date', '<=', $end_date);
            }
        } else {
            if ( $request->input('q') ) {
                $competition_ids = [];
                $competition_ids = $competitions->where('competition_name', 'like', '%' . $request->input('q') . '%')
                                                ->pluck('id')
                                                ->all();

                if ( count($competition_ids) > 0 ) {
                    $schedules = $schedules->whereIn('competition_id', $competition_ids);
                }
            }

            if ( $start_date != '' && $end_date != '' ) {
                $schedules = $schedules->where('game_date', '>=', $start_date)
                                            ->where('game_date', '<=', $end_date);
            }

            if (count($event_ids) > 0) {
                $schedules = $schedules->whereIn('event_id', $event_ids);
            } else {
                $schedules = $schedules->where('event_id', -1);
            }
            
        }

        // -- Search by query end -- //

        $totalResults = $competitions->count();

        if ( $comp_flag == 1 ) {
            if ( $sort == 'newest' ) {
                $competitions = $competitions->orderBy('competitions.created_at', 'desc');
            } else {
                $competitions = $competitions->orderBy('competitions.created_at', 'asc');
            }
        } else {
            if ( $sort == 'newest' ) {
                $schedules = $schedules->orderBy('schedules.created_at', 'desc');
            } else {
                $schedules = $schedules->orderBy('schedules.created_at', 'asc');
            }

            $totalResults = $schedules->count();
        }

        $competitions = $competitions->paginate(5);
        $schedules = $schedules->paginate(5);

        $from_num = ($results_page - 1) * 5 + 1;
        $to_num = $from_num + $competitions->count() - 1;

        if ( $comp_flag == 0 ) {
            $from_num = ($results_page - 1) * 5 + 1;
            $to_num = $from_num + $schedules->count() - 1;
        }
        

        $resultsNum = [
            'from' => $from_num,
            'to' => $to_num,
            'total' => $totalResults
        ];

        $request->flash();

        return view('pages.frontend.homepage', [
            'page' => 'homepage',
            'events' => $events,
            'comp_flag' => $comp_flag,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'date_range' => $date_range,
            'defaultSearched' => $defaultSearched,
            'competitions' => $competitions,
            'schedules' => $schedules,
            'resultsNum' => $resultsNum,
            'results_page' => $results_page,
        ]);
    }

    /**
     * Show the detail competition page.
     *
     * @return \Illuminate\Http\Response
     */
    public function competition_view($id)
    {
        $competition = Competition::find($id);

        if (empty($competition)) {
            Request()->session()->flash('fail', '해당한 경기대회를 찾을수 없습니다.');
            return back();
        }

        $competition->read_counts = $competition->read_counts + 1;
        $competition->save();


        // 일정 Tab     
        $json_data = [];
        $json_data = $this->makeJsonSchedule($id);

        $start_date = Schedule::where('competition_id', $id)->orderBy('game_date', 'asc')->value('game_date');

        if (empty($start_date)) {
            $start_date = date('Y-m-d');
        }

        // 결과 Tab
        $results = [];

        $events = explode(",", $competition->event_ids);
        $event_count = count($events);

        $teams = Team::where('competition_id', $competition->id)->get()->all();
        $team_count = count($teams);

        $event_game_counts = $team_count * ($team_count-1) / 2;
        $total_game_counts = $event_game_counts * $event_count;

        $progressed_schedule_ids = Schedule::where('competition_id', $competition->id)
                                        ->where('progress', 1)
                                        ->pluck('id')->all();

        $progressed_schedules_count = count($progressed_schedule_ids);
        $progress_percent = round(($progressed_schedules_count / $total_game_counts) * 100);

        $progressed_schedule_dates_array = Schedule::whereIn('id', $progressed_schedule_ids)->pluck('game_date')->all();
        $result_dates_array = array_unique($progressed_schedule_dates_array);

        foreach ($result_dates_array as $key => $result_date) {
            $progressed_schedules_id = Schedule::where('competition_id', $id)
                                            ->where('game_date', $result_date)
                                            ->where('progress', 1)
                                            ->pluck('id')->all();
            $results[$result_date] = Result::whereIn('schedule_id', $progressed_schedules_id)->get()->all();
        }

        return view('pages.frontend.competition_view', [
            'page' => 'competition_view',
            'competition' => $competition,
            'start_date' => $start_date,  //  fullcalendar first display !
            'event_count' => $event_count,
            'teams' => $teams,
            'team_count' => $team_count,
            'total_game_counts' => $total_game_counts,
            'progressed_schedules_count' => $progressed_schedules_count,
            'progress_percent' => $progress_percent,

            'stats'         => [
                                    'bar' => $this->userGraphBarData($competition, $team_count),
                                    'pie' => $this->userGraphPieData($competition)
                                ],

            'results' => $results,
            'json_data' => $json_data,
        ]);
    }

    /**
     * Show the detail schedule page.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule_view($id)
    {
        
        $schedule = Schedule::find($id);

        if (empty($schedule)) {
            Request()->session()->flash('fail', '해당한 경기를 찾을수 없습니다.');
            return back();
        }

        $schedule->read_counts = $schedule->read_counts + 1;
        $schedule->save();

        return view('pages.frontend.schedule_view', [
            'page' => 'schedule_view',
            'schedule' => $schedule,
        ]);
    }

    private function userGraphBarData($competition, $team_count) {

        $graphDataList = [];

        $teams = Team::where('competition_id', $competition->id)->get()->all();

        foreach ($teams as $key => $team) {
            $graphDataList[$team->id]['team_name']  = $team->team_name;
            $graphDataList[$team->id]['score'] = 0;
        }

        $progressed_schedules = Schedule::where('competition_id', $competition->id)
                                        ->where('progress', 1)
                                        ->get()->all();

        if ( !$progressed_schedules ) {
            return $graphDataList;
        }

        $event_game_counts = $team_count * ($team_count-1) / 2;

        foreach ($progressed_schedules as $key => $progressed_schedule) {
            $result = Result::where('schedule_id', $progressed_schedule->id)->first();
            $event_id = $progressed_schedule->event_id;
            $event_weight = EventMeter::where('competition_id', $competition->id)
                                        ->where('event_id', $event_id)
                                        ->value('event_weight');

            
            $weight_per_game = round($event_weight / $event_game_counts);
            $graphDataList[$result->winner_team_id]['score'] += $weight_per_game;
            
        }
        
        return array_values($graphDataList);
    }

    private function userGraphPieData($competition) {
        $graphDataList = [];

        $event_meters = EventMeter::where('competition_id', $competition->id)->get()->all();

        foreach ($event_meters as $key => $event_meter) {
            $graphDataList[$key]['type']  = Event::where('id', $event_meter->event_id)->value('event_name');
            $graphDataList[$key]['value'] = $event_meter->event_weight;
        }
        
        return array_values($graphDataList);
    }

    private function makeJsonSchedule($competition_id) {

        $schedules = Schedule::where('competition_id', $competition_id)->get();
        $json_data = [];

        foreach ($schedules as $key => $schedule) {
            
            $game_date_timestamp = strtotime($schedule->game_date);
            $diff_year  = date('Y') - date('Y', $game_date_timestamp);
            $diff_month = date('n') - date('n', $game_date_timestamp);
            $diff_day   = date('j') - date('j', $game_date_timestamp) - 1;
            $event_name = Event::find($schedule->event_id)->event_name;
            $team1_name = Team::where('competition_id', $competition_id)->where('id', $schedule->team1_id)->first()->team_name;
            $team2_name = Team::where('competition_id', $competition_id)->where('id', $schedule->team2_id)->first()->team_name;

            $title = $event_name . '-' . $team1_name . ':' . $team2_name;

            $json_data[$key]['title']      = $title;
            $json_data[$key]['diff_year']  = $diff_year;
            $json_data[$key]['diff_month'] = $diff_month;
            $json_data[$key]['diff_day']   = $diff_day;
            $json_data[$key]['progress']   = $schedule->progress;

        }

        return $json_data;
    }


}
