<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMode extends Model
{

	//protected $table = 'team_modes';

	const ROOM    		= 1;
    const ZONE    		= 2;
    const CELL    		= 3;
    const FLOOR   		= 4;
    const YOUTH  		= 5;
    const INDIVIDUAL   	= 6;
}
