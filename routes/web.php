<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UserController::class, 'Index']);

Route::get('/dashboard', function () {
    return view('frontend.dashboard.user_dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user/profile', [UserController::class, 'UserProfile'])->name('user.profile');
    Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');
    Route::get('/user/change-password', [UserController::class, 'UserChangePassword'])->name('user.change.password');
    Route::post('/user/update-password', [UserController::class, 'UserUpdatePassword'])->name('user.update.password');
});

require __DIR__.'/auth.php';


// Admin Group Middleware

Route::middleware(['auth','roles:admin'])->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');

    Route::get('/admin/change-password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update-password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');
});

Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');

Route::middleware(['auth','roles:admin'])->group(function(){
    // Team Route
    Route::controller(TeamController::class)->group(function(){
        Route::get('/team/all','AllTeam')->name('all.team');
        Route::get('/team/add','AddTeam')->name('add.team');
        Route::post('/team/store','StoreTeam')->name('store.team');
        Route::get('/team/edit/{id}','EditTeam')->name('edit.team');
        Route::post('/team/update','UpdateTeam')->name('update.team');
        Route::get('/team/delete/{id}','DeleteTeam')->name('delete.team');
        
    });
});