<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Permission;
use App\Models\Zone;
use App\Models\Room;
use App\Models\Event;

class EmployeeController extends Controller
{
    public function index()
    {
        

        $users = User::all();
        $zones = Zone::all();
        $rooms = Room::all();

        return view('pages.admin.employee', 
            [
                'page'        => 'employee',
                'users'       => $users,
                'zones'       => $zones,
                'rooms'       => $rooms,
                'permissions' => Permission::all()
            ]
        );
        
    }

    public function store(Request $request)
    {
        $user = new User;
        $user->name             = $request->input('name');
        $user->username         = $request->input('username');
        $user->sex              = $request->input('sex');
        $user->zone_id          = $request->input('zone');
        $user->birthday         = $request->input('birthday');
        $user->room_id          = $request->input('room');
        $user->belong           = $request->input('belong');
        $user->community        = $request->input('community');
        $user->role_id          = $request->input('role');
        $user->special_event_id = $request->input('special_event');

        $user->save();
        
        return redirect()->route("employee.index");
        
    }

    public function edit(Request $request, $user_id = 0)
    {
        $user = User::find($user_id);

        if (empty($user)) {
            return redirect()->route("employee.index");
        }

        $zones = Zone::all();
        $rooms = Room::all();

        $community_counts = 0;
        if ( $user->belong == '로동당' ) {
            $community_counts = 2;
        } elseif ( $user->belong == '직맹' ) {
            $community_counts = 2;
        } elseif ( $user->belong == '청년동맹' ) {
            $community_counts = 9;
        }

        $skills = $user->special_event_id;

        return view('pages.admin.employeeedit', 
            [
                'page'        => 'employeeedit',
                'user'        => $user,
                'zones'       => $zones,
                'rooms'       => $rooms,
                'community_counts'  => $community_counts,
                'skills' => $skills,
                'permissions'       => Permission::all(), 
            ]
        );
    }

    public function update(Request $request, $user_id = 0)
    {
        $user = User::find($user_id);

        if (empty($user)) {
            return redirect()->route("employee.index");
        }
    }

    public function show(Request $request) {

    }

    public function destroy($user_id)
    {
        
    }

    /**
    * Search Employee Skills (employee/search_skills) [AJAX]
    *
    * @author Pak Yong Hak
    * @since Oct 15, 2018
    * @version 1.0
    * @param  Request $request
    * @return Response
    */
    public function searchSkills(Request $request)
    {
        $result = [];

        if ( $request->input('ids') ) {

            $skills = Event::whereIn('id', explode(',', $request->input('ids')))->get()->all();

        } else {

            $skills = Event::where('event_name', 'like', '%' . $request->input('q') . '%')->orderby('event_name', 'asc')->get()->all();

        }

        if ( count($skills) > 0 ) {
            foreach ( $skills as $skill ) {
                $result[] = [
                    'id' => $skill->id, 
                    'title' => $skill->event_name
                ];
            }
        }

        return response()->json($result);
    }

    public function editsave(Request $request, $user_id = 0)
    {
        $user = User::find($user_id);

        if (empty($user)) {
            return redirect()->route("employee.index");
        }

        $user->name             = $request->input('name');
        $user->username         = $request->input('username');
        $user->sex              = $request->input('sex');
        $user->zone_id          = $request->input('zone');
        $user->birthday         = $request->input('birthday');
        $user->room_id          = $request->input('room');
        $user->belong           = $request->input('belong');
        $user->community        = $request->input('community');
        $user->role_id          = $request->input('role');
        $user->special_event_id = $request->input('special_event');

        $user->save();
        
        return redirect()->route("employee.index");
        
    }

}