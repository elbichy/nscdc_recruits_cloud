<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\NokController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PrivilageController;
use App\Http\Controllers\ProgressionController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SyncController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/login', [AuthenticatedSessionController::class, 'create']);
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::group(['prefix' => 'dashboard'], function (){
	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
	
	// PERSONNEL
	Route::group(['prefix' => 'personnel'], function () {
		
		Route::get('/', [PersonnelController::class, 'index'])->name('personnel_all');
		Route::get('/new', [PersonnelController::class, 'create'])->name('personnel_create');
		Route::get('/all', [PersonnelController::class, 'index'])->name('personnel_all');
		Route::get('/get_all', [PersonnelController::class, 'get_all'])->name('personnel_get_all');
		
		Route::get('/{user}/show', [PersonnelController::class, 'show'])->name('personnel_show');
		Route::get('/{user}/ros', [PersonnelController::class, 'ros'])->name('personnel_ros');
		Route::post('/store', [PersonnelController::class, 'store'])->name('store_personnel');
		Route::get('{user}/edit/', [PersonnelController::class, 'edit'])->name('personnel_edit');
		
		Route::get('/search/{search_value}', [PersonnelController::class, 'search'])->name('search_personnel');

		Route::post('{user}/change_password/', [PersonnelController::class, 'change_password'])->name('personnel_change_password');
		// Route::post('{user}/delete/', [PersonnelController::class, 'change_password'])->name('personnel_delete');
		
		Route::put('/{user}/update', [PersonnelController::class, 'update'])->name('personnel_update');
		Route::post('/delete', [PersonnelController::class, 'destroy'])->name('personnel_delete');
		
		Route::group(['prefix' => 'import'], function () {
			Route::get('/data', [PersonnelController::class, 'import_data'])->name('import_data')->middleware(['role:super admin|personnel manager']);
			Route::post('/users/store', [PersonnelController::class, 'store_imported_users'])->name('store_imported_users');
		});

		Route::group(['prefix' => 'nok'], function () {
			Route::post('{personnel}/store', [NokController::class, 'store'])->name('personnel_store_nok');
			Route::delete('/{nok}/delete', [NokController::class, 'destroy'])->name('personnel_delete_nok');
			Route::post('/{nok}/update', [NokController::class, 'update'])->name('personnel_update_nok');
		});

		Route::group(['prefix' => 'children'], function () {
			Route::post('{personnel}/store', [ChildrenController::class, 'store'])->name('personnel_store_child');
			Route::delete('/{child}/delete', [ChildrenController::class, 'destroy'])->name('personnel_delete_child');
			Route::post('/{child}/update', [ChildrenController::class, 'update'])->name('personnel_update_child');
		});

		Route::group(['prefix' => 'file'], function () {
			Route::post('/upload/{user}', [PersonnelController::class, 'upload_file'])->name('personnel_upload_file');
			Route::delete('/document/{document}/delete', [PersonnelController::class, 'destroyDocument'])->name('deletePersonnelDocument');
		});
		
		// QUALIFICATION
		Route::group(['prefix' => 'qualification'], function () {
			Route::post('{personnel}/store',  [QualificationController::class, 'store'])->name('personnel_store_qualification');
			Route::delete('/{qualification}/delete',  [QualificationController::class, 'destroy'])->name('personnel_delete_qualification');
			Route::post('/{qualification}/update',  [QualificationController::class, 'update'])->name('personnel_update_qualification');
		});
		
		// DEPLOYMENT
		Route::group(['prefix' => 'deployment'], function () {
			Route::post('{personnel}/store',  [DeploymentController::class, 'store'])->name('personnel_store_deployment');
			Route::delete('/{deployment}/delete',  [DeploymentController::class, 'destroy'])->name('personnel_delete_deployment');
			Route::post('/{deployment}/update',  [DeploymentController::class, 'update'])->name('personnel_update_deployment');
		});
		
		// PROGRESSION
		Route::group(['prefix' => 'progression'], function () {
			Route::post('{personnel}/store', [ProgressionController::class, 'store'])->name('personnel_store_progression');
			Route::delete('/{progression}/delete', [ProgressionController::class, 'destroy'])->name('personnel_delete_progression');
			Route::post('/{progression}/update', [ProgressionController::class, 'update'])->name('personnel_update_progression');
			Route::get('/appointment',  [AppointmentController::class, 'generate_appointment'])->name('generate_appointment');
		});

		// PRIVILAGE
		Route::group(['prefix' => 'privilages'], function () {
			Route::get('/', [PrivilageController::class, 'index'])->name('personnel_privilages');
			Route::post('/permissions/new', [PrivilageController::class, 'new_permissions'])->name('new_permissions');
			Route::post('/roles/new', [PrivilageController::class, 'new_roles'])->name('new_roles');
			// Route::post('/roles/get/permissions', [SettingsController::class, 'get_permissions'])->name('permissions_get_from_role');
			// Route::post('/update/', [SettingsController::class, 'asign_privilage'])->name('app_settings_assign_privilage');
			Route::post('/{user}/assign', [PrivilageController::class, 'asign_privilage'])->name('user_assign_privilage');
		});
		
		Route::get('export/{type}',  [PersonnelController::class, 'export']);

	});

	// PROGRESSION ROUTES
	Route::group(['prefix' => 'progression'], function () {

		Route::get('/', function(){
			return redirect()->back();
		});
		
		// APPOINTMENT
		Route::group(['prefix' => 'appointment'], function () {

			Route::get('/', [AppointmentController::class, 'manage'])->name('manage_appointment')->middleware(['role:super admin|appointment manager']);
			Route::get('/manage', [AppointmentController::class, 'manage'])->name('manage_appointment')->middleware(['role:super admin|appointment manager']);

			Route::get('/manage/{year}', [AppointmentController::class, 'manage_appointment'])->name('manage_appointment_year')->middleware(['role:super admin|appointment manager']);
			Route::get('/get_all/{year}/', [AppointmentController::class, 'get_all'])->name('appointment_get_list');

			Route::get('/manage/edit/{appointment}', [AppointmentController::class, 'edit'])->name('appointment_edit');
			Route::post('/manage/update/', [AppointmentController::class, 'update'])->name('appointment_update');
			Route::delete('/manage/delete/', [AppointmentController::class, 'delete'])->name('appointment_delete');

			Route::group(['prefix' => 'import'], function () {
				Route::post('/store', [AppointmentController::class, 'store_imported_promotion'])->name('store_imported_appointment');
			});

			// // GENERATION OF LETTER
			Route::post('/generate/letter/bulk', [AppointmentController::class, 'generate_bulk_appointment_letter'])->name('generate_bulk_appointment_letter')->middleware(['role:super admin|appointment manager']);
			Route::get('/generate/letter/{candidate}', [AppointmentController::class, 'generate_single_appointment_letter'])->name('generate_single_appointment_letter')->middleware(['role:super admin|appointment manager']);
		});

		
	});

});