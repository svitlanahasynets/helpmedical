<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Permission;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $admin = Admin::find(1);

        return view('pages.admin.profile', 
            [
                'page'        => 'profile',
                'admin'       => $admin,  
                'permissions' => Permission::all()
            ]
        );
        
    }

    public function update(Request $request)
    {
        $admin = Admin::find(1);

        $username = $request->input('username');
        $password = $request->input('password');

        $admin->username = $username;
        $admin->password = Hash::make($password);
        if ( $admin->save() ) {
            Request()->session()->flash('success', '관리자정보갱신이 성과적으로 진행되였습니다.');
        } else {
            Request()->session()->flash('fail', '관리자정보갱신이 실패하였습니다.');
        }

        return redirect()->route("profile");
    }

}