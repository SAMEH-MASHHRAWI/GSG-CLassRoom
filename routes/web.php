<?php

use App\Http\Controllers\ClassroomPeopleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JounClassroomController;
use App\Http\Controllers\SubmissionController;

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
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::prefix('/classrooms/trashed')
        ->as('classroom.')
        ->controller(ClassroomsController::class)
        ->group(function () {
            Route::get('/', 'trashed')->name('trashed');

            Route::put('/{classroom}', 'restore')->name('restore');

            Route::delete('/{classroom}', 'forceDelete')->name('force-delete');
        });


    Route::get('classrooms/{classroom}/join', [JounClassroomController::class, 'create'])
    ->middleware('signed')
    ->name('classrooms.join');


    Route::post('classrooms/{classroom}/join', [JounClassroomController::class, 'store']);

    // resource controller
    Route::resource('/classrooms', ClassroomsController::class);
    Route::resource('/topics', TopicsController::class);
    Route::resource('classrooms.classworks', ClassworkController::class);


    Route::put('classrooms/{classroom}/classworks/{classwork}/edit', [ClassworkController::class,'show']);

    // Grades Routes
    Route::get('/classrooms/{classroom}/people', [ClassroomPeopleController::class,'index'])
    ->name('classrooms.people');
    Route::delete('/classrooms/{classroom}/people', [ClassroomPeopleController::class,'destroy'])
    ->name('classrooms.people.destroy');


    Route::post('comments', [CommentController::class, 'store'])
    ->name('comments.store');


    Route::post('classworks/{classwork}/submissions', [SubmissionController::class, 'store'])
    ->name('submissions.store');

    Route::post('submissions/{submission}/file', [SubmissionController::class, 'file'])
    ->name('submissions.file');
    
});



require __DIR__ . '/auth.php';
