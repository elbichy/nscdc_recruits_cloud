<?php

namespace App\Http\Controllers;

use App\Conversion;
use App\Formation;
use App\Rank;
use App\State;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Milon\Barcode\Facades\DNS2DFacade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Str;

class ConversionController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth', 'role:super admin']);
    }

    // DISPLAYCONVERSION GRID
    public function manage(Request $request){
        if(auth()->user()->service_number !== 66818){
            return redirect()->back();
        }
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        $formations = Formation::all();
        $states = State::all();
        return view('administration/dashboard/conversion/manage');
    }

    // DISPLAY JNR CONVERSION LIST
    public function manage_jnr(Request $request){
        if(auth()->user()->service_number !== 66818){
            return redirect()->back();
        }
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        $formations = Formation::all();
        $ranks = Rank::get(['full_title', 'short_title']);
        $states = State::all();
        return view('administration/dashboard/conversion/all_junior', compact(['formations', 'states', 'ranks']));
    }
    // GET CONVERSION LIST
    public function get_all_junior(){
        $conversions = Conversion::all()->sortByDesc('updated_at');
        return DataTables::of($conversions)
        ->editColumn('updated_at', function ($conversion) {
            return $conversion->updated_at->toFormattedDateString();
            // return $redeployment->created_at->toDateString();
        })
        ->addColumn('view', function($conversion) {
            return '
                    <a href="'.route('generate_single_junior_conversion_letter', $conversion->id).'" style="margin-right:5px;" class="light-blue-text text-darken-3" title="Print conversion letter"><i class="fas fa-file-word fa-lg"></i></a>
                    <a href="#" style="margin-right:5px;" class="light-blue-text text-darken-3" title="Edit conversion record" data-conv_id="'.$conversion->id.'" onclick="editConversion(event)"><i class="fas fa-edit fa-lg"></i></a>
                ';
        })
        ->addColumn('checkbox', function($redeployment) {
            return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
        })
        ->rawColumns(['view', 'checkbox'])
        ->make();
    }

    // IMPORT NEW DATA
    public function import_data(Request $request){
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return view('administration/dashboard/conversion/import');
    }

    // IMPORT STORE IMPORTED CONVERSION DATA
    public function get_junior(Conversion $conversion)
    {  
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return $conversion;
    }

    // IMPORT STORE IMPORTED CONVERSION DATA
    public function store_junior(Request $request)
    {  
        
        if($request->has('present_key')){
            return Rank::where('cadre', $request->present_key)->get();
        }
        if($request->has('new_key')){
            return Rank::where('cadre', $request->new_key)->get();
        }
        if($request->has('svc_no_key')){
            $user = User::where('service_number', $request->svc_no_key)->with(['formations'=>function($query){
                return $query->latest()->first();
            }, 'qualifications'=>function($query){
                return $query->latest()->first();
            }])->get();
            $count = User::where('service_number', $request->svc_no_key)->count();
            return response()->json(['status' =>  true, 'record' => $user, 'message' => $count.' records found', 'count' => $count]);
        }

        $validate = $request->validate([
            'svc_no' => 'required|integer',
            'name' => 'required|string',
            'dob' => 'required|date',
            'formation' => 'required|integer',
            'soo' => 'required|string',
            'additional_qual' => 'required|string',
            'qual_year' => 'required|integer',
            'dofa' => 'required|date',
            'dopa' => 'required|date',
            'present_cadre' => 'required|string',
            'entry_gl' => 'required|integer',
            'present_gl' => 'required|integer',
            'new_cadre' => 'required|string',
            'new_gl' => 'required|integer'
        ]);
        
        $command_type = Formation::find($request->formation)->type;
        $command = Formation::find($request->formation)->formation;
        $entry_rank = Rank::where('cadre', $request->present_cadre)->where('gl', $request->entry_gl)->pluck('short_title')->first();
        $present_rank = Rank::where('cadre', $request->present_cadre)->where('gl', $request->present_gl)->first();
        $present_rank_full = $present_rank->full_title;
        $present_rank_short = $present_rank->short_title;
        $conversion_rank = Rank::where('cadre', $request->new_cadre)->where('gl', $request->new_gl)->first();
        $new_rank_full = $conversion_rank->full_title;
        $new_rank_short = $conversion_rank->short_title;

        $conversion = Conversion::create([
            "svc_no"=> $request->svc_no,
            "name"=> strtoupper($request->name),
            "dob"=> $request->dob,
            "soo"=> $request->soo,
            "command"=> ucwords($command),
            "command_type"=> $command_type,
            "additional_qual"=> $request->additional_qual,
            "qual_year"=> $request->qual_year,
            "dofa"=> $request->dofa,
            "dopa"=> $request->dopa,
            "entry_rank"=> $entry_rank,
            "old_gl"=> $request->present_gl,
            "present_rank_full"=> $present_rank_full,
            "present_rank_short"=> $present_rank_short,
            "new_gl"=> $request->new_gl,
            "conversion_rank_full"=> $new_rank_full,
            "conversion_rank_short"=> $new_rank_short,
            "effective_date"=> '1-1-2018',
            "type"=> $request->new_gl > $request->present_gl ? 'UPGRADING/CONVERSION
            ' : 'LATERAL CONVERSION',
            "ref_number" => Str::random(12)
        ]);

        if($conversion){
            Alert::success('Conversion record added successfully!', 'Success!')->autoclose(2500);
            return back();
        }
    }
   
   
    // IMPORT UPDATE IMPORTED CONVERSION DATA
    public function update_junior(Request $request)
    {
        // $validate = $request->validate([
        //     'svc_no' => 'required|integer',
        //     'name' => 'required|string',
        //     'dob' => 'required|date',
        //     'formation' => 'required',
        //     'soo' => 'required|string',
        //     'additional_qual' => 'required|string',
        //     'qual_year' => 'required|integer',
        //     'dofa' => 'required|date',
        //     'dopa' => 'required|date'
        // ]);

        $present_rank_short = Rank::where('full_title', $request->input('update-present_rank'))->pluck('short_title')->first();
        $conversion_rank_short = Rank::where('full_title', $request->input('update-new_rank'))->pluck('short_title')->first();
        $present_gl = Rank::where('full_title', $request->input('update-present_rank'))->pluck('gl')->first();
        $conversion_gl = Rank::where('full_title', $request->input('update-new_rank'))->pluck('gl')->first();
        $formation_type = Formation::where('formation', $request->input('update-formation'))->pluck('type')->first();
        $present_rank = $request->input('update-present_rank');
        $new_rank = $request->input('update-new_rank');

        $update = Conversion::find($request->id)->update([
            'svc_no' => $request->input('update-svc_no'),
            'name' => $request->input('update-name'),
            'command' => $request->input('update-formation'),
            'command_type' => $formation_type,
            'conversion_rank_full' => $new_rank,
            'conversion_rank_short' => $conversion_rank_short,
            'dob' => $request->input('update-dob'),
            'dofa' => $request->input('update-dofa'),
            'dopa' => $request->input('update-dopa'),
            // 'effective_date' => $request->input('update-dob'),
            'entry_rank' => $request->input('update-entry_rank'),
            'new_gl' => $conversion_gl,
            'old_gl' => $present_gl,
            'present_rank_full' => $present_rank,
            'present_rank_short' => $present_rank_short,
            'additional_qual' => $request->input('update-additional_qual'),
            'qual_year' => $request->input('update-qual_year'),
            // 'ref_number' => $request->input('update-dob'),
            // 'serial_no' => $request->input('update-dob'),
            'soo' => $request->input('update-soo'),
            'type' => $present_gl == $conversion_gl ? 'LATERAL CONVERSION' : 'UPGRADING/CONVERSION'
        ]);

        if($update){
            Alert::success('Conversion record updated successfully!', 'Success!')->autoclose(2500);
            return back();
        }
    }

    // DELETE CONVERSION DATA
    public function delete_junior(Request $request)
    {
        $delete = $request->delete_id;
        Conversion::find($delete)->delete();
        Alert::success('Conversion record trashed successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }

    // IMPORT STORE IMPORTED CONVERSION DATA
    public function store_imported_conversion(Request $request)
    {  
        $request->validate([
            'import_file' => 'required'
        ]);
        $path = $request->file('import_file')->getRealPath();
        $data = (new FastExcel)->import($path);
        // \dd($data);
        if($data->count()){
            $candidates = (new FastExcel)->import($path, function ($line) {
                $line['serial_no'] == '' ? $serial_no = null : $serial_no = $line['serial_no'];
                $line['svc_no'] == '' ? $svc_no = null : $svc_no = $line['svc_no'];
                $line['name'] == '' ? $name = null : $name = $line['name'];
                $line['dob'] == '' ? $dob = null : $dob = $line['dob'];
                $line['soo'] == '' ? $soo = null : $soo = $line['soo'];
                $line['command'] == '' ? $command = null : $command = $line['command'];
                $line['command_type'] == '' ? $command_type = null : $command_type = $line['command_type'];
                $line['additional_qual'] == '' ? $additional_qual = null : $additional_qual = $line['additional_qual'];
                $line['qual_year'] == '' ? $qual_year = null : $qual_year = $line['qual_year'];
                $line['dofa'] == '' ? $dofa = null : $dofa = $line['dofa'];
                $line['dopa'] == '' ? $dopa = null : $dopa = $line['dopa'];
                $line['entry_rank'] == '' ? $entry_rank = null : $entry_rank = $line['entry_rank'];
                $line['present_rank'] == '' ? $present_rank = null : $present_rank = $line['present_rank'];
                $line['old_gl'] == '' ? $old_gl = null : $old_gl = $line['old_gl'];
                $line['conversion_rank'] == '' ? $conversion_rank = null : $conversion_rank = $line['conversion_rank'];
                $line['new_gl'] == '' ? $new_gl = null : $new_gl = $line['new_gl'];
                $line['effective_date'] == '' ? $effective_date = null : $effective_date = $line['effective_date'];
                $line['type'] == '' ? $type = null : $type = $line['type'];
                
                $effective_date = date('d-m-Y', strtotime($effective_date));
                
                $present_rank = Rank::where('short_title', $present_rank)->first();
                $conversion_rank = Rank::where('short_title', $conversion_rank)->first();
                $ref_number = Str::random(12);

                $candidate = Conversion::updateOrInsert(
                    ['ref_number' => $ref_number],
                    [
                    'serial_no' => $serial_no,
                    'svc_no' => $svc_no,
                    'name' => $name,
                    'dob' => $dob,
                    'soo' => $soo,
                    'command' => ucwords($command),
                    'command_type' => $command_type,
                    'additional_qual' => $additional_qual,
                    'qual_year' => $qual_year,
                    'dofa' => $dofa,
                    'dopa' => $dopa,
                    'entry_rank' => $entry_rank,
                    'present_rank_full' => $present_rank->full_title,
                    'present_rank_short' => $present_rank->short_title,
                    'old_gl' => $old_gl,
                    'conversion_rank_full' => $conversion_rank->full_title,
                    'conversion_rank_short' => $conversion_rank->short_title,
                    'new_gl' => $new_gl,
                    'effective_date' => $effective_date,
                    'type' => $type,
                    'ref_number' => $ref_number,
                    'created_at' =>  Carbon::now(),
                    'updated_at' =>  Carbon::now()
                    ]
                );
            });
            Alert::success('Conversion records imported successfully!', 'Success!')->autoclose(222500);
            return back();
        }
    }

    // GENERATE SINGLE REDEPLOYMENT SIGNAL
    public function generate_single_junior_conversion_letter(Conversion $candidate){

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

            $current = Carbon::now();
            // $currentDate = $current->format('jS F, Y');
            $currentDate = "29th July, 2020";
            $image = DNS2DFacade::getBarcodePNG("<b>Authentic!</b> find full details of <b>$candidate->name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/conversion/jnr/2018/$candidate->ref_number", 'QRCODE', 50,50);
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            // $imageName = 'Conversion QR Code'.'.'.'png';
            $imageName = "Conversion QR Code_$candidate->svc_no.png";
            File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

            // PAGE CONTENT WRAPPER
            $section = $phpWord->addSection([
                'orientation' => 'portrait', 
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.45), 
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.68), 
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.30), 
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1),
                'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1)
            ]);
                // $section->addTextBreak(4);

                // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
                $section->addText("NSCDC/NHQ/JCU/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", null, [ 'spaceAfter' => 0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                $section->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                $section->addTextBreak(1);
                $section->addText("$candidate->name", ['bold' => true]);
                if($candidate->command_type == 'state'){

                    $section->addText("Ufs:  The State Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." State Command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." state.");

                }elseif($candidate->command_type == 'zone'){
                    
                    $section->addText("Ufs:  The Zonal Commander,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." Command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." state.");

                }elseif($candidate->command_type == 'college'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    // $section->addText("         $candidate->command state.");
                }elseif($candidate->command_type == 'nhq'){
                    
                    $section->addText("Ufs:  Deputy Commandant General Administration,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         National Headquarters,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Sauka, Fct Abuja.");

                }elseif($candidate->command_type == 'fct'){
                    
                    $section->addText("Ufs:  The Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." Command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Zone 5, Fct Abuja.");

                }
                elseif($candidate->command_type == 'elo'){
                    
                    $section->addText("Ufs:  The Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Zone 5, Fct Abuja.");

                }
                $section->addTextBreak(1, ['size' => 8]);

                // TITLE HERE ////////////////////////////////////////////////
                $section->addText("LETTER OF $candidate->type", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);

                // BODY OF LETTER //////////////////////////////////////////////////////////////////
                $fisrtPara = $section->addTextRun(['lineHeight' => 1.5, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText("I am pleased to inform you that having met the necessary requirements, the management of Nigeria Security and Civil Defence Corps (NSCDC) by the recommendations of the Junior Staff Committee on Promotion, approved your $candidate->type from the Rank of", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText(" $candidate->present_rank_full ($candidate->present_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS $candidate->old_gl)", ['bold' => true]);
                $fisrtPara->addText(" to the rank of");
                $fisrtPara->addText(" $candidate->conversion_rank_full ($candidate->conversion_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS $candidate->new_gl)", ['bold' => true]);
                $fisrtPara->addText(" with effect from");
                $fisrtPara->addText(" ".date('d/m/Y', strtotime($candidate->effective_date))."", ['bold' => true]);

                $section->addText('2.       Notice of the Conversion will be published in the Official Gazette soon.', 
                null, 
                [
                     'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH 
                ]);

                $section->addText('3.       Congratulations.', 
                null, 
                [ 
                    'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH 
                ]);
                $section->addTextBreak(1, ['size' => 14]);
                $section->addTextBreak(1, ['size' => 14]);
                
                $section->addImage(storage_path().'/app/docs/SVG/cc_admin_sign.png', [
                    'width' => 240,
                    'wrappingStyle' => 'infront',
                    'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
                    'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
                    'posVertical'    => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_CENTER,
                    'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_MARGIN,
                    'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
                    'margin-top' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(-12.85)
                ]);

                // FOOTER SIGNATURE //////////////////////////////////////////////////////////////////
                $section->addText('ADAMU SALIHU', ['bold' => true], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'spaceBefore' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('Commandant Administration', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('For: Commandant General', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addTextBreak(1, ['size' => 4]);

                $section->addImage(storage_path()."/app/docs/Conversion QR Code_$candidate->svc_no.png", ['width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

                
                $footer = $section->addFooter();
                $footer->addText("Please ensure QR Code scanning to authenticate the genuineness of this letter.", ['name' => 'calibri', 'size' => 12, 'italic' => true, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                // $section->addTextBreak(1, ['size' => 8]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/docs/'.$candidate->name.'.docx'));
        return response()->download(storage_path('app/docs/'.$candidate->name.'.docx'));
    }

    // GENERATE BULK REDEPLOYMENT SIGNAL
    public function generate_bulk_junior_conversion_letter(Request $request){

        $candidates = Conversion::orderByRaw("FIELD(conversion_rank_full, 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->find($request->candidates);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        foreach($candidates as $candidate){

            $current = Carbon::now();
            // $currentDate = $current->format('jS F, Y');
            $currentDate = '29th July, 2020';
            $image = DNS2DFacade::getBarcodePNG("<b>Authentic!</b> find full details of <b>$candidate->name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/conversion/jnr/2018/$request->ref_number", 'QRCODE', 50,50);
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = "Conversion QR Code_$candidate->svc_no.png";
            File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

            // PAGE CONTENT WRAPPER
            $section = $phpWord->addSection([
                'orientation' => 'portrait', 
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.45), 
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.68), 
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.30), 
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1),
                'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1)
            ]);
                // $section->addTextBreak(4);

                // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
                $section->addText("NSCDC/NHQ/JCU/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", null, [ 'spaceAfter' => 0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                $section->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                $section->addTextBreak(1);
                $section->addText("$candidate->name", ['bold' => true]);
                if($candidate->command_type == 'state'){

                    $section->addText("Ufs:  The State Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." State Command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." state.");

                }elseif($candidate->command_type == 'zone'){
                    
                    $section->addText("Ufs:  The Zonal Commander,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." Command,", null, [ 'spaceAfter' => 0]);
                    // $section->addText("         $candidate->command state.");

                }
                elseif($candidate->command_type == 'kc'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Katsina state.");
                }
                elseif($candidate->command_type == 'oc'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Ogun state.");
                }
                elseif($candidate->command_type == 'nhq'){
                    
                    $section->addText("Ufs:  Deputy Commandant General Administration,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         National Headquarters,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Sauka, Fct Abuja.");

                }elseif($candidate->command_type == 'fct'){
                    
                    $section->addText("Ufs:  The Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command)." Command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Zone 5, Fct Abuja.");

                }
                $section->addTextBreak(1, ['size' => 8]);

                // TITLE HERE ////////////////////////////////////////////////
                $section->addText("LETTER OF $candidate->type", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);

                // BODY OF LETTER //////////////////////////////////////////////////////////////////
                $fisrtPara = $section->addTextRun(['lineHeight' => 1.5, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText("I am pleased to inform you that having met the necessary requirements, the management of Nigeria Security and Civil Defence Corps (NSCDC) by the recommendations of the Junior Staff Committee on Promotion, approved your $candidate->type from the Rank of", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText(" $candidate->present_rank_full ($candidate->present_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS $candidate->old_gl)", ['bold' => true]);
                $fisrtPara->addText(" to the rank of");
                $fisrtPara->addText(" $candidate->conversion_rank_full ($candidate->conversion_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS $candidate->new_gl)", ['bold' => true]);
                $fisrtPara->addText(" with effect from");
                $fisrtPara->addText(" ".date('d/m/Y', strtotime($candidate->effective_date)).".", ['bold' => true]);

                $section->addText('2.       Notice of the Conversion will be published in the Official Gazette soon.', null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $section->addText('3.       Congratulations.', null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $section->addTextBreak(1, ['size' => 14]);
                $section->addTextBreak(1, ['size' => 14]);
                
                $section->addImage(storage_path().'/app/docs/SVG/cc_admin_sign.png', [
                    'width' => 240,
                    'wrappingStyle' => 'infront',
                    'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
                    'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
                    'posVertical'    => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_CENTER,
                    'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_MARGIN,
                    'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
                    'margin-top' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(-12.85)
                ]);
                
                // FOOTER SIGNATURE //////////////////////////////////////////////////////////////////
                $section->addText('ADAMU SALIHU', ['bold' => true], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('Commandant Administration', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('For: Commandant General', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addTextBreak(1, ['size' => 4]);

                $section->addImage(storage_path()."/app/docs/Conversion QR Code_$candidate->svc_no.png", ['width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

                
                $footer = $section->addFooter();
                $footer->addText("Please ensure QR Code scanning to authenticate the genuineness of this letter.", ['name' => 'calibri', 'size' => 12, 'italic' => true, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                // $section->addTextBreak(1, ['size' => 8]);

        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/docs/junior_conversion_letter.docx'));
        return response()->download(storage_path('app/docs/junior_conversion_letter.docx'));
    }

    public function manage_snr(Request $request){
        return back();
    }

}
