<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Formation;
use App\Models\Rank;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Milon\Barcode\DNS2D;
use Milon\Barcode\Facades\DNS2DFacade;
use Rap2hpoutre\FastExcel\FastExcel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    // DISPLAY APPOINTMENT CAT
    public function manage(){
        // if(auth()->user()->service_number !== 66818){
        //     return redirect()->back();
        // }
        abort_unless(auth()->user()->hasRole('super admin') || auth()->user()->hasAnyPermission(['view progression','create progression','edit progression','delete progression',]), 401, 'You don\'t have clearance to access this page.' );

        return view('dashboard.appointment.manage');
    }

    // DISPLAY JNR PROMOTION LIST
    public function manage_appointment(Request $request, $year){
        // return Appointment::where('year', $year)->orderBy('updated_at', 'DESC')->get();
        // if(auth()->user()->service_number !== 66818){
        //     return redirect()->back();
        // }
        $formations = Formation::all();
        $ranks = Rank::get(['full_title', 'short_title']);
        $states = State::all();
        return view('dashboard.appointment.all', compact(['year','formations', 'states', 'ranks']));
    }

    // GET PROMOTION LIST
    public function get_all($year){

        $appointment = Appointment::where('year', $year)->orderBy('updated_at', 'DESC')->get();
        return DataTables::of($appointment)
        // ->editColumn('updated_at', function ($appointment) {
        //     return $appointment->updated_at->toFormattedDateString();
        //     // return $redeployment->created_at->toDateString();
        // })
        ->addColumn('view', function($appointment) {
            return '
                <a href="'.route('generate_single_appointment_letter', $appointment->id).'" style="margin-right:5px;" class="light-blue-text text-darken-3" title="Print appointment letter"><i class="fas fa-file-word fa-lg"></i></a>
                <a href="#" style="margin-right:5px;" class="light-blue-text text-darken-3" title="Edit appointment record" data-pro_id="'.$appointment->id.'" onclick="editAppointment(event)"><i class="fas fa-edit fa-lg"></i></a>
            ';
        })
        ->addColumn('checkbox', function($redeployment) {
            return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
        })
        ->rawColumns(['view', 'checkbox'])
        ->make();
    }

    // IMPORT STORE IMPORTED PROMOTION DATA
    public function store_imported_promotion(Request $request)
    {  
        $request->validate([
            'import_file' => 'required'
        ]);
        
        $path = $request->file('import_file')->getRealPath();
        $data = (new FastExcel)->import($path);
        // \dd($data[0]);
        if($data->count()){
            try {
                $candidates = (new FastExcel)->import($path, function ($line) {
                    $line['tsa'] == '' ? $tsa = null : $tsa = $line['tsa'];
                    $line['num'] == '' ? $num = null : $num = $line['num'];
                    $line['application_code'] == '' ? $application_code = null : $application_code = $line['application_code'];
                    $line['name'] == '' ? $name = null : $name = $line['name'];
                    $line['email'] == '' ? $email = null : $email = $line['email'];
                    // $line['date_of_birth'] == '' ? $date_of_birth = null : $date_of_birth = $line['date_of_birth'];
                    $line['mobile_number'] == '' ? $mobile_number = null : $mobile_number = $line['mobile_number'];
                    $line['gender'] == '' ? $gender = null : $gender = $line['gender'];
                    $line['position'] == '' ? $position = null : $position = $line['position'];
                    $line['state'] == '' ? $state = null : $state = $line['state'];
                    $line['lga'] == '' ? $lga = null : $lga = $line['lga'];
                    $line['time'] == '' ? $time = null : $time = $line['time'];
                    // $line['date'] == '' ? $date = null : $date = $line['date'];
                    $line['day'] == '' ? $day = null : $day = $line['day'];
                    $line['amount'] == '' ? $amount = null : $amount = $line['amount'];
                    $line['id_number'] == '' ? $id_number = null : $id_number = $line['id_number'];
                    
                    $rank_applied = Rank::where('full_title', $position)->first();
    
                    $dNS2D = new DNS2D();
    
                    $candidate = Appointment::updateOrInsert(
                        ['id_number' => $id_number],
                        [
                        'tsa' => $tsa,
                        'num' => $num,
                        'application_code' => $application_code,
                        'name' => ucwords($name),
                        'email' => $email,
                        // 'date_of_birth' => $date_of_birth,
                        'mobile_number' => $mobile_number,
                        'gender' => $gender,
                        'position' => $position,
                        'state' => $state,
                        'lga' => $lga,
                        'year' => 2019,
                        'time' => $time,
                        // 'date' => $date,
                        'day' => $day,
                        'amount' => $amount,
                        'id_number' => $id_number,
                        'barcode' => $dNS2D->getBarcodePNG("<b>Authentic!</b> find full details of <b>$name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/appointment/2019/$id_number", 'QRCODE', 50,50),
                        ]
                    );
                });
                Alert::success('Promotion records imported successfully!', 'Success!')->autoclose(222500);
                return back();
            } catch (\Throwable $th) {
                $index = trim(explode(":", $th->getMessage())[1]); 
                return back()->withErrors($index, 'import');
            }
        }
    }

    // IMPORT UPDATE IMPORTED PROMOTION DATA
    public function edit(Appointment $appointment)
    {
        return response()->json($appointment);
    }
    
    // IMPORT UPDATE IMPORTED PROMOTION DATA
    public function update(Request $request)
    {
        $validated = $request->validate([
            'tsa' => 'required|integer',
            'num' => 'required|integer',
            'application_code' => 'required|string',
            'name' => 'required',
            'email' => 'required|string',
            'position' => 'required|string',
            'state' => 'required|string',
            'day' => 'required|string',
            'amount' => 'required',
            'id_number' => 'required|string'
        ]);

        $dNS2D = new DNS2D();

        $update = Appointment::find($request->id)->update([
            'tsa' => $validated['tsa'],
            'num' => $validated['num'],
            'application_code' => $validated['application_code'],
            'name' => ucwords($validated['name']),
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'mobile_number' => $request->mobile_number,
            'gender' => $request->gender,
            'position' => $validated['position'],
            'state' => $validated['state'],
            'lga' => $request->lga,
            'year' => 2019,
            'time' => $request->time,
            'date' => $request->date,
            'day' => $validated['day'],
            'amount' => $validated['amount'],
            'id_number' => $validated['id_number'],
            'barcode' => $dNS2D->getBarcodePNG("<b>Authentic!</b> find full details of <b>".$validated['name']."</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/appointment/2019/".$validated['id_number']."", 'QRCODE', 50,50),
        ]);

        if($update){
            Alert::success('Appointment record updated successfully!', 'Success!')->autoclose(2500);
            return back();
        }
    }
    
    // DELETE PROMOTION DATA
    public function delete(Request $request)
    {
        $delete = $request->delete_id;
        Appointment::find($delete)->delete();
        Alert::success('Appointment record trashed successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }

    // GENERATE SINGLE PROMOTION LETTER
    public function generate_single_appointment_letter(Appointment $candidate){

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template-universal.docx'));
        $gl = Rank::where('full_title', $candidate->position)->pluck('gl')->first();
        $templateProcessor->setValue('num', $candidate->num);
        $templateProcessor->setValue('name', strtoupper($candidate->name));
        $templateProcessor->setValue('state', strtoupper($candidate->state));
        $templateProcessor->setValue('position', strtoupper($candidate->position));
        $templateProcessor->setValue('gl', $gl);
        $templateProcessor->setValue('amount', number_format($candidate->amount, 2));
        $templateProcessor->setImageValue('barcode', "data:image/png;base64,$candidate->barcode");
        $templateProcessor->setValue('id_number', $candidate->id_number);
        
        // $templateProcessor->setImageValue('barcode', "data:image/png;base64,$candidate->barcode");
        $templateProcessor->saveAs(storage_path('app/docs/'.$candidate->name.'.docx'));
        return response()->download(storage_path('app/docs/'.$candidate->name.'.docx'));
    }


    // GENERATE BULK PROMOTION LETTERS
    public function generate_bulk_appointment_letter(Request $request){

        function formatNbr($nbr){
            if ($nbr < 10)
                return "000".$nbr;
            elseif ($nbr >= 10 && $nbr < 100 )
                return "00".$nbr;
            elseif ($nbr >= 100 && $nbr < 1000 )
                return "0".$nbr;
            elseif ($nbr >= 1000 )
                return $nbr;
            else
                return strval($nbr);
        }

        $candidates = Appointment::orderByRaw("FIELD(position, 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->find($request->candidates);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(13);

        foreach($candidates as $candidate){

            $image = DNS2DFacade::getBarcodePNG("<b>Authentic!</b> find full details of <b>$candidate->name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/appointment/$candidate->year/$candidate->id_number", 'QRCODE', 50,50);
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = "Appointment QR Code_$candidate->application_code.png";
            File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

            $gl = Rank::where('full_title', $candidate->position)->pluck('gl')->first();

            // PAGE CONTENT WRAPPER
            $section = $phpWord->addSection([
                'orientation' => 'portrait', 
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.75),
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.40),
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.08),
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.7),
                'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.3)
            ]);

            

            // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
            $section->addText("Ref No: NSCDC/NHQ/APT/2019/".formatNbr($candidate->num), ['bold' => true, 'italic' => true], [ 'spaceAfter' => 0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
            $section->addText("31st January, 2022", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
            // $section->addTextBreak(1);
            $section->addText(strtoupper($candidate->name), ['bold' => true], [ 'spaceAfter' => 0]);
            $section->addText(strtoupper($candidate->state), ['bold' => true], [ 'spaceAfter' => 0]);
            $section->addTextBreak(1, [], [ 'spaceAfter' => 0]);
            $section->addText('Sir/Madam', [], [ 'spaceAfter' => 0]);
            $section->addTextBreak(1, [], [ 'spaceAfter' => 0]);

            // // TITLE HERE ////////////////////////////////////////////////
            $section->addText("PROVISIONAL OFFER OF APPOINTMENT", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);

            // // BODY OF LETTER //////////////////////////////////////////////////////////////////
            $paraStyle = ['lineHeight' => 1.0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 250 ];
            $fisrtPara = $section->addTextRun($paraStyle);
            $fisrtPara->addText("I am directed to refer to your recent application for employment and pleased to inform you that you have been offered appointment as follows:", null, null);
            // $fisrtPara->addTextBreak(1, ['size' => 8], [ 'spaceAfter' => 0]);
            
            $listParaStyle = [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::START, 'indent' => 0.6, 'spaceAfter' => 0, 'spaceBefore' => 0, 'lineHeight' => 1 ];
            $list = $section->addTextRun($listParaStyle);
            $list->addText('i.    Rank: ', null, null);
            $list->addText(strtoupper($candidate->position), ['bold' => true], [ 'spaceAfter' => 0]);

            $list = $section->addTextRun($listParaStyle);
            $list->addText('ii.   Nature of Appointment: ', null, null);
            $list->addText('GENERAL DUTY', ['bold' => true], null);

            $list = $section->addTextRun($listParaStyle);
            $list->addText('iii.  Salary Grade Level/Step: ', null, null);
            $list->addText($gl, ['bold' => true], null);
            $list->addText(' Step 2', ['bold' => true], null);

            $naira = html_entity_decode('&#8358;', 0, 'UTF-8');

            $list = $section->addTextRun([ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::START, 'indent' => 0.6, 'spaceAfter' => 250, 'spaceBefore' => 0, 'lineHeight' => 1 ]);
            $list->addText('iv.  Basic Salary: ', null, null);
            $list->addText("$naira".number_format($candidate->amount, 2), ['bold' => true], null);
            $list->addText(' (per annum)', null, null);


            $secondPara = $section->addTextRun($paraStyle);
            $secondPara->addText("2.    Your appointment is subject to the Conditions of Service of ", null, null);
            $secondPara->addText("the Nigeria Security and Civil Defence Corps, ", ['bold' => true], null);
            $secondPara->addText("the ", null, null);
            $secondPara->addText("Public Service Rules and Other Regulations ", ['bold' => true], null);
            $secondPara->addText("of the ", null, null);
            $secondPara->addText("Civil Defence, Correctional, Fire and Immigration Services Board (CDCFIB).", ['bold' => true], null);
            // $secondPara->addTextBreak(1, null, [ 'spaceAfter' => 0, 'spaceBefore' => 0, 'lineHeight' => 1]);
            
            $thirdPara = $section->addTextRun($paraStyle);
            $thirdPara->addText("3.     Your appointment may be confirmed after passing the appropriate confirmation examination and two years of satisfactory service.", null, null);
            // $thirdPara->addTextBreak(1, ['size' => 8], [ 'spaceAfter' => 0]);
            
            $fouthPara = $section->addTextRun($paraStyle);
            $fouthPara->addText("4.     Your appointment may be terminated at any time by the ", null, null);
            $fouthPara->addText("Nigeria Security and Civil Defence Corps ", ['bold' => true], ['align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
            $fouthPara->addText("or the Board of ", null, null);
            $fouthPara->addText("Civil Defence, Correctional, Fire and Immigration Services Board ", ['bold' => true], ['align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
            $fouthPara->addText("or yourself by giving a month notice in writing.", null, null);
            // $fouthPara->addTextBreak(1, ['size' => 8], [ 'spaceAfter' => 0]);
            
            $fifthPara = $section->addTextRun($paraStyle);
            $fifthPara->addText("5.     You are required to inform this office in writing if you accept this offer within two weeks from the date of this letter, failing which the offer will lapse.", null, null);
            // $fifthPara->addTextBreak(1, ['size' => 8], [ 'spaceAfter' => 0]);
            
            $sixthPara = $section->addTextRun($paraStyle);
            $sixthPara->addText("6.     For regularization of your appointment, you are expected to tender for sighting, originals of your credentials.", null, null);
            // $sixthPara->addTextBreak(1, ['size' => 8], [ 'spaceAfter' => 0]);
            
            $seventhPara = $section->addTextRun($paraStyle);
            $seventhPara->addText("7.     Congratulations!!!", ['bold' => true, 'italic' => true], null);
            // $seventhPara->addTextBreak(1, ['size' => 8], [ 'spaceAfter' => 0]);

            $section->addImage(storage_path()."/app/docs/Appointment QR Code_$candidate->application_code.png", [
                'width' => 60, 'height' => 60, 
                'wrappingStyle' => 'infront',
                'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
                'positioning' => 'absolute',
                'posHorizontalRel' => 'margin',
                'posVerticalRel' => 'line',
            ]);

            $section->addImage(storage_path().'/app/docs/SVG/dcg_admin_sign4.png', [
                'width' => 200,
                'wrappingStyle' => 'behind',
                'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
                'positioning' => 'absolute',
                'posHorizontalRel' => 'margin',
                'posVerticalRel' => 'line',
            ]);

             // FOOTER SIGNATURE //////////////////////////////////////////////////////////////////
            $section->addText('ZAKARI IBRAHIM NINGI, fdc', ['bold' => true], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
            $section->addText('Ag. Deputy Commandant General (Administration)', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
            $section->addText('For: Commandant General', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
            
            $footer = $section->addFooter();
            $footer->addText($candidate->id_number, [
                'size' => 10, 'italic' => true, 'bold' => true
            ], 
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END
            ]);

        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/docs/appointment_letter.docx'));
        return response()->download(storage_path('app/docs/appointment_letter.docx'));
    }



}
