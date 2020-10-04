<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Competition;

class CompetitionObserver
{
    public function created(Competition $competition)
    {
    	$event_count = count(explode(',', $competition->event_ids));
        if ( $competition->start_date == $competition->end_date && $event_count == 1 ) {
            // $competition->update(['competition_name' => trans('common.individual_game')]);

            $competition->competition_name = $this->get_slug($competition->competition_name);
            $competition->save();
        }
    }

    // it should be replaced cvierbrock's Eloquent-Sluggable package
    public function get_slug($competition_name) {
        $slug = $competition_name;
        $slug_count = count( Competition::whereRaw("competition_name REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
        return ($slug_count > 1) ? "{$slug}-{$slug_count}" : $slug;
    }

}
