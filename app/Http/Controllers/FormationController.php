<?php

namespace App\Http\Controllers;

use App\Charts\FormationChart;
use App\Charts\GenderChart;
use App\Charts\MaritalStatusChart;
use App\Charts\RankChart;
use App\Formation;
use App\Rank;
use App\Redeployment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use \Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class FormationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super admin'])) {
            abort(401);
        }
        $formations = Formation::withCount('users')->get();
        return view('administration.dashboard.formation.all', compact(['formations']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('administration.dashboard.formation.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'type' => 'required|string',
            'formation' => 'required|string'
        ]);
       
        $formation = Formation::create([
            'type' => $request->type,
            'formation' => $request->formation,
            'level' => $request->level
        ]);

        if($formation){
            Alert::success('Formation record added successfully!', 'Success!')->autoclose(222500);
            return back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($formation)
    {
        $formation = Formation::where('formation', $formation)->first();
        if(auth()->user()->current_formation != $formation->formation && !auth()->user()->hasAnyRole(['super admin'])){
            abort(401, 'You can only view your formation');
        }
        return view('administration.dashboard.formation.manage', compact('formation'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_all($formation)
    {
        // $cmnd = Formation::find($formation);
        // return $personnel = $cmnd->users;

        $personnel = User::where('current_formation', $formation)->orderByRaw("FIELD(rank_full, 'Commandant General of Corps', 'Deputy Commandant General of Corps', 'Assistant Commandant General of Corps', 'Commandant of Corps', 'Deputy Commandant of Corps', 'Assistant Commandant of Corps', 'Chief Superintendent of Corps', 'Superintendent of Corps', 'Deputy Superintendent of Corps', 'Assistant Superintendent of Corps I', 'Assistant Superintendent of Corps II', 'Chief Inspector of Corps', 'Deputy Chief Inspector of Corps', 'Assistant Chief Inspector of Corps', 'Principal Inspector of Corps I', 'Principal Inspector of Corps II', 'Senior Inspector of Corps', 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->orderBy('service_number', 'ASC')->get();

        return DataTables::of($personnel)
            ->editColumn('name', function ($personnel) {
                return "<b><a href=\"/administration/dashboard/personnel/$personnel->id\">$personnel->name</a></b>";
            })
            ->addColumn('checkbox', function($redeployment) {
                return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
            })
            ->rawColumns(['name', 'checkbox'])
            ->make();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    // SHOW PERSONNEL STATS
    public function stats($formation)
    {
        $total_personnel = User::where('current_formation', $formation)->count();
        $total_formations = 0;
        $total_redeployments = Redeployment::count();
        $internal = Redeployment::where('to', 'LIKE', '%'.$formation.'%')->count();
        $external = Redeployment::where('from', 'LIKE', '%'.$formation.'%')->count();

        $formations = Formation::where('formation', $formation)->withCount('users')->pluck('users_count','formation');
        $formationChart = new FormationChart;
        $formationChart->labels($formations->keys());
        $formationChart->dataset('Formation', 'bar', $formations->values())->options([
            'backgroundColor' => 'red'
        ]);


        $ranks = Rank::withCount(['users' => function($q) use($formation) {
            $q->where('current_formation', $formation);
        }])->orderBy('gl', 'ASC')->pluck('users_count','gl');
        $keys = [];
        foreach($ranks->keys() as $gl){
            $Rtitle = Rank::where('gl', $gl)->get();
            $Rtitle->count() > 1 ? array_push($keys, 'GL'.$gl." (".$Rtitle[0]->short_title.' & '.$Rtitle[1]->short_title.")") : array_push($keys, 'GL'.$gl." (".$Rtitle[0]->short_title.")");
        }
        $rankChart = new RankChart;
        $rankChart->labels($keys);
        $rankChart->dataset('Rank', 'horizontalBar', $ranks->values())->options([
            'backgroundColor' => '#039be5',
            'hoverBackgroundColor' => '#0e75a7'
        ]);



        $male = User::where('current_formation', $formation)->where('sex', 'male')->count();
        $female = User::where('current_formation', $formation)->where('sex', 'female')->count();
        $null = User::where('current_formation', $formation)->where('sex', null)->count();
        $genderChart = new GenderChart;
        $genderChart->labels(['Male', 'Female', 'Null']);
        $genderChart->dataset('Rank', 'doughnut', [$male, $female, $null])->options([
            'backgroundColor' => [
                '#01579b',
                '#e91e63',
                '#00695c',
            ]
        ]);

        // "Married",
        // "Divorced",
        // "Single",
        // "",
        // "Widowed"

        $marital_status = [];
        $ms = User::where('current_formation', $formation)->distinct()->pluck('marital_status');
        foreach($ms as $status){
            $marital_status[$status] = User::where('current_formation', $formation)->where('marital_status', $status)->count();
        }
        $marital_status = collect($marital_status);
        $maritalStatusChart = new MaritalStatusChart;
        $maritalStatusChart->labels($marital_status->keys());
        $maritalStatusChart->dataset('Rank', 'pie', $marital_status->values())->options([
            'backgroundColor' => [
                '#43a047',
                '#e53935',
                '#3949ab',
                '#9e9e9e',
                '#8e24aa'
            ]
        ]);

        return view('administration.dashboard.formation.stats', compact(['total_personnel', 'total_formations', 'total_redeployments', 'internal', 'external', 'formationChart', 'rankChart', 'genderChart', 'maritalStatusChart']));
    }
}
