<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class QualificationController extends Controller
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
            'qualification' => 'required',
            'institution' => 'required',
            'year_commenced' => 'required',
            'year_obtained' => 'required'
        ]);
        $qualifications = $personnel->qualifications()->create([
            'qualification' => $request->qualification,
            'course' => $request->course,
            'institution' => $request->institution,
            'grade' => $request->grade,
            'year_commenced' => $request->year_commenced,
            'year_obtained' => $request->year_obtained
        ]);
        if($qualifications){
            Alert::success('Qualification record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Qualification  $qualification
     * @return \Illuminate\Http\Response
     */
    public function show(Qualification $qualification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Qualification  $qualification
     * @return \Illuminate\Http\Response
     */
    public function edit(Qualification $qualification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Qualification  $qualification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Qualification $qualification)
    {
        $update = $qualification->update([
            'qualification' => $request->qualification,
            'course' => $request->course,
            'institution' => $request->institution,
            'grade' => $request->grade,
            'year_commenced' => $request->year_commenced,
            'year_obtained' => $request->year_obtained
        ]);
        if($update){
            Alert::success('Qualification record updated successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Qualification  $qualification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Qualification $qualification)
    {
        if($qualification->delete()){
            Alert::success('Qualification record deleted successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }
}
