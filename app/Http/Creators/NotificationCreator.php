<?php
namespace App\Http\Creators;

use Illuminate\View\View;

use App\Models\Balance;
use App\Models\Company;
use App\Models\Pref;

use Config;
use Route;
use Auth;


class NotificationCreator
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

        $user = Auth::user();
        $message = "";
        /*$errors = array(
            'warning' => '',
            'danger' => ''
        );

        if (in_array($user->permission->role, array('B', 'C'))) {
            //NAMPO
            $total_balance1 = Pref::getDepositAmount(Company::UNJONG1, 1) - Pref::getDeliverAmount(Company::UNJONG1, 1);
            $total_balance2 = Pref::getDepositAmount(Company::UNJONG1, 2) - Pref::getDeliverAmount(Company::UNJONG1, 2);

            $balance1 = Balance::where('oil_type', 1)
                                ->where('company_id', '=', Company::NAMPO)
                                ->where('balance', '>', 0)
                                ->where('cost', '>', 0)
                                ->sum('balance');
            $balance2 = Balance::where('oil_type', 2)
                                ->where('company_id', '=', Company::NAMPO)
                                ->where('balance', '>', 0)
                                ->where('cost', '>', 0)
                                ->sum('balance');
            $total_balance1 = $total_balance1 / 1000;
            $total_balance2 = $total_balance2 / 1000;
            $balance1 = $balance1 / 1000;
            $balance2 = $balance2 / 1000;
            if ($total_balance1 < 10) {
                $errors['danger'] .= "<p>은정1에 휘발유 재고량이 {$total_balance1}톤 남았습니다.</p>";
            } else if ($total_balance1 < 50) {
                $errors['warning'] .= "<p>은정1에 휘발유 재고량이 {$total_balance1}톤 남았습니다.</p>";
            }

            if ($balance1 < 10) {
                $errors['danger'] .= "<p>남포창에 원가가 입력된 휘발유 재고량이 {$balance1}톤 남았습니다.</p>";
            } else if ($balance1 < 50) {
                $errors['warning'] .= "<p>남포창에 원가가 입력된 휘발유 재고량이 {$balance1}톤 남았습니다.</p>";
            }


            if ($total_balance2 < 10) {
                $errors['danger'] .= "<p>은정1에 디젤유 재고량이 {$total_balance2}톤 남았습니다.</p>";
            } else if ($total_balance2 < 50) {
                $errors['warning'] .= "<p>은정1에 디젤유 재고량이 {$total_balance2}톤 남았습니다.</p>";
            }
            
            if ($balance2 < 10) {
                $errors['danger'] .= "<p>남포창에 원가가 입력된 디젤유 재고량이 {$balance2}톤 남았습니다.</p>";
            } else if ($balance2 < 50) {
                $errors['warning'] .= "<p>남포창에 원가가 입력된 디젤유 재고량이 {$balance2}톤 남았습니다.</p>";
            }
            
        } */

        //Getting Current Balance
       /* 

        foreach ($errors as $c => $e) {
            if (!empty($e)) {
                $message .= '<div class="alert alert-' . $c . '">' . $e . '</div>';
            }
        }*/
        $message = '';
        $alert = session()->get('alert', '');
        if (!empty($alert)) {
            $message .= '<div class="alert alert-warning">' . $alert . '</div>';
        }

        view()->share('balance_notification', $message);

    }
}