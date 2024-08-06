<?php

use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AddsubjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/generate-report', [ReportController::class, 'generateReport']);
Route::get('/users', [UserController::class, 'index']);  ////ito ung linkk 
Route::get('/users/students', [UserController::class, 'getStudentUsers']); ///students
Route::get('/users/teachers', [UserController::class, 'getTeachersUsers']); ///Teachers
Route::get('/user-counts', [UserController::class, 'getUsersCounts']); /// user counts male and female
Route::post('register',[UserController::class,'register']);
Route::post('login', [UserController::class, 'login']);
Route::put('/user/{id}', [UserController::class, 'edit']);

Route::group([
    "middleware" => "auth:sanctum"
], function() {
    Route::get('userprofile', [UserController::class, 'userprofile']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('userResourceCollection', [UserController::class, 'userResourceCollection']);
    Route::get('userresource', [UserController::class, 'userresource']);
    Route::post('store', [TeacherController::class, 'store']);//post position
    Route::post('store2', [StudentController::class, 'store2']); ////post strand
    Route::get('index2', [StudentController::class, 'index2']); ///// show students
    Route::put('users/{id}',[TeacherController::class,'update']); //update teacher info
    Route::put('users/{id}',[StudentController::class,'update']);//update student info
    Route::post('store3', [SubjectController::class, 'store3']); // teacher create a subject
    Route::get('index', [TeacherController::class, 'index']);
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::post('store4', [AddsubjectController::class, 'store4']);/// students enroll to the subject 
    Route::get('index4', [AddsubjectController::class, 'index4']); /// show the enroll subject
    Route::get('newshow', [AddsubjectController::class, 'newshow']);
    Route::put('subjects/{id}', [SubjectController::class, 'update']); // Update subject
    Route::delete('subjects/{id}', [SubjectController::class, 'destroy']); // Delete subject

});
