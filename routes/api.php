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
    Route::post('store', [TeacherController::class, 'store']);
    Route::post('store2', [StudentController::class, 'store2']);
    Route::post('store3', [SubjectController::class, 'store3']);
    Route::get('index', [TeacherController::class, 'index']);
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::put('subjects/{id}', [SubjectController::class, 'update']); // Update subject
    Route::delete('subjects/{id}', [SubjectController::class, 'destroy']); // Delete subject
});
