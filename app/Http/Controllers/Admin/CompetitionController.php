<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\YouthCommunity;
use App\Models\Schedule;
use App\Models\Result;
use App\Models\File;

class CompetitionController extends Controller
{
    public function schedule(Request $request)
    {
        $events = Event::get()->all();
        $team_modes = TeamMode::get()->all();

        // already created competition id finding!
        $created_competition_ids = array_unique(Schedule::where('publish', 1)->pluck('competition_id')->all());
        $created_competitions = Competition::whereIn('id', $created_competition_ids)->get()->all();

        // ajax handler!
        $json_data = [];

        if ( $request->ajax() ) {

            $json = ['success' => false];

            // team setting mode select change handler
            $action = $request->input('action') ? $request->input('action') : '';

            if ( $action == 'require_snippet' ) {
                $team_mode_id = $request->input('team_mode');

                if ( $team_mode_id == TeamMode::ROOM ) {
                    $rooms_name = [];
                    $rooms = Room::whereRaw(true)->get()->all();

                    foreach ($rooms as $key => $room) {
                        $users_count = User::where('room_id', $room->id)->count();
                        $rooms_name[] = $room->room_name . '-' . $room->id . '-' . $users_count;
                    }

                    $rooms_name_str = implode(",", $rooms_name);

                    $json['success'] = true;
                    $json['team_mode'] = $team_mode_id;
                    $json['snippet'] = $rooms_name_str;

                } elseif ( $team_mode_id == TeamMode::ZONE ) {
                    $zones_name = [];
                    $zones = Zone::whereRaw(true)->get()->all();

                    foreach ($zones as $key => $zone) {
                        $users_count = User::where('zone_id', $zone->id)->count();
                        $zones_name[] = $zone->zone_name . '-' . $zone->id . '-' . $users_count;
                    }

                    $zones_name_str = implode(",", $zones_name);

                    $json['success'] = true;
                    $json['team_mode'] = $team_mode_id;
                    $json['snippet'] = $zones_name_str;

                } elseif ( $team_mode_id == TeamMode::CELL ) {
                    $cells_name = [];
                    $cells = Cell::whereRaw(true)->get()->all();

                    foreach ($cells as $key => $cell) {
                        $room_ids = Room::where('cell_id', $cell->id)->pluck('id')->all();
                        $users_count = User::whereIn('room_id', $room_ids)->count();
                        $cells_name[] = $cell->cell_name . '-' . $cell->id . '-' . $users_count;
                    }

                    $cells_name_str = implode(",", $cells_name);

                    $json['success'] = true;
                    $json['team_mode'] = $team_mode_id;
                    $json['snippet'] = $cells_name_str;

                } elseif ( $team_mode_id == TeamMode::FLOOR ) {
                    $floors_name = [];
                    $floors = Floor::whereRaw(true)->get()->all();

                    foreach ($floors as $key => $floor) {
                        $room_ids = Room::where('floor_id', $floor->id)->pluck('id')->all();
                        $users_count = User::whereIn('room_id', $room_ids)->count();
                        $floors_name[] = $floor->floor_name . '-' . $floor->id . '-' . $users_count;
                    }

                    $floors_name_str = implode(",", $floors_name);

                    $json['success'] = true;
                    $json['team_mode'] = $team_mode_id;
                    $json['snippet'] = $floors_name_str;
                }   elseif ( $team_mode_id == TeamMode::YOUTH ) {
                    $youths_name = [];
                    $youths = YouthCommunity::whereRaw(true)->get()->all();

                    foreach ($youths as $key => $youth) {
                        $user_ids = User::where('belong', '청년동맹')->where('community', $youth->id)->pluck('id')->all();
                        $users_count = count($user_ids);
                        $youths_array[] = $youth->community_name . '-' . $youth->id . '-' . $users_count;
                    }

                    $youths_str = implode(",", $youths_array);

                    $json['success'] = true;
                    $json['team_mode'] = $team_mode_id;
                    $json['snippet'] = $youths_str;

                } elseif ( $team_mode_id == TeamMode::INDIVIDUAL ) {
                    $individuals_name = [];
                    $individuals = User::whereRaw(true)->get()->all();

                    foreach ($individuals as $key => $individual) {
                        $users_count = 1;
                        $individuals_name[] = $individual->name . '-' . $individual->id . '-' . $users_count;
                    }

                    $individuals_name_str = implode(",", $individuals_name);

                    $json['success'] = true;
                    $json['team_mode'] = $team_mode_id;
                    $json['snippet'] = $individuals_name_str;

                }

                return response()->json($json);

            } elseif ( $action == 'edit_schedule' ) {

                $date = $request->input('date');
                $competition_id = $request->input('competition_id');

                $schedules = Schedule::where('competition_id', $competition_id)
                                    ->where('game_date', $date)
                                    ->get();

                $json['date'] = date('Y-n-j', strtotime($date));

                if (count($schedules) > 0) {

                    foreach ($schedules as $key => $schedule) {
                        $json['content'][$key]['schedule_id'] = $schedule->id;
                        $json['content'][$key]['game_date'] = $schedule->game_date;

                        $json['content'][$key]['team1_id'] = $schedule->team1_id;
                        $json['content'][$key]['team2_id'] = $schedule->team2_id;
                        $team1_name = Team::find($schedule->team1_id)->team_name;
                        $team2_name = Team::find($schedule->team2_id)->team_name;
                        $json['content'][$key]['team1_name'] = $team1_name;
                        $json['content'][$key]['team2_name'] = $team2_name;

                        $json['content'][$key]['event_id'] = $schedule->event_id;
                        $event_name = Event::find($schedule->event_id)->event_name;
                        $json['content'][$key]['event_name'] = $event_name;
                    }

                } else {
                    $json['content'] = [];
                }

                $softDeletedSchedules = Schedule::where('competition_id', $competition_id)->onlyTrashed()->get();

                if (count($softDeletedSchedules) > 0) {

                    foreach ($softDeletedSchedules as $key => $softDeletedSchedule) {
                        $json['addition'][$key]['schedule_id'] = $softDeletedSchedule->id;
                        $json['addition'][$key]['game_date'] = $softDeletedSchedule->game_date;

                        $json['addition'][$key]['team1_id'] = $softDeletedSchedule->team1_id;
                        $json['addition'][$key]['team2_id'] = $softDeletedSchedule->team2_id;
                        $team1_name = Team::find($softDeletedSchedule->team1_id)->team_name;
                        $team2_name = Team::find($softDeletedSchedule->team2_id)->team_name;
                        $json['addition'][$key]['team1_name'] = $team1_name;
                        $json['addition'][$key]['team2_name'] = $team2_name;

                        $json['addition'][$key]['event_id'] = $softDeletedSchedule->event_id;
                        $event_name = Event::find($softDeletedSchedule->event_id)->event_name;
                        $json['addition'][$key]['event_name'] = $event_name;
                    }

                } else {
                    $json['addition'] = [];
                }

                $json['success'] = true;
                return response()->json($json);

            } elseif ( $action == 'remove_schedule' ) {

                $date = $request->input('date');
                $competition_id = $request->input('competition_id');
                $team1_id = $request->input('team1_id');
                $team2_id = $request->input('team2_id');
                $event_id = $request->input('event_id');

                $schedules = Schedule::where('competition_id', $competition_id)
                                    ->where('game_date', $date)
                                    ->where('event_id', $event_id)
                                    ->where('team1_id', $team1_id)
                                    ->where('team2_id', $team2_id)
                                    ->delete();

                $json['success'] = true;
                return response()->json($json);

            } elseif ( $action == 'get_updated_schedule' ) {

                $json['success'] = true;
                $json['content'] = $this->getUpdatedSchedule($request);
                return response()->json($json);

            } elseif ( $action == 'publish_schedule' ) {

                $competition_id = $request->input('competition_id');

                if ($competition_id == '') {
                    $json['success'] = false;
                    $json['error'] = '창조된 일정이 없습니다!';
                } else {

                    $schedules_count = Schedule::where('competition_id', $competition_id)->count();
                    $onlySoftDeleted = Schedule::where('competition_id', $competition_id)->onlyTrashed()->get();

                    if ( $schedules_count > 0 && count($onlySoftDeleted) == 0) {
                        $schedules = Schedule::where('competition_id', $competition_id)->get();
                        foreach ($schedules as $key => $schedule) {
                            $schedule->publish = 1;
                            $schedule->save();
                        }
                        $json['success'] = true;
                    } else {
                        $json['success'] = false;
                        $json['error'] = '추가되지 않은 경기가 있습니다!';
                    }
                }

                return response()->json($json);
            }

            // each step handler
            $competition_id = $request->input('competition_id');

            if ($competition_id) {
                $competition = Competition::findOrFail($competition_id);
            } else {
                $competition = new Competition;
            }

            $current_step = $request->input('current_step');
           
            switch ($current_step) {
                case 'create_new_competition':
                    $competition_name   = $request->input('competition_name');
                    $start_date         = $request->input('start_date');
                    $end_date           = $request->input('end_date');
                    $event_ids          = $request->input('eventIds');

                    $add_event_names    = $request->input('add_event_name');
                    $overlapped_event_count = 0;

                    if ( count($add_event_names) > 0 ) {
                        foreach ($add_event_names as $key => $add_event_name) {
                            if ( trim($add_event_name) != '' && Event::where('event_name', 'like', '%' . trim($add_event_name) . '%')->first() ) {
                                $overlapped_event_count ++;
                            }
                        }
                    }
                    

                    if ( $overlapped_event_count > 0 ) {
                        $json['success'] = false;
                        $json['message'] = '이미 존재하는 종목은 추가할수 없습니다!.';
                        return response()->json($json);
                    }

                    $current_event_key = count($event_ids);
                    $add_event_ids = [];
                    $total_event_ids = [];

                    if ( count($add_event_names) > 0 ) {
                        for ($i=0; $i < count($add_event_names); $i++) { 
                            $new_event = new Event;
                            $new_event->event_name  = $request->input('add_event_name')[$i];
                            $new_event->default_weight  = $request->input('add_event_weight')[$i];
                            $new_event->default_slalom  = $request->input('add_event_slalom')[$i];
                            $new_event->save();

                            $add_event_ids[$current_event_key] = $new_event->id;
                            $current_event_key ++;
                        }
                    }

                    if ( count($add_event_ids) != 0 ) {
                        $total_event_ids = array_merge($event_ids, $add_event_ids);
                    } else {
                        $total_event_ids = $event_ids;
                    }

                    if ($competition_id) {
                        EventMeter::where('competition_id', $competition_id)
                                    ->whereNotIn('event_id', $total_event_ids)
                                    ->delete();
                    }

                    $event_ids_str = implode(",", $total_event_ids);

                    $competition->competition_name = $competition_name;
                    $competition->start_date = $start_date;
                    $competition->end_date = $end_date;
                    $competition->event_ids = $event_ids_str;

                    if ( $competition->save() ) {
                        $json['success'] = true;
                        $json['id'] = $competition->id;
                    }

                    if ( count($add_event_ids) != 0 ) {
                        foreach ($add_event_ids as $key => $add_event_id) {
                            $new_event_meter = new EventMeter;
                            $new_event_meter->competition_id = $competition->id;
                            $new_event_meter->event_id       = $add_event_id;
                            $new_event_meter->event_weight   = Event::find($add_event_id)->default_weight;
                            $new_event_meter->slalom_count   = Event::find($add_event_id)->default_slalom;
                            $new_event_meter->save();
                        }
                    }

                    $event_meters = [];
                    $index_w = [];
                    $index_s = []; 
                                      
                    foreach ($event_ids as $key => $event_id) {
                        $index_w[$key] = 'event_weight_' . $event_id;
                        $index_s[$key] = 'event_slalom_' . $event_id;
                        $event_weight = $request->input($index_w[$key]);
                        $slalom_count = $request->input($index_s[$key]);


                        if ($competition_id) {
                            $event_meter = EventMeter::where('competition_id', $competition_id)
                                                    ->where('event_id', $event_id)
                                                    ->withTrashed()
                                                    ->first();

                            if ($event_meter) {

                                if ($event_meter->trashed()) {
                                    $event_meter->restore();
                                }
                            
                                $event_meter->event_weight = $event_weight;
                                $event_meter->slalom_count = $slalom_count;

                            } else {
                                $event_meter = new EventMeter;
                                $event_meter->competition_id = $competition_id;
                                $event_meter->event_id = $event_id;
                                $event_meter->event_weight = $event_weight;
                                $event_meter->slalom_count = $slalom_count;
                            }

                            $event_meter->save();

                        } else {
                            $event_meter = new EventMeter;
                            $event_meter->competition_id = $competition->id;
                            $event_meter->event_id       = $event_id;
                            $event_meter->event_weight   = $event_weight;
                            $event_meter->slalom_count   = $slalom_count;
                            $event_meter->save();
                        }
                    }

                    break;

                case 'create_team':

                    $team_setting_mode = $request->input('team_setting_mode');
                    $team_count = $request->input('team_count');

                    for ($i=0; $i < $team_count; $i++) { 
                        $input_team_name = 'team_name_' . $i;
                        $input_snippet_ids_name = 'snippet_ids_' . $i;
                        $team_name = $request->input($input_team_name);
                        $snippet_ids = $request->input($input_snippet_ids_name);

                        $team = new Team;
                        $team->team_name       = $team_name;
                        $team->team_mode_id    = $team_setting_mode;
                        $team->team_member     = $snippet_ids;
                        $team->competition_id  = $competition_id;
                        $team->save();
                    }

                    $json['success'] = true;
                    $json['id'] = $competition->id;
                    
                    break;

                case 'auto_create_schedule':

                    // force delete old schedules
                    $old_schedules = Schedule::where('competition_id', $competition_id)
                                            ->where('publish', 0)
                                            ->forceDelete();

                    // auto create new schedules
                    $start_date = $competition->start_date;
                    $end_date = $competition->end_date;

                    $event_ids_str = $competition->event_ids;
                    $event_ids_array = explode(',', $event_ids_str);
                    $event_counts = count($event_ids_array);
                    $team_counts = Team::where('competition_id', $competition->id)->count();

                    $start_date_timestamp = strtotime($start_date);
                    $end_date_timestamp = strtotime($end_date);

                    $date_array = [];
                    $holidays   = [];

                    $interval_time = $end_date_timestamp - $start_date_timestamp;
                    $interval_date = $interval_time / ( 24 * 3600 );

                    $holidays = $this->getHolidays($start_date_timestamp, $end_date_timestamp);

                    for ($i=0; $i <= $interval_date; $i++) { 
                        $timestamp = $start_date_timestamp + $i * 24 * 3600;
                        $weekday = date("w", $timestamp);
                        $date = date("Y-m-d", $timestamp); 

                        if ( $weekday != 0 && !in_array($date, $holidays) ) {
                            $date_array[] = $date;
                        }                          
                    }
                    
                    $result_dates = $this->getAutoSchedule($competition->id, $event_counts, $team_counts, $date_array);
                    foreach ($result_dates as $key => $result_date) {

                        $pieces = explode (":", $result_date);

                        $schedule = new Schedule;
                        $schedule->competition_id  = $competition->id;
                        $schedule->event_id        = $event_ids_array[intval($pieces[1]) - 1];
                        $schedule->game_date       = $pieces[0];

                        $teams = Team::where('competition_id', $competition->id)->get()->all();
                        $team1_key = substr($pieces[2], 0, 1);
                        $team2_key = substr($pieces[2], 1, 1);
                        $team1_id  = $teams[$team1_key-1]['id'];
                        $team2_id  = $teams[$team2_key-1]['id'];

                        $schedule->team1_id = $team1_id;
                        $schedule->team2_id = $team2_id;

                        $schedule->publish  = 0;
                        $schedule->progress = 0;

                        $schedule->save();
                    }

                    $json['success'] = true;
                    $json['content'] = $this->scheduleToJson($competition->id, $result_dates);

                    break;

                default:
                    break;
            }

            return response()->json($json);
        }

        return view('pages.admin.schedule', 
            [
                'page'       => 'schedule',
                'events'     => $events,
                'team_modes' => $team_modes,
                'created_competitions' => $created_competitions,
            ]
        );
        
    }

