<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BookAreaController;
use App\Http\Controllers\Backend\RoomController;
use App\Http\Controllers\Backend\RoomTypeController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\Frontend\FrontendRoomController;
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

    // Book Area Route
    Route::controller(BookAreaController::class)->group(function(){
        Route::get('/book-area/edit','EditBookArea')->name('edit.book.area');
        Route::post('/book-area/update','UpdateBookArea')->name('update.book.area');
    });

    // Room Type Route
    Route::controller(RoomTypeController::class)->group(function(){
        Route::get('/room-type/list','ListRoomType')->name('list.room.type');
        Route::get('/room-type/add','AddRoomType')->name('add.room.type');
        Route::post('/room-type/store','StoreRoomType')->name('store.room.type');
    });

    // Room Route
    Route::controller(RoomController::class)->group(function(){
        Route::get('/room/edit/{id}','EditRoom')->name('edit.room');
        Route::get('/room/delete/{id}','DeleteRoom')->name('delete.room');
        Route::post('/room/update/{id}', 'UpdateRoom')->name('update.room');
        Route::post('/room-number/store/{id}', 'StoreRoomNumber')->name('store.room.number');
        Route::get('/room-number/edit/{id}', 'EditRoomNumber')->name('edit.room.number');
        Route::post('/room-number/update/{id}', 'UpdateRoomNumber')->name('update.room.number');
        Route::get('/room-number/delete/{id}', 'DeleteRoomNumber')->name('delete.room.number');
        Route::get('/multi-image/delete/{id}','MultiImageDelete')->name('delete.multi.image');
    });
});


// Room Route
Route::controller(FrontendRoomController::class)->group(function(){
    Route::get('/rooms','AllFrontendRoomList')->name('fRoom.all');
    Route::get('/room/details/{id}','RoomDetailsPage');
});