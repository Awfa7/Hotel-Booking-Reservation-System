<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\backend\BlogController;
use App\Http\Controllers\Backend\BookAreaController;
use App\Http\Controllers\backend\CommentController;
use App\Http\Controllers\Backend\RoomController;
use App\Http\Controllers\Backend\RoomListController;
use App\Http\Controllers\Backend\RoomTypeController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Frontend\BookingController;
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

    // Booking Route
    Route::controller(BookingController::class)->group(function(){
        Route::get('/booking/list','BookingList')->name('booking.list');
        Route::get('/booking/edit/{id}','EditBooking')->name('edit.booking');
        Route::get('/booking/download-invoice/{id}','DownloadBookingInvoice')->name('download.booking.invoice');

    });

    // Room List Route
    Route::controller(RoomListController::class)->group(function(){
        Route::get('/room/list','RoomList')->name('room.list');
        Route::get('/room/list/add','AddRoomList')->name('add.room.list');
        Route::post('/room/list/store','StoreRoomList')->name('store.room.list');
    });

    // Setting Route
    Route::controller(SettingController::class)->group(function(){
        Route::get('/smtp/setting','SmtpSetting')->name('smtp.setting');
        Route::post('/smtp/update','SmtpUpdate')->name('smtp.update');
    });

    // Testimonial Route
    Route::controller(TestimonialController::class)->group(function(){
        Route::get('/testimonial/all','AllTestimonial')->name('all.testimonial');
        Route::get('/testimonial/add','AddTestimonial')->name('add.testimonial');
        Route::post('/testimonial/store','StoreTestimonial')->name('store.testimonial');
        Route::get('/testimonial/edit/{id}','EditTestimonial')->name('edit.testimonial');
        Route::post('/testimonial/update','UpdateTestimonial')->name('update.testimonial');
        Route::get('/testimonial/delete/{id}','DeleteTestimonial')->name('delete.testimonial');
    });

    // Blog Category Route
    Route::controller(BlogController::class)->group(function(){
        Route::get('/blog/category','BlogCategory')->name('blog.category');
        Route::get('/blog/category/edit/{id}','EditBlogCategory');
        Route::post('/blog/category/store','StoreBlogCategory')->name('store.blog.category');
        Route::post('/blog/category/update','UpdateBlogCategory')->name('update.blog.category');
        Route::get('/blog/category/delete/{id}','DeleteBlogCategory')->name('delete.blog.category');
    });

    // Blog Post Route
    Route::controller(BlogController::class)->group(function(){
        Route::get('/blog/post/all','AllBlogPost')->name('all.blog.post');
        Route::get('/blog/post/add','AddBlogPost')->name('add.blog.post');
        Route::post('/blog/post/store','StoreBlogPost')->name('store.blog.post');
        Route::get('/blog/post/edit/{id}','EditBlogPost')->name('edit.blog.post');
        Route::post('/blog/post/update','UpdateBlogPost')->name('update.blog.post');
        Route::get('/blog/post/delete/{id}','DeleteBlogPost')->name('delete.blog.post');
    });

    // Comment Post Route
    Route::controller(CommentController::class)->group(function(){
        Route::get('/comment/all','AllComment')->name('all.comment.post');
        Route::post('/comment/status/update','UpdateCommentStatus')->name('update.comment.status');
    });
});


// Room Route
Route::controller(FrontendRoomController::class)->group(function(){
    Route::get('/rooms','AllFrontendRoomList')->name('fRoom.all');
    Route::get('/room/details/{id}','RoomDetailsPage');

    Route::get('/bookings','BookingSearch')->name('booking.search');
    Route::get('/search/room/details/{id}','SearchRoomDetails')->name('search.room.details');

    Route::get('/check_room_availability','CheckRoomAvailability')->name('check.room.availability');
});


Route::middleware(['auth'])->group(function(){
    Route::controller(BookingController::class)->group(function(){
        Route::get('/checkout','Checkout')->name('checkout');
        Route::post('/booking/store','StoreBooking')->name('store.user.booking');
        Route::post('/checkout/store','StoreCheckout')->name('store.checkout');
        Route::match(['get', 'post'],'/stripe_pay', [BookingController::class, 'stripe_pay'])->name('stripe_pay');

        // Booking Update
        Route::post('/update/booking/status/{id}','UpdateBookingStatus')->name('update.booking.status');
        Route::post('/update/booking/{id}','UpdateBooking')->name('update.booking');

        // Assign Room
        Route::get('/assign/room/{id}','AssignRoom')->name('assign.room');
        Route::get('/assign/room/store/{booking_id}/{room_number_id}','StoreAssignRoom')->name('store.assign.room');
        Route::get('/assign/room/delete/{id}', 'DeleteAssignRoom')->name('delete.assign.room');


        Route::get('/user/booking','UserBooking')->name('user.booking');
        Route::get('/user/invoice/{id}','UserInvoice')->name('user.invoice');

    });
});


// Frontend Blog Route
Route::controller(BlogController::class)->group(function(){
    Route::get('/blog/details/{slug}','BlogDetails');
    Route::get('/blog/category/list/{id}','BlogCategoryList');
    Route::get('/blog/post/list','BlogPostList')->name('blog.post.list');
});

// Frontend Comment Route
Route::controller(CommentController::class)->group(function(){
    Route::post('/comment/store','StoreComment')->name('store.comment');
});