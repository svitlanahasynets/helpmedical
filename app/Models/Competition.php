<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Event;
use App\Models\Team;
use App\Models\TeamMode;
use App\Models\Schedule;

class Competition extends Model
{

	protected $table = 'competitions';

	public static function formatDate($timestamp)
    {
        $date = date('Y', $timestamp) . '년 ' . date('n', $timestamp) . '월 ' . date('j', $timestamp) . '일';
        return $date;
    }

	/**
     * Get the date when competition is created .
     */
    public function createdDate()
    {
        $timestamp = strtotime($this->updated_at);
        return $this->formatDate($timestamp);
    }

    /**
     * Get the dates when competition is token place.
     */
    public function gameDates()
    {
        $start_timestamp = strtotime($this->start_date);
        $end_timestamp = strtotime($this->end_date);
        $gameDates = $this->formatDate($start_timestamp) . ' ~ ' . $this->formatDate($end_timestamp);
        return $gameDates;
    }

    /**
     * Get the teams that is participating to the competition.
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team', 'competition_id');
    }

    /**
     * Get the team setting mode of the competition.
     */
    public function teamMode()
    {
        $team = $this->teams()->first();
        $team_mode_id = $team->team_mode_id;
        $team_mode_name = TeamMode::where('id', $team_mode_id)->value('team_mode_name');
        
        return $team_mode_name;
    }

    /**
     * Get team names which is participating to the competition.
     */
    public function participationTeams()
    {
    	$team_names = '';
        $team_names_array = Team::where('competition_id', $this->id)->pluck('team_name')->all();

        if ( count($team_names_array) > 0 ) {
        	foreach ($team_names_array as $key => $team_name) {
        		if ( $team_names == '' ) {
        			$team_names = $team_name . '팀';
        		} else {
        			$team_names = $team_names . ', ' . $team_name . '팀';
        		}
        	}
        }

        return $team_names;
    }

    /**
     * Get the total game counts for the competition.
     */
    public function totalGameCounts()
    {
        $event_ids = explode(",", $this->event_ids);
        $event_count = count($event_ids);
        $team_count = count($this->teams);

        $event_game_counts = $team_count * ($team_count-1) / 2;
        $total_game_counts = $event_game_counts * $event_count;

        return $total_game_counts;
    }

    /**
     * Get the progress percent of the competition.
     */
    public function progressPercent()
    {
        $total_game_counts = $this->totalGameCounts();
        $progressed_schedules = Schedule::where('competition_id', $this->id)
                                        ->where('progress', 1)
                                        ->get()->all();

        $progressed_schedules_count = count($progressed_schedules);
        $progress_percent = round(($progressed_schedules_count / $total_game_counts) * 100);

        return $progress_percent;
    }

    /**
     * Get winner team of the competition.
     */
    public function winner()
    {
        $winner_team_name = '-';

        $progressed_schedules = Schedule::where('competition_id', $this->id)
                                        ->where('progress', 1)
                                        ->get()->all();

        $teams = Team::where('competition_id', $this->id)->get()->all();
        $teams_count = count($teams);
        $event_game_counts = $teams_count * ($teams_count-1) / 2;

        $team_score = [];
        foreach ($teams as $key => $team) {
            $team_score[$team->id] = 0;
        }

        if ( count($progressed_schedules) > 0 ) {
            foreach ($progressed_schedules as $key => $progressed_schedule) {
                $progressed_result = Result::where('schedule_id', $progressed_schedule->id)->first();
                $event_id = $progressed_schedule->event_id;
                $event_weight = EventMeter::where('competition_id', $this->id)
                                            ->where('event_id', $event_id)
                                            ->value('event_weight');
                
                $weight_per_game = round($event_weight / $event_game_counts);
                $team_score[$progressed_result->winner_team_id] += $weight_per_game;
            }

            $competitions_array[$this->id]['winner_team_name'] = '-';

            if ( count($team_score) != 0 ) {
                $max_value = max($team_score);
                $winner_team_id = array_search($max_value, $team_score);
                $winner_team_name = Team::where('id', $winner_team_id)->value('team_name');
            }
        }

        return $winner_team_name;
    }
}
