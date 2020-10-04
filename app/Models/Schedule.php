<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Competition;
use App\Models\Event;
use App\Models\Team;
use App\Models\Result;

class Schedule extends Model
{

	
	use SoftDeletes;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'schedules';

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
    * Indicates if the model should be timestamped.
    *
    * @var bool
    */
    public $timestamps = true;

    /**
     * Get the competition that is related to schedule.
     */
    public function competition()
    {
        return $this->belongsTo('App\Models\Competition', 'competition_id');
    }

    /**
     * Get the event that is related to schedule.
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    /**
     * Get game date when schedule is taken place.
     */
    public function gameDate()
    {
        $game_timestamp = strtotime($this->game_date);
        $year = date('Y', $game_timestamp);
        $month = date('n', $game_timestamp);
        $day = date('j', $game_timestamp);
        return $year . '년 ' . $month . '월 ' . $day . '일';
    }

    /**
     * Get teams that is related to schedule.
     */
    public function team1()
    {
        return $this->belongsTo('App\Models\Team', 'team1_id');
    }
    public function team2()
    {
        return $this->belongsTo('App\Models\Team', 'team2_id');
    }

    /**
     * Get result that is related to schedule.
     */
    public function result()
    {
        $result = Result::where('schedule_id', $this->id)->first();
        return $result;
    }
}