    public function schedule_view(Request $request, $competition_id)
    {
        $competition_name = Competition::where('id', $competition_id)->value('competition_name');
        $start_date = Schedule::where('competition_id', $competition_id)->orderBy('game_date', 'asc')->value('game_date');

        $json_data = [];
        $json_data = $this->makeJsonSchedule($competition_id);

        return view('pages.admin.schedule_view', 
            [
                'page' => 'schedule_view',
                'competition_id' => $competition_id,
                'competition_name' => $competition_name,
                'json_data' => $json_data,
                'start_date' => $start_date,
            ]
        );
    }

    public function schedule_edit(Request $request, $competition_id)
    {
        $competition_name = Competition::where('id', $competition_id)->value('competition_name');
        $start_date = Schedule::where('competition_id', $competition_id)->orderBy('game_date', 'asc')->value('game_date');

        $json_data = [];
        $json_data = $this->makeJsonSchedule($competition_id);

        if ( $request->ajax() ) {

            $json = ['success' => false];

            // team setting mode select change handler
            $action = $request->input('action') ? $request->input('action') : '';

            if ( $action == 'edit_schedule' ) {

                $date = $request->input('date');
                $competition_id = $request->input('competition_id');

                $schedules = Schedule::where('competition_id', $competition_id)
                                    ->where('game_date', $date)
                                    ->get();

                $json['date'] = date('Y-n-j', strtotime($date));

                if (count($schedules) > 0) {

                    foreach ($schedules as $key => $schedule) {
                        $json['content'][$key]['schedule_id'] = $schedule->id;
                        $json['content'][$key]['game_date'] = $schedule->game_date;

                        $json['content'][$key]['team1_id'] = $schedule->team1_id;
                        $json['content'][$key]['team2_id'] = $schedule->team2_id;
                        $team1_name = Team::find($schedule->team1_id)->team_name;
                        $team2_name = Team::find($schedule->team2_id)->team_name;
                        $json['content'][$key]['team1_name'] = $team1_name;
                        $json['content'][$key]['team2_name'] = $team2_name;

                        $json['content'][$key]['event_id'] = $schedule->event_id;
                        $event_name = Event::find($schedule->event_id)->event_name;
                        $json['content'][$key]['event_name'] = $event_name;
                    }

                } else {
                    $json['content'] = [];
                }

                $softDeletedSchedules = Schedule::where('competition_id', $competition_id)->onlyTrashed()->get();

                if (count($softDeletedSchedules) > 0) {

                    foreach ($softDeletedSchedules as $key => $softDeletedSchedule) {
                        $json['addition'][$key]['schedule_id'] = $softDeletedSchedule->id;
                        $json['addition'][$key]['game_date'] = $softDeletedSchedule->game_date;

                        $json['addition'][$key]['team1_id'] = $softDeletedSchedule->team1_id;
                        $json['addition'][$key]['team2_id'] = $softDeletedSchedule->team2_id;
                        $team1_name = Team::find($softDeletedSchedule->team1_id)->team_name;
                        $team2_name = Team::find($softDeletedSchedule->team2_id)->team_name;
                        $json['addition'][$key]['team1_name'] = $team1_name;
                        $json['addition'][$key]['team2_name'] = $team2_name;

                        $json['addition'][$key]['event_id'] = $softDeletedSchedule->event_id;
                        $event_name = Event::find($softDeletedSchedule->event_id)->event_name;
                        $json['addition'][$key]['event_name'] = $event_name;
                    }

                } else {
                    $json['addition'] = [];
                }

                $json['success'] = true;
                return response()->json($json);

            } elseif ( $action == 'get_updated_schedule' ) {

                $json['success'] = true;
                $json['content'] = $this->getUpdatedSchedule($request);
                return response()->json($json);
            }
        }

        return view('pages.admin.schedule_edit', 
            [
                'page' => 'schedule_edit',
                'competition_id' => $competition_id,
                'competition_name' => $competition_name,
                'json_data' => $json_data,
                'start_date' => $start_date,
            ]
        );
    }

