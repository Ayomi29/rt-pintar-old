<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CoverLetterController;
use App\Http\Controllers\DashboardNotificationController;
use App\Http\Controllers\DataRtController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FamilyCardController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\ImportantNumberController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PollingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', [AuthenticationController::class, 'index'])->name('login');
Route::post('', [AuthenticationController::class, 'login']);
Route::post('send-email-otp-admin', [AuthenticationController::class, 'sendEmailOtpAdmin']);
Route::post('confirm-otp-admin', [AuthenticationController::class, 'confirmOtpAdmin']);
Route::post('change-password', [AuthenticationController::class, 'changePassword']);

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);
    Route::post('/dashboard-notification-read', [DashboardNotificationController::class, 'update']);
    // Home
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('get-data-panic-button', [HomeController::class, 'getDataPanicButton']);
    Route::get('ubah-status-marker/{id}/edit', [HomeController::class, 'editStatus']);
    Route::post('ubah-status-marker/{id}', [HomeController::class, 'updateStatus']);
    // manajement data users
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/data-rts', DataRtController::class);
    Route::resource('/important-numbers', ImportantNumberController::class);
    Route::resource('/houses', HouseController::class);
    Route::resource('/family-cards', FamilyCardController::class);
    Route::resource('/family-members', FamilyMemberController::class);
    Route::resource('/notices', NoticeController::class);
    Route::resource('/complaints', ComplaintController::class);
    Route::resource('/pollings', PollingController::class);
    Route::resource('/cover-letters', CoverLetterController::class);
    Route::resource('/iurans', DonationController::class);
});
