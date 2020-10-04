<?php
namespace App\Http\Creators;

use Illuminate\View\View;

use Config;
use Route;
use Auth;


class BreadcrumbsCreator
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
            'admin.login',
            'homepage',
            'competition.overview',
            'schedule.overview',
        );

        if (in_array(Route::currentRouteName(), $escapes)) 
            return;

        $current_route = explode('::', Route::currentRouteName());

        $menu = config('menu.menu');
        $user = Auth::User();
        $user_role = $user->permission->role;

        $menu = $menu[$user_role];

        $breadcrumbs = array();

        $breadcrumbs[] = array(
            'title' => '<i class="fa fa-home"></i> ' . $menu['dashboard']['title']
        );

        foreach ($current_route as $key => $route) {

            if ($route == 'dashboard') continue;
            
            if ($key == 0) {
                $menu = $menu[$route];
            } else {
                $menu = $menu['children'][$route];
            }

            $breadcrumbs[] = array(
                'title' => $menu['title']
            );
        }

        view()->share('breadcrumbs', $breadcrumbs);
        view()->share('main_title', strip_tags($breadcrumbs[count($breadcrumbs) - 1]['title']));
    }
}