    private function getHolidays($start_date_timestamp, $end_date_timestamp) {
        $current_year = date('Y', $start_date_timestamp);

        $holidays = [];
        $holidays[0] = $current_year . '-01-01';
        $holidays[1] = $current_year . '-01-08';
        $holidays[2] = $current_year . '-02-16';
        $holidays[3] = $current_year . '-04-15';
        $holidays[4] = $current_year . '-04-25';
        $holidays[5] = $current_year . '-05-01';
        $holidays[6] = $current_year . '-07-27';
        $holidays[7] = $current_year . '-08-15';
        $holidays[8] = $current_year . '-09-09';
        $holidays[9] = $current_year . '-10-10';
        $holidays[10] = $current_year . '-12-24';

        return $holidays;
    }


    private function getAutoSchedule($competition_id, $event_counts, $team_counts, $date_array) {
       


       // 4개까지의 팀개수에 적용되는 알고리듬
        $array = [];
        
        $event_game_counts = $team_counts * ($team_counts-1) / 2;
        $total_game_counts = $event_game_counts * $event_counts;
        $game_date_counts = count($date_array);

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

        $collect_array = collect($array);
        $result_array = $collect_array->shuffle();

        
        // $current_event_value = 0;
        // $prev_event_value = 0;

        // while ( count($result_array) < $total_game_counts ) {

        //     $index = rand(1, $total_game_counts);

        //     $current_event_value = intval(substr($array[$index], 0, 1));

        //     if (!in_array($array[$index], $result_array) && ($current_event_value != $prev_event_value)) {
        //         $result_array[] = $array[$index];
        //         $prev_event_value = $current_event_value;
        //         if ($prev_event_value > $event_counts) {
        //             $prev_event_value = 1;
        //         }
        //     }
        // }


        // 경기날자별로 묶기

        $divide = floor($total_game_counts / $game_date_counts);
        $mod = $total_game_counts % $game_date_counts;
        
        $result_dates = array();
        $count = 0;

        for ($date=1; $date <= $game_date_counts ; $date++) { 
            
            $first = $count;
            $end = ($date <= $mod) ? $count+$divide+1 : $count+$divide;

            for ($key=$first; $key <$end ; $key++) { 
                $result_dates[] = $date_array[$date-1] . ':' . $result_array[$key];
                $count ++;
            }
            
        }

        return $result_dates;
    }

