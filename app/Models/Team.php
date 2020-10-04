<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\TeamMode;
use App\Models\Room;
use App\Models\Zone;
use App\Models\Cell;
use App\Models\Floor;
use App\Models\YouthCommunity;
use App\Models\User;

class Team extends Model
{

	//protected $table = 'teams';

	/**
     * Get team members of the team.
     */
    public function teamMembersInfo($event_id = '')
    {
    	$team_members = [];
    	$team_members_info = [];
    	$team_members_name_array = [];
    	$team_members_name = '';
    	$team_members_count = 0;
        $team_special_members = [];
        $team_special_members_str = '';
        $index = '';

        $team_setting_mode = $this->team_mode_id;
        $team_member_ids_str  = $this->team_member;
        $team_member_ids = [];
        $team_member_ids = explode(',', $team_member_ids_str);

        if ( $team_setting_mode == TeamMode::ROOM ) {
        	$team_members = Room::whereIn('id', $team_member_ids)->get()->all();
        	foreach ($team_members as $key => $team_member) {
        		$team_members_name_array[$key] = $team_member->room_name;
        	}
        	$team_members_count = User::whereIn('room_id', $team_member_ids)->count();

            $index = 'room_id';
            $team_special_members_str = $this->specialInfo($event_id, $index, $team_member_ids);
        } elseif ( $team_setting_mode == TeamMode::ZONE ) {
        	$team_members = Zone::whereIn('id', $team_member_ids)->get()->all();
        	foreach ($team_members as $key => $team_member) {
        		$team_members_name_array[$key] = $team_member->zone_name;
        	}
        	$team_members_count = User::whereIn('zone_id', $team_member_ids)->count();

            $index = 'zone_id';
            $team_special_members_str = $this->specialInfo($event_id, $index, $team_member_ids);
        } elseif ( $team_setting_mode == TeamMode::CELL ) {
        	$team_members = Cell::whereIn('id', $team_member_ids)->get()->all();
        	foreach ($team_members as $key => $team_member) {
        		$team_members_name_array[$key] = $team_member->cell_name;
        	}
        	$room_id_array = Room::whereIn('cell_id', $team_member_ids)->pluck('id')->all();
        	$team_members_count = User::whereIn('room_id', $room_id_array)->count();

            $index = 'room_id';
            $team_special_members_str = $this->specialInfo($event_id, $index, $room_id_array);
        } elseif ( $team_setting_mode == TeamMode::FLOOR ) {
        	$team_members = Floor::whereIn('id', $team_member_ids)->get()->all();
        	foreach ($team_members as $key => $team_member) {
        		$team_members_name_array[$key] = $team_member->floor_name;
        	}
        	$room_id_array = Room::whereIn('floor_id', $team_member_ids)->pluck('id')->all();
        	$team_members_count = User::whereIn('room_id', $room_id_array)->count();

            $index = 'room_id';
            $team_special_members_str = $this->specialInfo($event_id, $index, $room_id_array);
        } elseif ( $team_setting_mode == TeamMode::YOUTH ) {
            $team_members = YouthCommunity::whereIn('id', $team_member_ids)->get()->all();
            foreach ($team_members as $key => $team_member) {
                $team_members_name_array[$key] = $team_member->community_name;
            }
            $user_ids = User::where('belong', '청년동맹')->whereIn('community', $team_member_ids)->pluck('id')->all();
            $team_members_count = count($user_ids);

            $index = 'community';
            $team_special_members_str = $this->specialInfo($event_id, $index, $team_member_ids);
        } elseif ( $team_setting_mode == TeamMode::INDIVIDUAL ) {
        	$team_members = User::whereIn('id', $team_member_ids)->get()->all();
        	foreach ($team_members as $key => $team_member) {
        		$team_members_name_array[$key] = $team_member->name;
        	}
        	$team_members_count = count($team_member_ids);

            $index = 'id';
            $team_special_members_str = $this->specialInfo($event_id, $index, $team_member_ids);
        }

        $team_members_name = implode(',', $team_members_name_array);

        $team_members_info['name']      = $team_members_name;
        $team_members_info['count']     = $team_members_count;
        $team_members_info['special']   = $team_special_members_str;

        return $team_members_info;
    }

    private function specialInfo($event_id, $index, $team_member_ids) {
        $team_special_members_str = '';
        $team_special_members = User::whereIn($index, $team_member_ids);
        if ( $index == 'community' ) {
            $team_special_members = $team_special_members->where('belong', '청년동맹');
        }
        $team_special_members = $team_special_members->whereNotNull('special_event_id')->get()->all();

        foreach ($team_special_members as $key => $team_special_member) {
            if ( $event_id == '' ) {
                $team_special_members_str = $team_special_members_str . $team_special_member->name . ',';
            } else {
                $special_event_ids = explode(',', $team_special_member->special_event_id);
                if ( in_array($event_id, $special_event_ids) ) {
                    $team_special_members_str = $team_special_members_str . $team_special_member->name . ',';
                }
            }
        }

        return $team_special_members_str;
    }
}
