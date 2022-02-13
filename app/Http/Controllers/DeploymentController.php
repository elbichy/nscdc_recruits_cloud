<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use App\Models\Formation;
use App\Models\FormationUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Illuminate\Support\Facades\Gate;
use RealRashid\SweetAlert\Facades\Alert;

class DeploymentController extends Controller
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
    public function store(Request $request, User $personnel, DNS2D $dNS2D)
    {
        $validation = $request->validate([
            'command' => 'required',
            'from' => 'required'
        ]);


        $command = Formation::where('id', $request->command)->pluck('formation')->first();
        $deployment = $personnel->formations()->attach($request->command, [
            'command' => $command,
            'department' => $request->department,
            'designation' => $request->designation,
            'from' => $request->from,
            'to' => $request->to == Carbon::today()->format('Y-m-d') ? $request->to : $request->to
        ]);
        
        $old_formation_type = Formation::where('formation', $personnel->current_formation)->pluck('type')->first();
        $new_formation_type = Formation::where('formation', $command)->pluck('type')->first();

        // $ref_number = Str::random(12);
        // $insert = auth()->user()->redeployments->create([
        //     'type' => $old_formation_type == 'nhq' && $new_formation_type == 'nhq' ? 'internal' : 'external',
        //     'fullname' => $personnel->name,
        //     'service_number' => $personnel->service_number,
        //     'ref_number' => $ref_number,
        //     'rank' => $personnel->rank_full,
        //     'from' => $old_formation_type == 'state' ? $personnel->current_formation.' State Command' : $personnel->current_formation,
        //     'to' => $new_formation_type == 'state' ? $command.' State Command' : $command,
        //     'designation' => $request->designation,
        //     // 'reason' => $request->reason,
        //     // 'signatory' => $request->signatory,
        //     'barcode' => $dNS2D->getBarcodePNG("Kindly follow the link below to verify the document <br/>http://redeployment.nscdc.gov.ng/redeployment/$ref_number", 'QRCODE', 10,10),
        //     'created_at' => Carbon::today()->format('Y-m-d'),
        // ]);
        
        $updated = $personnel->update([
            'current_formation' => $command,
            'current_department' => $request->department != '' ? 'N/A' : $request->department
        ]);
        

        if($updated){
            Alert::success('Deployment record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function show(Deployment $deployment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function edit(Deployment $deployment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormationUser $deployment)
    {
        // return FormationUser::find($deployment);
        $command = Formation::where('id', $request->command)->pluck('formation')->first();
        $update = $deployment->update([
            'formation_id' => $request->command,
            'command' => $command,
            'department' => $request->department,
            'designation' => $request->designation,
            'from' => $request->from,
            'to' => $request->to
        ]);
        if($update){
            Alert::success('Deployment record updated successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormationUser $deployment)
    {
        if($deployment->delete()){
            Alert::success('Deployment record deleted successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }
}