    private function scheduleToJson($competition_id, $result_dates) {

        $json_data = [];

        foreach ($result_dates as $key => $result_date) {

            $event_ids_str = Competition::find($competition_id)->event_ids;
            $event_ids_array = explode(',', $event_ids_str);

            $pieces = explode (":", $result_date);

            $diff_year  = date('Y') - date('Y', strtotime($pieces[0]));
            $diff_month = date('n') - date('n', strtotime($pieces[0]));
            $diff_day   = date('j') - date('j', strtotime($pieces[0])) - 1;

            $event_name = Event::find($event_ids_array[intval($pieces[1]) - 1])->event_name;

            $teams = Team::where('competition_id', $competition_id)->get()->all();
            $team1_key = substr($pieces[2], 0, 1);
            $team2_key = substr($pieces[2], 1, 1);
            $team1_name = $teams[$team1_key-1]['team_name'];
            $team2_name = $teams[$team2_key-1]['team_name'];

            $title = $event_name . '-' . $team1_name . ':' . $team2_name;

            $json_data[$key]['title']      = $title;
            $json_data[$key]['diff_year']  = $diff_year;
            $json_data[$key]['diff_month'] = $diff_month;
            $json_data[$key]['diff_day']   = $diff_day;
        }

        return $json_data;

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

    private function getUpdatedSchedule(Request $request) 
    {
        $json_data = [];
        
        $competition_id = $request->input('competition_id');
        $competition = Competition::find($competition_id);
        $schedule_ids_str = $request->input('schedule_ids');
        $date = $request->input('date');

        if ( $schedule_ids_str == '' ) {
            Schedule::where('competition_id', $competition_id)
                                    ->where('game_date', $date)
                                    ->delete();
        } else {

            $schedule_ids_array = explode(',', $schedule_ids_str);
            $deleted_schedules = Schedule::where('competition_id', $competition_id)
                                        ->where('game_date', $date)
                                        ->whereNotIn('id', $schedule_ids_array)
                                        ->delete();

            $softDeletedSchedule_ids = Schedule::where('competition_id', $competition_id)
                                        ->onlyTrashed()
                                        ->whereIn('id', $schedule_ids_array)
                                        ->pluck('id')->all();

            foreach ($softDeletedSchedule_ids as $key => $softDeletedSchedule_id) {
                $softDeletedSchedule = Schedule::withTrashed()->find($softDeletedSchedule_id);
                $softDeletedSchedule->restore();
                $softDeletedSchedule->game_date = $date;
                $softDeletedSchedule->save();
            }
        }

        $competition->start_date = min(Schedule::where('competition_id', $competition_id)->pluck('game_date')->all());
        $competition->end_date = max(Schedule::where('competition_id', $competition_id)->pluck('game_date')->all());
        $competition->save();

        $updated_schedules = Schedule::where('competition_id', $competition_id)->get();

        foreach ($updated_schedules as $key => $updated_schedule) {
            
            $game_date_timestamp = strtotime($updated_schedule->game_date);
            $diff_year  = date('Y') - date('Y', $game_date_timestamp);
            $diff_month = date('n') - date('n', $game_date_timestamp);
            $diff_day   = date('j') - date('j', $game_date_timestamp) - 1;
            $event_name = Event::find($updated_schedule->event_id)->event_name;
            $team1_name = Team::where('competition_id', $competition_id)->where('id', $updated_schedule->team1_id)->first()->team_name;
            $team2_name = Team::where('competition_id', $competition_id)->where('id', $updated_schedule->team2_id)->first()->team_name;

            $title = $event_name . '-' . $team1_name . ':' . $team2_name;

            $json_data[$key]['title']      = $title;
            $json_data[$key]['diff_year']  = $diff_year;
            $json_data[$key]['diff_month'] = $diff_month;
            $json_data[$key]['diff_day']   = $diff_day;

        }

        return $json_data;
    }

    public function result(Request $request)
    {

        //  - 이때까지 진행된 경기대회들(publish가 진행된 경기대회들) - 
        $now = date('Y-m-d');
        $published_competition_ids = array_unique(Schedule::where('publish', 1)->pluck('competition_id')->all()); 
        $progressed_competitions = Competition::where('start_date', '<', $now)
                                                ->whereIn('id', $published_competition_ids)
                                                ->get()->all();

        //  - 가장 최근의 경기대회
        $recent_competition = Competition::whereRaw('true')->get()->last();

        if ( $request->input('competition_id') ) {
            $competition_id = $request->input('competition_id');
            $competition_name = Competition::where('id', $competition_id)->value('competition_name');
        } else {
            $competition_id = $recent_competition->id;
            $competition_name = $recent_competition->competition_name;
        }

        $schedules = Schedule::where('competition_id', $competition_id)->get();

        $start_date = Schedule::where('competition_id', $competition_id)->orderBy('game_date', 'asc')->value('game_date');
        
        $json_data = [];
        $json_data = $this->makeJsonSchedule($competition_id);

        if ( $request->ajax() ) { 

            $json = ['success' => false];
            
            $action = $request->input('action') ? $request->input('action') : $request->input('modal_event');

            $date = $request->input('date');
            $competition_id = $request->input('competition_id');

            $oneday_schedules = Schedule::where('competition_id', $competition_id)
                                ->where('game_date', $date)
                                ->get();

            if ( $action == 'edit_result' ) {

                $json['date'] = date('Y-n-j', strtotime($date));

                $input_name_array = array();
                $slalom_score = array();

                if (count($oneday_schedules) > 0) {

                    foreach ($oneday_schedules as $key => $schedule) {
                        $json['content'][$key]['schedule_id'] = $schedule->id;
                        $json['content'][$key]['game_date'] = $schedule->game_date;

                        $json['content'][$key]['team1_id'] = $schedule->team1_id;
                        $json['content'][$key]['team2_id'] = $schedule->team2_id;
                        $team1_name = Team::find($schedule->team1_id)->team_name;
                        $team2_name = Team::find($schedule->team2_id)->team_name;
                        $json['content'][$key]['team1_name'] = $team1_name;
                        $json['content'][$key]['team2_name'] = $team2_name;

                        $json['content'][$key]['event_id'] = $schedule->event_id;
                        $event_name = Event::find($schedule->event_id)->event_name;
                        $json['content'][$key]['event_name'] = $event_name;

                        $event_meter = EventMeter::where('competition_id', $competition_id)
                                                        ->where('event_id', $schedule->event_id)
                                                        ->first();

                        if ( $event_meter ) {
                            $event_slalom_count = $event_meter->slalom_count;
                            $json['content'][$key]['event_slalom_count'] = $event_slalom_count;
                        }

                        $json['content'][$key]['progress'] = $schedule->progress;

                        if ($schedule->progress == 1) {         // 이미 결과편집이 진행된 schedule인 경우
                            $result = Result::where('schedule_id', $schedule->id)->first();
                            if ( $schedule->team1_id == $result->winner_team_id ) {
                                $json['content'][$key]['team1_score'] = $result->winner_score;
                                $json['content'][$key]['team2_score'] = $result->loser_score;
                            } else {
                                $json['content'][$key]['team2_score'] = $result->winner_score;
                                $json['content'][$key]['team1_score'] = $result->loser_score;
                            }
                             
                            $json['content'][$key]['desc'] = $result->result_desc;

                            // file section
                            
                            if ( $result->file_ids != '' ) {
                                $json['content'][$key]['file_ids'] = $result->file_ids;
                                $files = [];
                                $file_ids_array = [];
                                $file_ids_array = explode(',', $result->file_ids);
                                foreach ($file_ids_array as $key1 => $file_id) {
                                    $file = File::find($file_id);
                                    $files[$key1]['id'] = $file_id;
                                    $files[$key1]['name'] = $file->name;
                                    $files[$key1]['hash'] = $file->hash;
                                }
                                $json['content'][$key]['files'] = $files;
                            }

                        }
                    }

                } else {
                    $json['content'] = [];
                }

                $json['success'] = true;
                

            } elseif ( $action == 'modalSave' ) {

                $input_name_array = array();
                $result_score = array();

                if ( $now > $date ) {               // 지나간 날자에 대해서만 결과편집을 진행할수 있다.
                    if (count($oneday_schedules) > 0) {

                        foreach ($oneday_schedules as $key => $schedule) {
                            
                            $event_meter = EventMeter::where('competition_id', $competition_id)         // slalom count calculate
                                                    ->where('event_id', $schedule->event_id)
                                                    ->first();    

                            if ( $event_meter ) {
                                $slalom_count = $event_meter->slalom_count;
                            }

                            for ($i=1; $i <= 2; $i++) { 

                                $input_name_array[$schedule->id][$i] = $schedule->id . '_' . $i . '_result_score';
                                $result_score[$schedule->id][$i] = $request->input($input_name_array[$schedule->id][$i]) ? $request->input($input_name_array[$schedule->id][$i]) : 0;
                                
                            }

                            $input_name_array[$schedule->id]['desc'] = $schedule->id . '_result_desc';
                            $result_score[$schedule->id]['desc'] = trim($request->input($input_name_array[$schedule->id]['desc'])) ? trim($request->input($input_name_array[$schedule->id]['desc'])) : '';

                            $input_name_array[$schedule->id]['file_ids'] = $schedule->id . '_uploaded_files';
                            $result_score[$schedule->id]['file_ids'] = $request->input($input_name_array[$schedule->id]['file_ids']) ? $request->input($input_name_array[$schedule->id]['file_ids']) : '';
                        }

                        foreach ($result_score as $key1 => $result_score_schedule) {   
                            $winner_id = 0;
                            $loser_id = 0;

                            $single_schedule = Schedule::find($key1);
                            $single_team1_id = $single_schedule->team1_id;
                            $single_team2_id = $single_schedule->team2_id;

                            if ( $result_score_schedule['1'] > $result_score_schedule['2'] ) {
                                $winner_id = $single_team1_id;
                                $loser_id = $single_team2_id;
                            } elseif ( $result_score_schedule['1'] < $result_score_schedule['2'] ) {
                                $winner_id = $single_team2_id;
                                $loser_id = $single_team1_id;
                            }

                            if ( $winner_id != 0 && $loser_id != 0 ) {

                                $result = Result::where('schedule_id', $key1)->first();

                                if ( empty($result) ) {
                                    $result = new Result;
                                }
                                
                                $result->schedule_id = $key1;
                                $result->winner_team_id = $winner_id;
                                $result->loser_team_id = $loser_id;
                                $result->winner_score = max($result_score_schedule['1'], $result_score_schedule['2']);
                                $result->loser_score = min($result_score_schedule['1'], $result_score_schedule['2']);
                                $result->result_desc = $result_score_schedule['desc'];
                                $result->file_ids = $result_score_schedule['file_ids'];
                                $result->save();        // results table에 결과자료를 녛는다.

                                $single_schedule->progress = 1;
                                $single_schedule->save();       // schedule table의 progress를 1로  설정한다.
                            }                        
                        }

                        $json['message']['success'] = '경기결과편집이 정확히 진행되였습니다!';

                    } else {
                        $json['content'] = [];
                        $json['message']['error'] = '진행하게 된 경기가 없습니다!';
                    }
                } else {
                    $json['message']['error'] = '진행되지 않은 경기는 결과편집할수 없습니다!';
                }

                $json['success'] = true;

            } elseif ( $action == 'uploadfiles' ) {
                try {

                    $json['success'] = true;

                    $schedule_id = $request->input('schedule_id');
                    $input_name = $schedule_id . '_files';

                    // Upload Files.
                    if ( count($request->file($input_name)) ) {

                        $upload_dir = getUploadDir($schedule_id);
                        createDir($upload_dir);

                        foreach ( $request->file($input_name) as $file ) {
                            $ext = strtolower($file->getClientOriginalExtension());

                            if ( !in_array($ext, ['jpg', 'png', 'bmp', 'avi', 'mp4', 'mpg', '3gp']) ) {
                                $json['success'] = false;
                                $json['error'] = '지원하지 않는 화일형식입니다!';
                                continue;
                            }

                            $filename = generateFileName($upload_dir, $file->getClientOriginalName());

                            if ( $file->move($upload_dir, $filename) ) {
                                $fileObj = new File;
                                $fileObj->schedule_id = $schedule_id;
                                $fileObj->name = $filename;
                                $fileObj->ext =$ext;
                                $fileObj->mime_type = $file->getClientMimeType();
                                $fileObj->size = $file->getClientSize();
                                $fileObj->path = $upload_dir;
                                $fileObj->hash();

                                if ( $fileObj->save() ) {
                                    $json['files'][] = [
                                        'id' => $fileObj->id, 
                                        'name' => $filename,
                                        'hash' => $fileObj->hash
                                    ];
                                }
                            } else {
                                $json['success'] = false;
                                $json['error'] = '화일복사가 제대로 진행되지 않았습니다.';
                                continue;
                            }
                        }

                    }
                } catch ( Exception $e ) {
                    $json['success'] = false;
                    $json['error'] = $e->getMessage();
                }
            }

            return response()->json($json);

        }

        return view('pages.admin.result', 
            [
                'page' => 'result',
                'progressed_competitions' => $progressed_competitions,
                'competition_id' => $competition_id,
                'competition_name' => $competition_name,
                'json_data' => $json_data,

                'start_date' => $start_date,  //  fullcalendar first display !
            ]
        );
    }

    /**
    * Delete file uploaded by ajax
    */
    public function deleteFile(Request $request) {

        $json = [
            'success' => false
        ];

        $id = $request->input('id');

        try {
            $file = File::findOrFail($id);

            if ( $file ) {
                
                $file->delete();

                $file_path = $file->path . $file->name;
                if ( file_exists($file_path) ) {
                    unlink($file_path);
                }

                $json['success'] = true;
                
            }
        } catch ( Exception $e ) {
            error_log('CompetitionController.php [deleteFile] - ' . $e->getMessage());
        }

        return response()->json($json);
    }
}