<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Competition;
use App\Models\Event;
use App\Models\Team;
use App\Models\File;
use App\Models\Schedule;

class Result extends Model
{

	
	use SoftDeletes;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'results';

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
     * Get winner team that is related to result.
     */
    public function winner()
    {
        return $this->belongsTo('App\Models\Team', 'winner_team_id');
    }

    /**
     * Get loser team that is related to result.
     */
    public function loser()
    {
        return $this->belongsTo('App\Models\Team', 'loser_team_id');
    }

    /**
     * Get files that is related to result.
     */
    public function files()
    {
        $file_ids = [];
        $file_ids = explode(',', $this->file_ids);
        $files = File::whereIn('id', $file_ids)->get()->all();
        return $files;
    }

    /**
     * Get schedule that corresponds to result.
     */
    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule', 'schedule_id');
    }
}
