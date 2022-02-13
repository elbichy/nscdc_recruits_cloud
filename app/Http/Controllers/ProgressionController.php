<?php

namespace App\Http\Controllers;

use App\Models\Progression;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use RealRashid\SweetAlert\Facades\Alert;

class ProgressionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $personnel)
    {
        $validation = $request->validate([
            'type' => 'required',
            'cadre' => 'required',
            'gl' => 'required',
            'effective_date' => 'required'
        ]);

        $rank = Rank::where('cadre', $request->cadre)->where('gl', $request->gl)->first();

        $progressions = $personnel->progressions()->create([
            'type' => $request->type,
            'cadre' => $request->cadre,
            'gl' => $request->gl,
            'rank_full' => $rank->full_title,
            'rank_short' => $rank->short_title,
            'effective_date' => $request->effective_date
        ]);
        if($progressions){
            $personnel->update([
                'cadre' => $request->cadre,
                'gl' => $request->gl,
                'rank_full' => $rank->full_title,
                'rank_short' => $rank->short_title,
                'dopa' => $request->effective_date
            ]);
            Alert::success('Progression record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Progression  $progression
     * @return \Illuminate\Http\Response
     */
    public function show(Progression $progression)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Progression  $progression
     * @return \Illuminate\Http\Response
     */
    public function edit(Progression $progression)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Progression  $progression
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Progression $progression)
    {
        $rank = Rank::where('cadre', $request->cadre)->where('gl', $request->gl)->first();
        $update = $progression->update([
            'type' => $request->type,
            'cadre' => $request->cadre,
            'gl' => $request->gl,
            'rank_full' => $rank->full_title,
            'rank_short' => $rank->short_title,
            'effective_date' => $request->effective_date
        ]);
        if($update){
            Alert::success('Progression record updated successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Progression  $progression
     * @return \Illuminate\Http\Response
     */
    public function destroy(Progression $progression)
    {
        if($progression->delete()){
            Alert::success('Progression record deleted successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }
}
