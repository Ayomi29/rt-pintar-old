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

Route::get('', [AuthenticationController::class, 'index']);
// Route::post('', [AuthenticationController::class, 'login']);
Route::post('send-email-otp-admin', [AuthenticationController::class, 'sendEmailOtpAdmin']);
Route::post('confirm-otp-admin', [AuthenticationController::class, 'confirmOtpAdmin']);
Route::post('change-password', [AuthenticationController::class, 'changePassword']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);
    Route::post('/dashboard-notification-read', [DashboardNotificationController::class, 'update']);
    // Home
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('get-data-panic-button', [HomeController::class, 'getDataPanicButton']);
    Route::get('ubah-status-marker/{id}/edit', [HomeController::class, 'editStatus']);
    Route::post('ubah-status-marker/{id}', [HomeController::class, 'updateStatus']);
    // manajement data users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/data-rts', [DataRtController::class, 'index']);
    Route::get('/important-numbers', [ImportantNumberController::class, 'index']);
    Route::get('/houses', [HouseController::class, 'index']);
    Route::get('/family-cards', [FamilyCardController::class, 'index']);
    Route::get('/family-members', [FamilyMemberController::class, 'index']);
    Route::get('/notices', [NoticeController::class, 'index']);
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::get('/pollings', [PollingController::class, 'index']);
    Route::post('/pollings/{polling}/start', [PollingController::class, 'startPolling']);
    Route::post('/pollings/{polling}/finish', [PollingController::class, 'finishPolling']);
    Route::get('/cover-letters', [CoverLetterController::class, 'index']);
    Route::get('/cv-download', [CoverLetterController::class, 'download']);
    Route::get('/iurans', [DonationController::class, 'index']);
});
