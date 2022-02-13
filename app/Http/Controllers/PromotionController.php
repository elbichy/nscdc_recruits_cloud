<?php

namespace App\Http\Controllers;

use App\Formation;
use App\Promotion;
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

class PromotionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'role:super admin']);
    }

    // DISPLAY PROMOTION GRID
    public function manage(Request $request){
        if(auth()->user()->service_number !== 66818){
            return redirect()->back();
        }
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        $formations = Formation::all();
        $states = State::all();
        return view('administration/dashboard/promotion/manage');
    }

    // DISPLAY JNR PROMOTION LIST
    public function manage_jnr(Request $request, $year){
        if(auth()->user()->service_number !== 66818){
            return redirect()->back();
        }
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        $formations = Formation::all();
        $ranks = Rank::get(['full_title', 'short_title']);
        $states = State::all();
        return view('administration/dashboard/promotion/all_junior', compact(['year','formations', 'states', 'ranks']));
    }
    // GET PROMOTION LIST
    public function get_all_junior($year){

        $promotions = Promotion::where('year', $year)->orderBy('updated_at', 'DESC')->get();
        return DataTables::of($promotions)
        ->editColumn('updated_at', function ($promotion) {
            return $promotion->updated_at->toFormattedDateString();
            // return $redeployment->created_at->toDateString();
        })
        ->addColumn('view', function($promotion) {
            return '
                    <a href="'.route('generate_single_junior_promotion_letter', $promotion->id).'" style="margin-right:5px;" class="light-blue-text text-darken-3" title="Print promotion letter"><i class="fas fa-file-word fa-lg"></i></a>
                    <a href="#" style="margin-right:5px;" class="light-blue-text text-darken-3" title="Edit promotion record" data-pro_id="'.$promotion->id.'" onclick="editPromotion(event)"><i class="fas fa-edit fa-lg"></i></a>
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
        return view('administration/dashboard/promotion/import');
    }

    // IMPORT STORE IMPORTED PROMOTION DATA
    public function get_junior(Promotion $promotion)
    {  
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }
        return $promotion;
    }

    // STORE PROMOTION DATA
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
            'dofa' => 'required|date',
            'dopa' => 'required|date',
            'present_cadre' => 'required|string',
            'present_gl' => 'required|integer',
            'new_gl' => 'required|integer'
        ]);
        
        $command_type = Formation::find($request->formation)->type;
        $command = Formation::find($request->formation)->formation;
        $present_rank = Rank::where('cadre', $request->present_cadre)->where('gl', $request->present_gl)->first();
        $present_rank_full = $present_rank->full_title;
        $present_rank_short = $present_rank->short_title;
        $promotion_rank = Rank::where('cadre', $request->present_cadre)->where('gl', $request->new_gl)->first();
        $new_rank_full = $promotion_rank->full_title;
        $new_rank_short = $promotion_rank->short_title;

        // return $request;
        $promotion = Promotion::create([
            "svc_no"=> $request->svc_no,
            "name"=> strtoupper($request->name),
            "dob"=> $request->dob,
            "soo"=> $request->soo,
            "command"=> ucwords($command),
            "command_type"=> $command_type,
            "dofa"=> $request->dofa,
            "dopa"=> $request->dopa,
            "present_gl"=> $request->present_gl,
            "present_rank_full"=> $present_rank_full,
            "present_rank_short"=> $present_rank_short,
            "promotion_gl"=> $request->new_gl,
            "promotion_rank_full"=> $new_rank_full,
            "promotion_rank_short"=> $new_rank_short,
            "effective_date"=> '1-1-2018',
            "type"=> 'normal',
            "ref_number" => Str::random(12)
        ]);

        if($promotion){
            Alert::success('Promotion record added successfully!', 'Success!')->autoclose(2500);
            return back();
        }
    }
   
   
    // IMPORT UPDATE IMPORTED PROMOTION DATA
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
        // return $request;
        $present_rank_short = Rank::where('full_title', $request->input('update-present_rank'))->pluck('short_title')->first();
        $promotion_rank_short = Rank::where('full_title', $request->input('update-new_rank'))->pluck('short_title')->first();
        $present_gl = Rank::where('full_title', $request->input('update-present_rank'))->pluck('gl')->first();
        $promotion_gl = Rank::where('full_title', $request->input('update-new_rank'))->pluck('gl')->first();
        $formation_type = Formation::where('formation', $request->input('update-formation'))->pluck('type')->first();
        $present_rank = $request->input('update-present_rank');
        $promotion_rank = $request->input('update-new_rank');

        $update = Promotion::find($request->id)->update([
            'svc_no' => $request->input('update-svc_no'),
            'name' => $request->input('update-name'),
            'command' => $request->input('update-formation'),
            'command_type' => $formation_type,
            'promotion_rank_full' => $promotion_rank,
            'promotion_rank_short' => $promotion_rank_short,
            'promotion_gl' => $promotion_gl,
            'dob' => $request->input('update-dob'),
            'dofa' => $request->input('update-dofa'),
            'dopa' => $request->input('update-dopa'),
            // 'effective_date' => $request->input('update-dob'),
            'present_rank_full' => $present_rank,
            'present_rank_short' => $present_rank_short,
            'present_gl' => $present_gl,
            // 'ref_number' => $request->input('update-dob'),
            // 'serial_no' => $request->input('update-dob'),
            'soo' => $request->input('update-soo'),
            'type' => 'normal'
        ]);

        if($update){
            Alert::success('Promotion record updated successfully!', 'Success!')->autoclose(2500);
            return back();
        }
    }
    
    // DELETE PROMOTION DATA
    public function delete_junior(Request $request)
    {
        $delete = $request->delete_id;
        Promotion::find($delete)->delete();
        Alert::success('Promotion record trashed successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }

    // IMPORT STORE IMPORTED PROMOTION DATA
    public function store_imported_promotion(Request $request)
    {  
        $request->validate([
            'import_file' => 'required',
            'year' => 'required'
        ]);
        
        $path = $request->file('import_file')->getRealPath();
        $data = (new FastExcel)->import($path);
        // \dd($data[0]);
        if($data->count()){

            $candidates = (new FastExcel)->import($path, function ($line) {
                $line['SN'] == '' ? $serial_no = null : $serial_no = $line['SN'];
                $line['SVC. NO'] == '' ? $svc_no = null : $svc_no = $line['SVC. NO'];
                $line['NAME'] == '' ? $name = null : $name = $line['NAME'];
                $line['COMMAND'] == '' ? $command = null : $command = $line['COMMAND'];
                $line['CMD. TYPE'] == '' ? $command_type = null : $command_type = $line['CMD. TYPE'];
                $line['OLD RANK'] == '' ? $old_rank = null : $old_rank = $line['OLD RANK'];
                $line['OLD GL'] == '' ? $present_gl = null : $present_gl = $line['OLD GL'];
                $line['SOO'] == '' ? $soo = null : $soo = $line['SOO'];
                $line['DOB'] == '' ? $dob = null : $dob = $line['DOB'];
                $line['DOFA'] == '' ? $dofa = null : $dofa = $line['DOFA'];
                $line['DOPA'] == '' ? $dopa = null : $dopa = $line['DOPA'];
                $line['NEW RANK'] == '' ? $new_rank = null : $new_rank = $line['NEW RANK'];
                $line['NEW GL'] == '' ? $promotion_gl = null : $promotion_gl = $line['NEW GL'];
                $line['EFFECTIVE DATE'] == '' ? $effective_date = null : $effective_date = $line['EFFECTIVE DATE'];
                $line['YEAR'] == '' ? $year = null : $year = $line['YEAR'];
                
                $effective_date = date('d-m-Y', strtotime($effective_date));

                $present_rank = Rank::where('short_title', $old_rank)->first();
                $promotion_rank = Rank::where('short_title', $new_rank)->first();
                $ref_number = Str::random(12);
                $candidate = Promotion::updateOrInsert(
                    ['ref_number' => $ref_number],
                    [
                    'serial_no' => $serial_no,
                    'svc_no' => $svc_no,
                    'name' => $name,
                    'dob' => $dob,
                    'soo' => $soo,
                    'command' => ucwords($command),
                    'command_type' => $command_type,
                    'dofa' => $dofa,
                    'dopa' => $dopa,
                    'present_rank_full' => $present_rank->full_title,
                    'present_rank_short' => $present_rank->short_title,
                    'present_gl' => $present_gl,
                    'promotion_rank_full' => $promotion_rank->full_title,
                    'promotion_rank_short' => $promotion_rank->short_title,
                    'promotion_gl' => $promotion_gl,
                    'effective_date' => $effective_date,
                    'year' => $year,
                    'ref_number' => $ref_number,
                    'created_at' =>  Carbon::now(),
                    'updated_at' =>  Carbon::now()
                    ]
                );
            });
            Alert::success('Promotion records imported successfully!', 'Success!')->autoclose(222500);
            return back();
        }
    }

    // GENERATE SINGLE PROMOTION LETTER
    public function generate_single_junior_promotion_letter(Promotion $candidate){

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

            $current = Carbon::now();
            // $currentDate = $current->format('jS F, Y');
            $currentDate = "25th November, 2021";
            $image = DNS2DFacade::getBarcodePNG("<b>Authentic!</b> find full details of <b>$candidate->name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/promotion/jnr/$candidate->year/$candidate->ref_number", 'QRCODE', 50,50);
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = "Promotion QR Code_$candidate->svc_no.png";
            File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

            // PAGE CONTENT WRAPPER
            $section = $phpWord->addSection([
                'orientation' => 'portrait', 
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.45), 
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.68), 
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.70), 
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1),
                'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1)
            ]);
                // $section->addTextBreak(4);

                // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
                $table = $section->addTable(['width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);
                $table->addRow();

                $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(3), ['valign' => 'bottom'])->addText("NSCDC/NHQ/JP/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", ['size' => 14, 'regular' => true, 'bold' => true]);

                $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.8))->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                $section->addTextBreak(1);

                // $section->addText("NSCDC/NHQ/JP/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", null, [ 'spaceAfter' => 0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                
                // $section->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                // $section->addTextBreak(1);
                
                $section->addText("$candidate->name ".strtoupper("($candidate->present_rank_short)"), ['bold' => true]);
                
                $state = $candidate->command;
                if($candidate->command == 'Zone A Headquarters'){
                    $state = 'Lagos';
                }else if($candidate->command == 'Zone B Headquarters'){
                    $state = 'Kaduna';
                }else if($candidate->command == 'Zone C Headquarters'){
                    $state = 'Bauchi';
                }else if($candidate->command == 'Zone D Headquarters'){
                    $state = 'Minna, Niger';
                }else if($candidate->command == 'Zone E Headquarters'){
                    $state = 'Owerri, Imo';
                }else if($candidate->command == 'Zone F Headquarters'){
                    $state = 'Abeokuta';
                }else if($candidate->command == 'Zone G Headquarters'){
                    $state = 'Benin City, Edo';
                }else if($candidate->command == 'Zone H Headquarters'){
                    $state = 'Makurdi, Benue';
                }else if($candidate->command == 'Zone I Headquarters'){
                    $state = 'Damaturu, Yobe';
                }else if($candidate->command == 'Zone J Headquarters'){
                    $state = 'Osun';
                }else if($candidate->command == 'Zone K Headquarters'){
                    $state = 'Awka, Anambra';
                }else if($candidate->command == 'Zone L Headquarters'){
                    $state = 'Port Harcourt, Rivers';
                }else if($candidate->command == 'Zone M Headquarters'){
                    $state = 'Sokoto';
                }else if($candidate->command == 'Zone N Headquarters'){
                    $state = 'Kano';
                }else if($candidate->command == 'Zone O'){
                    $state = 'FCT, Abuja';
                }
                    
                if($candidate->command_type == 'state'){
                    $section->addText("Ufs:  The State Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($candidate->command)." State Command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($state)." state.");
                }
                elseif($candidate->command_type == 'zone'){
                    $section->addText("Ufs:  The Zonal Commander,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($state)." state.");
                }
                elseif($candidate->command_type == 'sa'){
                    
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         FCT, Abuja.");
                }
                elseif($candidate->command_type == 'kc'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Katsina state.");
                }
                elseif($candidate->command_type == 'oc'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Ogun state.");
                }
                elseif($candidate->command_type == 'll'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Lagos state.");
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
                elseif($candidate->command_type == 'elo'){
                    $section->addText("Ufs:  The Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Zone 5, Fct Abuja.");
                }
                $section->addTextBreak(1, ['size' => 8]);

                // TITLE HERE ////////////////////////////////////////////////
                $section->addText("LETTER OF PROMOTION", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);

                // BODY OF LETTER //////////////////////////////////////////////////////////////////
                $fisrtPara = $section->addTextRun(['lineHeight' => 1.5, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText("I am pleased to inform you that sequel to your performance at the ".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year." promotion examination, the Commandant General has approved your promotion from the rank of", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText(" $candidate->present_rank_full ($candidate->present_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS 0$candidate->present_gl)", ['bold' => true]);
                $fisrtPara->addText(" to the rank of");
                $fisrtPara->addText(" $candidate->promotion_rank_full ($candidate->promotion_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS 0$candidate->promotion_gl)", ['bold' => true]);
                $fisrtPara->addText(" with effect from");
                $fisrtPara->addText(" ".date('d/m/Y', strtotime($candidate->effective_date)).".", ['bold' => true]);

                $section->addText('2.       Notice of the promotion will be published in the official gazette soon.', 
                null, 
                [
                     'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH 
                ]);

                $section->addText('3.       Please accept my hearty congratulations.', 
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

                $section->addImage(storage_path()."/app/docs/Promotion QR Code_$candidate->svc_no.png", ['width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

                
                $footer = $section->addFooter();
                $footer->addText("Please ensure QR Code scanning to authenticate the genuineness of this letter.", ['name' => 'calibri', 'size' => 12, 'italic' => true, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $section->addTextBreak(1, ['size' => 8]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/docs/'.$candidate->name.'.docx'));
        return response()->download(storage_path('app/docs/'.$candidate->name.'.docx'));
    }

    
    // GENERATE BULK PROMOTION LETTERS
    public function generate_bulk_junior_promotion_letter(Request $request){

        $candidates = Promotion::orderByRaw("FIELD(promotion_rank_full, 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->find($request->candidates);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        foreach($candidates as $candidate){

            $current = Carbon::now();
            // $currentDate = $current->format('jS F, Y');
            $currentDate = '25th November, 2021';
            $image = DNS2DFacade::getBarcodePNG("<b>Authentic!</b> find full details of <b>$candidate->name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/promotion/jnr/$candidate->year/$candidate->ref_number", 'QRCODE', 50,50);
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = "Promotion QR Code_$candidate->svc_no.png";
            File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

            // PAGE CONTENT WRAPPER
            $section = $phpWord->addSection([
                'orientation' => 'portrait', 
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.45), 
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.68), 
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.70), 
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1),
                'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1)
            ]);
                // $section->addTextBreak(4);

                // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
                $table = $section->addTable(['width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);
                $table->addRow();

                $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(3), ['valign' => 'bottom'])->addText("NSCDC/NHQ/JP/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", ['size' => 14, 'regular' => true, 'bold' => true]);

                $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.8))->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                $section->addTextBreak(1);

                // $section->addText("NSCDC/NHQ/JP/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", null, [ 'spaceAfter' => 0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                
                // $section->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                // $section->addTextBreak(1);
                
                $section->addText("$candidate->name ".strtoupper("($candidate->present_rank_short)"), ['bold' => true]);
                    $state = $candidate->command;
                    if($candidate->command == 'Zone A Headquarters'){
                        $state = 'Lagos';
                    }else if($candidate->command == 'Zone B Headquarters'){
                        $state = 'Kaduna';
                    }else if($candidate->command == 'Zone C Headquarters'){
                        $state = 'Bauchi';
                    }else if($candidate->command == 'Zone D Headquarters'){
                        $state = 'Minna, Niger';
                    }else if($candidate->command == 'Zone E Headquarters'){
                        $state = 'Owerri, Imo';
                    }else if($candidate->command == 'Zone F Headquarters'){
                        $state = 'Abeokuta';
                    }else if($candidate->command == 'Zone G Headquarters'){
                        $state = 'Benin City, Edo';
                    }else if($candidate->command == 'Zone H Headquarters'){
                        $state = 'Makurdi, Benue';
                    }else if($candidate->command == 'Zone I Headquarters'){
                        $state = 'Damaturu, Yobe';
                    }else if($candidate->command == 'Zone J Headquarters'){
                        $state = 'Osun';
                    }else if($candidate->command == 'Zone K Headquarters'){
                        $state = 'Awka, Anambra';
                    }else if($candidate->command == 'Zone L Headquarters'){
                        $state = 'Port Harcourt, Rivers';
                    }else if($candidate->command == 'Zone M Headquarters'){
                        $state = 'Sokoto';
                    }else if($candidate->command == 'Zone N Headquarters'){
                        $state = 'Kano';
                    }else if($candidate->command == 'Zone O'){
                        $state = 'FCT, Abuja';
                    }
                    
                if($candidate->command_type == 'state'){
                    $section->addText("Ufs:  The State Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($candidate->command)." State Command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($state)." state.");
                }
                elseif($candidate->command_type == 'zone'){
                    $section->addText("Ufs:  The Zonal Commander,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($state)." state.");
                }
                elseif($candidate->command_type == 'sa'){
                    
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         FCT, Abuja.");
                }
                elseif($candidate->command_type == 'kc'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucwords($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Katsina state.");
                }
                elseif($candidate->command_type == 'oc'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Ogun state.");
                }
                elseif($candidate->command_type == 'll'){
                    $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Lagos state.");
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
                elseif($candidate->command_type == 'elo'){
                    $section->addText("Ufs:  The Commandant,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
                    $section->addText("         ".ucfirst($candidate->command).",", null, [ 'spaceAfter' => 0]);
                    $section->addText("         Zone 5, Fct Abuja.");
                }
                $section->addTextBreak(1, ['size' => 8]);

                // TITLE HERE ////////////////////////////////////////////////
                $section->addText("LETTER OF PROMOTION", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);

                // BODY OF LETTER //////////////////////////////////////////////////////////////////
                $fisrtPara = $section->addTextRun(['lineHeight' => 1.5, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText("I am pleased to inform you that sequel to your performance at the ".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year." promotion examination, the Commandant General has approved your promotion from the rank of", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
                $fisrtPara->addText(" $candidate->present_rank_full ($candidate->present_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS 0$candidate->present_gl)", ['bold' => true]);
                $fisrtPara->addText(" to the rank of");
                $fisrtPara->addText(" $candidate->promotion_rank_full ($candidate->promotion_rank_short)", ['bold' => true]);
                $fisrtPara->addText(" on");
                $fisrtPara->addText(" (CONPASS 0$candidate->promotion_gl)", ['bold' => true]);
                $fisrtPara->addText(" with effect from");
                $fisrtPara->addText(" ".date('d/m/Y', strtotime($candidate->effective_date)).".", ['bold' => true]);

                $section->addText('2.       Notice of the promotion will be published in the official gazette soon.', 
                null, 
                [
                     'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH 
                ]);

                $section->addText('3.       Please accept my hearty congratulations.', 
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
                $section->addText('ADAMU SALIHU', ['bold' => true], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('Commandant Administration', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addText('For: Commandant General', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
                $section->addTextBreak(1, ['size' => 4]);

                $section->addImage(storage_path()."/app/docs/Promotion QR Code_$candidate->svc_no.png", ['width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

                
                $footer = $section->addFooter();
                $footer->addText("Please ensure QR Code scanning to authenticate the genuineness of this letter.", ['name' => 'calibri', 'size' => 12, 'italic' => true, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $section->addTextBreak(1, ['size' => 8]);

        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/docs/junior_promotion_letter.docx'));
        return response()->download(storage_path('app/docs/junior_promotion_letter.docx'));
    }


    
    // DISPLAY JNR PROMOTION LIST
    public function manage_snr(Request $request){
        return back();
    }
}
