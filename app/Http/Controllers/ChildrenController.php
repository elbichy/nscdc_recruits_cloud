<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ChildrenController extends Controller
{

    public function store(Request $request, User $personnel)
    {
        // return $request;
        $validation = $request->validate([
            'child_name' => 'required',
            'child_sex' => 'required',
            'child_dob' => 'required'
        ]);
        $children = $personnel->children()->create([
            'name' => $request->child_name,
            'sex' => $request->child_sex,
            'dob' => $request->child_dob
        ]);
        if($children){
            Alert::success('Child record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nok  $nok
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Children $child)
    {
        $update = $child->update([
            'name' => $request->child_name,
            'sex' => $request->child_sex,
            'dob' => $request->child_dob
        ]);
        if($update){
            Alert::success('Child record updated successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nok  $nok
     * @return \Illuminate\Http\Response
     */
    public function destroy(Children $child)
    {
        if($child->delete()){
            Alert::success('Child record deleted successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }
    }
}
