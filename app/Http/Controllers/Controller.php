<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Auth;
use App\Models\SloganTitle;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
    * Constructor
    */
    public function __construct() {

        $slogan_titles = SloganTitle::getSettings();

        view()->share('slogan_titles', $slogan_titles);
    }
}
