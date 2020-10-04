<?php
namespace App\Http\Creators;

use Illuminate\View\View;

use Config;
use Route;
use Auth;

// use App\Models\Company;

class MenuCreator
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
    */
    public function create(View $view)
    {
        if (!Auth::check()) return;

        $escapes = array(
            'account::getRecords'
        );

        if (in_array(Route::currentRouteName(), $escapes)) 
            return;
        
        $current_route = Route::currentRouteName();

        $menu = config('menu.menu');
        $user = Auth::User();
        $user_role = $user->permission->role;

        $main_menu = $menu[$user_role];

        foreach ($main_menu as $key => $menu) {
            $route_name = $key;

            if ($key == $current_route) {
                $main_menu[$key]['active'] = true;
            } else {
                $main_menu[$key]['active'] = false;
            }

            if (isset($main_menu[$key]['hidden'])) {                            
                if (isset($main_menu[$key]['proxy'])) {
                    $_proxy_menu = $main_menu[$key]['proxy'];
                    if ($route_name == $current_route) {
                        $main_menu[$_proxy_menu]['active'] = true;
                    }
                }

                $main_menu[$key] = null;
                unset($main_menu[$key]);
                continue;
            } else {
                $main_menu[$key]['url'] = route($key);
            } 
        }



        view()->share('main_menu', $main_menu);

        view()->share('user', $user);
    }
}