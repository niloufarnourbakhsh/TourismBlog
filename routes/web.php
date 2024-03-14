<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
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

Route::get('/', function () {
    return view('main');
})->name('main');

Route::middleware('can:is_admin')->group(function (){

    Route::get('/users',[UserController::class,'index'])->name('users');
    Route::get('/notifications',[NotificationController::class,'notification'])->name('notifications');
    Route::get('/notification/markNotification/{id}',[NotificationController::class,'markNotification'])->name('markNotification');
    Route::delete('/user/{user}',[UserController::class,'delete'])->name('user.delete');
    Route::resource('posts',PostController::class);
    Route::patch('/posts/active/{post}',[PostController::class,'active'])->name('posts.active');
    Route::delete('/photo/{post}/{photo}',PhotoController::class)->name('photo');
    Route::resource('categories',CategoryController::class);
});

Route::middleware('auth')->group(function (){
    Route::post('/posts/like/{post}', [PostController::class, 'storeLikes'])->name('like.post');
    Route::post('/comment/{post}',[CommentController::class,'store'])->name('comment.store');
    Route::delete('/comment/{comment}',[CommentController::class,'delete'])->name('comment.delete');
    Route::post('/comment/like/{comment}',[CommentController::class,'LikeComment'])->name('like.comment');
});
Route::get('/contact-us',[ContactController::class,'create'])->name('contact.us');
Route::post('/contact-us',[ContactController::class,'submit'])->name('contact.us.submit');
Route::view('/about-us','Users.about-us')->name('about.us');

Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');





Route::get('/gallery',[PostController::class,'all'])->name('gallery');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
