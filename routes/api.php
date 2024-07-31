<?php

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

Route::get('/users', [UserController::class, 'index']);  ////ito ung linkk 
Route::get('/users/students', [UserController::class, 'getStudentUsers']); ///students
Route::get('/users/teachers', [UserController::class, 'getTeachersUsers']); ///Teachers
Route::get('/user-counts', [UserController::class, 'getUsersCounts']); /// user counts male and female
Route::post('register',[UserController::class,'register']);
Route::post('login', [UserController::class, 'login']);
Route::put('/user/{id}', [UserController::class, 'edit']);

Route::group([
    "middleware" => "auth:sanctum"
], function(){


    Route::get('userprofile',[UserController::class,'userprofile']);
   // Route::get('/users/{id}', [AuthController::class, 'show']);
   Route::post('logout',[UserController::class,'logout']);
  // Route::put('/user/{id}', [UserController::class, 'edit']);
  //  Route::post('logout', 'AuthController@logout');
  Route::get('userResourceCollection',[UserController::class,'userResourceCollection']);
  Route::get('userresource',[UserController::class,'userresource']);
  Route::post('store',[TeacherController::class,'store']); /// add position by teacher 
  Route::post('store2',[StudentController::class,'store2']); //// add strand by student 
  Route::get('/countstrand', [StudentController::class, 'countStrandStudents']);
  Route::post('store3',[SubjectController::class,'store3']);
  Route::get('index',[TeacherController::class,'index']); //show teacher user
  Route::get('index2', [StudentController::class, 'index2']); ///show student users 

  Route::put('users/{id}',[TeacherController::class,'update']);
  Route::put('users/{id}',[StudentController::class,'update']);
  Route::post('store4',[AddsubjectController::class,'store4']);
  Route::get('index4',[AddsubjectController::class,'index4']);
  

});
