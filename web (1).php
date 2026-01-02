<?php

use App\Models\Resident;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\RwController;

Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);



Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware('role:admin,user');

Route::get('/notifications', function() {
    return view ('pages.notifications');

});


Route::post('/notification/{id}/read', function($id){
    $notifications = Illuminate\Support\Facades\DB::table('notifications')->where('id', $id);
    $notifications->update([
        'read_at' => Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'),
    ]);

    $dataArray = json_decode($notifications->firstOrFail()->data, true);

    if (isset($dataArray['complaint_id'])){
        return redirect('/complaint');
    }

    return back();

} )->middleware('role:admin,user');

Route::get('/resident', [ResidentController::class, 'index'])->middleware('role:admin');
Route::get('/resident/create', [ResidentController::class, 'create'])->middleware('role:admin');
Route::get('/resident/{id}', [ResidentController::class, 'edit'])->middleware('role:admin');
Route::post('/resident', [ResidentController::class, 'store'])->middleware('role:admin');
Route::put('/resident/{id}', [ResidentController::class, 'update'])->middleware('role:admin');
Route::delete('/resident/{id}', [ResidentController::class, 'destroy'])->middleware('role:admin');  

Route::get('/account-list', [UserController::class, 'account_list_view'])->middleware('role:admin');

Route::get('/account-request', [UserController::class, 'account_request_view'])->middleware('role:admin');
Route::post('/account-request/approval/{id}', [UserController::class, 'account_approval'])->middleware('role:admin');

Route::get('/profile', [UserController::class, 'profile_view'])->middleware('role:admin,user');
Route::post('/profile/{id}', [UserController::class, 'update_profile'])->middleware('role:admin,user');
Route::get('/change-password', [UserController::class, 'change_password_view'])->middleware('role:admin,user');
Route::post('/change-password/{id}', [UserController::class, 'change_password'])->middleware('role:admin,user');

Route::get('/complaint', [Complaintcontroller::class, 'index'])->middleware('role:admin,user');
Route::get('/complaint/create', [Complaintcontroller::class, 'create'])->middleware('role:user');
Route::get('/complaint/{id}', [Complaintcontroller::class, 'edit'])->middleware('role:user');
Route::post('/complaint', [Complaintcontroller::class, 'store'])->middleware('role:user');
Route::put('/complaint/{id}', [Complaintcontroller::class, 'update'])->middleware('role:user');
Route::delete('/complaint/{id}', [Complaintcontroller::class, 'destroy'])->middleware('role:user');  
Route::post('/complaint/update-status/{id}', [Complaintcontroller::class, 'update_status'])->middleware('role:admin');  

Route::get('/rw-unit', [RwController::class, 'index'])->middleware('role:admin');
Route::get('/rw-unit/create', [RwController::class, 'create'])->middleware('role:admin');
Route::get('/rw-unit/{id}', [RwController::class, 'edit'])->middleware('role:admin');
Route::post('/rw-unit', [RwController::class, 'store'])->middleware('role:admin');
Route::put('/rw-unit/{id}', [RwController::class, 'update'])->middleware('role:admin');
Route::delete('/rw-unit/{id}', [RwController::class, 'destroy'])->middleware('role:admin');  