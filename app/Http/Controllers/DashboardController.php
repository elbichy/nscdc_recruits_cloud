<?php

namespace App\Http\Controllers;

use App\Charts\FormationChart;
use App\Charts\GenderChart;
use App\Charts\MaritalStatusChart;
use App\Charts\RankChart;
use App\Charts\SooChart;
use App\Models\Formation;
use App\Models\Rank;
use App\Models\State;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $commissioned = User::whereDate('dofa', '=', '2022-01-31')->where('cadre', 'superintendent')->count();
        $other_ranks = User::whereDate('dofa', '=', '2022-01-31')->where('cadre', '!=', 'superintendent')->count();
        $total_personnel = User::whereDate('dofa', '2022-01-31')->count();
        $admin = User::whereDate('dofa', '!=', '2022-01-31')->count();

        $male = User::whereDate('dofa', '=', '2022-01-31')->where('sex', 'male')->count();
        $female = User::whereDate('dofa', '=', '2022-01-31')->where('sex', 'female')->count();

        $null = User::whereDate('dofa', '=', '2022-01-31')->where('sex', 'others')->count();
        $genderChart = new GenderChart;
        $genderChart->labels(['Male', 'Female', 'Other']);
        $genderChart->dataset('Sex', 'doughnut', [$male, $female, $null])->options([
            'backgroundColor' => [
                '#01579b',
                '#e91e63',
                '#00695c',
            ]
        ]);

        $marital_status = [];
        $ms = User::whereDate('dofa', '=', '2022-01-31')->distinct()->pluck('marital_status');
        
        foreach($ms as $status){
            $marital_status[ucfirst($status)] = User::where('marital_status', $status)->count();
        }

        $marital_status = collect($marital_status);

        $maritalStatusChart = new MaritalStatusChart;
        $maritalStatusChart->labels($marital_status->keys());
        $maritalStatusChart->dataset('Rank', 'pie', $marital_status->values())->options([
            'backgroundColor' => [
                '#27ad83',
                '#e53935',
                '#3949ab',
                '#9e9e9e',
                '#8e24aa',
                '#2e15aa'
            ]
        ]);

        $ranks = Rank::withCount('users')->orderBy('gl', 'ASC')->pluck('users_count','gl');
        $keys = [];
        foreach($ranks->keys() as $gl){
            $Rtitle = Rank::where('gl', $gl)->get();
            $Rtitle->count() > 1 ? array_push($keys, 'GL'.$gl." (".$Rtitle[0]->short_title.' & '.$Rtitle[1]->short_title.")") : array_push($keys, 'GL'.$gl." (".$Rtitle[0]->short_title.")");
        }
        
        $rankChart = new RankChart;
        $rankChart->labels($keys);
        $rankChart->dataset('Rank', 'horizontalBar', $ranks->values())->options([
            'backgroundColor' => '#0e75a7'
        ]);

        // $formations = Formation::withCount('users')->pluck('users_count','formation');
        // $formationChart = new FormationChart;
        // $formationChart->labels($formations->keys());
        // $formationChart->dataset('Formation', 'horizontalBar', $formations->values())->options([
            
        // ]);
        

        $soo = State::withCount(['users' => function($q){
            $q->whereDate('dofa', '=', '2022-01-31');
        }])->pluck('users_count','state_name');
        $sooChart = new SooChart;
        $sooChart->labels($soo->keys()->map(function ($value){
            return strtoupper($value);
        }));
        $sooChart->dataset('States', 'horizontalBar', $soo->values())->options([
            'backgroundColor' => '#27ad83'
        ]);

        return view('dashboard.dashboard', compact(['total_personnel', 'commissioned', 'other_ranks', 'admin', 'rankChart', 'genderChart', 'maritalStatusChart', 'sooChart']));
    }

}
