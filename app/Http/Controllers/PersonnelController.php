<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Document;
use App\Models\Formation;
use App\Models\Lga;
use App\Models\Permission;
use App\Models\Rank;
use App\Models\Role;
use App\Models\State;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PersonnelController extends Controller
{

    // CONSTRUCTOR
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    // SHOW ALL ACTIVE PERSONNEL
    public function index()
    {
        abort_unless(auth()->user()->hasRole('super admin') || auth()->user()->hasPermissionTo('view personnel list'), 401, 'You don\'t have clearance to access this page.' );

        return view('dashboard.personnel.all');
    }
    public function get_all(){
        $personnel = User::whereDate('dofa', '2022-01-31')
        ->orderByRaw(
            "FIELD(
                rank_full, 
                'Commandant General of Corps', 
                'Deputy Commandant General of Corps', 
                'Assistant Commandant General of Corps', 
                'Commandant of Corps', 
                'Deputy Commandant of Corps', 
                'Assistant Commandant of Corps', 
                'Chief Superintendent of Corps', 
                'Superintendent of Corps', 
                'Deputy Superintendent of Corps', 
                'Assistant Superintendent of Corps I', 
                'Assistant Superintendent of Corps II', 
                'Chief Inspector of Corps', 
                'Deputy Chief Inspector of Corps', 
                'Assistant Chief Inspector of Corps', 
                'Principal Inspector of Corps I', 
                'Principal Inspector of Corps II', 
                'Senior Inspector of Corps', 
                'Inspector of Corps', 
                'Assistant Inspector of Corps', 
                'Chief Corps Assistant', 
                'Senior Corps Assistant', 
                'Corps Assistant I', 
                'Corps Assistant II', 
                'Corps Assistant III'
            )"
        )->orderBy('updated_at', 'DESC');

        return DataTables::of($personnel)
            ->editColumn('name', function ($personnel) {
                return "<b><a href=\"/dashboard/personnel/$personnel->id/show\">$personnel->name</a></b>";
            })
            ->editColumn('updated_at', function ($personnel) {
                return Carbon::create($personnel->updated_at)->diffForHumans();
            })
            ->addColumn('checkbox', function($redeployment) {
                return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
            })
            ->addColumn('passport', function($redeployment) {
                if(!$redeployment->passport){
                    return '
                    <span style="margin-left:5px;" class="red-text" title="push to cloud"><i class="material-icons">close</i></span>
                    ';
                }else{
                    return '
                        <span style="margin-left:5px;" class="green-text"><i class="fad fa-check-double fa-lg"></i></span>
                    ';
                }
            })
            ->rawColumns(['name', 'checkbox', 'passport'])
            ->make();
    }
    public function unsynched(){
        $users = User::whereDate('dofa', '2019-01-01')->where('synched', 0)->with(['noks', 'children', 'progressions', 'qualifications'])->get();
        return response()->json($users);
    }
    

    // CREATE NEW PERSONNEL
    public function create()
    {

        abort_unless(auth()->user()->hasRole('super admin') || auth()->user()->hasPermissionTo('create personnel'), 401, 'You don\'t have clearance to access this page.' );

        $banks = array(
            array('id' => '1','name' => 'Access Bank','code'=>'044'),
            array('id' => '2','name' => 'Citibank','code'=>'023'),
            array('id' => '3','name' => 'Diamond Bank','code'=>'063'),
            array('id' => '4','name' => 'Dynamic Standard Bank','code'=>''),
            array('id' => '5','name' => 'Ecobank Nigeria','code'=>'050'),
            array('id' => '6','name' => 'Fidelity Bank Nigeria','code'=>'070'),
            array('id' => '7','name' => 'First Bank of Nigeria','code'=>'011'),
            array('id' => '8','name' => 'First City Monument Bank','code'=>'214'),
            array('id' => '9','name' => 'Guaranty Trust Bank','code'=>'058'),
            array('id' => '10','name' => 'Heritage Bank Plc','code'=>'030'),
            array('id' => '11','name' => 'Jaiz Bank','code'=>'301'),
            array('id' => '12','name' => 'Keystone Bank Limited','code'=>'082'),
            array('id' => '13','name' => 'Providus Bank Plc','code'=>'101'),
            array('id' => '14','name' => 'Polaris Bank','code'=>'076'),
            array('id' => '15','name' => 'Stanbic IBTC Bank Nigeria Limited','code'=>'221'),
            array('id' => '16','name' => 'Standard Chartered Bank','code'=>'068'),
            array('id' => '17','name' => 'Sterling Bank','code'=>'232'),
            array('id' => '18','name' => 'Suntrust Bank Nigeria Limited','code'=>'100'),
            array('id' => '19','name' => 'Union Bank of Nigeria','code'=>'032'),
            array('id' => '20','name' => 'United Bank for Africa','code'=>'033'),
            array('id' => '21','name' => 'Unity Bank Plc','code'=>'215'),
            array('id' => '22','name' => 'Wema Bank','code'=>'035'),
            array('id' => '23','name' => 'Zenith Bank','code'=>'057')
        );
        
        $pfas = array(
            array('id' => '1','name' => 'AIICO Pension Managers Limited','code'=>'044'),
            array('id' => '2','name' => 'APT Pension Fund Managers Limited','code'=>'023'),
            array('id' => '3','name' => 'ARM Pension Managers Limited','code'=>'063'),
            array('id' => '4','name' => 'AXA Mansard Pension Limited (Tangerine)','code'=>''),
            array('id' => '5','name' => 'CrusaderSterling Pensions Limited','code'=>'050'),
            array('id' => '6','name' => 'FCMB Pensions Limited','code'=>'070'),
            array('id' => '7','name' => 'Fidelity Pension Managers','code'=>'011'),
            array('id' => '8','name' => 'First Guarantee Pension Limited','code'=>'214'),
            array('id' => '9','name' => 'IEI-Anchor Pension Managers Limited','code'=>'058'),
            array('id' => '10','name' => 'Investment One Pension Managers Limited','code'=>'030'),
            array('id' => '11','name' => 'Leadway Pensure PFA Limited','code'=>'301'),
            array('id' => '12','name' => 'Nigerian University Pension Management Company (NUPEMCO)','code'=>'082'),
            array('id' => '13','name' => 'NLPC Pension Fund Administrators Limited','code'=>'101'),
            array('id' => '14','name' => 'NPF Pensions Limited','code'=>'076'),
            array('id' => '15','name' => 'OAK Pensions Limited','code'=>'221'),
            array('id' => '16','name' => 'Pensions Alliance Limited','code'=>'068'),
            array('id' => '17','name' => 'Premium Pension Limited','code'=>'232'),
            array('id' => '18','name' => 'Radix Pension Managers Limited','code'=>'100'),
            array('id' => '19','name' => 'Sigma Pensions Limited','code'=>'032'),
            array('id' => '20','name' => 'Stanbic IBTC Pension Managers Limited','code'=>'033'),
            array('id' => '21','name' => 'Trustfund Pensions Limited','code'=>'215'),
            array('id' => '22','name' => 'Veritas Glanvills Pensions Limited','code'=>'035'),
        );

        $formations = Formation::all();

        return view('dashboard.personnel.new', compact(['formations', 'banks','pfas']));
    }

    // STORE NEW PERSONNEL
    public function store(Request $request)
    {
        // return $request;
        $validation = $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date',
            'soo' => 'required',
            'lgoo' => 'required',
            'cadre' => 'required|string',
            'gl' => 'required|numeric',
            'step' => 'required|numeric',
            'service_number' => 'required|numeric|unique:users,service_number',
            'dofa' => 'required|date',
            'dopa' => 'required|date',
            'email' => 'required|email|unique:users,email'
        ]);

        $image_name = NULL;
        if($request->has('passport')){

            $val = $request->validate([
                'passport' => 'required|image|mimes:jpeg,png,jpg,|max:800',
            ]);

            $file = $request->file('passport');
            $image = $file->getClientOriginalName();
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = $request->service_number.'.'.$ext;
            $file->storeAs('public/documents/'.$request->service_number.'/passport/', $image_name);
            // $image->storeAs('public/documents/'.$request->service_number.'/passport/', $image->getClientOriginalName());
        }

        $formation_name = Formation::where('id', $request->command)->first('formation')->formation;
        $rank = Rank::where('cadre', $request->cadre)->where('gl', $request->gl)->first();
        $personnel = User::create([
            'username' => $request->service_number,
            'name' => $request->name,
            'dob' => $request->dob,
            'sex' => $request->sex,
            'marital_status' => $request->marital_status,
            'date_of_marriage' => $request->date_of_marriage,
            'name_of_spouse' => $request->name_of_spouse,
            'religion' => $request->religion,
            'blood_group' => $request->blood_group,
            'genotype' => $request->genotype,
            'height' => $request->height,
            'weight' => $request->weight,
            'soo' => $request->soo,
            'lgoo' => $request->lgoo,
            'place_of_birth' => $request->place_of_birth,
            'residential_address' => $request->residential_address,
            'permanent_address' => $request->permanent_address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'cadre' => $request->cadre,
            'gl' => $request->gl,
            'step' => $request->step,
            'rank_full' => $rank->full_title,
            'rank_short' => $rank->short_title,
            'service_number' => $request->service_number,
            'password' => Hash::make($request->service_number.$request->phone),
            'dofa' => $request->dofa,
            'doc' => $request->doc,
            'dopa' => $request->dopa,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
            'bvn' => $request->bvn,
            'paypoint' => $request->paypoint,
            'salary_structure' => $request->salary_structure,
            'nin_number' => $request->nin_number,
            'nhis_number' => $request->nhis_number,
            'ippis_number' => $request->ippis_number,
            'nhf' => $request->nhf,
            'pfa' => $request->pfa,
            'pen_number' => $request->pen_number,
            'current_formation' => $formation_name
        ]);
        if($personnel){

            $rank = Rank::where('cadre', $request->cadre)->where('gl', $request->gl)->first();

            $personnel->progressions()->create([
                'type' => 'Entry Rank',
                'cadre' => $request->cadre,
                'gl' => $request->gl,
                'rank_full' => $rank->full_title,
                'rank_short' => $rank->short_title,
                'effective_date' => $request->dopa,
            ]);

            if($request->has('file')){
                $images = $request->file('file');
                foreach($images as $image)
                {
                    $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $image->storeAs('public/documents/'.$personnel->service_number.'/', $image->getClientOriginalName());

                    $upload = User::find($personnel->id)->documents()->create([
                        'title' => $file_name,
                        'file' => $image->getClientOriginalName()
                    ]);
                }
            }
            $personnel->formations()->attach($request->command, [
                'command' => $formation_name
            ]);
            Alert::success('Personnel record added successfully!', 'Success!')->autoclose(2500);
            return redirect()->route('personnel_create');
        }

    }

    // IMPORT NEW DATA
    public function import_data(Request $request){
        return view('dashboard/personnel/import');
    }

    // STORE IMPORTED PERSONNEL
    public function store_imported_users(Request $request)
    {
        $request->validate([
            'import_file' => 'required'
        ]);
        $path = $request->file('import_file')->getRealPath();
        $data = (new FastExcel)->import($path);
        // \dd($data);
        if($data->count()){
            $users = (new FastExcel)->import($path, function ($line) {
                $line['name'] == '' ? $name = null : $name = $line['name'];
                $line['sex'] == '' ? $sex = null : $sex = $line['sex'];
                $line['dob'] == '' ? $dob = null : $dob = $line['dob'];
                $line['marital_status'] == '' ? $marital_status = null : $marital_status = $line['marital_status'];
                $line['soo'] == '' ? $soo = null : $soo = $line['soo'];
                $line['lgaoo'] == '' ? $lgaoo = null : $lgaoo = $line['lgaoo'];
                $line['phone_number'] == '' ? $phone_number = null : $phone_number = $line['phone_number'];
                $line['service_number'] == '' ? $service_number = null : $service_number = $line['service_number'];
                $line['dofa'] == '' ? $dofa = null : $dofa = $line['dofa'];
                $line['doc'] == '' ? $doc = null : $doc = $line['doc'];
                $line['dopa'] == '' ? $dopa = null : $dopa = $line['dopa'];
                $line['cadre'] == '' ? $cadre = null : $cadre = $line['cadre'];
                $line['gl'] == '' ? $gl = null : $gl = $line['gl'];
                $line['paypoint'] == '' ? $paypoint = null : $paypoint = $line['paypoint'];
                $line['salary_structure'] == '' ? $salary_structure = null : $salary_structure = $line['salary_structure'];
                $line['bank'] == '' ? $bank = null : $bank = $line['bank'];
                $line['account_number'] == '' ? $account_number = null : $account_number = $line['account_number'];
                $line['bvn'] == '' ? $bvn = null : $bvn = $line['bvn'];
                $line['ippis_number'] == '' ? $ippis_number = null : $ippis_number = $line['ippis_number'];
                $line['nin_number'] == '' ? $nin_number = null : $nin_number = $line['nin_number'];
                $line['nhis_number'] == '' ? $nhis_number = null : $nhis_number = $line['nhis_number'];
                $line['nhf'] == '' ? $nhf = null : $nhf = $line['nhf'];
                $line['pfa'] == '' ? $pfa = null : $pfa = $line['pfa'];
                $line['pen_number'] == '' ? $pen_number = null : $pen_number = $line['pen_number'];
                $line['qualifications'] == '' ? $qualifications = null : $qualifications = $line['qualifications'];
                $line['qualifications_year'] == '' ? $qualifications_year = null : $qualifications_year = $line['qualifications_year'];
                $line['specialization'] == '' ? $specialization = null : $specialization = $line['specialization'];
                $line['command'] == '' ? $formation = null : $formation = $line['command'];
                $line['department'] == '' ? $department = null : $department = $line['department'];
                
                $rank = Rank::where(['gl'=>$gl, 'cadre'=>strtolower($cadre)])->first();
                $rank = $rank == NULL ? 'N/A' : $rank->short_title;
                $dob = date('Y-m-d', strtotime($dob));
                $dofa = date('Y-m-d', strtotime($dofa));
                $doc = date('Y-m-d', strtotime($doc));
                $dopa = date('Y-m-d', strtotime($dopa));
                $rank = Rank::where('cadre', $line['cadre'])->where('gl', $line['gl'])->first();

                $user = User::create([
                    'name' => $name,
                    'username' => $service_number,
                    'sex' => $sex,
                    'dob' => $dob,
                    'dob' => $dob,
                    'marital_status' => $marital_status,
                    'soo' => $soo,
                    'lgoo' => $lgaoo,
                    'phone_number' => $phone_number,
                    'service_number' => $service_number,
                    'dofa' => $dofa,
                    'doc' => $doc,
                    'dopa' => $dopa,
                    'cadre' => $cadre,
                    'gl' => $gl,
                    'rank_full' => $rank->full_title,
                    'rank_short' => $rank->short_title,
                    'paypoint' => $paypoint,
                    'salary_structure' => $salary_structure,
                    'bank' => $bank,
                    'account_number' => $account_number,
                    'bvn' => $bvn,
                    'ippis_number' => $ippis_number,
                    'nin_number' => $nin_number,
                    'nhis_number' => $nhis_number,
                    'nhf' => $nhf,
                    'pfa' => $pfa,
                    'pen_number' => $pen_number,
                    'current_formation' => $formation,
                    'current_department' => $department,
                    'specialization' => $specialization
                ]);
                
                $user->deployments()->create([
                    'command' => $line['command'],
                    'department' => $department
                ]);
                
                $user->progressions()->create([
                    'cadre' => $line['cadre'],
                    'gl' => $line['gl'],
                    'rank_full' => $rank->full_title,
                    'rank_short' => $rank->short_title,
                    'effective_date' => $dopa,
                ]);
                
                $qual = explode(',', $qualifications);
                $qual_yr = explode(',', $qualifications_year);
                $count = min(count($qual), count($qual_yr));
                $qualification = array_combine(array_slice($qual, 0, $count), array_slice( $qual_yr, 0, $count));
                foreach($qualification as $quali=>$yr){
                    // echo 'I got '.$.' in the year '.$yr.'</br>';
                    $user->qualifications()->create([
                        'qualification' => $quali,
                        'year_obtained' => $yr,
                    ]);
                }
                
                // $line['nok_name'] == '' ? $nok_name = null : $nok_name = $line['nok_name'];
                // $line['nok_relationship'] == '' ? $nok_relationship = null : $nok_relationship = $line['nok_relationship'];
                // $line['nok_phone'] == '' ? $nok_phone = null : $nok_phone = $line['nok_phone'];

                // return $user->noks()->create([
                //     'name' => $nok_name,
                //     'relationship' => $nok_relationship,
                //     'phone' => $nok_phone
                // ]);

            });
            
            Alert::success('Personnel records imported successfully!', 'Success!')->autoclose(222500);
            return back();
        }
    }

    // SHOW SPECIFIC PERSONNEL
    public function show(User $user)
    {
        $personnel = $user;
        $all_formations = Formation::all();
        $state = State::where('id', $personnel->soo)->first();
        $lga = Lga::where('id', $personnel->lgoo)->first();

        // GET TIME TILL RETIREMENT
        $max_svc_yr = 35;
        $max_age = 60;
        $dofa = Carbon::create($personnel->dofa);
        $dob = Carbon::create($personnel->dob);
        if($dofa >= Carbon::create('2004/1/1')){
            $prop_rt_yr_by_svc = $dofa->addYears($max_svc_yr);
            $prop_rt_yr_by_age = $dob->addYears($max_age);
            if($prop_rt_yr_by_age < $prop_rt_yr_by_svc){
                $ttr = $prop_rt_yr_by_age;
            }else{
                $ttr = $prop_rt_yr_by_svc;
            }
        }else{
            $ttr = 'DOFA/DOB not valid';
        }

        $roles = Role::with(['users' => function($q) use($user){
            $q->where('id', $user->id);
        }])->get();

        $permissions = Permission::with(['users' => function($q) use($user){
            $q->where('id', $user->id);
        }])->get();
        
        return view('dashboard.personnel.show', compact(['personnel', 'state', 'lga', 'all_formations', 'ttr', 'roles', 'permissions']));
    }
    
    public function ros(User $user)
    {

        $personnel = $user;
        $all_formations = Formation::all();
        $state = State::where('id', $personnel->soo)->first();
        $lga = Lga::where('id', $personnel->lgoo)->first();
        $region =  $state != null ? State::find($personnel->soo)->region : 'N/A';
        // GET TIME TILL RETIREMENT
        $max_svc_yr = 35;
        $max_age = 60;
        $dofa = Carbon::create($personnel->dofa);
        $dob = Carbon::create($personnel->dob);
        if($dofa >= Carbon::create('2004/1/1')){
            $prop_rt_yr_by_svc = $dofa->addYears($max_svc_yr);
            $prop_rt_yr_by_age = $dob->addYears($max_age);
            if($prop_rt_yr_by_age < $prop_rt_yr_by_svc){
                $ttr = $prop_rt_yr_by_age;
            }else{
                $ttr = $prop_rt_yr_by_svc;
            }
        }else{
            $ttr = 'DOFA/DOB not valid';
        }
        
        
        // return view('dashboard/personnel/ros', compact(['personnel', 'state', 'lga', 'all_formations', 'ttr']));

        // $pdf = PDF::loadView('dashboard/personnel/ros', compact(['personnel', 'state', 'lga', 'all_formations', 'region', 'ttr']));
        // return $pdf->download($personnel->name.'.pdf');
    }

    // UPLOAD A FILE(S)
    public function upload_file(Request $request, User $user)
    {
        $storage_drive = env("FILESYSTEM_DRIVER");
        
        if($storage_drive == 'do_spaces'){
            // DIGITAL OCEAN OPTION //
            $image_name = $user->passport;
            if($request->has('passport')){
                $val = $request->validate([
                    'passport' => 'required|image|mimes:jpeg,png,jpg,|max:300|dimensions:max_width=800,max_height=800',
                ]);
                $file = $request->file('passport');
                $image = $file->getClientOriginalName();
                $ext = pathinfo($image, PATHINFO_EXTENSION);
                $image_name = $user->service_number.'.'.$ext;
                $file->storePubliclyAs('public/documents/'.$user->service_number.'/passport/', strtolower($image_name));

                $url = Storage::url('public/documents/'.$user->service_number.'/passport/'.strtolower($image_name));

                $personnel = $user->update([
                    'passport' => $url
                ]);
            }

            if($request->has('file')){
                $images = $request->file('file');
                foreach($images as $image)
                {
                    $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $image->storePubliclyAs ('public/documents/'.$user->service_number.'/', strtolower($image->getClientOriginalName()));

                    $url = Storage::url('public/documents/'.$user->service_number.'/'.strtolower($image->getClientOriginalName()));

                    $upload = $user->documents()->create([
                        'title' => ucwords($file_name),
                        'file' => $url
                    ]);
                }
            }
            // DIGITAL OCEAN OPTION ENDS HERE//
        }
        else{
            // LOCAL FILE STORAGE GOES HERE //
            $image_name = $user->passport;
            if($request->has('passport')){
                $request->validate([
                    'passport' => 'required|image|mimes:jpeg,png,jpg,|max:300|dimensions:max_width=800,max_height=800',
                ]);
                $file = $request->file('passport');
                $image = $file->getClientOriginalName();
                $ext = pathinfo($image, PATHINFO_EXTENSION);
                $image_name = $user->service_number.'.'.$ext;
                $file->storeAs('public/documents/'.$user->service_number.'/passport/', $image_name);

                $personnel = $user->update([
                    'passport' => $image_name,
                ]);
            }

            if($request->has('file')){
                $images = $request->file('file');
                foreach($images as $image)
                {
                    $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $image->storeAs('public/documents/'.$user->service_number.'/', $image->getClientOriginalName());

                    $upload = $user->documents()->create([
                        'title' => $file_name,
                        'file' => $image->getClientOriginalName()
                    ]);
                }
            }
            // LOCAL FILE STORAGE ENDS HERE //
        }

        Alert::success('File(s) uploaded successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('personnel_show', $user->id);
    }

    // EDIT A PERSONNEL
    public function edit($user)
    {
        abort_unless(auth()->user()->hasRole('super admin') || auth()->user()->hasPermissionTo('edit personnel'), 401, 'You don\'t have clearance to access this page.' );

        $personnel = User::where('id', $user)->with(['formations' => function($query){
            $query->latest()->first();
        }])->first();

        $banks = array(
            array('id' => '1','name' => 'Access Bank','code'=>'044'),
            array('id' => '2','name' => 'Citibank','code'=>'023'),
            array('id' => '3','name' => 'Diamond Bank','code'=>'063'),
            array('id' => '4','name' => 'Dynamic Standard Bank','code'=>''),
            array('id' => '5','name' => 'Ecobank Nigeria','code'=>'050'),
            array('id' => '6','name' => 'Fidelity Bank Nigeria','code'=>'070'),
            array('id' => '7','name' => 'First Bank of Nigeria','code'=>'011'),
            array('id' => '8','name' => 'First City Monument Bank','code'=>'214'),
            array('id' => '9','name' => 'Guaranty Trust Bank','code'=>'058'),
            array('id' => '10','name' => 'Heritage Bank Plc','code'=>'030'),
            array('id' => '11','name' => 'Jaiz Bank','code'=>'301'),
            array('id' => '12','name' => 'Keystone Bank Limited','code'=>'082'),
            array('id' => '13','name' => 'Providus Bank Plc','code'=>'101'),
            array('id' => '14','name' => 'Polaris Bank','code'=>'076'),
            array('id' => '15','name' => 'Stanbic IBTC Bank Nigeria Limited','code'=>'221'),
            array('id' => '16','name' => 'Standard Chartered Bank','code'=>'068'),
            array('id' => '17','name' => 'Sterling Bank','code'=>'232'),
            array('id' => '18','name' => 'Suntrust Bank Nigeria Limited','code'=>'100'),
            array('id' => '19','name' => 'Union Bank of Nigeria','code'=>'032'),
            array('id' => '20','name' => 'United Bank for Africa','code'=>'033'),
            array('id' => '21','name' => 'Unity Bank Plc','code'=>'215'),
            array('id' => '22','name' => 'Wema Bank','code'=>'035'),
            array('id' => '23','name' => 'Zenith Bank','code'=>'057')
        );
        
        $pfas = array(
            array('id' => '1','name' => 'AIICO Pension Managers Limited','code'=>'044'),
            array('id' => '2','name' => 'APT Pension Fund Managers Limited','code'=>'023'),
            array('id' => '3','name' => 'ARM Pension Managers Limited','code'=>'063'),
            array('id' => '4','name' => 'AXA Mansard Pension Limited (Tangerine)','code'=>''),
            array('id' => '5','name' => 'CrusaderSterling Pensions Limited','code'=>'050'),
            array('id' => '6','name' => 'FCMB Pensions Limited','code'=>'070'),
            array('id' => '7','name' => 'Fidelity Pension Managers','code'=>'011'),
            array('id' => '8','name' => 'First Guarantee Pension Limited','code'=>'214'),
            array('id' => '9','name' => 'IEI-Anchor Pension Managers Limited','code'=>'058'),
            array('id' => '10','name' => 'Investment One Pension Managers Limited','code'=>'030'),
            array('id' => '11','name' => 'Leadway Pensure PFA Limited','code'=>'301'),
            array('id' => '12','name' => 'Nigerian University Pension Management Company (NUPEMCO)','code'=>'082'),
            array('id' => '13','name' => 'NLPC Pension Fund Administrators Limited','code'=>'101'),
            array('id' => '14','name' => 'NPF Pensions Limited','code'=>'076'),
            array('id' => '15','name' => 'OAK Pensions Limited','code'=>'221'),
            array('id' => '16','name' => 'Pensions Alliance Limited','code'=>'068'),
            array('id' => '17','name' => 'Premium Pension Limited','code'=>'232'),
            array('id' => '18','name' => 'Radix Pension Managers Limited','code'=>'100'),
            array('id' => '19','name' => 'Sigma Pensions Limited','code'=>'032'),
            array('id' => '20','name' => 'Stanbic IBTC Pension Managers Limited','code'=>'033'),
            array('id' => '21','name' => 'Trustfund Pensions Limited','code'=>'215'),
            array('id' => '22','name' => 'Veritas Glanvills Pensions Limited','code'=>'035'),
        );

        $formations = Formation::all();

        $lga = Lga::where('id', $personnel->lgoo)->first();
        $lga = $lga !== NULL ? $lga->lg_name : '';
        $rank = Rank::where('gl', $personnel->gl)->first();
        $rank = $rank !== NULL ? $rank->full_title : '';
        return view('dashboard/personnel/edit', compact(['banks', 'pfas', 'formations', 'personnel', 'lga', 'rank']));
    }
    
    // SEARCH A PERSONNEL
    public function search($search_value)
    {
        $result = User::where('service_number', 'LIKE', '%'.$search_value.'%')
        ->orWhere('name', 'LIKE', '%'.$search_value.'%')
        ->paginate(3);
        return response()->json($result);
    }

    // EDIT PERSONNEL PASSWORD
    public function change_password(Request $request, User $user)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        
        if(auth()->user()->hasRole('super admin')){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            Alert::success('Personnel password updated successfully!', 'Success!')->autoclose(2500);
        }
        else if($user->password != null){
            if(Hash::check($request->old_pass, $user->password)){
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                Alert::success('Personnel password updated successfully!', 'Success!')->autoclose(2500);
            }else{
                Alert::error('Sorry your old password does not match with password on the database', 'Error!')->autoclose(2500);
            }
        }else{
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            Alert::success('Personnel password updated successfully!', 'Success!')->autoclose(2500);
        }
        return back();
    }
    
    // UPDATE A PERSONNEL RECORD
    public function update(Request $request, User $user)
    {

        $validation = $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date',
            'soo' => 'required',
            'lgoo' => 'string',
            'cadre' => 'required|string',
            'gl' => 'required|numeric',
            'step' => 'required|numeric',
            'dofa' => 'required|date',
            'dopa' => 'required|date'
        ]);

        $image_name = $user->passport;
        if($request->has('passport')){

            $val = $request->validate([
                'passport' => 'required|image|mimes:jpeg,png,jpg,|max:200',
            ]);

            $file = $request->file('passport');
            $image = $file->getClientOriginalName();
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = $request->service_number.'.'.$ext;
            $file->storeAs('public/documents/'.$request->service_number.'/passport/', $image_name);
        }

        $rank = Rank::where('cadre', $request->cadre)->where('gl', $request->gl)->first();
        
        // return $request;

        $personnel = $user->update([
            'name' => $request->name,
            'dob' => $request->dob,
            'sex' => $request->sex,
            'blood_group' => $request->blood_group,
            'genotype' => $request->genotype,
            'height' => $request->height,
            'weight' => $request->weight,
            'marital_status' => $request->marital_status,
            'date_of_marriage' => $request->date_of_marriage,
            'name_of_spouse' => $request->name_of_spouse,
            'religion' => $request->religion,
            'soo' => $request->soo,
            'lgoo' => $request->lgoo,
            'place_of_birth' => $request->place_of_birth,
            'residential_address' => $request->residential_address,
            'permanent_address' => $request->permanent_address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'cadre' => $request->cadre,
            'gl' => $request->gl,
            'step' => $request->step,
            'rank_full' => $rank->full_title,
            'rank_short' => $rank->short_title,
            'dofa' => $request->dofa,
            'doc' => $request->doc,
            'dopa' => $request->dopa,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
            'bvn' => $request->bvn,
            'paypoint' => $request->paypoint,
            'salary_structure' => $request->salary_structure,
            'nin_number' => $request->nin_number,
            'nhis_number' => $request->nhis_number,
            'ippis_number' => $request->ippis_number,
            'nhf' => $request->nhf,
            'pfa' => $request->pfa,
            'pen_number' => $request->pen_number,
            'specialization' => $request->specialization,
            'passport' => $image_name,
        ]);
        

        // if($request->has('command')){
        //     $user->formations()->create([
        //         'command' => $request->command
        //     ]);
        // }

        // if($request->has('file')){

        //     $images = $request->file('file');
        //     foreach($images as $image)
        //     {
        //         $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        //         $image->storeAs('public/documents/'.$request->service_number.'/', $image->getClientOriginalName());

        //         $upload = $user->documents()->create([
        //             'title' => $file_name,
        //             'file' => $image->getClientOriginalName()
        //         ]);
        //     }
        // }
        Alert::success('Personnel record updated successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('personnel_show', $user->id);
    }

    // DELETE PERSONNEL RECORD
    public function destroy(Request $request)
    {
        $user = User::find($request->user);
        // Storage::deleteDirectory('public/documents/'.$user->service_number);
        $user->status = $request->reason;
        $user->save();
        $user->delete();
        Alert::success('Personnel record deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->route('personnel_all');
    }

    // DELETE PERSONNEL DOCUMENT
    public function destroyDocument($id)
    {
        $document = Document::where('id', $id)->with('user')->first();
        $arr = explode('/', $document->file);
        $file = end($arr);
        Storage::delete('public/documents/'.$document->user->service_number.'/'.$file);
        $document->delete();
        Alert::success('Document deleted successfully!', 'Success!')->autoclose(2500);
        return redirect()->back();
    }

}
