<?php

namespace App\Http\Controllers;

use App\Models\Nok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use RealRashid\SweetAlert\Facades\Alert;

class NokController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $personnel)
    {
        $validation = $request->validate([
            'nok_name' => 'required',
            'relationship' => 'required',
            'address' => 'required',
            'nok_phone' => 'required'
        ]);
        $nok = $personnel->noks()->create([
            'name' => $request->nok_name,
            'relationship' => $request->relationship,
            'address' => $request->address,
            'phone' => $request->nok_phone
        ]);
        if($nok){
            Alert::success('Next of kin record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nok  $nok
     * @return \Illuminate\Http\Response
     */
    public function show(Nok $nok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nok  $nok
     * @return \Illuminate\Http\Response
     */
    public function edit(Nok $nok)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nok  $nok
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nok $nok)
    {
        $update = $nok->update([
            'name' => $request->nok_name,
            'relationship' => $request->relationship,
            'phone' => $request->nok_phone,
            'address' => $request->nok_address
        ]);
        if($update){
            Alert::success('Next of Kin record updated successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nok  $nok
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nok $nok)
    {
        if($nok->delete()){
            Alert::success('Next of kin record deleted successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }
}
