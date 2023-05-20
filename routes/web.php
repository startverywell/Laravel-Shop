<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RandomController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/test', [RandomController::class, 'index']);
Route::get('/', [SurveyController::class,'index'])->name('admin.surveys');

Route::get('/profile', [ProfileController::class,'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');


Route::get('/admin', [SurveyController::class,'index'])->name('admin.surveys');

Route::get('/admin/users', [UserController::class,'index'])->name('admin.users');
Route::get('/admin/user/edit/{id}', [UserController::class,'edit'])->name('admin.user.edit');
Route::get('/admin/user/delete/{id}', [UserController::class,'delete'])->name('admin.user.delete');
Route::post('/admin/user/save', [ClientController::class,'save'])->name('admin.user.save');
Route::post('admin/user/upload', [UserController::class,'upload'])->name('admin.user.upload');

Route::get('/admin/clients', [ClientController::class,'index'])->name('admin.clients');
Route::get('/admin/client/show/{id}', [ClientController::class,'show'])->name('admin.client.show');

Route::get('/admin/setting', [SettingController::class,'index'])->name('admin.setting');
Route::put('/admin/setting', [SettingController::class, 'update'])->name('admin.setting.update');

Route::get('/admin/surveys', [SurveyController::class,'index'])->name('admin.surveys');
Route::get('/admin/survey/add', [SurveyController::class,'add'])->name('admin.survey.add');
Route::get('/admin/survey/delete/{id}', [SurveyController::class,'delete'])->name('admin.survey.delete');
Route::post('/admin/survey/save', [SurveyController::class,'save'])->name('admin.survey.save');
Route::post('/admin/survey/imagesearch', [SurveyController::class,'imageSearch'])->name('admin.survey.imagesearch');
Route::get('/admin/survey/edit/{id}', [SurveyController::class,'edit'])->name('admin.survey.edit');
Route::post('/admin/survey/widget', [SurveyController::class,'saveWidget'])->name('admin.survey.save_widget');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
