<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

	/**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'files';

	public function hash() {
		$this->hash = md5($this->schedule_id . '_' . $this->name);
	}
}
