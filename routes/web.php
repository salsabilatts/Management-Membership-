<?php

use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\UmkmController;
use App\Http\Controllers\Admin\EducationAidController;
use App\Http\Controllers\Admin\HealthEventController;
use App\Http\Controllers\Admin\LegalAidController;
use App\Http\Controllers\Admin\SocialActivityController;
use App\Http\Controllers\Admin\EmoneyController;
use App\Http\Controllers\Admin\InstitutionController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Member Management
    Route::resource('members', MemberController::class);
    Route::post('members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');
    
    // UMKM Module
    Route::resource('umkm', UmkmController::class);
    Route::post('umkm/{umkm}/verify', [UmkmController::class, 'verify'])->name('umkm.verify');
    Route::get('umkm/export/report', [UmkmController::class, 'exportReport'])->name('umkm.export');
    
    // Education Aid Module
    Route::resource('education-aids', EducationAidController::class);
    Route::post('education-aids/{educationAid}/approve', [EducationAidController::class, 'approve'])->name('education-aids.approve');
    Route::post('education-aids/{educationAid}/disburse', [EducationAidController::class, 'disburse'])->name('education-aids.disburse');
    
    // Health Events Module
    Route::resource('health-events', HealthEventController::class);
    Route::get('health-events/{healthEvent}/participants', [HealthEventController::class, 'participants'])->name('health-events.participants');
    
    // Legal Aid Module
    Route::resource('legal-aids', LegalAidController::class);
    Route::post('legal-aids/{legalAid}/verify', [LegalAidController::class, 'verify'])->name('legal-aids.verify');
    
    // Social Activities Module
    Route::resource('social-activities', SocialActivityController::class);
    Route::get('social-activities/{socialActivity}/beneficiaries', [SocialActivityController::class, 'beneficiaries'])->name('social-activities.beneficiaries');
    
    // E-Money Management
    Route::resource('emoney', EmoneyController::class)->only(['index', 'show']);
    Route::post('emoney/{emoneyCard}/toggle-status', [EmoneyController::class, 'toggleStatus'])->name('emoney.toggle-status');
    Route::get('emoney/{emoneyCard}/transactions', [EmoneyController::class, 'transactions'])->name('emoney.transactions');
    
    // Institutions
    Route::resource('institutions', InstitutionController::class);
    
    // // Helpdesk
    // Route::resource('helpdesk', HelpdeskController::class)->only(['index', 'show']);
    // Route::post('helpdesk/{ticket}/assign', [HelpdeskController::class, 'assign'])->name('helpdesk.assign');
    // Route::post('helpdesk/{ticket}/respond', [HelpdeskController::class, 'respond'])->name('helpdesk.respond');
    
    // Reports & Transactions
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
    });
    
    // // Settings
    // Route::prefix('settings')->name('settings.')->group(function () {
    //     Route::get('/roles', [SettingController::class, 'roles'])->name('roles');
    //     Route::get('/backup', [SettingController::class, 'backup'])->name('backup');
    //     Route::post('/backup/create', [SettingController::class, 'createBackup'])->name('backup.create');
    //     Route::get('/api-integration', [SettingController::class, 'apiIntegration'])->name('api-integration');
    // });
    
    // Activity Logs
    Route::get('/activity-logs', [DashboardController::class, 'activityLogs'])->name('activity-logs');
});
