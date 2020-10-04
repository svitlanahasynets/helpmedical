<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

	protected $table = 'events';
	
	public $timestamps = false;

	public static $ball_event_ids = [ 1, 2, 3, 5, 13, 15 ];
}
