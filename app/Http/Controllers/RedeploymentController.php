<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\Redeployment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Formation;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Rap2hpoutre\FastExcel\FastExcel;

class RedeploymentController extends Controller
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
    
    // CREATE NEW RECORDS
    public function create(){
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        $formations = Formation::all();
        return view('dashboard.redeployment.new', compact(['formations']));
    }

    // STORE NEW RECORDS
    public function store(Request $request, DNS2D $dNS2D)
    {
        $validation = $request->validate([
            'type' => 'required',
            'fullname' => 'required',
            'service_number' => 'required',
            'rank' => 'required',
            'from' => 'required',
            'to' => 'required',
            'financial_implication' => 'required',
            'incharge' => 'required',
            'signatory' => 'required',
            'date' => 'required'
        ]);

        $ref_number = Str::random(12);
        $rank = Rank::where('short_title', $request->rank)->first();
        if($request->date == Carbon::today()->format('Y-m-d')){
            $date = Carbon::now();
        }else{
            $date = $request->date;
        }
        $insert = auth()->user()->redeployments()->create([
            'type' => $request->type,
            'fullname' => $request->fullname,
            'service_number' => $request->service_number,
            'ref_number' => $ref_number,
            'rank' => $rank->full_title,
            'rank_acronym' => $request->rank,
            'from' => $request->from,
            'to' => $request->to,
            'designation' => $request->designation,
            'reason' => $request->reason,
            'incharge' => $request->incharge,
            'signatory' => $request->signatory,
            'financial_implication' => $request->financial_implication,
            'barcode' => $dNS2D->getBarcodePNG("Kindly follow the link below to verify the document <br/>http://admindb.nscdc.gov.ng/redeployment/$ref_number", 'QRCODE', 10,10),
            'created_at' => $date,
        ]);

        if($insert){
            // $request->session()->flash('success', 'Task was successful!');
            Alert::success('Redeployment created successfully!', 'Success!')->autoclose(2500);
            return redirect()->back();
        }else{
            // $request->session()->flash('error', 'Error processing task!');
            Alert::error('Something went wrong!', 'Error!')->autoclose(2500);
            return redirect()->back();
        }
    }
    // IMPORT STORE IMPORTED PROMOTION DATA
    public function store_imported_redeployment(Request $request)
    {  
        $request->validate([
            'import_file' => 'required',
            // 'date' => 'required'
        ]);
        
        $path = $request->file('import_file')->getRealPath();
        $data = (new FastExcel)->import($path);
        // dd($data[0]);
        if($data->count()){

            $candidates = (new FastExcel)->import($path, function ($line) {

                $dNS2D = new DNS2D();
                $ref_number = Str::random(12);

                $line['type'] == '' ? $type = null : $type = $line['type'];
                $line['service_number'] == '' ? $service_number = null : $service_number = $line['service_number'];
                $line['fullname'] == '' ? $fullname = null : $fullname = $line['fullname'];
                $line['rank'] == '' ? $rank = null : $rank = $line['rank'];
                $line['from'] == '' ? $from = null : $from = $line['from'];
                $line['to'] == '' ? $to = null : $to = $line['to'];
                $line['designation'] == '' ? $designation = null : $designation = $line['designation'];
                
                $user_id = auth()->user()->id;
                $full_rank = Rank::where('short_title', $rank)->first();
                $candidate = Redeployment::updateOrInsert(
                    ['ref_number' => $ref_number],
                    [
                    'user_id' => $user_id,
                    'type' => $type,
                    'service_number' => $service_number,
                    'fullname' => $fullname,
                    'ref_number' => $ref_number,
                    'rank' => $full_rank->full_title,
                    'rank_acronym' => $rank,
                    'from' => $from,
                    'to' => $to,
                    'designation' => $designation,
                    'barcode' => $dNS2D->getBarcodePNG("Kindly follow the link below to verify the document <br/>http://admindb.nscdc.gov.ng/redeployment/$ref_number", 'QRCODE', 10,10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                    ]
                );
            });
            Alert::success('Redeployment records imported successfully!', 'Success!')->autoclose(222500);
            return back();
        }
    }


    // SHOW TODAY RECORDS
    public function today()
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('dashboard.redeployment.today');
    }
    // FETCH TODAY RECORDS
    public function redeployment_today()
    {
        $redeployments = Redeployment::whereDate('created_at', Carbon::today())->orderBy('created_at', 'DESC')->get();
        return DataTables::of($redeployments)
                ->editColumn('created_at', function ($redeployment) {
                    return $redeployment->created_at->toFormattedDateString();
                })
                ->editColumn('fullname', function ($redeployment) {
                    return "<b><a href='/dashboard/redeployment/show/$redeployment->id'>$redeployment->fullname</a></b>";
                })
                ->addColumn('view', function($redeployment) {
                    if(!$redeployment->synched){
                        return '
                            <a href="/dashboard/redeployment/edit/'.$redeployment->id.'" style="margin-right:5px;" class="light-blue-text text-darken-3" title="edit record"><i class="fas fa-edit fa-lg"></i></a>
                            <a href="/dashboard/redeployment/generate_letter/'.$redeployment->id.'" class="light-blue-text text-darken-3" title="generate letter"><i class="fas fa-file-word fa-lg"></i></a>
                            <a href="/dashboard/redeployment/sync/'.$redeployment->ref_number.'" style="margin-left:5px;" class="red-text" title="push to cloud"><i class="fas fa-cloud-upload fa-lg"></i></a>
                        ';
                    }else{
                        return '
                            <a href="/dashboard/redeployment/edit/'.$redeployment->id.'" style="margin-right:5px;" class="light-blue-text text-darken-3" title="edit record"><i class="fas fa-edit fa-lg"></i></a>
                            <a href="/dashboard/redeployment/generate_letter/'.$redeployment->id.'" class="light-blue-text text-darken-3" title="generate letter"><i class="fas fa-file-word fa-lg"></i></a>
                            <span style="margin-left:5px;" class="green-text"><i class="fad fa-check-double fa-lg"></i></span>
                        ';
                    }
                })
                ->addColumn('checkbox', function($redeployment) {
                    return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
                })
                ->rawColumns(['view', 'checkbox', 'fullname'])
                ->make();
    }


    // SHOW ALL RECORDS
    public function all()
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('dashboard.redeployment.all');
    }
    // GET ALL RECORDS
    public function redeployments(){
        // $redeployments = Redeployment::get();
        $redeployments = Redeployment::orderBy('updated_at', 'DESC')->get();
        return DataTables::of($redeployments)
                ->editColumn('created_at', function ($redeployment) {
                    return $redeployment->created_at->toFormattedDateString();
                    // return $redeployment->created_at->toDateString();
                })
                ->editColumn('fullname', function ($redeployment) {
                    return "<b><a href='/dashboard/redeployment/show/$redeployment->id'>$redeployment->fullname</a></b>";
                })
                ->addColumn('view', function($redeployment) {
                    if(!$redeployment->synched){
                        return '
                            <a href="/dashboard/redeployment/edit/'.$redeployment->id.'" style="margin-right:5px;" class="light-blue-text text-darken-3" title="edit record"><i class="fas fa-edit fa-lg"></i></a>
                            <a href="/dashboard/redeployment/generate_letter/'.$redeployment->id.'" class="light-blue-text text-darken-3" title="generate letter"><i class="fas fa-file-word fa-lg"></i></a>
                            <a href="/dashboard/redeployment/sync/'.$redeployment->ref_number.'" style="margin-left:5px;" class="red-text" title="push to cloud"><i class="fas fa-cloud-upload fa-lg"></i></a>
                        ';
                    }else{
                        return '
                            <a href="/dashboard/redeployment/edit/'.$redeployment->id.'" style="margin-right:5px;" class="light-blue-text text-darken-3"><i class="fas fa-edit fa-lg" title="edit record"></i></a>
                            <a href="/dashboard/redeployment/generate_letter/'.$redeployment->id.'" class="light-blue-text text-darken-3"><i class="fas fa-file-word fa-lg"></i></a>
                            <span style="margin-left:5px;" class="green-text"><i class="fad fa-check-double fa-lg"></i></span>
                        ';
                    }
                })
                ->addColumn('checkbox', function($redeployment) {
                    return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
                })
                ->rawColumns(['view', 'checkbox', 'fullname'])
                ->make();
    }


    
    // DISPLAY SINGLE RECORD
    public function show(Redeployment $redeployment)
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('dashboard.redeployment.show', compact('redeployment', $redeployment));
    }
    
    // EDIT RECORD
    public function edit(Redeployment $redeployment)
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('dashboard.redeployment.edit', compact('redeployment', $redeployment));
    }
    // UPDATE EDITED RECORD
    public function update(Request $request, Redeployment $redeployment, DNS2D $dNS2D)
    {
        $validation = $request->validate([
            'type' => 'required',
            'fullname' => 'required',
            'service_number' => 'required',
            'rank' => 'required',
            'from' => 'required',
            'to' => 'required',
            'date' => 'required'
        ]);

        $redeployment->update([
            'type' => $request->type,
            'fullname' => $request->fullname,
            'service_number' => $request->service_number,
            'rank' => $request->rank,
            'from' => $request->from,
            'to' => $request->to,
            'designation' => $request->designation,
            'signatory' => $request->signatory,
            'barcode' => $dNS2D->getBarcodePNG("Kindly follow the link below to verify the document <br/>http://admindb.nscdc.gov.ng/redeployment/$request->ref_number", 'QRCODE', 10,10),
            'synched' => 0,
            'created_at' => $request->date,
            'updated_at' => Carbon::now('Africa/Lagos'),
        ]);
        Alert::success('Redeployment updated successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('redeployment_all');
    }
    // CHECK IF RECORD EXIST
    public function redeployment_check($redeployment)
    {
        $query = Redeployment::where('service_number', $redeployment);
        $count = $query->count();
        $records = $query->get();
        $personnel = User::where('service_number', $redeployment)->get();
        if($count > 0){
            return response()->json(['status' =>  true, 'count' => $count, 'records' => $records, 'personnel' => $personnel, 'message' => $count.' records found, <a href="'.route('redeployment_show_existing', $redeployment).'" target="_blank">View records</a>']);
        }else{
            return response()->json(['status' =>  false, 'count' => $count, 'records' => $records, 'personnel' => $personnel, 'message' => 'No record found']);
        }
    }
    // SHOW EXISTING RECORD
    public function show_existing($redeployment)
    {
        return Redeployment::where('service_number', $redeployment)->get();
    }
    // MOVE RECORD TO TRASH
    public function destroy(Redeployment $redeployment)
    {
        $redeployment->delete();
        Alert::success('Redeployment trashed successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('redeployment_all');
    }
    // BULK MOVE RECORD TO TRASH
    public function destroy_bulk(Request $request)
    {
        // return $request->personnel;
        $query = Redeployment::destroy($request->personnel);
        if($query){
            return response()->json(['status' => true, 'message' => 'Records moved to trash']);
        }else{
            return response()->json(['status' => false, 'message' => 'Fsiled to move records to trash']);
        }
    }
    // SHOW TRASHED RECORDS
    public function trash()
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('dashboard.redeployment.trash');
    }
    // FETCH TRASHED RECORDS
    public function redeployment_trash()
    {
        $redeployments = Redeployment::onlyTrashed()->orderBy('updated_at', 'DESC')->get();
        return DataTables::of($redeployments)
                ->editColumn('created_at', function ($redeployment) {
                    return $redeployment->created_at->toFormattedDateString();
                })
                ->editColumn('fullname', function ($redeployment) {
                    return "<b><a href='/dashboard/redeployment/show/$redeployment->id'>$redeployment->fullname</a></b>";
                })
                ->addColumn('view', function($redeployment) {
                    if(!$redeployment->synched){
                        return '
                            <a href="/dashboard/redeployment/trash/restore/'.$redeployment->ref_number.'" style="margin-left:5px;" class="green-text"><i style="font-size: 1.8rem;" class="medium material-icons">restore</i></a>
                            <a href="/dashboard/redeployment/trash/permanently/'.$redeployment->ref_number.'" style="margin-left:5px;" class="red-text"><i style="font-size: 1.8rem;" class="medium material-icons">delete_forever</i></a>
                        ';
                    }else{
                        return '
                            <a href="/dashboard/redeployment/trash/restore/'.$redeployment->ref_number.'" style="margin-left:5px;" class="green-text"><i style="font-size: 1.8rem;" class="medium material-icons">restore</i></a>
                            <a href="/dashboard/redeployment/trash/cloud/'.$redeployment->ref_number.'" style="margin-left:5px;" class="red-text"><i style="font-size: 1.8rem;" class="medium material-icons">cloud_off</i></a>
                        ';
                    }
                })
                ->addColumn('checkbox', function($redeployment) {
                    return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
                })
                ->rawColumns(['view', 'checkbox', 'fullname'])
                ->make();
    }
    // PERMANENTLY DELETE
    public function permanently($ref_number)
    {
        Redeployment::withTrashed()->where('ref_number', $ref_number)->forceDelete();
        Alert::success('Redeployment permanently deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }
    // PERMANENTLY DELETE BULK RECORDS
    public function permanently_bulk(Request $request)
    {
        $ref_number = Redeployment::onlyTrashed()->where('id', $request->personnel)->pluck('ref_number');
        try {
            $live_database = DB::connection('my-live-db');
            $result = $live_database->table('redeployments')->where('ref_number', $ref_number)->delete();
            $record = Redeployment::onlyTrashed()->where('ref_number', $ref_number)->forceDelete();
            return response()->json(['status' => true, 'message' => 'Record deleted permanently from both cloud and local database']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Could not open connection to database server.  Please check your configuration.']);
        }
    }
    // RESTORE RECORD FROM TRASH
    public function restore($ref_number)
    {
        Redeployment::withTrashed()->where('ref_number', $ref_number)->restore();
        Alert::success('Redeployment restored successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }
    
    // RESTORE BULK RECORD FROM TRASH
    public function restore_bulk(Request $request)
    {
        $query = Redeployment::onlyTrashed()->where('id', $request->personnel)->restore();
        if($query){
            return response()->json(['status' => true, 'message' => true]);
        }
    }
    // GENERATE SINGLE REDEPLOYMENT LETTER
    public function generate_letter(Redeployment $redeployment){
        // return "<img src=\"data:image/png;base64,$redeployment->barcode\" alt=\"barcode\" />";
        if($redeployment->signatory == 'dcg' && $redeployment->type == 'external'){
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template - DCG_External.docx'));
        }else if($redeployment->signatory == 'acg' && $redeployment->type == 'external'){
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template - ACG_External.docx'));
        }else if($redeployment->signatory == 'dcg' && $redeployment->type == 'internal'){
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template - DCG_Internal.docx'));
        }else if($redeployment->signatory == 'acg' && $redeployment->type == 'internal'){
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template - ACG_Internal.docx'));
        }
        $templateProcessor->setValue('svcNo', $redeployment->service_number);
        $templateProcessor->setValue('date', Carbon::parse($redeployment->created_at)->format('jS F, Y'));
        $templateProcessor->setValue('fullname', strtoupper($redeployment->fullname));
        $templateProcessor->setValue('rank', $redeployment->rank);
        $templateProcessor->setValue('title', $redeployment->type == 'internal' ? strtoupper($redeployment->type).' REDEPLOYMENT' : 'REDEPLOYMENT');
        // $templateProcessor->setValue('incharge', $redeployment->incharge);
        $templateProcessor->setValue('from', $redeployment->from);
        $templateProcessor->setValue('to', $redeployment->to);
        
        if ($redeployment->designation != NULL) {
            $templateProcessor->setValue('designation', ' '.$redeployment->designation);
        }else{
            $templateProcessor->setValue('designation', '');
        }

        // $templateProcessor->setValue('request_type', 'The redeployment takes immediate effect.');
    
        if($redeployment->financial_implication == 1){
            $templateProcessor->setValue('request_type', 'The redeployment takes immediate effect.');
        }else{
            $templateProcessor->setValue('request_type', 'The redeployment takes immediate effect and does not attract any financial benefit, please.');
        }
        
        if($redeployment->reason != NULL){
            $templateProcessor->setValue('reason', ' '.$redeployment->reason);
        }else{
            $templateProcessor->setValue('reason', '');
        }
        $templateProcessor->setImageValue('barcode', "data:image/png;base64,$redeployment->barcode");
        $templateProcessor->saveAs(storage_path('app/docs/'.$redeployment->fullname.'.docx'));
        return response()->download(storage_path('app/docs/'.$redeployment->fullname.'.docx'));
    }
    // GENERATE BULK REDEPLOYMENT LETTER
    public function generate_bulk_redeployment_letter(Request $request, DNS2D $dNS2D){

        $redeployments = Redeployment::orderByRaw("FIELD(rank, 'Commandant of Corps', 'Deputy Commandant of Corps', 'Assistant Commandant of Corps', 'Chief Superintendent of Corps', 'Superintendent of Corps', 'Deputy Superintendent of Corps', 'Assistant Superintendent of Corps I', 'Assistant Superintendent of Corps II', 'Chief Inspector of Corps', 'Chief Inspector of Corps I', 'Chief Inspector of Corps II', 'Deputy Chief Inspector of Corps', 'Assistant Chief Inspector of Corps', 'Principal Inspector of Corps', 'Principal Inspector of Corps I', 'Principal Inspector of Corps II','Senior Inspector of Corps', 'Inspector of Corps','Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->find($request->redeployment_id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        foreach($redeployments as $redeployment){
            $current = Carbon::now();
            $currentDate = $current->format('jS F, Y');
            $image = $redeployment->barcode;

            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = "Redeployment QR Code_$redeployment->service_number.png";
            File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

            // PAGE CONTENT WRAPPER
            $section = $phpWord->addSection([
                'orientation' => 'portrait', 
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.85), 
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.68), 
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.40), 
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1),
                'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1)
            ]);
                // $section->addTextBreak(4);

                // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
                $table = $section->addTable(['width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);
                $table->addRow();
                $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(5))->addText("NSCDC/NHQ/S.".$redeployment->service_number."/VOL.1/", null, [ 'spaceAfter' => 0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::START ]);
                $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(5))->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                $section->addTextBreak(1);

                // RIGHT ADDRESS /////////////////////////////////////////////////////////////////
                $section->addText("".strtoupper($redeployment->fullname)."", null, [ 'spaceAfter' => 0]);
                $section->addText("$redeployment->rank,", null, [ 'spaceAfter' => 0]);
                $section->addText("Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                $section->addText("$redeployment->from.");
                $section->addTextBreak(1);
                
                // TITLE HERE ////////////////////////////////////////////////
                if($redeployment->type == 'internal'){
                    $section->addText("INTERNAL REDEPLOYMENT", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                }else{
                    $section->addText("REDEPLOYMENT", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                }

                // BODY OF LETTER //////////////////////////////////////////////////////////////////
                $fisrtPara = $section->addTextRun(['lineHeight' => 2, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText("          I am directed to inform you that the $redeployment->incharge has approved your redeployment from $redeployment->from to ", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                if($redeployment->designation == null && $redeployment->reason == null){
                    $fisrtPara->addText("$redeployment->to.", ['bold' => true], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                }else{
                    $fisrtPara->addText("$redeployment->to", ['bold' => true], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                }

                if($redeployment->designation != null && $redeployment->reason == null){
                    $fisrtPara->addText(" $redeployment->designation.", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                }else{
                    $fisrtPara->addText(" $redeployment->designation", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                }
                if($redeployment->reason != null){
                    $fisrtPara->addText(" $redeployment->reason.", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                }

                // SECOND PARAGRAPH /////////////////////////////////////////////////////////////////
                
                // $section->addText('2.       The redeployment takes immediate effect.', null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                // $section->addTextBreak(1, ['size' => 14]);
                // $section->addTextBreak(1, ['size' => 14]);

                if($redeployment->financial_implication == true){
                    $section->addText('2.       The redeployment takes immediate effect.', null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                    $section->addTextBreak(1, ['size' => 14]);
                    $section->addTextBreak(1, ['size' => 14]);
                }else{
                    $section->addText('2.       The redeployment takes immediate effect and does not attract any financial benefit, please.', null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                    $section->addTextBreak(1, ['size' => 14]);
                    $section->addTextBreak(1, ['size' => 14]);
                }
                
                // FOOTER SIGNATURE //////////////////////////////////////////////////////////////////
                $section->addText('ZAKARI IBRAHIM NINGI fdc', ['bold' => true], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('Ag. Deputy Commandant General (Administration)', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('For: '.$redeployment->incharge.'', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addTextBreak(1);

                // DISTRIBUTION ////////////////////////////////////////////////////////////
                $section->addText('Distributions', ['italic' => true, 'underline' => 'single']);
                $table2 = $section->addTable(['width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);
                $table2->addRow();

                $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(5), ['valign' => 'top'])->addText("1.   $redeployment->from <w:br/>2.   $redeployment->to <w:br/>3.   File", ['size' => 14]);
                
                $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.8))->addImage(storage_path().'/app/docs/QR Code.png', ['width' => 50, 'height' => 50, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
                $section->addTextBreak(1, ['size' => 8]);

                // FOOTER AREA ///////////////////////////////////////////////////////////////
                $footer = $section->addFooter();
                $footer->addText("Please ensure QR Code scanning to authenticate the genuineness before releasing an officer.", ['name' => 'calibri', 'size' => 10, 'italic' => true, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $section->addTextBreak(1, ['size' => 8]);

        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/docs/Bulk_Redeployment_Letters.docx'));
        return response()->download(storage_path('app/docs/Bulk_Redeployment_Letters.docx'));
    }
    // GENERATE SINGLE REDEPLOYMENT LETTER
    public function generate_signed_letter(Redeployment $redeployment){
        // return "<img src=\"data:image/png;base64,$redeployment->barcode\" alt=\"barcode\" />";
        if($redeployment->signatory == 'dcg'){
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template - DCG SIGNED - 4.docx'));
        }else if($redeployment->signatory == 'acg'){
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template - ACG.docx'));
        }
        $templateProcessor->setValue('svcNo', $redeployment->service_number);
        $templateProcessor->setValue('date', Carbon::parse($redeployment->created_at)->format('jS F, Y'));
        $templateProcessor->setValue('fullname', strtoupper($redeployment->fullname));
        $templateProcessor->setValue('rank', $redeployment->rank);
        $templateProcessor->setValue('title', $redeployment->type == 'internal' ? strtoupper($redeployment->type).' REDEPLOYMENT' : 'REDEPLOYMENT');
        // $templateProcessor->setValue('incharge', $redeployment->incharge);
        $templateProcessor->setValue('from', $redeployment->from);
        $templateProcessor->setValue('to', $redeployment->to);
        if ($redeployment->designation != NULL) {
            $templateProcessor->setValue('designation', ' '.$redeployment->designation);
        }else{
            $templateProcessor->setValue('designation', '');
        }
        if($redeployment->reason != NULL){
            $templateProcessor->setValue('reason', ' '.$redeployment->reason);
        }else{
            $templateProcessor->setValue('reason', '');
        }
        $templateProcessor->setImageValue('barcode', "data:image/png;base64,$redeployment->barcode");
        $templateProcessor->saveAs(storage_path('app/docs/'.$redeployment->fullname.'.docx'));
        return response()->download(storage_path('app/docs/'.$redeployment->fullname.'.docx'));
    }
    // GENERATE REDEPLOYMENT SIGNAL
    public function generate_signal(Request $request, DNS2D $dNS2D){
      
        $ref = Str::random(5);
        $from = Redeployment::find($request->personnel)->pluck('from');
        $to = Redeployment::find($request->personnel)->pluck('to');
        $records = $from->concat($to)->sort()->unique();
        $distributions = '1.    ';
        $current = Carbon::now();
        $currentDate = $current->format('jS F, Y');
        $collectionDate = $current->addDays(14)->format('l jS F, Y');
        $counter = 0;
        foreach($records as $record){
            if($counter == count($records) - 1){
                $distributions = $distributions.$record.'.';
            }else{
                $distributions = $distributions.$record.', ';
            }
            $counter = $counter + 1;
        }

        $image =$dNS2D->getBarcodePNG("Document is Genuine! Ref No: NSCDC/NHQ/SIGNAL/VOL.1/$ref", 'QRCODE', 10,10);
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'QR Code'.'.'.'png';
        File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

        $personnel = Redeployment::orderByRaw("FIELD(rank, 'Commandant of Corps', 'Deputy Commandant of Corps', 'Assistant Commandant of Corps', 'Chief Superintendent of Corps', 'Superintendent of Corps', 'Deputy Superintendent of Corps', 'Assistant Superintendent of Corps I', 'Assistant Superintendent of Corps II', 'Chief Inspector of Corps', 'Deputy Chief Inspector of Corps', 'Assistant Chief Inspector of Corps', 'Principal Inspector of Corps I', 'Principal Inspector of Corps II', 'Senior Inspector of Corps', 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->find($request->personnel);
        
        $count = 1;
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        // PAGE CONTENT WRAPPER
        $section = $phpWord->addSection([
            'orientation' => 'portrait', 
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.65), 
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.38), 
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.40), 
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1),
            'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1)]);
            // $section->addTextBreak(4);

            // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
            $section_style = $section->getStyle();
            $position =
                $section_style->getPageSizeW()
                - $section_style->getMarginRight()
                - $section_style->getMarginLeft();
            $phpWord->addParagraphStyle("leftRight", array("tabs" => array(
                new \PhpOffice\PhpWord\Style\Tab("right", $position)
            )));
            $section->addText("NSCDC/NHQ/SIGNAL/VOL.1/".$ref."\t $currentDate", array(), "leftRight");
            
            // DISTRIBUTION HERE ////////////////////////////////////////////////
            $section->addText('See distribution below', ['underline' => 'single']);
            $section->addTextBreak(1);

            // TITLE HERE ////////////////////////////////////////////////
            $section->addText('REDEPLOYMENT SIGNAL', ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);

            // BODY OF LETTER //////////////////////////////////////////////////////////////////
            
            $section->addText('I am directed to inform you that the '.$personnel[0]->incharge.' has approved the redeployment of the under listed officers as indicated against their names.', [], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
            $fisrtPara = $section->addTextRun([ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
            $fisrtPara->addText('2.       The under listed personnel are to collect their redeployment letters in person or via proxy at the National Headquarters on or before ', [], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
            $fisrtPara->addText("$collectionDate.", ['bold' => true]);
            $section->addTextBreak(1, ['size' => 2]);


            // TABLE STARTS HERE /////////////////////////////////////////////////////////
            // Table th style
            $cellStyles = [
                'valign' => 'center'
            ];
            $thFontStyles = [
                'bold' => true,
                'size' => 12,
                'name' => 'calibri'
            ];
            // Table td style
            $trFontStyles = [
                'size' => 11,
                'name' => 'calibri'
            ];

            $table = $section->addTable('redeployment');
            $table->addRow();
            $table->addCell(700, $cellStyles)->addText('SN', $thFontStyles);
            $table->addCell(2500, $cellStyles)->addText('NAME', $thFontStyles);
            $table->addCell(1200, $cellStyles)->addText('SVC NO.', $thFontStyles);
            $table->addCell(2200, $cellStyles)->addText('RANK', $thFontStyles);
            $table->addCell(1600, $cellStyles)->addText('FROM', $thFontStyles);
            $table->addCell(1600, $cellStyles)->addText('TO', $thFontStyles);
            foreach($personnel as $record){
                $table->addRow();
                $table->addCell(700, $cellStyles)->addText($count++, $trFontStyles);
                $table->addCell(2100, $cellStyles)->addText($record->fullname, $trFontStyles);
                $table->addCell(1200, $cellStyles)->addText($record->service_number, $trFontStyles);
                $table->addCell(2100, $cellStyles)->addText($record->rank_acronym, $trFontStyles);
                $table->addCell(1600, $cellStyles)->addText($record->from, $trFontStyles);
                $table->addCell(1600, $cellStyles)->addText($record->to.' '.$record->designation, $trFontStyles);
            }
            $phpWord->addTableStyle('redeployment', ['borderColor' => 'black', 'borderSize'  => 6, 'cellMargin'  => 80], ['bgColor' => 'white', 'bold' => true, 'width' => 100 * 50, 'unit' => 'pct']);
            $section->addTextBreak(1, ['size' => 8]);

            // BODY OF LETTER CONTINUE //////////////////////////////////////////////////////////////////
            $section->addText('3.       The redeployment takes immediate effect.', [], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
            $section->addTextBreak(1);
            
            // FOOTER SIGNATURE //////////////////////////////////////////////////////////////////
            $section->addText('ZAKARI IBRAHIM NINGI fdc', ['bold' => true], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
            $section->addText('Ag. Deputy Commandant General (Administration)', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
            $section->addText('For: '.$personnel[0]->incharge.'', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
            $section->addTextBreak(1, ['size' => 8]);


            $section->addText('Distributions', ['bold' => true, 'italic' => true, 'underline' => 'single']);
            $section->addText("$distributions", ['size' => 14]);
            $section->addText("2.   File.", ['size' => 14]);
            
            $footer = $section->addFooter();
            $table = $footer->addTable(['width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);
            $table->addRow();
            $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(5), ['valign' => 'bottom'])->addText("Please ensure QR Code scanning to authenticate the genuineness before releasing an officer.", ['name' => 'calibri', 'size' => 10, 'italic' => true, 'bold' => true]);
            $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.8))->addImage(storage_path().'/app/docs/QR Code.png', ['width' => 50, 'height' => 50, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
            $section->addTextBreak(1, ['size' => 8]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/docs/redeployment_signal.docx'));
        return response()->download(storage_path('app/docs/redeployment_signal.docx'));
    }
    

    // REMOVE FROM CLOUD
    public function trash_cloud($ref_number)
    {
        try {
            $live_database = DB::connection('my-live-db');
            $result = $live_database->table('redeployments')->where('ref_number', $ref_number)->delete();
            $record = Redeployment::withTrashed()->where('ref_number', $ref_number)->first();
            if($result){
                $record->synched = 0;
                $record->save();
                Alert::success('Cloud record deleted successfully!', 'Success!')->autoclose(2500);
                return redirect()->back();
            }else{
                return 'Error deleting cloud record';
            }
        } catch (\Exception $e) {
            die("Could not open connection to database server.  Please check your configuration.");
        }
    }
    
    
    // REMOVE FROM CLOUD IN BULK
    public function trash_cloud_bulk(Request $request)
    {
        $ref_number = Redeployment::onlyTrashed()->where('id', $request->personnel)->pluck('ref_number');
        try {
            $live_database = DB::connection('my-live-db');
            $result = $live_database->table('redeployments')->where('ref_number', $ref_number)->delete();
            $record = Redeployment::withTrashed()->where('ref_number', $ref_number)->first();
            if($result){
                $record->synched = 0;
                $record->save();
                return response()->json(['status' => 'true', 'message' => 'Cloud record deleted successfully!']);
            }else{
                return response()->json(['status' => 'false', 'message' => 'Record does not exist on the cloud']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'connection_error', 'message' => 'Could not open connection to database server.  Please check your configuration.']);
        }
    }


    // PROCESS OF SINGLE PERSONNEL UPLOAD
    public function single_cloud_upload($ref_number){
        try {
            DB::connection('my-live-db')->getPdo();
            $live_database = DB::connection('my-live-db');
            // $result = $live_database->table('redeployments')->where('ref_number', $ref_number)->get();
            $record = Redeployment::where('ref_number', $ref_number)->first();
            $resp = $live_database->table('redeployments')->updateOrInsert(
                ['ref_number' =>   $record->ref_number],
                [
                    'user_id' =>   $record->user_id,
                    'type' =>   $record->type,
                    'fullname' =>   $record->fullname,
                    'service_number' =>   $record->service_number,
                    'file_number' =>   $record->file_number,
                    'ref_number' =>   $record->ref_number,
                    'rank' =>   $record->rank,
                    'from' =>   $record->from,
                    'to' =>   $record->to,
                    'designation' =>   $record->designation,
                    'reason' =>   $record->reason,
                    'signatory' =>   $record->signatory,
                    'financial_implication' =>   $record->financial_implication,
                    'barcode' =>   $record->barcode,
                    'synched' =>   $record->synched,
                    'created_at' =>  $record->created_at,
                    'updated_at' =>  $record->updated_at
                ]
            );
            if($resp){
                $record->synched = 1;
                $record->save();
                Alert::success('Record updated successfully!', 'Success!')->autoclose(2500);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            die("Could not open connection to database server.  Please check your configuration.");
        }
        
    }


    // PROCESS OF BULK PERSONNEL UPLOAD
    public function bulk_cloud_upload(Request $request){
        $person = Redeployment::find($request->personnel);
        try {
            DB::connection('my-live-db')->getPdo();
            $live_database = DB::connection('my-live-db');
            $record = Redeployment::where('ref_number', $person->ref_number)->first();
            $resp = $live_database->table('redeployments')->updateOrInsert(
                ['ref_number' =>   $record->ref_number],
                [
                    'user_id' =>   $record->user_id,
                    'type' =>   $record->type,
                    'fullname' =>   $record->fullname,
                    'service_number' =>   $record->service_number,
                    'file_number' =>   $record->file_number,
                    'ref_number' =>   $record->ref_number,
                    'rank' =>   $record->rank,
                    'from' =>   $record->from,
                    'to' =>   $record->to,
                    'designation' =>   $record->designation,
                    'reason' =>   $record->reason,
                    'signatory' =>   $record->signatory,
                    'financial_implication' =>   $record->financial_implication,
                    'barcode' =>   $record->barcode,
                    'synched' =>   $record->synched,
                    'created_at' =>  $record->created_at,
                    'updated_at' =>  $record->updated_at
                ]
            );
            if($resp){
                $record->synched = 1;
                $record->save();
                return response()->json(['status' => true, 'message' => true]);
            }
            return response()->json(['status' => true, 'message' => 'Records updated to cloud successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Could not open connection to database server.  Please check your configuration.']);
        }
    }
    

    // PROCESS OF BULK PERSONNEL SYNC
    public function cloud_download(){
        try {
            DB::connection('my-live-db')->getPdo();
            $live_database = DB::connection('my-live-db');
            if (auth()->user()->service_number == 66818) {
                $fetch = $live_database->table('redeployments')->where('user_id', '!=', 1)->where('suleiman', 0);
            }
            elseif(auth()->user()->service_number == 31886){
                $fetch = $live_database->table('redeployments')->where('user_id', '!=', 3)->where('lukman', 0);
            }
            else{
                $fetch = $live_database->table('redeployments')->where('user_id', '!=', 2)->where('patricia', 0);
            }
            $response = $fetch->get();
            $count = $fetch->count();
            foreach ($response as $record) {
                $insert = Redeployment::updateOrInsert(
                    ['ref_number' =>   $record->ref_number],
                    [
                    'user_id' =>   $record->user_id,
                    'type' =>   $record->type,
                    'fullname' =>   $record->fullname,
                    'service_number' =>   $record->service_number,
                    'file_number' =>   $record->file_number,
                    'ref_number' =>   $record->ref_number,
                    'rank' =>   $record->rank,
                    'from' =>   $record->from,
                    'to' =>   $record->to,
                    'designation' =>   $record->designation,
                    'reason' =>   $record->reason,
                    'signatory' =>   $record->signatory,
                    'barcode' =>   $record->barcode,
                    'synched' =>   1,
                    'patricia' =>   $record->patricia,
                    'suleiman' =>   $record->suleiman,
                    'created_at' =>  $record->created_at,
                    'updated_at' =>  $record->updated_at
                    ]
                );
                if($insert){
                    if (auth()->user()->service_number == 66818) {
                        $live_database->table('redeployments')->where('ref_number', $record->ref_number)->update(['suleiman' => 1]);
                    }
                    elseif(auth()->user()->service_number == 31886){
                        $live_database->table('redeployments')->where('ref_number', $record->ref_number)->update(['lukman' => 1]);
                    }
                    else{
                        $live_database->table('redeployments')->where('ref_number', $record->ref_number)->update(['patricia' => 1]);
                    }
                }
            }
            return response()->json(['status' => true, 'count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Could not open connection to database server.  Please check your configuration.']);
        }
        
    }


}
