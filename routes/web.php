<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/test', function () {
    return view('test');
})->middleware(['auth', 'verified'])->name('test');

Route::get('/my-posts', [PostController::class, 'myPosts'])->name('my-posts')->middleware(['auth']);

// Crud Posts
Route::get('/create-post', function () {
    return view('create-post');
})->name('create.post');

Route::get('/create-post', [PostController::class, 'create'])->name('create.post');
Route::post('/create-post', [PostController::class, 'store']);
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/dashboard', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('my-posts');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit-post');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});


// Edit Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
