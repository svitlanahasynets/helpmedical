<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

	protected $table = 'permissions';
    
    const ROLE_BOSS = 'B';
    const ROLE_ADVISOR = 'C';
    const ROLE_PUBLIC = 'D';
    const ROLE_MANAGER = 'M';
}
